<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Membership') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-sm rounded-r" role="alert">
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 shadow-sm rounded-r" role="alert">
                    <p class="font-medium">{{ session('error') }}</p>
                </div>
            @endif

            @if($member)
                <!-- Detail Membership Aktif -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- Kolom Kiri: Kartu Member Digital & Progress XP -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Digital Card Container -->
                        <div class="relative overflow-hidden rounded-2xl h-56 shadow-2xl transition duration-500 hover:scale-105
                            @if($member->tier === 'bronze')
                                bg-gradient-to-br from-amber-700 via-amber-800 to-yellow-950 text-amber-50
                            @elseif($member->tier === 'silver')
                                bg-gradient-to-br from-slate-400 via-slate-500 to-slate-700 text-slate-50
                            @elseif($member->tier === 'gold')
                                bg-gradient-to-br from-yellow-400 via-amber-500 to-yellow-700 text-amber-950
                            @endif
                        ">
                            <!-- Shine Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white to-transparent opacity-10 rotate-12 -translate-y-full animate-pulse"></div>
                            
                            <!-- Card Content -->
                            <div class="p-6 h-full flex flex-col justify-between relative z-10">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-xs uppercase tracking-widest opacity-80">Sportbook Member</p>
                                        <h3 class="text-lg font-bold tracking-wider mt-1">
                                            @if($member->tier === 'bronze') Bronze Card @elseif($member->tier === 'silver') Silver Card @else Gold Card @endif
                                        </h3>
                                    </div>
                                    <!-- Card Chip SVG -->
                                    <svg class="h-10 w-10 opacity-90 @if($member->tier === 'gold') text-amber-950 @else text-amber-200 @endif" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M4 6h16a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2zm2 3v6h3V9H6zm5 0v6h3V9h-3zm5 0v6h2V9h-2z" opacity=".8"/>
                                    </svg>
                                </div>

                                <div>
                                    <p class="text-2xl font-mono tracking-widest font-bold">{{ $member->member_code }}</p>
                                </div>

                                <div class="flex justify-between items-end border-t @if($member->tier === 'gold') border-amber-900/30 @else border-white/20 @endif pt-3">
                                    <div>
                                        <p class="text-[10px] uppercase tracking-wider opacity-70">Nama Lengkap</p>
                                        <p class="text-sm font-semibold truncate max-w-[180px]">{{ $user->name }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] uppercase tracking-wider opacity-70">Tingkat</p>
                                        <p class="text-sm font-black uppercase tracking-widest">
                                            Lvl {{ $member->level }} ({{ $member->tier }})
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar XP Card -->
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
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
                                    // Progress bar counts from 100 to 300
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
                                <span>Total Poin: <strong class="text-gray-900 font-semibold">{{ $member->xp }} XP</strong></span>
                                @if($nextTier)
                                    <span>Target: {{ $maxXp }} XP</span>
                                @else
                                    <span class="text-yellow-600 font-bold">Tingkat Maksimum</span>
                                @endif
                            </div>

                            <div class="w-full bg-gray-100 rounded-full h-3.5 mb-4">
                                <div class="bg-green-600 h-3.5 rounded-full transition-all duration-1000" style="width: {{ $progress }}%"></div>
                            </div>

                            @if($nextTier)
                                <p class="text-sm text-gray-600">
                                    Butuh <strong class="text-green-600 font-bold">{{ $xpNeeded }} XP</strong> lagi untuk naik ke tingkat <strong class="text-gray-800 font-semibold">{{ $nextTier }}</strong>.
                                </p>
                            @else
                                <p class="text-sm text-gray-600">
                                    Luar biasa! Anda adalah member premium <strong class="text-yellow-600 font-bold">Gold</strong>. Anda mendapatkan diskon maksimal 20% untuk setiap pemesanan.
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Kolom Kanan: Benefit & Riwayat Log XP -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Card Benefit Keanggotaan -->
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                            <h3 class="font-bold text-gray-800 text-lg mb-4">Keuntungan Membership</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Benefit Bronze -->
                                <div class="border rounded-lg p-4 transition hover:shadow-md relative overflow-hidden {{ $member->tier === 'bronze' ? 'border-amber-600 bg-amber-50/10' : 'border-gray-100' }}">
                                    @if($member->tier === 'bronze')
                                        <div class="absolute top-0 right-0 bg-amber-600 text-white text-[10px] px-2 py-0.5 rounded-bl">Aktif</div>
                                    @endif
                                    <h4 class="font-bold text-amber-800 mb-2">Bronze Member</h4>
                                    <ul class="text-xs text-gray-600 space-y-2">
                                        <li class="flex items-center gap-1.5">
                                            <svg class="h-4 w-4 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            Status member aktif
                                        </li>
                                        <li class="flex items-center gap-1.5">
                                            <svg class="h-4 w-4 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            Kumpul poin XP setiap jam sewa
                                        </li>
                                        <li class="flex items-center gap-1.5 opacity-55">
                                            <svg class="h-4 w-4 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Diskon pemesanan 0%
                                        </li>
                                    </ul>
                                </div>

                                <!-- Benefit Silver -->
                                <div class="border rounded-lg p-4 transition hover:shadow-md relative overflow-hidden {{ $member->tier === 'silver' ? 'border-slate-500 bg-slate-50/20' : 'border-gray-100' }}">
                                    @if($member->tier === 'silver')
                                        <div class="absolute top-0 right-0 bg-slate-500 text-white text-[10px] px-2 py-0.5 rounded-bl">Aktif</div>
                                    @endif
                                    <h4 class="font-bold text-slate-800 mb-2">Silver Member</h4>
                                    <ul class="text-xs text-gray-600 space-y-2">
                                        <li class="flex items-center gap-1.5">
                                            <svg class="h-4 w-4 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            Semua benefit Bronze
                                        </li>
                                        <li class="flex items-center gap-1.5 font-semibold text-green-700">
                                            <svg class="h-4 w-4 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            Diskon pemesanan 10%
                                        </li>
                                        <li class="flex items-center gap-1.5">
                                            <svg class="h-4 w-4 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            Persyaratan: 100 XP
                                        </li>
                                    </ul>
                                </div>

                                <!-- Benefit Gold -->
                                <div class="border rounded-lg p-4 transition hover:shadow-md relative overflow-hidden {{ $member->tier === 'gold' ? 'border-yellow-600 bg-yellow-50/10' : 'border-gray-100' }}">
                                    @if($member->tier === 'gold')
                                        <div class="absolute top-0 right-0 bg-yellow-600 text-white text-[10px] px-2 py-0.5 rounded-bl">Aktif</div>
                                    @endif
                                    <h4 class="font-bold text-yellow-800 mb-2">Gold Member</h4>
                                    <ul class="text-xs text-gray-600 space-y-2">
                                        <li class="flex items-center gap-1.5">
                                            <svg class="h-4 w-4 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            Semua benefit Silver
                                        </li>
                                        <li class="flex items-center gap-1.5 font-semibold text-green-700">
                                            <svg class="h-4 w-4 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            Diskon pemesanan 20%
                                        </li>
                                        <li class="flex items-center gap-1.5">
                                            <svg class="h-4 w-4 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            Persyaratan: 300 XP
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Riwayat Log XP -->
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                            <h3 class="font-bold text-gray-800 text-lg mb-4">Riwayat Poin XP</h3>
                            
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Deskripsi</th>
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
                                                    <span class="text-red-600">{{ $log->xp_amount }} XP</span>
                                                @else
                                                    <span class="text-gray-500">0 XP</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="px-4 py-4 text-center text-xs text-gray-400">Belum ada riwayat aktivitas XP.</td>
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
                <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-lg border border-gray-150 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-600 to-emerald-700 px-6 py-8 text-center text-white relative">
                        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-white/10 via-transparent to-transparent"></div>
                        <h3 class="text-3xl font-extrabold mb-2 relative z-10">Aktifkan Fitur Membership!</h3>
                        <p class="text-green-100 text-sm relative z-10">Dapatkan keuntungan loyalitas khusus & potongan harga sewa lapangan hingga 20%</p>
                    </div>
                    
                    <div class="p-8 space-y-6">
                        <div class="space-y-4">
                            <h4 class="font-bold text-gray-800 text-lg">Bagaimana Cara Kerjanya?</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="flex flex-col items-center text-center p-4 border rounded-xl bg-gray-50">
                                    <span class="h-10 w-10 bg-green-100 text-green-700 flex items-center justify-center rounded-full font-bold text-lg mb-3">1</span>
                                    <h5 class="font-semibold text-sm text-gray-800 mb-1">Aktivasi Gratis</h5>
                                    <p class="text-xs text-gray-500">Aktifkan fitur member secara instan dan mulai dari tingkat Bronze.</p>
                                </div>
                                <div class="flex flex-col items-center text-center p-4 border rounded-xl bg-gray-50">
                                    <span class="h-10 w-10 bg-green-100 text-green-700 flex items-center justify-center rounded-full font-bold text-lg mb-3">2</span>
                                    <h5 class="font-semibold text-sm text-gray-800 mb-1">Kumpulkan XP</h5>
                                    <p class="text-xs text-gray-500">Setiap 1 jam penyewaan lapangan yang disetujui akan menghasilkan 10 XP.</p>
                                </div>
                                <div class="flex flex-col items-center text-center p-4 border rounded-xl bg-gray-50">
                                    <span class="h-10 w-10 bg-green-100 text-green-700 flex items-center justify-center rounded-full font-bold text-lg mb-3">3</span>
                                    <h5 class="font-semibold text-sm text-gray-800 mb-1">Naik Tingkat & Diskon</h5>
                                    <p class="text-xs text-gray-500">Capai tingkat Silver (diskon 10%) dan Gold (diskon 20%) secara otomatis.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Skema Tingkatan Ringkas -->
                        <div class="border rounded-xl p-6 bg-green-50/20 border-green-100">
                            <h4 class="font-bold text-green-900 text-sm mb-3">Skema Diskon & Persyaratan XP</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center text-sm border-b pb-2 border-green-100/50">
                                    <span class="font-bold text-amber-800">Bronze Member (Awal)</span>
                                    <span class="text-gray-600">Diskon 0% • Min. 0 XP</span>
                                </div>
                                <div class="flex justify-between items-center text-sm border-b pb-2 border-green-100/50">
                                    <span class="font-bold text-slate-700">Silver Member</span>
                                    <span class="text-green-700 font-semibold">Diskon 10% • Min. 100 XP</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="font-bold text-yellow-700">Gold Member</span>
                                    <span class="text-green-700 font-semibold">Diskon 20% • Min. 300 XP</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-center pt-2">
                            <form action="{{ route('user.membership.activate') }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-full shadow-md transition transform hover:-translate-y-0.5">
                                    Aktifkan Fitur Membership Sekarang
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
