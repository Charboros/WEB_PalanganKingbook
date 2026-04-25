@extends('layouts.admin')

@section('header', 'Tambah Lapangan')

@section('content')
    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <form action="{{ route('admin.fields.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lapangan</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-md border-gray-300 p-2 border focus:border-green-500 focus:ring-green-500" required>
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Lapangan</label>
                    <select name="field_type_id" class="w-full rounded-md border-gray-300 p-2 border focus:border-green-500 focus:ring-green-500" required>
                        <option value="">-- Pilih Jenis --</option>
                        @foreach($fieldTypes as $type)
                            <option value="{{ $type->id }}" {{ old('field_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                    @error('field_type_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Off-peak (Rp)</label>
                    <input type="number" name="price_offpeak" value="{{ old('price_offpeak') }}" class="w-full rounded-md border-gray-300 p-2 border focus:border-green-500 focus:ring-green-500" required min="0">
                    @error('price_offpeak') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Peak (17:00-22:00) (Rp)</label>
                    <input type="number" name="price_peak" value="{{ old('price_peak') }}" class="w-full rounded-md border-gray-300 p-2 border focus:border-green-500 focus:ring-green-500" required min="0">
                    @error('price_peak') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full rounded-md border-gray-300 p-2 border focus:border-green-500 focus:ring-green-500">{{ old('description') }}</textarea>
                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto</label>
                <input type="file" name="photo" class="w-full">
                @error('photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6 flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                <label for="is_active" class="ml-2 block text-sm text-gray-900">Aktif</label>
            </div>
            
            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.fields.index') }}" class="bg-gray-200 text-gray-700 py-2 px-4 rounded hover:bg-gray-300 transition">Batal</a>
                <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition">Simpan</button>
            </div>
        </form>
    </div>
@endsection
