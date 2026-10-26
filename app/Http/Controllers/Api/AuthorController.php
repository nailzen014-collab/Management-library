<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\author;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = min(max($request->integer('per_page', 10), 1), 100);

        $query = author::query()->withCount('buku');

        if ($request->filled('search')) {
            $query->where('nama_author', 'like', '%'.$request->input('search').'%');
        }

        return response()->json(
            $query->latest()->paginate($perPage)->withQueryString()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama_author' => ['required', 'string', 'max:255'],
        ]);

        $author = author::create($validated);

        return response()->json([
            'message' => 'Author berhasil ditambahkan.',
            'data' => $author->loadCount('buku'),
        ], 201);
    }

    public function show(author $author): JsonResponse
    {
        return response()->json([
            'data' => $author->loadCount('buku')->load('buku'),
        ]);
    }

    public function update(Request $request, author $author): JsonResponse
    {
        $validated = $request->validate([
            'nama_author' => ['required', 'string', 'max:255'],
        ]);

        $author->update($validated);

        return response()->json([
            'message' => 'Author berhasil diperbarui.',
            'data' => $author->loadCount('buku'),
        ]);
    }

    public function destroy(author $author): JsonResponse
    {
        if ($author->buku()->exists()) {
            return response()->json([
                'message' => 'Author tidak bisa dihapus karena masih punya buku terkait.',
            ], 409);
        }

        $author->delete();

        return response()->json([
            'message' => 'Author berhasil dihapus.',
        ]);
    }
}
