<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Notifications\BookingStatusUpdated;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'field']);

        if ($request->filled('search')) {
            $query->where('booking_code', 'like', '%'.$request->search.'%')
                ->orWhereHas('user', function ($q) use ($request) {
                    $q->where('name', 'like', '%'.$request->search.'%');
                });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Memperbarui status pesanan dari panel Admin.
     * Mengelola pelepasan slot lapangan jika dibatalkan, pembagian/penarikan Experience Points (XP)
     * untuk sistem Membership, serta pengiriman notifikasi ke web pengguna (lonceng notifikasi).
     * 
     * @param Request $request Data status baru
     * @param Booking $booking Objek pesanan yang sedang diubah
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:menunggu_pembayaran,terkonfirmasi,dibatalkan,selesai,refund',
        ]);

        $newStatus = $request->status;
        $booking->update(['status' => $newStatus]);

        if (in_array($newStatus, ['dibatalkan', 'refund'])) {
            $booking->bookingSlots()->delete();
        }

        // XP Awarding Logic
        $user = $booking->user;
        if ($user && $user->isMember()) {
            $xpAmount = 10 * $booking->duration_hours;

            // Award XP if status changes to confirmed or completed and not yet awarded
            if (in_array($newStatus, ['terkonfirmasi', 'selesai']) && ! $booking->xp_awarded) {
                $user->member->addXP($xpAmount, "Mendapat XP dari Booking {$booking->booking_code}", $booking->id);
                $booking->update(['xp_awarded' => true]);
            }
            // Revoke XP if status changes to canceled, refund, or waiting payment and XP was awarded
            elseif (in_array($newStatus, ['dibatalkan', 'refund', 'menunggu_pembayaran']) && $booking->xp_awarded) {
                $user->member->subtractXP($xpAmount, "Pengurangan XP karena Pembatalan/Perubahan status Booking {$booking->booking_code}", $booking->id);
                $booking->update(['xp_awarded' => false]);
            }
        }

        // Send Notification
        if (in_array($newStatus, ['terkonfirmasi', 'dibatalkan'])) {
            $statusText = $newStatus === 'terkonfirmasi' ? 'Terkonfirmasi' : 'Dibatalkan';
            $message = "Pesanan lapangan Anda dengan kode {$booking->booking_code} telah {$statusText} oleh Admin.";
            if ($booking->user) {
                $booking->user->notify(new BookingStatusUpdated($booking, $message));
            }
        }

        return back()->with('success', 'Status booking berhasil diubah.');
    }
}
