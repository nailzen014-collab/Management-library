<?php

namespace App\Http\Controllers;
use App\Models\author;
use Illuminate\Http\Request;

class authorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = author::withCount('buku');
 
        if ($request->filled('search')) {
            $query->where('nama_author', 'like', '%' . $request->search . '%');
        }
 
        $authors = $query->latest()->paginate(10)->withQueryString();
 
        return view('author.index', compact('authors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('author.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validated = $request->validate([
            'nama_author' => 'required|string|max:255',
        ]);
 
        author::create($validated);
 
        return redirect()->route('author.index')->with('success', 'Author berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(author $author)
    {
         $author->loadCount('buku');
        $author->load('buku');
 
        return view('author.show', compact('author'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(author $author)
    {
        return view('author.edit', compact('author'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, author $author)
    {
        $validated = $request->validate([
            'nama_author' => 'required|string|max:255',
        ]);
 
        $author->update($validated);
 
        return redirect()->route('author.index')->with('success', 'Author berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(author $author)
    {
        $author->delete();
 
        return redirect()->route('author.index')->with('success', 'Author berhasil dihapus.');
    }
}
