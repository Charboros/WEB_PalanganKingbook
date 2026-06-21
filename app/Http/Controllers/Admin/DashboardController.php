<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $todayRevenue = Booking::whereDate('created_at', $today)
            ->whereIn('status', ['terkonfirmasi', 'selesai'])
            ->sum('total_price');

        $totalBookings = Booking::count();

        // Calendar data
        $bookings = Booking::whereNotIn('status', ['dibatalkan', 'refund'])
            ->with(['field', 'user'])
            ->get();
        $calendarEvents = $bookings->map(function ($booking) {
            return [
                'title' => $booking->field->name.' - '.$booking->user->name,
                'start' => $booking->booking_date->format('Y-m-d').'T'.$booking->start_time->format('H:i:s'),
                'end' => $booking->booking_date->format('Y-m-d').'T'.$booking->end_time->format('H:i:s'),
                'url' => route('admin.bookings.index', ['search' => $booking->booking_code]),
                'backgroundColor' => $booking->status === 'terkonfirmasi' ? '#10B981' : ($booking->status === 'menunggu_pembayaran' ? '#F59E0B' : '#6B7280'),
            ];
        });

        return view('admin.dashboard', compact('todayRevenue', 'totalBookings', 'calendarEvents'));
    }
}
