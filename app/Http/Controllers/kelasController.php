<?php

namespace App\Http\Controllers;

use App\Models\kelas;
use Illuminate\Http\Request;

class kelasController extends Controller
{
    public function index(Request $request)
    {
        $query = kelas::withCount('siswa');

        if ($request->filled('search')) {
            $query->where('nama_kelas', 'like', '%' . $request->search . '%');
        }

        $kelasList = $query->latest()->paginate(10)->withQueryString();

        return view('kelas.index', ['kelasList' => $kelasList]);
    }

    public function create()
    {
        return view('kelas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'tingkat'    => 'required|string|max:255',
        ]);

        kelas::create($validated);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function show(kelas $kela)
    {
        $kela->loadCount('siswa');
        $kela->load('siswa');

        return view('kelas.show', compact('kela'));
    }

   public function edit(kelas $kela)
{
    return view('kelas.edit', compact('kela'));
}

public function update(Request $request, kelas $kela)
{
    $validated = $request->validate([
        'nama_kelas' => 'required|string|max:255',
        'tingkat' => 'required|string|max:255',
    ]);

    $kela->update($validated);

    return redirect()
        ->route('kelas.index')
        ->with('success', 'Kelas berhasil diperbarui.');
}
 public function destroy(kelas $kela)
{
    if ($kela->siswa()->exists()) {
        return redirect()
            ->route('kelas.index')
            ->with('error', 'Kelas tidak bisa dihapus karena masih punya siswa terkait.');
    }

    $kela->delete();

    return redirect()
        ->route('kelas.index')
        ->with('success', 'Kelas berhasil dihapus.');
}
}