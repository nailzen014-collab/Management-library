<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\kelas;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = min(max($request->integer('per_page', 10), 1), 100);

        $query = kelas::query()->withCount('siswa');

        if ($request->filled('search')) {
            $query->where('nama_kelas', 'like', '%'.$request->input('search').'%');
        }

        return response()->json(
            $query->latest()->paginate($perPage)->withQueryString()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama_kelas' => ['required', 'string', 'max:255'],
            'tingkat' => ['required', 'string', 'max:255'],
        ]);

        $kelas = kelas::create($validated);

        return response()->json([
            'message' => 'Kelas berhasil ditambahkan.',
            'data' => $kelas->loadCount('siswa'),
        ], 201);
    }

    public function show(kelas $kelas): JsonResponse
    {
        return response()->json([
            'data' => $kelas->loadCount('siswa')->load('siswa'),
        ]);
    }

    public function update(Request $request, kelas $kelas): JsonResponse
    {
        $validated = $request->validate([
            'nama_kelas' => ['required', 'string', 'max:255'],
            'tingkat' => ['required', 'string', 'max:255'],
        ]);

        $kelas->update($validated);

        return response()->json([
            'message' => 'Kelas berhasil diperbarui.',
            'data' => $kelas->loadCount('siswa'),
        ]);
    }

    public function destroy(kelas $kelas): JsonResponse
    {
        if ($kelas->siswa()->exists()) {
            return response()->json([
                'message' => 'Kelas tidak bisa dihapus karena masih punya siswa terkait.',
            ], 409);
        }

        $kelas->delete();

        return response()->json([
            'message' => 'Kelas berhasil dihapus.',
        ]);
    }
}
