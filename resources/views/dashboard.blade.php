<x-app-layout>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>

    <div class="min-h-screen bg-stone-950 text-white p-6">

        {{-- Greeting Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 animate-[fadeIn_0.5s_ease-in-out]">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-white">
                    Selamat datang, {{ auth()->user()->name }} <span class="inline-block"><i class="fa-solid fa-circle-check"></i></span>
                </h1>
                <p class="text-stone-400 mt-1">Berikut ringkasan data perpustakaan hari ini.</p>
            </div>
            <div class="mt-4 md:mt-0 flex gap-2">
                <a href="{{ route('buku.create') }}"
                   class="flex items-center gap-2 bg-violet-600 hover:bg-violet-500 transition-all duration-300 text-white px-4 py-2 rounded-xl shadow-lg shadow-violet-900/40">
                    <i class="fa-solid fa-plus"></i> Tambah Buku
                </a>
            </div>
        </div>

        {{-- Stat Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
            @php
                $stats = [
                    ['label' => 'Total Buku', 'value' => $totalBuku, 'icon' => 'fa-book', 'color' => 'violet'],
                    ['label' => 'Total Author', 'value' => $totalAuthor, 'icon' => 'fa-pen-nib', 'color' => 'fuchsia'],
                    ['label' => 'Total Kategori', 'value' => $totalKategori, 'icon' => 'fa-tags', 'color' => 'indigo'],
                    ['label' => 'Total Siswa', 'value' => $totalSiswa, 'icon' => 'fa-user-graduate', 'color' => 'purple'],
                    ['label' => 'Total Kelas', 'value' => $totalKelas, 'icon' => 'fa-chalkboard', 'color' => 'violet'],
                ];
            @endphp

            @foreach ($stats as $stat)
                <div class="relative bg-stone-900/80 backdrop-blur border border-stone-800 rounded-2xl p-5
                            hover:border-{{ $stat['color'] }}-600/60 hover:shadow-lg hover:shadow-{{ $stat['color'] }}-900/30
                            transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-{{ $stat['color'] }}-600/20 text-{{ $stat['color'] }}-400">
                            <i class="fa-solid {{ $stat['icon'] }}"></i>
                        </div>
                    </div>
                    <p class="text-2xl font-bold mt-4">{{ $stat['value'] }}</p>
                    <p class="text-stone-400 text-sm">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Chart --}}
            <div class="lg:col-span-2 bg-stone-900/80 backdrop-blur border border-stone-800 rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-white">Buku Ditambahkan (6 Bulan Terakhir)</h2>
                    <i class="fa-solid fa-chart-line text-violet-400"></i>
                </div>
                <canvas id="bukuChart" height="120"></canvas>
            </div>

            {{-- Recent Activity --}}
            <div class="bg-stone-900/80 backdrop-blur border border-stone-800 rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-white">Buku Terbaru</h2>
                    <a href="{{ route('buku.index') }}" class="text-xs text-violet-400 hover:text-violet-300">Lihat Semua</a>
                </div>

                <div class="space-y-3">
                    @forelse ($recentBuku as $buku)
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-stone-950/60 border border-stone-800 hover:border-violet-700/50 transition-all">
                            <div class="w-9 h-9 flex items-center justify-center rounded-lg bg-violet-600/20 text-violet-400 shrink-0">
                                <i class="fa-solid fa-book"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-white truncate">{{ $buku->judul ?? $buku->nama ?? '-' }}</p>
                                <p class="text-xs text-stone-400 truncate">
                                    {{ optional($buku->author)->nama ?? '-' }} · {{ $buku->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-stone-500 text-sm text-center py-6">Belum ada data buku.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="mt-6 bg-stone-900/80 backdrop-blur border border-stone-800 rounded-2xl p-6">
            <h2 class="font-semibold text-white mb-4">Aksi Cepat</h2>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                @php
                    $quickLinks = [
                        ['label' => 'Kelola Buku', 'route' => 'buku.index', 'icon' => 'fa-book'],
                        ['label' => 'Kelola Author', 'route' => 'author.index', 'icon' => 'fa-pen-nib'],
                        ['label' => 'Kelola Kategori', 'route' => 'kategori.index', 'icon' => 'fa-tags'],
                        ['label' => 'Kelola Siswa', 'route' => 'siswa.index', 'icon' => 'fa-user-graduate'],
                        ['label' => 'Kelola Kelas', 'route' => 'kelas.index', 'icon' => 'fa-chalkboard'],
                    ];
                @endphp

                @foreach ($quickLinks as $link)
                    <a href="{{ route($link['route']) }}"
                       class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl bg-stone-950/60 border border-stone-800
                              hover:border-violet-600/60 hover:bg-violet-600/10 transition-all duration-300 text-center">
                        <i class="fa-solid {{ $link['icon'] }} text-violet-400"></i>
                        <span class="text-xs text-stone-300">{{ $link['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <script>
        const ctx = document.getElementById('bukuChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Buku Ditambahkan',
                    data: @json($chartData),
                    fill: true,
                    borderColor: '#a78bfa',
                    backgroundColor: 'rgba(167, 139, 250, 0.15)',
                    tension: 0.4,
                    pointBackgroundColor: '#a78bfa',
                    pointBorderColor: '#fff',
                    pointRadius: 4,
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { color: '#292524' }, ticks: { color: '#a8a29e' } },
                    y: { grid: { color: '#292524' }, ticks: { color: '#a8a29e' }, beginAtZero: true }
                }
            }
        });
    </script>
</x-app-layout>