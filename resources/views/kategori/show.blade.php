<x-app-layout>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    </head>

    <div class="min-h-screen bg-stone-950 text-white p-6">

        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('kategori.index') }}"
               class="w-10 h-10 flex items-center justify-center rounded-xl bg-stone-900 border border-stone-800 hover:border-violet-600/60 text-stone-400 hover:text-white transition-all duration-200">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-white">Detail Kategori</h1>
                <p class="text-stone-400 text-sm">Informasi lengkap dan daftar buku.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Profile Card --}}
            <div class="bg-stone-900 border border-stone-800 rounded-2xl p-6 h-fit">
                <div class="flex flex-col items-center text-center">
                    <div class="w-20 h-20 rounded-2xl bg-indigo-600/20 flex items-center justify-center text-indigo-400 text-3xl mb-4">
                        <i class="fa-solid fa-tags"></i>
                    </div>
                    <h2 class="text-xl font-bold text-white">{{ $kategori->nama_kategori }}</h2>
                    <p class="text-stone-400 text-sm mt-1">Dibuat {{ $kategori->created_at->diffForHumans() }}</p>

                    <div class="mt-4 px-4 py-2 rounded-xl bg-violet-600/20 text-violet-400 text-sm">
                        <i class="fa-solid fa-book me-1"></i> {{ $kategori->buku_count }} buku
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-6 pt-6 border-t border-stone-800">
                    <a href="{{ route('kategori.edit', $kategori) }}"
                       class="flex-1 flex items-center justify-center gap-2 bg-violet-600 hover:bg-violet-500 transition-all duration-300 text-white px-4 py-2.5 rounded-xl">
                        <i class="fa-solid fa-pen"></i> Edit
                    </a>
                    <a href="{{ route('kategori.index') }}"
                       class="flex-1 text-center px-4 py-2.5 rounded-xl border border-stone-800 text-stone-400 hover:text-white hover:border-stone-600 transition-all duration-200">
                        Kembali
                    </a>
                </div>
            </div>

            {{-- Books List --}}
            <div class="lg:col-span-2 bg-stone-900 border border-stone-800 rounded-2xl p-6">
                <h3 class="font-semibold text-white mb-4">Buku dalam kategori "{{ $kategori->nama_kategori }}"</h3>

                <div class="space-y-3">
                    @forelse ($kategori->buku as $buku)
                        <a href="{{ route('buku.show', $buku) }}"
                           class="flex items-center gap-3 p-3 rounded-xl bg-stone-950/60 border border-stone-800 hover:border-violet-700/50 transition-all">
                            <div class="w-9 h-9 flex items-center justify-center rounded-lg bg-violet-600/20 text-violet-400 shrink-0">
                                <i class="fa-solid fa-book"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-white truncate">{{ $buku->judul }}</p>
                                <p class="text-xs text-stone-400">Stok: {{ $buku->stock }}</p>
                            </div>
                            <i class="fa-solid fa-chevron-right text-stone-600 text-xs"></i>
                        </a>
                    @empty
                        <p class="text-stone-500 text-sm text-center py-10">
                            <i class="fa-solid fa-inbox text-2xl mb-2 block"></i>
                            Belum ada buku dalam kategori ini.
                        </p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>