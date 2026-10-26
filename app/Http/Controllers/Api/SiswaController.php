<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\siswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SiswaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = min(max($request->integer('per_page', 10), 1), 100);

        $query = siswa::query()->with('kelas');

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($query) use ($search) {
                $query->where('nama', 'like', '%'.$search.'%')
                    ->orWhere('nis', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->integer('kelas_id'));
        }

        return response()->json(
            $query->latest()->paginate($perPage)->withQueryString()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nis' => ['required', 'integer', 'unique:siswas,nis'],
            'kelas_id' => ['required', 'integer', 'exists:kelas,id'],
        ]);

        $siswa = siswa::create($validated);

        return response()->json([
            'message' => 'Siswa berhasil ditambahkan.',
            'data' => $siswa->load('kelas'),
        ], 201);
    }

    public function show(siswa $siswa): JsonResponse
    {
        return response()->json([
            'data' => $siswa->load('kelas'),
        ]);
    }

    public function update(Request $request, siswa $siswa): JsonResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nis' => ['required', 'integer', Rule::unique('siswas', 'nis')->ignore($siswa->id)],
            'kelas_id' => ['required', 'integer', 'exists:kelas,id'],
        ]);

        $siswa->update($validated);

        return response()->json([
            'message' => 'Data siswa berhasil diperbarui.',
            'data' => $siswa->load('kelas'),
        ]);
    }

    public function destroy(siswa $siswa): JsonResponse
    {
        $siswa->delete();

        return response()->json([
            'message' => 'Siswa berhasil dihapus.',
        ]);
    }
}
