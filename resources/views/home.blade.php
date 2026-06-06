<x-app-layout>
    <!-- Premium Hero Banner -->
    <div class="relative bg-gradient-to-br from-emerald-800 via-green-700 to-teal-900 text-white overflow-hidden py-20">
        <!-- Abstract background pattern -->
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:24px_24px]"></div>
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-green-400/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-emerald-400/20 rounded-full blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <!-- Modern Badge -->
            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-green-500/25 text-green-200 border border-green-400/20 mb-6">
                <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                ⚡ Premium Sports Venue Booking
            </span>
            <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight mb-4">
                Sistem Reservasi Lapangan Olahraga
            </h1>
            <p class="text-lg sm:text-xl text-emerald-100 max-w-3xl mx-auto font-light leading-relaxed">
                Booking lapangan berkualitas premium untuk futsal, badminton, dan basket dengan mudah, instan, dan terpercaya.
            </p>
        </div>
    </div>

    <!-- Floating Search & Filter Bar -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20">
        <form action="{{ route('home') }}" method="GET" class="bg-white/95 backdrop-blur-md p-6 rounded-2xl shadow-xl border border-gray-100 flex flex-col md:flex-row gap-4 items-end">
            <!-- Search field -->
            <div class="flex-1 w-full">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Cari Lapangan</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full pl-10 rounded-xl border-gray-250 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm py-2.5" 
                           placeholder="Ketik nama lapangan (e.g. Lapangan Futsal)...">
                </div>
            </div>

            <!-- Field Type Filter -->
            <div class="w-full md:w-64">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Jenis Cabang Olahraga</label>
                <select name="field_type_id" class="w-full rounded-xl border-gray-250 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm py-2.5">
                    <option value="">Semua Jenis</option>
                    @foreach($fieldTypes as $type)
                        <option value="{{ $type->id }}" {{ request('field_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Action Button -->
            <div class="w-full md:w-auto">
                <button type="submit" class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-8 rounded-xl shadow-md transition transform hover:-translate-y-0.5">
                    Filter Lapangan
                </button>
            </div>
        </form>
    </div>

    <!-- Main Content Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex justify-between items-center mb-8 border-b pb-4 border-gray-100">
            <div>
                <h2 class="text-2xl font-extrabold text-gray-800">Daftar Lapangan Tersedia</h2>
                <p class="text-sm text-gray-500 mt-1">Pilih lapangan favorit Anda dan langsung lakukan reservasi jadwal.</p>
            </div>
        </div>

        <!-- Fields Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($fields as $field)
            <div class="group bg-white rounded-2xl shadow-sm border border-gray-150 overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col hover:-translate-y-1.5">
                <!-- Image Header Container -->
                <div class="h-56 bg-gray-100 w-full overflow-hidden relative">
                    @if($field->photo)
                        <img src="{{ Storage::url($field->photo) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-50">
                            <svg class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-xs text-gray-400 mt-2 font-medium">Gambar Tidak Tersedia</span>
                        </div>
                    @endif
                    <!-- Category Badge floating on image -->
                    <span class="absolute top-4 right-4 inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white/90 backdrop-blur text-green-800 shadow-sm">
                        {{ $field->fieldType->name }}
                    </span>
                </div>

                <!-- Card Body -->
                <div class="p-6 flex flex-col flex-1">
                    <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-green-700 transition duration-300">{{ $field->name }}</h3>
                    
                    <p class="text-sm text-gray-600 mb-6 flex-1 line-clamp-2 leading-relaxed">
                        {{ $field->description ?? 'Nikmati fasilitas lapangan prima dengan standar turnamen khusus untuk menunjang aktivitas olahraga terbaik Anda.' }}
                    </p>

                    <!-- Price Ticket Layout -->
                    <div class="grid grid-cols-2 gap-4 p-3.5 bg-gray-50/70 border border-gray-100 rounded-xl mb-6 text-xs">
                        <div>
                            <span class="block text-[10px] uppercase font-bold tracking-wider text-gray-400">Harga Jam Biasa</span>
                            <span class="font-extrabold text-green-700 text-sm">Rp {{ number_format($field->price_offpeak, 0, ',', '.') }}<span class="text-[10px] text-gray-400 font-medium">/jam</span></span>
                        </div>
                        <div class="border-l border-gray-200/60 pl-4">
                            <span class="block text-[10px] uppercase font-bold tracking-wider text-gray-400 flex items-center gap-1">
                                Harga Peak
                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500" title="Peak Hours (17:00 - 22:00)"></span>
                            </span>
                            <span class="font-extrabold text-green-700 text-sm">Rp {{ number_format($field->price_peak, 0, ',', '.') }}<span class="text-[10px] text-gray-400 font-medium">/jam</span></span>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <a href="{{ route('user.fields.show', $field) }}" 
                       class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-xl transition duration-300 shadow-sm hover:shadow-md">
                        Lihat Jadwal & Booking
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full bg-white rounded-2xl border border-gray-150 p-12 text-center text-gray-500">
                <svg class="h-16 w-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h4 class="text-lg font-bold text-gray-700 mb-1">Tidak Ada Lapangan Ditemukan</h4>
                <p class="text-sm text-gray-400">Silakan sesuaikan filter pencarian atau kata kunci pencarian Anda.</p>
            </div>
            @endforelse
        </div>
        
        <!-- Pagination Links -->
        <div class="mt-10">
            {{ $fields->links() }}
        </div>
    </div>
</x-app-layout>
