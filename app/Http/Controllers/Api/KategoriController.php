<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\kategori;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = min(max($request->integer('per_page', 10), 1), 100);

        $query = kategori::query()->withCount('buku');

        if ($request->filled('search')) {
            $query->where('nama_kategori', 'like', '%'.$request->input('search').'%');
        }

        return response()->json(
            $query->latest()->paginate($perPage)->withQueryString()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:255'],
        ]);

        $kategori = kategori::create($validated);

        return response()->json([
            'message' => 'Kategori berhasil ditambahkan.',
            'data' => $kategori->loadCount('buku'),
        ], 201);
    }

    public function show(kategori $kategori): JsonResponse
    {
        return response()->json([
            'data' => $kategori->loadCount('buku')->load('buku'),
        ]);
    }

    public function update(Request $request, kategori $kategori): JsonResponse
    {
        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:255'],
        ]);

        $kategori->update($validated);

        return response()->json([
            'message' => 'Kategori berhasil diperbarui.',
            'data' => $kategori->loadCount('buku'),
        ]);
    }

    public function destroy(kategori $kategori): JsonResponse
    {
        if ($kategori->buku()->exists()) {
            return response()->json([
                'message' => 'Kategori tidak bisa dihapus karena masih punya buku terkait.',
            ], 409);
        }

        $kategori->delete();

        return response()->json([
            'message' => 'Kategori berhasil dihapus.',
        ]);
    }
}
