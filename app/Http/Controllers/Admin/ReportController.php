<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function exportPdf(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $bookings = Booking::with(['field.fieldType', 'user'])
            ->whereBetween('booking_date', [$request->start_date, $request->end_date])
            ->whereIn('status', ['terkonfirmasi', 'selesai'])
            ->get();

        $totalRevenue = $bookings->sum('total_price');

        $pdf = Pdf::loadView('admin.reports.pdf', [
            'bookings' => $bookings,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'totalRevenue' => $totalRevenue,
        ]);

        return $pdf->download('laporan-pendapatan-sportbook-'.now()->format('YmdHis').'.pdf');
    }
}
