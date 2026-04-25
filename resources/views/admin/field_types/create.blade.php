@extends('layouts.admin')

@section('header', 'Tambah Jenis Lapangan')

@section('content')
    <div class="bg-white rounded-lg shadow p-6 max-w-lg">
        <form action="{{ route('admin.field-types.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Jenis Lapangan</label>
                <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-2 border" required>
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            
            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.field-types.index') }}" class="bg-gray-200 text-gray-700 py-2 px-4 rounded hover:bg-gray-300 transition">Batal</a>
                <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition">Simpan</button>
            </div>
        </form>
    </div>
@endsection
