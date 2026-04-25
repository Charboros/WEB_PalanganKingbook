<x-app-layout>
    <div class="bg-green-700 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold mb-4">Sistem Reservasi Lapangan Olahraga</h1>
            <p class="text-xl text-green-100">Booking lapangan futsal, badminton, dan basket dengan mudah dan cepat.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form action="{{ route('home') }}" method="GET" class="bg-white p-6 rounded-lg shadow-md mb-8 flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari Lapangan</label>
                <input type="text" name="search" value="{{ request('search') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="Ketik nama lapangan...">
            </div>
            <div class="w-full md:w-64">
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Lapangan</label>
                <select name="field_type_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    <option value="">Semua Jenis</option>
                    @foreach($fieldTypes as $type)
                        <option value="{{ $type->id }}" {{ request('field_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow">Filter</button>
            </div>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($fields as $field)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition flex flex-col">
                <div class="h-48 bg-gray-200 w-full object-cover">
                    @if($field->photo)
                        <img src="{{ Storage::url($field->photo) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">No Image</div>
                    @endif
                </div>
                <div class="p-6 flex flex-col flex-1">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-xl font-bold text-gray-900">{{ $field->name }}</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $field->fieldType->name }}
                        </span>
                    </div>
                    <p class="text-gray-600 mb-4 flex-1 line-clamp-2">{{ $field->description }}</p>
                    <div class="mt-auto">
                        <div class="flex justify-between text-sm mb-4">
                            <div>
                                <span class="block text-gray-500">Harga Off-peak</span>
                                <span class="font-semibold text-green-700">Rp {{ number_format($field->price_offpeak, 0, ',', '.') }}/jam</span>
                            </div>
                            <div class="text-right">
                                <span class="block text-gray-500">Harga Peak (17-22)</span>
                                <span class="font-semibold text-green-700">Rp {{ number_format($field->price_peak, 0, ',', '.') }}/jam</span>
                            </div>
                        </div>
                        <a href="{{ route('user.fields.show', $field) }}" class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded transition">
                            Lihat & Booking
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="mt-8">
            {{ $fields->links() }}
        </div>
    </div>
</x-app-layout>
