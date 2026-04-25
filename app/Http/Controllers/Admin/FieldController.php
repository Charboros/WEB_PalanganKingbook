<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Field;
use App\Models\FieldType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FieldController extends Controller
{
    public function index()
    {
        $fields = Field::with('fieldType')->paginate(10);
        return view('admin.fields.index', compact('fields'));
    }

    public function create()
    {
        $fieldTypes = FieldType::all();
        return view('admin.fields.create', compact('fieldTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'field_type_id' => 'required|exists:field_types,id',
            'name' => 'required|string|max:50',
            'price_offpeak' => 'required|numeric|min:0',
            'price_peak' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('fields', 'public');
        }

        Field::create($data);
        return redirect()->route('admin.fields.index')->with('success', 'Lapangan berhasil ditambahkan.');
    }

    public function edit(Field $field)
    {
        $fieldTypes = FieldType::all();
        return view('admin.fields.edit', compact('field', 'fieldTypes'));
    }

    public function update(Request $request, Field $field)
    {
        $request->validate([
            'field_type_id' => 'required|exists:field_types,id',
            'name' => 'required|string|max:50',
            'price_offpeak' => 'required|numeric|min:0',
            'price_peak' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('photo')) {
            if ($field->photo) {
                Storage::disk('public')->delete($field->photo);
            }
            $data['photo'] = $request->file('photo')->store('fields', 'public');
        }

        $field->update($data);
        return redirect()->route('admin.fields.index')->with('success', 'Lapangan berhasil diperbarui.');
    }

    public function destroy(Field $field)
    {
        if ($field->bookings()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus lapangan karena sudah memiliki riwayat booking.');
        }
        
        if ($field->photo) {
            Storage::disk('public')->delete($field->photo);
        }
        
        $field->delete();
        return redirect()->route('admin.fields.index')->with('success', 'Lapangan berhasil dihapus.');
    }
}
