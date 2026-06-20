<?php

namespace App\Http\Controllers;

use App\Models\Nation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class NationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Guard: hanya Admin (Komandan) yang boleh kelola master data
    private function checkAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'AKSES DITOLAK! Area ini hanya untuk Komandan (Admin).');
        }
    }

    public function index()
    {
        $this->checkAdmin();
        $nations = Nation::all();
        return view('nations.index', compact('nations'));
    }

    public function create()
    {
        $this->checkAdmin();
        return view('nations.create');
    }

    public function store(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'name' => 'required|string|max:255|unique:nations,name',
            'flag' => 'nullable|string|max:255',
        ]);

        Nation::create([
            'name' => $request->name,
            'flag' => $request->flag,
        ]);

        return redirect()->route('nations.index')->with('success', 'Negara berhasil ditambahkan!');
    }

    public function edit(Nation $nation)
    {
        $this->checkAdmin();
        return view('nations.edit', compact('nation'));
    }

    public function update(Request $request, Nation $nation)
    {
        $this->checkAdmin();

        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('nations', 'name')->ignore($nation->id)],
            'flag' => 'nullable|string|max:255',
        ]);

        $nation->update([
            'name' => $request->name,
            'flag' => $request->flag,
        ]);

        return redirect()->route('nations.index')->with('success', 'Negara berhasil diperbarui!');
    }

    public function destroy(Nation $nation)
    {
        $this->checkAdmin();
        $nation->delete();
        return redirect()->route('nations.index')->with('success', 'Negara berhasil dihapus!');
    }
}
