<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingSlot;
use App\Models\Field;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with('field')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.bookings.index', compact('bookings'));
    }

    public function show(Request $request, Field $field)
    {
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $carbonDate = Carbon::parse($date);

        $bookedSlots = BookingSlot::where('field_id', $field->id)
            ->where('slot_date', $date)
            ->pluck('slot_hour')
            ->toArray();

        $isWeekend = $carbonDate->isWeekend();

        return view('user.fields.show', compact('field', 'date', 'bookedSlots', 'isWeekend'));
    }

    /**
     * Menyimpan data pemesanan (booking) lapangan baru.
     * Mengatur validasi jam berurutan, harga peak/off-peak, akhir pekan,
     * serta fitur diskon otomatis dari tingkatan (tier) membership.
     * 
     * @param Request $request Data request yang dikirimkan dari form pemesanan
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Database\QueryException Jika terjadi bentrok jam (double booking)
     */
    public function store(Request $request)
    {
        $request->validate([
            'field_id' => 'required|exists:fields,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'slots' => 'required|array|min:1',
            'slots.*' => 'integer|min:8|max:22',
        ]);

        $field = Field::findOrFail($request->field_id);
        $slots = $request->slots;
        sort($slots);

        // Check continuous slots
        if (count($slots) > 1) {
            for ($i = 0; $i < count($slots) - 1; $i++) {
                if ($slots[$i + 1] - $slots[$i] !== 1) {
                    return back()->with('error', 'Jam booking harus berurutan. Jika terputus, buat booking terpisah.');
                }
            }
        }

        $carbonDate = Carbon::parse($request->booking_date);
        $isWeekend = $carbonDate->isWeekend();

        $originalPrice = 0;
        $slotDetails = [];

        foreach ($slots as $hour) {
            // Check peak hour (17:00 - 22:00)
            $isPeak = ($hour >= 17 && $hour <= 22);

            $basePrice = $isPeak ? $field->price_peak : $field->price_offpeak;

            // Weekend calculation (+20%)
            $price = $isWeekend ? $basePrice * 1.2 : $basePrice;

            $originalPrice += $price;
            $slotDetails[] = [
                'field_id' => $field->id,
                'slot_date' => $request->booking_date,
                'slot_hour' => $hour,
                'price' => $price,
            ];
        }

        // Calculate discount based on membership tier
        $discountPercentage = 0;
        $user = auth()->user();
        if ($user && $user->isMember()) {
            $tier = $user->member->tier;
            if ($tier === 'silver') {
                $discountPercentage = 0.10; // 10%
            } elseif ($tier === 'gold') {
                $discountPercentage = 0.20; // 20%
            }
        }

        $discountAmount = $originalPrice * $discountPercentage;
        $totalPrice = $originalPrice - $discountAmount;

        $startTime = sprintf('%02d:00:00', $slots[0]);
        $endTime = sprintf('%02d:00:00', end($slots) + 1);

        try {
            DB::beginTransaction();

            $booking = Booking::create([
                'booking_code' => 'BKG'.strtoupper(Str::random(6)).time(),
                'user_id' => $user->id,
                'field_id' => $field->id,
                'booking_date' => $request->booking_date,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'duration_hours' => count($slots),
                'original_price' => $originalPrice,
                'discount_amount' => $discountAmount,
                'total_price' => $totalPrice,
                'status' => 'menunggu_pembayaran',
            ]);

            foreach ($slotDetails as &$detail) {
                $detail['booking_id'] = $booking->id;
            }

            BookingSlot::insert($slotDetails);

            DB::commit();

            return redirect()->route('user.bookings.index')->with('success', 'Booking berhasil dibuat. Silakan upload bukti pembayaran.');

        } catch (QueryException $e) {
            DB::rollBack();
            // Code 23000 is integrity constraint violation (Unique Key uk_slot failed)
            if ($e->getCode() == 23000) {
                return back()->with('error', 'Maaf, salah satu slot waktu yang Anda pilih baru saja di-booking oleh orang lain. Silakan pilih slot lain.');
            }
            throw $e;
        }
    }

    /**
     * Mengunggah file bukti pembayaran untuk suatu pesanan.
     * 
     * @param Request $request Data file upload
     * @param Booking $booking Objek pesanan yang bersangkutan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadPayment(Request $request, Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:500',
        ]);

        if ($request->hasFile('payment_proof')) {
            if ($booking->payment_proof) {
                Storage::disk('public')->delete($booking->payment_proof);
            }

            $path = $request->file('payment_proof')->store('payments', 'public');
            $booking->update([
                'payment_proof' => $path,
            ]);

            return back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu konfirmasi admin.');
        }

        return back()->with('error', 'Gagal mengupload bukti pembayaran.');
    }

    /**
     * Membatalkan pesanan dan mengatur kebijakan pengembalian dana (refund).
     * Aturan Refund:
     * - >= 24 Jam sebelum main: Refund 100%
     * - 12 hingga 24 Jam sebelum main: Refund 50%
     * - Kurang dari 12 Jam sebelum main: Hangus (Refund 0)
     * 
     * @param Request $request
     * @param Booking $booking Objek pesanan yang akan dibatalkan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Request $request, Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status === 'dibatalkan') {
            return back()->with('error', 'Booking ini sudah dibatalkan sebelumnya.');
        }

        $bookingDateTime = Carbon::parse($booking->booking_date->format('Y-m-d').' '.$booking->start_time->format('H:i:s'));

        if ($bookingDateTime->isPast()) {
            return back()->with('error', 'Booking di masa lalu tidak dapat dibatalkan.');
        }

        $hoursDifference = now()->diffInHours($bookingDateTime, false);

        if ($hoursDifference < 0) {
            return back()->with('error', 'Booking di masa lalu tidak dapat dibatalkan.');
        }

        $refundAmount = 0;

        // Only calculate refund if they already paid (assuming they paid full amount as DP/Lunas logic isn't fully detailed on price, but total_price is known)
        // If status is menunggu_pembayaran, there's no money to refund yet.
        if (in_array($booking->status, ['terkonfirmasi'])) {
            if ($hoursDifference >= 24) {
                $refundAmount = $booking->total_price;
            } elseif ($hoursDifference >= 12 && $hoursDifference < 24) {
                $refundAmount = $booking->total_price * 0.5;
            } else {
                $refundAmount = 0;
            }
        }

        $booking->update([
            'status' => 'dibatalkan',
            'refund_amount' => $refundAmount,
            'cancelled_at' => now(),
        ]);

        // Release slots so others can book
        $booking->bookingSlots()->delete();

        return back()->with('success', 'Booking berhasil dibatalkan. Dana yang di-refund: Rp '.number_format($refundAmount, 0, ',', '.'));
    }
}
