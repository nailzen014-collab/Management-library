<x-app-layout>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    </head>

    <div class="min-h-screen bg-stone-950 text-white p-6">

        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('kelas.index') }}"
               class="w-10 h-10 flex items-center justify-center rounded-xl bg-stone-900 border border-stone-800 hover:border-violet-600/60 text-stone-400 hover:text-white transition-all duration-200">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-white">Detail Kelas</h1>
                <p class="text-stone-400 text-sm">Informasi lengkap dan daftar siswa.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Profile Card --}}
            <div class="bg-stone-900 border border-stone-800 rounded-2xl p-6 h-fit">
                <div class="flex flex-col items-center text-center">
                    <div class="w-20 h-20 rounded-2xl bg-purple-600/20 flex items-center justify-center text-purple-400 text-3xl mb-4">
                        <i class="fa-solid fa-chalkboard"></i>
                    </div>
                    <h2 class="text-xl font-bold text-white">{{ $kela->nama_kelas }}</h2>
                    <span class="mt-2 px-3 py-1 rounded-lg bg-purple-600/20 text-purple-400 text-sm">
                        Tingkat {{ $kela->tingkat }}
                    </span>

                    <div class="mt-4 px-4 py-2 rounded-xl bg-violet-600/20 text-violet-400 text-sm">
                        <i class="fa-solid fa-user-graduate me-1"></i> {{ $kela->siswa_count }} siswa
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-6 pt-6 border-t border-stone-800">
                    <a href="{{ route('kelas.edit', $kela) }}"
                       class="flex-1 flex items-center justify-center gap-2 bg-violet-600 hover:bg-violet-500 transition-all duration-300 text-white px-4 py-2.5 rounded-xl">
                        <i class="fa-solid fa-pen"></i> Edit
                    </a>
                    <a href="{{ route('kelas.index') }}"
                       class="flex-1 text-center px-4 py-2.5 rounded-xl border border-stone-800 text-stone-400 hover:text-white hover:border-stone-600 transition-all duration-200">
                        Kembali
                    </a>
                </div>
            </div>

            {{-- Students List --}}
            <div class="lg:col-span-2 bg-stone-900 border border-stone-800 rounded-2xl p-6">
                <h3 class="font-semibold text-white mb-4">Siswa di kelas {{ $kela->nama_kelas }}</h3>

                <div class="space-y-3">
                    @forelse ($kela->siswa as $siswa)
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-stone-950/60 border border-stone-800 hover:border-violet-700/50 transition-all">
                            <div class="w-9 h-9 flex items-center justify-center rounded-lg bg-violet-600/20 text-violet-400 shrink-0 text-xs font-semibold">
                                {{ strtoupper(substr($siswa->nama, 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-white truncate">{{ $siswa->nama }}</p>
                                <p class="text-xs text-stone-400">NIS: {{ $siswa->nis }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-stone-500 text-sm text-center py-10">
                            <i class="fa-solid fa-inbox text-2xl mb-2 block"></i>
                            Belum ada siswa di kelas ini.
                        </p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 