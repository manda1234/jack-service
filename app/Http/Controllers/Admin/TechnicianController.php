<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TechnicianController extends Controller
{
    public function index()
    {
        $technicians = User::where('role', 'Teknisi')->latest()->get();
        return view('admin.technicians.index', compact('technicians'));
    }

    public function create()
    {
        return view('admin.technicians.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'Teknisi',
        ]);

        return redirect()->route('admin.technicians.index')->with('success', 'Data teknisi berhasil ditambahkan.');
    }

    public function edit(User $technician)
    {
        abort_if($technician->role !== 'Teknisi', 404);
        return view('admin.technicians.edit', compact('technician'));
    }

    public function update(Request $request, User $technician)
    {
        abort_if($technician->role !== 'Teknisi', 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($technician->id)],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $technician->name = $validated['name'];
        $technician->email = $validated['email'];
        if (!empty($validated['password'])) {
            $technician->password = Hash::make($validated['password']);
        }
        $technician->save();

        return redirect()->route('admin.technicians.index')->with('success', 'Data teknisi berhasil diperbarui.');
    }

    public function destroy(User $technician)
    {
        abort_if($technician->role !== 'Teknisi', 404);

        if ($technician->services()->whereNotIn('status', ['Selesai', 'Ditolak'])->exists()) {
            return back()->with('error', 'Teknisi masih memiliki servis yang berjalan, tidak bisa dihapus.');
        }

        $technician->delete();

        return redirect()->route('admin.technicians.index')->with('success', 'Data teknisi berhasil dihapus.');
    }
}