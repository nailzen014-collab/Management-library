<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\buku;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = min(max($request->integer('per_page', 10), 1), 100);

        $query = buku::query()->with(['author', 'kategoris']);

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%'.$request->input('search').'%');
        }

        if ($request->filled('author_id')) {
            $query->where('author_id', $request->integer('author_id'));
        }

        if ($request->filled('kategori_id')) {
            $query->whereHas('kategoris', function ($query) use ($request) {
                $query->where('kategoris.id', $request->integer('kategori_id'));
            });
        }

        return response()->json(
            $query->latest()->paginate($perPage)->withQueryString()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'stock' => ['required', 'integer', 'min:0'],
            'author_id' => ['required', 'integer', 'exists:authors,id'],
            'kategori_id' => ['required', 'array', 'min:1'],
            'kategori_id.*' => ['required', 'integer', 'exists:kategoris,id'],
        ]);

        $buku = buku::create([
            'judul' => $validated['judul'],
            'stock' => $validated['stock'],
            'author_id' => $validated['author_id'],
        ]);

        $buku->kategoris()->sync($validated['kategori_id']);

        return response()->json([
            'message' => 'Buku berhasil ditambahkan.',
            'data' => $buku->load(['author', 'kategoris']),
        ], 201);
    }

    public function show(buku $buku): JsonResponse
    {
        return response()->json([
            'data' => $buku->load(['author', 'kategoris']),
        ]);
    }

    public function update(Request $request, buku $buku): JsonResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'stock' => ['required', 'integer', 'min:0'],
            'author_id' => ['required', 'integer', 'exists:authors,id'],
            'kategori_id' => ['required', 'array', 'min:1'],
            'kategori_id.*' => ['required', 'integer', 'exists:kategoris,id'],
        ]);

        $buku->update([
            'judul' => $validated['judul'],
            'stock' => $validated['stock'],
            'author_id' => $validated['author_id'],
        ]);

        $buku->kategoris()->sync($validated['kategori_id']);

        return response()->json([
            'message' => 'Buku berhasil diperbarui.',
            'data' => $buku->load(['author', 'kategoris']),
        ]);
    }

    public function destroy(buku $buku): JsonResponse
    {
        $buku->kategoris()->detach();
        $buku->delete();

        return response()->json([
            'message' => 'Buku berhasil dihapus.',
        ]);
    }
}
