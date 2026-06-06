<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Membership') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                    <p class="font-medium">{{ session('error') }}</p>
                </div>
            @endif

            @if($member)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Kolom Kiri: Kartu Member Digital & Progress XP -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Digital Card Container -->
                        <div class="rounded-xl h-56 shadow-md border 
                            @if($member->tier === 'bronze')
                                bg-amber-700 border-amber-800 text-amber-50
                            @elseif($member->tier === 'silver')
                                bg-slate-600 border-slate-700 text-slate-50
                            @elseif($member->tier === 'gold')
                                bg-yellow-500 border-yellow-600 text-yellow-950
                            @endif
                        ">
                            <div class="p-6 h-full flex flex-col justify-between">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-xs uppercase tracking-widest opacity-90">Sportbook Member</p>
                                        <h3 class="text-xl font-bold mt-1">
                                            @if($member->tier === 'bronze') Bronze Card @elseif($member->tier === 'silver') Silver Card @else Gold Card @endif
                                        </h3>
                                    </div>
                                    <svg class="h-8 w-8 opacity-80" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M4 6h16a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2zm2 3v6h3V9H6zm5 0v6h3V9h-3zm5 0v6h2V9h-2z"/>
                                    </svg>
                                </div>

                                <div>
                                    <p class="text-2xl font-mono tracking-widest font-bold">{{ $member->member_code }}</p>
                                </div>

                                <div class="flex justify-between items-end border-t border-current pt-3 opacity-90">
                                    <div>
                                        <p class="text-[10px] uppercase tracking-wider">Nama Lengkap</p>
                                        <p class="text-sm font-semibold truncate max-w-[150px]">{{ $user->name }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] uppercase tracking-wider">Tingkat</p>
                                        <p class="text-sm font-bold uppercase">
                                            Lvl {{ $member->level }} ({{ $member->tier }})
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar XP Card -->
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                            <h3 class="font-bold text-gray-800 text-lg mb-4">Kemajuan XP</h3>
                            
                            @php
                                $maxXp = 100;
                                $progress = 0;
                                $nextTier = 'Silver';
                                $xpNeeded = 100 - $member->xp;

                                if ($member->tier === 'bronze') {
                                    $maxXp = 100;
                                    $progress = min(100, ($member->xp / 100) * 100);
                                    $nextTier = 'Silver';
                                    $xpNeeded = max(0, 100 - $member->xp);
                                } elseif ($member->tier === 'silver') {
                                    $maxXp = 300;
                                    $progress = min(100, (($member->xp - 100) / 200) * 100);
                                    $nextTier = 'Gold';
                                    $xpNeeded = max(0, 300 - $member->xp);
                                } else {
                                    $maxXp = 300;
                                    $progress = 100;
                                    $nextTier = null;
                                    $xpNeeded = 0;
                                }
                            @endphp

                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                <span>Poin: <strong class="text-gray-900">{{ $member->xp }} XP</strong></span>
                                @if($nextTier)
                                    <span>Target: {{ $maxXp }} XP</span>
                                @else
                                    <span class="text-yellow-600 font-bold">Maksimum</span>
                                @endif
                            </div>

                            <div class="w-full bg-gray-200 rounded-full h-3 mb-4">
                                <div class="bg-green-600 h-3 rounded-full" style="width: {{ $progress }}%"></div>
                            </div>

                            @if($nextTier)
                                <p class="text-sm text-gray-600">
                                    Butuh <strong class="text-green-600">{{ $xpNeeded }} XP</strong> lagi untuk tingkat <strong class="text-gray-800">{{ $nextTier }}</strong>.
                                </p>
                            @else
                                <p class="text-sm text-gray-600">
                                    Anda adalah member <strong class="text-yellow-600">Gold</strong>. Nikmati diskon maksimal 20%.
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Kolom Kanan: Benefit & Riwayat Log XP -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Card Benefit Keanggotaan -->
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                            <h3 class="font-bold text-gray-800 text-lg mb-4">Keuntungan Membership</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Benefit Bronze -->
                                <div class="border rounded-lg p-4 relative {{ $member->tier === 'bronze' ? 'border-amber-600 bg-amber-50' : 'border-gray-200' }}">
                                    @if($member->tier === 'bronze')
                                        <div class="absolute top-0 right-0 bg-amber-600 text-white text-xs px-2 py-1 rounded-bl-lg font-semibold">Aktif</div>
                                    @endif
                                    <h4 class="font-bold text-amber-800 mb-2">Bronze</h4>
                                    <ul class="text-sm text-gray-700 space-y-2">
                                        <li>• Kumpul poin XP</li>
                                        <li>• Diskon sewa 0%</li>
                                    </ul>
                                </div>

                                <!-- Benefit Silver -->
                                <div class="border rounded-lg p-4 relative {{ $member->tier === 'silver' ? 'border-slate-500 bg-slate-100' : 'border-gray-200' }}">
                                    @if($member->tier === 'silver')
                                        <div class="absolute top-0 right-0 bg-slate-500 text-white text-xs px-2 py-1 rounded-bl-lg font-semibold">Aktif</div>
                                    @endif
                                    <h4 class="font-bold text-slate-700 mb-2">Silver</h4>
                                    <ul class="text-sm text-gray-700 space-y-2">
                                        <li>• Benefit Bronze</li>
                                        <li class="font-bold text-green-700">• Diskon sewa 10%</li>
                                        <li class="text-gray-500 text-xs">Syarat: 100 XP</li>
                                    </ul>
                                </div>

                                <!-- Benefit Gold -->
                                <div class="border rounded-lg p-4 relative {{ $member->tier === 'gold' ? 'border-yellow-600 bg-yellow-50' : 'border-gray-200' }}">
                                    @if($member->tier === 'gold')
                                        <div class="absolute top-0 right-0 bg-yellow-600 text-white text-xs px-2 py-1 rounded-bl-lg font-semibold">Aktif</div>
                                    @endif
                                    <h4 class="font-bold text-yellow-700 mb-2">Gold</h4>
                                    <ul class="text-sm text-gray-700 space-y-2">
                                        <li>• Benefit Silver</li>
                                        <li class="font-bold text-green-700">• Diskon sewa 20%</li>
                                        <li class="text-gray-500 text-xs">Syarat: 300 XP</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Riwayat Log XP -->
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                            <h3 class="font-bold text-gray-800 text-lg mb-4">Riwayat Aktivitas XP</h3>
                            
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Tanggal</th>
                                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Deskripsi</th>
                                            <th class="px-4 py-3 text-center font-semibold text-gray-600">XP</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-100">
                                        @forelse($logs as $log)
                                        <tr>
                                            <td class="px-4 py-3 whitespace-nowrap text-gray-500">
                                                {{ $log->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }}
                                            </td>
                                            <td class="px-4 py-3 text-gray-800">
                                                {{ $log->description }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-center font-bold">
                                                @if($log->xp_amount > 0)
                                                    <span class="text-green-600">+{{ $log->xp_amount }}</span>
                                                @elseif($log->xp_amount < 0)
                                                    <span class="text-red-600">{{ $log->xp_amount }}</span>
                                                @else
                                                    <span class="text-gray-500">0</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="px-4 py-4 text-center text-gray-500">Belum ada riwayat aktivitas.</td>
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
            @else
                <!-- Tampilan Ajakan Aktivasi Membership -->
                <div class="max-w-3xl mx-auto bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
                    <div class="bg-green-700 px-6 py-8 text-center text-white">
                        <h3 class="text-2xl font-bold mb-2">Aktifkan Fitur Membership!</h3>
                        <p class="text-green-100">Dapatkan keuntungan loyalitas khusus & potongan harga sewa lapangan hingga 20%</p>
                    </div>
                    
                    <div class="p-8 space-y-8">
                        <div>
                            <h4 class="font-bold text-gray-800 text-lg mb-4 text-center">Bagaimana Cara Kerjanya?</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="text-center">
                                    <div class="h-10 w-10 mx-auto bg-green-100 text-green-700 flex items-center justify-center rounded-full font-bold mb-3">1</div>
                                    <h5 class="font-semibold text-gray-800 mb-1">Aktivasi Gratis</h5>
                                    <p class="text-sm text-gray-600">Daftar sekarang dan otomatis menjadi member tingkat Bronze.</p>
                                </div>
                                <div class="text-center">
                                    <div class="h-10 w-10 mx-auto bg-green-100 text-green-700 flex items-center justify-center rounded-full font-bold mb-3">2</div>
                                    <h5 class="font-semibold text-gray-800 mb-1">Kumpulkan XP</h5>
                                    <p class="text-sm text-gray-600">Dapatkan 10 XP untuk setiap 1 jam penyewaan lapangan.</p>
                                </div>
                                <div class="text-center">
                                    <div class="h-10 w-10 mx-auto bg-green-100 text-green-700 flex items-center justify-center rounded-full font-bold mb-3">3</div>
                                    <h5 class="font-semibold text-gray-800 mb-1">Nikmati Diskon</h5>
                                    <p class="text-sm text-gray-600">Capai tingkat Silver (10%) dan Gold (20%) untuk otomatis mendapatkan potongan harga.</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-center border-t border-gray-100 pt-8">
                            <form action="{{ route('user.membership.activate') }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg transition">
                                    Aktifkan Membership Sekarang
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
