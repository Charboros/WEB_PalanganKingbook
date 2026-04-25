@extends('layouts.admin')

@section('header', 'Laporan Pendapatan')

@section('content')
    <div class="bg-white rounded-lg shadow p-6 max-w-lg">
        <p class="mb-4 text-gray-600">Pilih rentang tanggal untuk mengekspor laporan pendapatan (hanya booking yang terkonfirmasi atau selesai).</p>
        
        <form action="{{ route('admin.reports.export') }}" method="GET" target="_blank">
            <div class="grid grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ date('Y-m-01') }}" class="w-full rounded-md border-gray-300 p-2 border focus:border-green-500 focus:ring-green-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                    <input type="date" name="end_date" value="{{ date('Y-m-t') }}" class="w-full rounded-md border-gray-300 p-2 border focus:border-green-500 focus:ring-green-500" required>
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition shadow flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    Export PDF
                </button>
            </div>
        </form>
    </div>
@endsection
