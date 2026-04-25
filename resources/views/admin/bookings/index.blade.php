@extends('layouts.admin')

@section('header', 'Kelola Booking')

@section('content')
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form action="{{ route('admin.bookings.index') }}" method="GET" class="flex gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari (Kode/Nama)</label>
                <input type="text" name="search" value="{{ request('search') }}" class="w-full rounded-md border-gray-300 p-2 border" placeholder="Cari...">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full rounded-md border-gray-300 p-2 border">
                    <option value="">Semua</option>
                    <option value="menunggu_pembayaran" {{ request('status') == 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="terkonfirmasi" {{ request('status') == 'terkonfirmasi' ? 'selected' : '' }}>Terkonfirmasi</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div>
                <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded">Filter</button>
                <a href="{{ route('admin.bookings.index') }}" class="bg-gray-300 text-gray-700 py-2 px-4 rounded ml-2">Reset</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lapangan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($bookings as $booking)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $booking->booking_code }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->user->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->field->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}<br>
                        {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                        @if($booking->payment_proof)
                            <br><a href="{{ Storage::url($booking->payment_proof) }}" target="_blank" class="text-blue-600 text-xs underline">Lihat Bukti</a>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $colors = [
                                'menunggu_pembayaran' => 'bg-yellow-100 text-yellow-800',
                                'terkonfirmasi' => 'bg-green-100 text-green-800',
                                'selesai' => 'bg-blue-100 text-blue-800',
                                'dibatalkan' => 'bg-red-100 text-red-800',
                                'refund' => 'bg-purple-100 text-purple-800',
                            ];
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ str_replace('_', ' ', ucfirst($booking->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <form action="{{ route('admin.bookings.updateStatus', $booking) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="text-sm rounded border-gray-300" onchange="this.form.submit()">
                                <option value="menunggu_pembayaran" {{ $booking->status == 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu</option>
                                <option value="terkonfirmasi" {{ $booking->status == 'terkonfirmasi' ? 'selected' : '' }}>Terkonfirmasi</option>
                                <option value="selesai" {{ $booking->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="dibatalkan" {{ $booking->status == 'dibatalkan' ? 'selected' : '' }}>Batalkan</option>
                            </select>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $bookings->links() }}
        </div>
    </div>
@endsection
