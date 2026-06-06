@extends('layouts.admin')

@section('header', 'Kelola Member')

@section('content')
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Filter dan Search -->
        <div class="p-6 border-b border-gray-200">
            <form action="{{ route('admin.members.index') }}" method="GET" class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex flex-1 flex-col sm:flex-row gap-4">
                    <!-- Search Input -->
                    <div class="flex-1">
                        <label for="search" class="sr-only">Cari Member</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               placeholder="Cari Kode Member, Nama, atau Email..." 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                    </div>
                    
                    <!-- Tier Filter -->
                    <div class="w-full sm:w-48">
                        <label for="tier" class="sr-only">Filter Tingkat</label>
                        <select name="tier" id="tier" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                            <option value="">Semua Tingkat</option>
                            <option value="bronze" {{ request('tier') === 'bronze' ? 'selected' : '' }}>Bronze</option>
                            <option value="silver" {{ request('tier') === 'silver' ? 'selected' : '' }}>Silver</option>
                            <option value="gold" {{ request('tier') === 'gold' ? 'selected' : '' }}>Gold</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded text-sm transition">
                        Filter
                    </button>
                    @if(request()->anyFilled(['search', 'tier']))
                        <a href="{{ route('admin.members.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded text-sm transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table View -->
        <div class="p-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Member</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tingkat</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Poin XP</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Terdaftar</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($members as $member)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                            <a href="{{ route('admin.members.show', $member) }}" class="text-green-700 hover:underline">
                                {{ $member->member_code }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $member->user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $member->user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-semibold text-gray-700">Lvl {{ $member->level }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-full uppercase
                                @if($member->tier === 'bronze') bg-amber-100 text-amber-800
                                @elseif($member->tier === 'silver') bg-slate-100 text-slate-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ $member->tier }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-bold text-green-700">{{ $member->xp }} XP</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                            {{ $member->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <a href="{{ route('admin.members.show', $member) }}" class="text-blue-600 hover:text-blue-900 font-semibold">
                                Detail / Edit XP
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">Tidak ada data member ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $members->links() }}
            </div>
        </div>
    </div>
@endsection
