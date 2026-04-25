<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Booking Lapangan: ') }} {{ $field->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 shadow-sm" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col md:flex-row mb-8">
                <div class="md:w-1/3">
                    @if($field->photo)
                        <img src="{{ Storage::url($field->photo) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-64 bg-gray-200 flex items-center justify-center text-gray-500">Tidak ada foto</div>
                    @endif
                </div>
                <div class="p-6 md:w-2/3">
                    <div class="flex justify-between">
                        <h3 class="text-2xl font-bold mb-2 text-gray-900">{{ $field->name }}</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 h-fit">
                            {{ $field->fieldType->name }}
                        </span>
                    </div>
                    <p class="text-gray-600 mb-6">{{ $field->description }}</p>
                    
                    <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg">
                        <div>
                            <span class="block text-sm text-gray-500">Harga Off-peak (08:00 - 16:59)</span>
                            <span class="text-lg font-semibold text-green-700">Rp {{ number_format($field->price_offpeak, 0, ',', '.') }}/jam</span>
                        </div>
                        <div>
                            <span class="block text-sm text-gray-500">Harga Peak (17:00 - 22:00)</span>
                            <span class="text-lg font-semibold text-green-700">Rp {{ number_format($field->price_peak, 0, ',', '.') }}/jam</span>
                        </div>
                        <div class="col-span-2 text-sm text-gray-500 bg-yellow-50 p-2 rounded text-yellow-800 border border-yellow-200">
                            * Harga pada akhir pekan (Sabtu-Minggu) dikenakan biaya tambahan sebesar 20%.
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-xl font-bold mb-4 text-gray-900">Pilih Jadwal</h3>
                
                <form action="{{ route('user.fields.show', $field) }}" method="GET" class="mb-6 flex gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Tanggal</label>
                        <input type="date" name="date" value="{{ $date }}" min="{{ date('Y-m-d') }}" class="rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" onchange="this.form.submit()">
                    </div>
                    @if($isWeekend)
                    <div class="text-sm font-medium text-orange-600 bg-orange-50 px-3 py-2 rounded">
                        Tanggal ini adalah akhir pekan (+20% harga).
                    </div>
                    @endif
                </form>

                <form action="{{ route('user.bookings.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="field_id" value="{{ $field->id }}">
                    <input type="hidden" name="booking_date" value="{{ $date }}">
                    
                    <div class="mb-2 text-sm text-gray-600">Pilih slot waktu yang tersedia (jam berurutan):</div>
                    <div class="grid grid-cols-3 sm:grid-cols-5 md:grid-cols-7 lg:grid-cols-8 gap-3 mb-6">
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
                                <input type="checkbox" name="slots[]" value="{{ $i }}" id="slot_{{ $i }}" class="peer sr-only" {{ $disabled ? 'disabled' : '' }}>
                                <label for="slot_{{ $i }}" class="block text-center cursor-pointer p-2 rounded border border-gray-300 
                                    {{ $disabled ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200' : 'hover:bg-green-50 peer-checked:bg-green-600 peer-checked:text-white peer-checked:border-green-600' }} transition">
                                    <div class="font-medium text-sm">{{ sprintf('%02d:00', $i) }}</div>
                                    <div class="text-xs mt-1 {{ $disabled ? '' : 'text-gray-500 peer-checked:text-green-100' }}">Rp {{ number_format($price/1000, 0, ',', '.') }}k</div>
                                </label>
                                @if($isPeak && !$disabled)
                                    <div class="absolute -top-2 -right-2 bg-yellow-400 text-xs rounded-full h-4 w-4 flex items-center justify-center font-bold" title="Peak Hour">P</div>
                                @endif
                            </div>
                        @endfor
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow transition">
                            Proses Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
