<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'field']);

        if ($request->filled('search')) {
            $query->where('booking_code', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.bookings.index', compact('bookings'));
    }

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
            if (in_array($newStatus, ['terkonfirmasi', 'selesai']) && !$booking->xp_awarded) {
                $user->member->addXP($xpAmount, "Mendapat XP dari Booking {$booking->booking_code}", $booking->id);
                $booking->update(['xp_awarded' => true]);
            }
            // Revoke XP if status changes to canceled, refund, or waiting payment and XP was awarded
            elseif (in_array($newStatus, ['dibatalkan', 'refund', 'menunggu_pembayaran']) && $booking->xp_awarded) {
                $user->member->subtractXP($xpAmount, "Pengurangan XP karena Pembatalan/Perubahan status Booking {$booking->booking_code}", $booking->id);
                $booking->update(['xp_awarded' => false]);
            }
        }

        return back()->with('success', 'Status booking berhasil diubah.');
    }
}
