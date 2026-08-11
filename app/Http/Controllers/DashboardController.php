<?php

namespace App\Http\Controllers;
use AddressInfo;
use App\Models\buku;
use App\Models\author;
use App\Models\kategori;
use App\Models\siswa;
use App\Models\kelas;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
    {
        $totalBuku     = buku::count();
        $totalAuthor   = author::count();
        $totalKategori = kategori::count();
        $totalSiswa    = siswa::count();
        $totalKelas    = kelas::count();
 
        // Data buku ditambahkan per bulan (6 bulan terakhir) buat chart
        $chartRaw = buku::selectRaw('MONTH(created_at) as bulan, COUNT(*) as jumlah')
            ->whereDate('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('bulan')
            ->pluck('jumlah', 'bulan');
 
        $chartLabels = [];
        $chartData   = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $chartLabels[] = $date->translatedFormat('M');
            $chartData[]   = $chartRaw[$date->month] ?? 0;
        }
 
        // Buku terbaru buat "Recent Activity"
        $recentBuku = buku::with(['author', 'kategori'])
            ->latest()
            ->take(5)
            ->get();
 
        return view('dashboard', compact(
            'totalBuku',
            'totalAuthor',
            'totalKategori',
            'totalSiswa',
            'totalKelas',
            'chartLabels',
            'chartData',
            'recentBuku'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
