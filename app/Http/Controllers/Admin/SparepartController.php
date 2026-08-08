<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sparepart;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SparepartController extends Controller
{
 public function index()
{
    $spareparts = Sparepart::with('supplier')->orderBy('code', 'asc')->get();
    $suppliers = Supplier::orderBy('name')->get();
    return view('admin.spareparts.index', compact('spareparts', 'suppliers'));
}

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        return view('admin.spareparts.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'code' => ['required', 'string', 'max:50', 'unique:spareparts,code'],
            'name' => ['required', 'string', 'max:100'],
            'stock' => ['required', 'integer', 'min:0'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
        ]);

        Sparepart::create($validated);

        return redirect()->route('admin.spareparts.index')->with('success', 'Sparepart berhasil ditambahkan.');
    }

    public function edit(Sparepart $sparepart)
    {
        $suppliers = Supplier::orderBy('name')->get();
        return view('admin.spareparts.edit', compact('sparepart', 'suppliers'));
    }

    public function update(Request $request, Sparepart $sparepart)
    {
        $validated = $request->validate([
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'code' => ['required', 'string', 'max:50', Rule::unique('spareparts', 'code')->ignore($sparepart->id)],
            'name' => ['required', 'string', 'max:100'],
            'stock' => ['required', 'integer', 'min:0'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
        ]);

        $sparepart->update($validated);

        return redirect()->route('admin.spareparts.index')->with('success', 'Sparepart berhasil diperbarui.');
    }

    public function destroy(Sparepart $sparepart)
    {
        if ($sparepart->serviceUsages()->exists()) {
            return back()->with('error', 'Sparepart sudah pernah dipakai di servis, tidak bisa dihapus.');
        }

        $sparepart->delete();

        return redirect()->route('admin.spareparts.index')->with('success', 'Sparepart berhasil dihapus.');
    }
}