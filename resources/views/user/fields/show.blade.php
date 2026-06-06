<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Booking Lapangan: ') }} {{ $field->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 shadow-sm rounded-r" role="alert">
                    <p class="font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Field Profil Card -->
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-150 flex flex-col md:flex-row mb-8">
                <!-- Foto Lapangan -->
                <div class="md:w-2/5 h-64 md:h-auto overflow-hidden relative">
                    @if($field->photo)
                        <img src="{{ Storage::url($field->photo) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gray-50 flex flex-col items-center justify-center text-gray-400">
                            <svg class="h-16 w-16 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-xs font-semibold">Foto Belum Tersedia</span>
                        </div>
                    @endif
                    <span class="absolute top-4 left-4 inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white/90 backdrop-blur text-green-800 shadow-sm">
                        {{ $field->fieldType->name }}
                    </span>
                </div>

                <!-- Info Lapangan -->
                <div class="p-8 md:w-3/5 flex flex-col justify-between">
                    <div>
                        <h3 class="text-3xl font-extrabold text-gray-900 mb-3">{{ $field->name }}</h3>
                        <p class="text-gray-650 leading-relaxed mb-6">{{ $field->description ?? 'Rasakan sensasi bermain di lapangan berkualitas standar turnamen. Dilengkapi dengan pencahayaan memadai untuk aktivitas siang maupun malam.' }}</p>
                    </div>
                    
                    <!-- Harga Tiket Layout -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-gray-55/70 p-4 border border-gray-100 rounded-xl">
                        <div>
                            <span class="block text-[10px] uppercase font-bold tracking-wider text-gray-400">Harga Jam Biasa (Off-Peak)</span>
                            <span class="text-lg font-extrabold text-green-700">Rp {{ number_format($field->price_offpeak, 0, ',', '.') }}<span class="text-xs text-gray-450 font-normal"> / jam</span></span>
                        </div>
                        <div class="sm:border-l sm:border-gray-200 sm:pl-4">
                            <span class="block text-[10px] uppercase font-bold tracking-wider text-gray-400 flex items-center gap-1">
                                Harga Jam Sibuk (Peak Hours)
                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500" title="17:00 - 22:00"></span>
                            </span>
                            <span class="text-lg font-extrabold text-green-700">Rp {{ number_format($field->price_peak, 0, ',', '.') }}<span class="text-xs text-gray-450 font-normal"> / jam</span></span>
                        </div>
                        <div class="col-span-1 sm:col-span-2 text-xs text-amber-800 bg-amber-50/50 p-2.5 rounded-lg border border-amber-250/30 flex items-center gap-1.5 mt-2">
                            <svg class="h-4 w-4 text-amber-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span>Akhir pekan (Sabtu-Minggu) dikenakan biaya tambahan sebesar <strong>20%</strong>.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Section -->
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-150 p-8">
                <h3 class="text-2xl font-bold mb-6 text-gray-900 border-b pb-3">Konfigurasi Jadwal & Booking</h3>
                
                <!-- Filter Tanggal -->
                <form action="{{ route('user.fields.show', $field) }}" method="GET" class="mb-8 flex flex-col sm:flex-row gap-4 items-start sm:items-end">
                    <div class="w-full sm:w-auto">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Pilih Tanggal Sewa</label>
                        <input type="date" name="date" value="{{ $date }}" min="{{ date('Y-m-d') }}" 
                               class="rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm py-2 px-4 w-full" 
                               onchange="this.form.submit()">
                    </div>
                    @if($isWeekend)
                        <div class="text-sm font-bold text-orange-700 bg-orange-50/70 border border-orange-200/50 px-4 py-2 rounded-xl flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-orange-500 animate-pulse"></span>
                            Tanggal ini adalah Akhir Pekan (Tambahan tarif +20%)
                        </div>
                    @endif
                </form>

                <!-- Membership Banner Status -->
                @if(auth()->check() && auth()->user()->isMember())
                    <div class="mb-8 p-4 rounded-xl bg-green-50/70 border border-green-200/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 shadow-sm">
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 text-xs font-black uppercase rounded-full tracking-wider
                                @if(auth()->user()->member->tier === 'bronze') bg-amber-100 text-amber-800
                                @elseif(auth()->user()->member->tier === 'silver') bg-slate-100 text-slate-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ auth()->user()->member->tier }} member
                            </span>
                            <span class="text-sm text-gray-700">
                                @if(auth()->user()->member->tier === 'bronze')
                                    Anda berada di tingkat Bronze (belum ada diskon). Kumpulkan 100 XP untuk otomatis diskon 10%!
                                @elseif(auth()->user()->member->tier === 'silver')
                                    Anda menghemat <strong class="text-green-700 font-bold">10%</strong> untuk setiap pemesanan lapangan!
                                @else
                                    Luar biasa! Diskon member maksimal <strong class="text-green-700 font-bold">20%</strong> telah aktif untuk akun Anda!
                                @endif
                            </span>
                        </div>
                        <a href="{{ route('user.membership') }}" class="text-xs text-green-700 hover:underline font-bold whitespace-nowrap">Membership Saya &rarr;</a>
                    </div>
                @elseif(auth()->check())
                    <div class="mb-8 p-4 rounded-xl bg-gray-50 border border-gray-250/60 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <span class="text-sm text-gray-600">Aktifkan keanggotaan member untuk mengumpulkan XP dan nikmati diskon sewa sepeser pun hingga 20%!</span>
                        <a href="{{ route('user.membership') }}" class="text-xs text-green-700 hover:underline font-bold whitespace-nowrap">Aktifkan Gratis Sekarang &rarr;</a>
                    </div>
                @endif

                <!-- Form Booking Slots -->
                <form action="{{ route('user.bookings.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="field_id" value="{{ $field->id }}">
                    <input type="hidden" name="booking_date" value="{{ $date }}">
                    
                    <div class="mb-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Pilih Slot Waktu Yang Tersedia:</div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-7 gap-4 mb-8">
                        @for($i = 8; $i <= 21; $i++)
                            @php
                                $isBooked = in_array($i, $bookedSlots);
                                $isPeak = ($i >= 17 && $i <= 22);
                                $basePrice = $isPeak ? $field->price_peak : $field->price_offpeak;
                                $price = $isWeekend ? $basePrice * 1.2 : $basePrice;
                                $timeString = sprintf('%02d:00 - %02d:00', $i, $i+1);
                                
                                // Disable past slots today
                                $isPast = ($date == date('Y-m-d') && $i <= date('H'));
                                $disabled = $isBooked || $isPast;
                            @endphp
                            
                            <div class="relative group">
                                <input type="checkbox" name="slots[]" value="{{ $i }}" data-price="{{ $price }}" id="slot_{{ $i }}" class="peer sr-only" {{ $disabled ? 'disabled' : '' }}>
                                <label for="slot_{{ $i }}" class="block text-center cursor-pointer p-3 rounded-xl border border-gray-200 
                                    {{ $disabled ? 'bg-gray-55 text-gray-300 cursor-not-allowed border-gray-150' : 'hover:border-green-500 hover:bg-green-50/30 peer-checked:bg-gradient-to-br peer-checked:from-green-600 peer-checked:to-emerald-700 peer-checked:text-white peer-checked:border-green-600 peer-checked:shadow-md' }} transition duration-200">
                                    <div class="font-bold text-sm">{{ sprintf('%02d:00', $i) }}</div>
                                    <div class="text-[10px] mt-1 {{ $disabled ? '' : 'text-gray-400 peer-checked:text-green-100 font-semibold' }}">Rp {{ number_format($price/1000, 0, ',', '.') }}k</div>
                                </label>
                                @if($isPeak && !$disabled)
                                    <div class="absolute -top-1.5 -right-1.5 bg-yellow-400 text-yellow-950 text-[9px] rounded-full h-4.5 w-4.5 flex items-center justify-center font-extrabold border border-white shadow-sm" title="Jam Sibuk (Peak Hours)">P</div>
                                @endif
                            </div>
                        @endfor
                    </div>

                    <!-- Live Pricing Summary (Kwitansi Visual) -->
                    <div id="pricing-summary" class="hidden bg-gray-50/70 border border-gray-150 rounded-2xl p-6 mb-8 max-w-md ml-auto">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3 border-b pb-2">Rincian Estimasi Biaya</h4>
                        <div class="space-y-2.5 text-sm text-gray-600">
                            <div class="flex justify-between">
                                <span>Harga Lapangan:</span>
                                <span id="summary-original-price" class="font-semibold text-gray-900">Rp 0</span>
                            </div>
                            @if(auth()->check() && auth()->user()->isMember() && auth()->user()->member->tier !== 'bronze')
                                <div class="flex justify-between text-green-600">
                                    <span>Diskon Member ({{ auth()->user()->member->tier === 'silver' ? '10%' : '20%' }}):</span>
                                    <strong id="summary-discount-amount" class="font-bold">-Rp 0</strong>
                                </div>
                            @endif
                            <div class="flex justify-between border-t border-dashed border-gray-200 pt-3 text-base text-gray-900 font-extrabold">
                                <span>Total Bayar:</span>
                                <span id="summary-total-price" class="text-green-700 text-lg">Rp 0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Action -->
                    <div class="flex justify-end">
                        <button type="submit" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-xl shadow transition duration-200 transform hover:-translate-y-0.5">
                            Lanjutkan Proses Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Live Pricing Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('input[name="slots[]"]');
            const summaryDiv = document.getElementById('pricing-summary');
            const originalPriceSpan = document.getElementById('summary-original-price');
            const discountAmountSpan = document.getElementById('summary-discount-amount');
            const totalPriceSpan = document.getElementById('summary-total-price');

            // Discount rate
            let discountRate = 0;
            @if(auth()->check() && auth()->user()->isMember())
                @if(auth()->user()->member->tier === 'silver')
                    discountRate = 0.10;
                @elseif(auth()->user()->member->tier === 'gold')
                    discountRate = 0.20;
                @endif
            @endif

            function formatRupiah(number) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
            }

            function updatePrice() {
                let originalPrice = 0;
                let checkedCount = 0;

                checkboxes.forEach(cb => {
                    if (cb.checked) {
                        originalPrice += parseFloat(cb.getAttribute('data-price'));
                        checkedCount++;
                    }
                });

                if (checkedCount > 0) {
                    summaryDiv.classList.remove('hidden');
                    let discountAmount = originalPrice * discountRate;
                    let totalPrice = originalPrice - discountAmount;

                    originalPriceSpan.textContent = formatRupiah(originalPrice);
                    if (discountAmountSpan) {
                        discountAmountSpan.textContent = '- ' + formatRupiah(discountAmount);
                    }
                    totalPriceSpan.textContent = formatRupiah(totalPrice);
                } else {
                    summaryDiv.classList.add('hidden');
                }
            }

            checkboxes.forEach(cb => {
                cb.addEventListener('change', updatePrice);
            });
        });
    </script>
</x-app-layout>
