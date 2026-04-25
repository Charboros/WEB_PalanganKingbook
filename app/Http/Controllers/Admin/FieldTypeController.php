<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FieldType;
use Illuminate\Http\Request;

class FieldTypeController extends Controller
{
    public function index()
    {
        $fieldTypes = FieldType::paginate(10);
        return view('admin.field_types.index', compact('fieldTypes'));
    }

    public function create()
    {
        return view('admin.field_types.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:50|unique:field_types']);
        FieldType::create($request->all());
        return redirect()->route('admin.field-types.index')->with('success', 'Jenis lapangan berhasil ditambahkan.');
    }

    public function edit(FieldType $fieldType)
    {
        return view('admin.field_types.edit', compact('fieldType'));
    }

    public function update(Request $request, FieldType $fieldType)
    {
        $request->validate(['name' => 'required|string|max:50|unique:field_types,name,' . $fieldType->id]);
        $fieldType->update($request->all());
        return redirect()->route('admin.field-types.index')->with('success', 'Jenis lapangan berhasil diperbarui.');
    }

    public function destroy(FieldType $fieldType)
    {
        if ($fieldType->fields()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus jenis lapangan karena masih memiliki lapangan.');
        }
        $fieldType->delete();
        return redirect()->route('admin.field-types.index')->with('success', 'Jenis lapangan berhasil dihapus.');
    }
}
