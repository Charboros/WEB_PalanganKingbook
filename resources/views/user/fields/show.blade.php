<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Booking Lapangan: ') }} {{ $field->name }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 shadow-sm rounded-r">
                    <p class="font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Field Profil Card -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200 flex flex-col md:flex-row mb-8">
                <!-- Foto Lapangan -->
                <div class="md:w-2/5 h-64 md:h-auto overflow-hidden relative">
                    @if($field->photo)
                        <img src="{{ Storage::url($field->photo) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400">
                            <span class="text-sm font-semibold">Foto Belum Tersedia</span>
                        </div>
                    @endif
                    <span class="absolute top-4 left-4 px-3 py-1 rounded bg-white text-green-800 text-xs font-bold border border-green-100 shadow-sm">
                        {{ $field->fieldType->name }}
                    </span>
                </div>

                <!-- Info Lapangan -->
                <div class="p-6 md:w-3/5 flex flex-col justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $field->name }}</h3>
                        <p class="text-gray-600 mb-6 text-sm">{{ $field->description ?? 'Fasilitas lapangan olahraga dengan standar turnamen yang siap menunjang aktivitas Anda.' }}</p>
                    </div>
                    
                    <!-- Harga Tiket Layout -->
                    <div class="bg-green-50 p-4 border border-green-100 rounded-lg">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="block text-gray-500 font-semibold mb-1">Harga Reguler</span>
                                <span class="text-lg font-bold text-green-800">Rp {{ number_format($field->price_offpeak, 0, ',', '.') }}<span class="text-xs font-normal"> / jam</span></span>
                            </div>
                            <div>
                                <span class="block text-gray-500 font-semibold mb-1">
                                    Harga Malam (17:00 - 22:00)
                                </span>
                                <span class="text-lg font-bold text-green-800">Rp {{ number_format($field->price_peak, 0, ',', '.') }}<span class="text-xs font-normal"> / jam</span></span>
                            </div>
                        </div>
                        <div class="mt-3 text-xs text-green-800 bg-green-100 p-2 rounded">
                            Akhir pekan (Sabtu-Minggu) dikenakan biaya tambahan sebesar <strong>20%</strong>.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Section -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200 p-6 sm:p-8">
                <h3 class="text-xl font-bold mb-6 text-gray-900 border-b pb-3">Pilih Jadwal</h3>
                
                <!-- Filter Tanggal -->
                <form action="{{ route('user.fields.show', $field) }}" method="GET" class="mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-end gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Sewa</label>
                            <input type="date" name="date" value="{{ $date }}" min="{{ date('Y-m-d') }}" 
                                   class="rounded-lg border-gray-300 focus:border-green-600 focus:ring-green-600 py-2 px-4 w-full sm:w-auto" 
                                   onchange="this.form.submit()">
                        </div>
                        @if($isWeekend)
                            <div class="text-sm font-semibold text-orange-800 bg-orange-100 px-4 py-2 rounded-lg">
                                Tarif Akhir Pekan (+20%) Berlaku
                            </div>
                        @endif
                    </div>
                </form>

                <!-- Membership Banner Status -->
                @if(auth()->check() && auth()->user()->isMember())
                    <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="text-sm text-green-900">
                            Status Member Anda: <strong class="uppercase">{{ auth()->user()->member->tier }}</strong>.
                            @if(auth()->user()->member->tier === 'bronze')
                                Kumpulkan XP untuk mendapatkan diskon!
                            @elseif(auth()->user()->member->tier === 'silver')
                                Anda mendapat diskon <strong>10%</strong>.
                            @else
                                Anda mendapat diskon <strong>20%</strong>.
                            @endif
                        </div>
                        <a href="{{ route('user.membership') }}" class="text-sm font-bold text-green-700 hover:text-green-800">Detail Membership</a>
                    </div>
                @elseif(auth()->check())
                    <div class="mb-6 p-4 rounded-lg bg-gray-50 border border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <span class="text-sm text-gray-600">Aktifkan keanggotaan member untuk menikmati diskon sewa hingga 20%.</span>
                        <a href="{{ route('user.membership') }}" class="text-sm font-bold text-green-700 hover:text-green-800">Aktifkan Sekarang</a>
                    </div>
                @endif

                <!-- Form Booking Slots -->
                <form action="{{ route('user.bookings.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="field_id" value="{{ $field->id }}">
                    <input type="hidden" name="booking_date" value="{{ $date }}">
                    
                    <div class="mb-3 text-sm font-semibold text-gray-700">Waktu yang tersedia:</div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-7 gap-3 mb-8">
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
                            
                            <div class="relative">
                                <input type="checkbox" name="slots[]" value="{{ $i }}" data-price="{{ $price }}" id="slot_{{ $i }}" class="peer sr-only" {{ $disabled ? 'disabled' : '' }}>
                                <label for="slot_{{ $i }}" class="block text-center cursor-pointer p-3 rounded-lg border 
                                    {{ $disabled ? 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed' : 'bg-green-50 text-green-800 border-green-200 hover:bg-green-100 peer-checked:bg-green-600 peer-checked:text-white peer-checked:border-green-600' }} transition">
                                    <div class="font-bold text-base">{{ sprintf('%02d:00', $i) }}</div>
                                    <div class="text-xs mt-1 {{ $disabled ? '' : 'opacity-80' }}">Rp {{ number_format($price/1000, 0, ',', '.') }}k</div>
                                </label>
                                @if($isPeak && !$disabled)
                                    <div class="absolute -top-2 -right-2 bg-yellow-400 text-yellow-900 text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold" title="Harga Malam">M</div>
                                @endif
                            </div>
                        @endfor
                    </div>

                    <!-- Live Pricing Summary -->
                    <div id="pricing-summary" class="hidden bg-gray-50 border border-gray-200 rounded-lg p-5 mb-6 max-w-sm ml-auto text-sm">
                        <h4 class="font-bold text-gray-700 mb-3 border-b pb-2">Rincian Biaya</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-gray-600">
                                <span>Total Harga Lapangan:</span>
                                <span id="summary-original-price" class="font-semibold text-gray-900">Rp 0</span>
                            </div>
                            @if(auth()->check() && auth()->user()->isMember() && auth()->user()->member->tier !== 'bronze')
                                <div class="flex justify-between text-green-600">
                                    <span>Diskon Member:</span>
                                    <strong id="summary-discount-amount">-Rp 0</strong>
                                </div>
                            @endif
                            <div class="flex justify-between border-t pt-2 mt-2 text-base font-bold text-gray-900">
                                <span>Total Bayar:</span>
                                <span id="summary-total-price" class="text-green-700">Rp 0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Action -->
                    <div class="flex justify-end">
                        <button type="submit" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-8 rounded-lg transition">
                            Lanjutkan Booking
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
