@extends('layouts.admin')

@section('header', 'Daftar Lapangan')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.fields.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow">
            Tambah Lapangan
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Off-peak</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Peak</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($fields as $field)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($field->photo)
                            <img src="{{ Storage::url($field->photo) }}" class="h-10 w-10 object-cover rounded">
                        @else
                            <div class="h-10 w-10 bg-gray-200 rounded flex items-center justify-center text-gray-500 text-xs">No img</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $field->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $field->fieldType->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">Rp {{ number_format($field->price_offpeak, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">Rp {{ number_format($field->price_peak, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($field->is_active)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Tidak Aktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex gap-2">
                        <a href="{{ route('admin.fields.edit', $field) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                        <form action="{{ route('admin.fields.destroy', $field) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $fields->links() }}
        </div>
    </div>
@endsection
