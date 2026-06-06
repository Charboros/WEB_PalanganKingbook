@extends('layouts.admin')

@section('header', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 border-l-4 border-l-green-600">
            <h3 class="text-gray-500 text-sm font-bold uppercase tracking-wider">Pendapatan Hari Ini</h3>
            <p class="text-3xl font-extrabold text-gray-800 mt-2">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 border-l-4 border-l-emerald-600">
            <h3 class="text-gray-500 text-sm font-bold uppercase tracking-wider">Total Booking</h3>
            <p class="text-3xl font-extrabold text-gray-800 mt-2">{{ $totalBookings }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-800">Kalender Booking</h3>
        </div>
        <div class="p-6">
            <div id="calendar"></div>
        </div>
    </div>

    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                slotMinTime: '08:00:00',
                slotMaxTime: '23:00:00',
                events: @json($calendarEvents),
            });
            calendar.render();
        });
    </script>
@endsection
