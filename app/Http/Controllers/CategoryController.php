<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
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
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        $this->checkAdmin();
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'nullable|string|max:255',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(Category $category)
    {
        $this->checkAdmin();
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $this->checkAdmin();

        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($category->id)],
            'slug' => 'nullable|string|max:255',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Category $category)
    {
        $this->checkAdmin();
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
