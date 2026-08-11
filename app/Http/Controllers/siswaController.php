<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with('kelas');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nis', 'like', '%' . $request->search . '%');
            });
        }

        $siswaList = $query->latest()
            ->paginate(10)
            ->withQueryString();

        return view('siswa.index', [
            'siswaList' => $siswaList
        ]);
    }


    public function create()
    {
        $kelasList = Kelas::orderBy('nama_kelas')->get();

        return view('siswa.create', compact('kelasList'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'     => 'required|string|max:255',
         'nis' => 'required|integer|unique:siswas,nis',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        Siswa::create($validated);

        return redirect()
            ->route('siswa.index')
            ->with('success', 'Siswa berhasil ditambahkan.');
    }


    public function show(Siswa $siswa)
    {
        $siswa->load('kelas');

        return view('siswa.show', compact('siswa'));
    }


    public function edit(Siswa $siswa)
    {
        $kelasList = Kelas::orderBy('nama_kelas')->get();

        return view('siswa.edit', compact('siswa', 'kelasList'));
    }


    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nama'     => 'required|string|max:255',
            'nis'      => 'required|integer|unique:siswas,nis,' . $siswa->id,
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $siswa->update($validated);

        return redirect()
            ->route('siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }


    public function destroy(Siswa $siswa)
    {
        $siswa->delete();

        return redirect()
            ->route('siswa.index')
            ->with('success', 'Siswa berhasil dihapus.');
    }
}