<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\FieldType;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $query = Field::with('fieldType')->where('is_active', true);

        if ($request->filled('field_type_id')) {
            $query->where('field_type_id', $request->field_type_id);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $fields = $query->paginate(10);
        $fieldTypes = FieldType::all();

        return view('home', compact('fields', 'fieldTypes'));
    }
}
