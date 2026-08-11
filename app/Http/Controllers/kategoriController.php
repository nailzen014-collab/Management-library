<?php

namespace App\Http\Controllers;

use App\Models\kategori;
use Illuminate\Http\Request;

class kategoriController extends Controller
{
    public function index(Request $request)
    {
        $query = kategori::withCount('buku');

        if ($request->filled('search')) {
            $query->where('nama_kategori', 'like', '%' . $request->search . '%');
        }

        $kategoris = $query->latest()->paginate(10)->withQueryString();

        return view('kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        kategori::create($validated);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function show(kategori $kategori)
    {
        $kategori->loadCount('buku');
        $kategori->load('buku');

        return view('kategori.show', compact('kategori'));
    }

    public function edit(kategori $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, kategori $kategori)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        $kategori->update($validated);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(kategori $kategori)
    {
        if ($kategori->buku()->exists()) {
            return redirect()->route('kategori.index')->with('error', 'Kategori tidak bisa dihapus karena masih punya buku terkait.');
        }

        $kategori->delete();

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}