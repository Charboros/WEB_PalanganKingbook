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

        $booking->update(['status' => $request->status]);

        if (in_array($request->status, ['dibatalkan', 'refund'])) {
            $booking->bookingSlots()->delete();
        }

        return back()->with('success', 'Status booking berhasil diubah.');
    }
}
