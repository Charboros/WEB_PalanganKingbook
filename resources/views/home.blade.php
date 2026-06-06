<x-app-layout>
    <!-- Modern yet clean Hero Banner -->
    <div class="relative bg-green-700 text-white overflow-hidden shadow-inner">
        <!-- Subtle background decoration -->
        <div class="absolute inset-0 opacity-10 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-green-600 blur-3xl opacity-50"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 py-20 text-center">
            <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight mb-5 drop-shadow-sm">
                Sistem Reservasi Lapangan Olahraga
            </h1>
            <p class="text-lg sm:text-xl text-green-50 max-w-2xl mx-auto font-medium leading-relaxed">
                Temukan dan pesan lapangan favorit Anda untuk futsal, badminton, maupun basket dengan mudah, cepat, dan aman.
            </p>
        </div>
    </div>

    <!-- Search & Filter Bar (Floating effect) -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20 mb-12">
        <form action="{{ route('home') }}" method="GET" class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 flex flex-col md:flex-row gap-5 items-end">
            <!-- Search field -->
            <div class="flex-1 w-full">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Cari Nama Lapangan</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full pl-10 rounded-xl border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600 text-sm py-3 transition" 
                           placeholder="Ketik nama lapangan (e.g., Futsal Jaya)...">
                </div>
            </div>

            <!-- Field Type Filter -->
            <div class="w-full md:w-64">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jenis Lapangan</label>
                <select name="field_type_id" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600 text-sm py-3 transition">
                    <option value="">Semua Jenis Lapangan</option>
                    @foreach($fieldTypes as $type)
                        <option value="{{ $type->id }}" {{ request('field_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Action Button -->
            <div class="w-full md:w-auto">
                <button type="submit" class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-xl shadow-md transition transform hover:-translate-y-0.5">
                    Filter Lapangan
                </button>
            </div>
        </form>
    </div>

    <!-- Main Content Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <div class="flex items-center justify-between mb-8 border-b border-gray-200 pb-4">
            <div>
                <h2 class="text-2xl font-extrabold text-gray-900">Katalog Lapangan</h2>
                <p class="text-sm text-gray-500 mt-1">Pilih lapangan yang tersedia untuk melihat jadwal dan melakukan reservasi.</p>
            </div>
        </div>

        <!-- Fields Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($fields as $field)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden flex flex-col hover:shadow-lg transition-shadow duration-300 group">
                <!-- Image Header Container -->
                <div class="h-52 bg-gray-100 w-full relative overflow-hidden">
                    @if($field->photo)
                        <img src="{{ Storage::url($field->photo) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-50">
                            <svg class="h-10 w-10 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Belum Ada Foto</span>
                        </div>
                    @endif
                    <!-- Category Badge -->
                    <span class="absolute top-4 right-4 px-3 py-1 rounded-full bg-white text-green-700 text-xs font-bold border border-green-100 shadow-sm">
                        {{ $field->fieldType->name }}
                    </span>
                </div>

                <!-- Card Body -->
                <div class="p-6 flex flex-col flex-1">
                    <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-green-700 transition-colors">{{ $field->name }}</h3>
                    
                    <p class="text-sm text-gray-600 mb-5 flex-1 line-clamp-2 leading-relaxed">
                        {{ $field->description ?? 'Nikmati fasilitas lapangan prima dengan standar turnamen khusus untuk menunjang aktivitas olahraga terbaik Anda.' }}
                    </p>

                    <!-- Price Info (Modern Layout) -->
                    <div class="grid grid-cols-2 gap-3 mb-6 bg-green-50/50 p-4 rounded-xl border border-green-100/50">
                        <div>
                            <span class="block text-[10px] uppercase font-bold tracking-wider text-gray-500 mb-1">Reguler (Pagi-Sore)</span>
                            <span class="text-base font-extrabold text-green-700">Rp {{ number_format($field->price_offpeak, 0, ',', '.') }}<span class="text-[10px] font-normal text-gray-500">/jam</span></span>
                        </div>
                        <div class="border-l border-green-200/50 pl-3">
                            <span class="block text-[10px] uppercase font-bold tracking-wider text-gray-500 mb-1 flex items-center gap-1">
                                Peak (17:00-22:00)
                            </span>
                            <span class="text-base font-extrabold text-green-700">Rp {{ number_format($field->price_peak, 0, ',', '.') }}<span class="text-[10px] font-normal text-gray-500">/jam</span></span>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <a href="{{ route('user.fields.show', $field) }}" 
                       class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-xl transition duration-200 shadow-sm">
                        Lihat Jadwal & Booking
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full bg-white rounded-2xl border border-gray-200 p-12 text-center text-gray-500 shadow-sm">
                <svg class="h-16 w-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h4 class="text-lg font-bold text-gray-800 mb-1">Tidak Ada Lapangan Tersedia</h4>
                <p class="text-sm text-gray-500">Silakan ubah kata kunci atau jenis filter pencarian Anda.</p>
            </div>
            @endforelse
        </div>
        
        <!-- Pagination Links -->
        <div class="mt-10">
            {{ $fields->links() }}
        </div>
    </div>
</x-app-layout>
