<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\author;
use App\Models\buku;
use App\Models\kategori;
use App\Models\kelas;
use App\Models\siswa;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $chartRaw = buku::query()
            ->whereDate('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->get(['created_at'])
            ->groupBy(fn ($buku) => $buku->created_at->month)
            ->map->count();

        $chartLabels = [];
        $chartData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $chartLabels[] = $date->translatedFormat('M');
            $chartData[] = $chartRaw[$date->month] ?? 0;
        }

        return response()->json([
            'data' => [
                'stats' => [
                    'total_buku' => buku::count(),
                    'total_author' => author::count(),
                    'total_kategori' => kategori::count(),
                    'total_siswa' => siswa::count(),
                    'total_kelas' => kelas::count(),
                ],
                'chart' => [
                    'labels' => $chartLabels,
                    'data' => $chartData,
                ],
                'recent_buku' => buku::query()
                    ->with(['author', 'kategoris'])
                    ->latest()
                    ->take(5)
                    ->get(),
            ],
        ]);
    }
}
