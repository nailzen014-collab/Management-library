<?php

namespace App\Http\Controllers;
use App\Models\buku;
use App\Models\author;
use App\Models\kategori;
use Illuminate\Http\Request;

class bukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = buku::with(['author', 'kategoris']);

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        $bukus = $query->latest()->paginate(10)->withQueryString();

        return view('buku.index', compact('bukus'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
 {
        $authors   = author::orderBy('nama_author')->get();
        $kategoris = kategori::orderBy('nama_kategori')->get();
 
        return view('buku.create', compact('authors', 'kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    $validated = $request->validate([
        'judul' => 'required|string|max:255',
        'stock' => 'required|integer|min:0',
        'author_id' => 'required|exists:authors,id',
        'kategori_id' => 'required|array',
        'kategori_id.*' => 'exists:kategoris,id',
    ]);

    $buku = buku::create([
        'judul' => $validated['judul'],
        'stock' => $validated['stock'],
        'author_id' => $validated['author_id'],
    ]);

    $buku->kategoris()->sync($validated['kategori_id']);

    return redirect()
        ->route('buku.index')
        ->with('success', 'Buku berhasil ditambahkan.');
}
    /**
     * Display the specified resource.
     */
    public function show(buku $buku)
    {
        $buku->load(['author', 'kategoris']);

        return view('buku.show', compact('buku'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(buku $buku)
    {
        $authors = author::orderBy('nama_author')->get();
        $kategoris = kategori::orderBy('nama_kategori')->get();

        return view('buku.edit', compact('buku', 'authors', 'kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, buku $buku)
{
    $validated = $request->validate([
        'judul' => 'required|string|max:255',
        'author_id' => 'required|exists:authors,id',
        'kategori_id' => 'required|array',
        'kategori_id.*' => 'exists:kategoris,id',
        'stock' => 'required|integer|min:0',
    ]);

    $buku->update([
        'judul' => $validated['judul'],
        'author_id' => $validated['author_id'],
        'stock' => $validated['stock'],
    ]);

    $buku->kategoris()->sync($validated['kategori_id']);

    return redirect()
        ->route('buku.index')
        ->with('success', 'Buku berhasil diperbarui.');
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(buku $buku)
    {
        $buku->delete();

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus.');
    }
}
