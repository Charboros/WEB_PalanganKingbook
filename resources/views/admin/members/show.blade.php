@extends('layouts.admin')

@section('header', 'Detail Member: ' . $member->member_code)

@section('content')
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.members.index') }}" class="text-green-700 hover:text-green-900 font-semibold flex items-center gap-1">
            &larr; Kembali ke Daftar Member
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Kolom Kiri: Profil & Status Member -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">Informasi Akun & Member</h3>
                
                <div class="space-y-4">
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase">Nama Lengkap</span>
                        <span class="text-sm font-medium text-gray-850">{{ $member->user->name }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase">Email</span>
                        <span class="text-sm font-medium text-gray-850">{{ $member->user->email }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase">Nomor Telepon</span>
                        <span class="text-sm font-medium text-gray-850">{{ $member->user->phone ?? '-' }}</span>
                    </div>
                    <div class="border-t pt-4">
                        <span class="block text-xs font-semibold text-gray-400 uppercase">Kode Member</span>
                        <span class="text-sm font-mono font-bold text-green-800">{{ $member->member_code }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase">Tingkat Keanggotaan</span>
                        <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-bold rounded-full uppercase mt-1
                            @if($member->tier === 'bronze') bg-amber-100 text-amber-800
                            @elseif($member->tier === 'silver') bg-slate-100 text-slate-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ $member->tier }} (Level {{ $member->level }})
                        </span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase">Total XP saat ini</span>
                        <span class="text-lg font-extrabold text-green-700">{{ $member->xp }} XP</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase">Terdaftar Sejak</span>
                        <span class="text-sm text-gray-600">{{ $member->created_at->format('d F Y, H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Form Penyesuaian XP Manual -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">Penyesuaian XP Manual</h3>
                
                <form action="{{ route('admin.members.adjust-xp', $member) }}" method="POST" class="space-y-4">
                    @csrf
                    <!-- Jenis Penyesuaian -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Aksi</label>
                        <div class="flex gap-4">
                            <label class="inline-flex items-center text-sm">
                                <input type="radio" name="type" value="add" checked class="text-green-600 focus:ring-green-500 border-gray-300">
                                <span class="ml-2 font-medium text-green-700">Tambah XP</span>
                            </label>
                            <label class="inline-flex items-center text-sm">
                                <input type="radio" name="type" value="subtract" class="text-red-655 focus:ring-red-500 border-gray-300">
                                <span class="ml-2 font-medium text-red-700">Kurangi XP</span>
                            </label>
                        </div>
                    </div>

                    <!-- Jumlah XP -->
                    <div>
                        <label for="xp_amount" class="block text-sm font-semibold text-gray-700 mb-1">Jumlah XP</label>
                        <input type="number" name="xp_amount" id="xp_amount" min="1" required 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm"
                               placeholder="Contoh: 50">
                    </div>

                    <!-- Deskripsi / Alasan -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">Keterangan / Alasan</label>
                        <textarea name="description" id="description" rows="3" required
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm"
                                  placeholder="Tuliskan alasan penyesuaian XP..."></textarea>
                    </div>

                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded text-sm transition">
                        Simpan Penyesuaian XP
                    </button>
                </form>
            </div>
        </div>

        <!-- Kolom Kanan: Riwayat Log XP -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">Riwayat Log XP</h3>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Deskripsi / Keterangan</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Jumlah XP</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-150">
                            @forelse($logs as $log)
                            <tr>
                                <td class="px-4 py-3.5 whitespace-nowrap text-xs text-gray-500">
                                    {{ $log->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }}
                                </td>
                                <td class="px-4 py-3.5 text-xs text-gray-700">
                                    {{ $log->description }}
                                </td>
                                <td class="px-4 py-3.5 whitespace-nowrap text-xs text-center font-bold">
                                    @if($log->xp_amount > 0)
                                        <span class="text-green-600">+{{ $log->xp_amount }} XP</span>
                                    @elseif($log->xp_amount < 0)
                                        <span class="text-red-655">{{ $log->xp_amount }} XP</span>
                                    @else
                                        <span class="text-gray-500">0 XP</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-center text-xs text-gray-400">Belum ada riwayat aktivitas XP untuk member ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
