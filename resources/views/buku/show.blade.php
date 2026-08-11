<x-app-layout>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    </head>

    <div class="min-h-screen bg-stone-950 text-white p-6">

        {{-- Header --}}
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('buku.index') }}"
               class="w-10 h-10 flex items-center justify-center rounded-xl bg-stone-900 border border-stone-800 hover:border-violet-600/60 text-stone-400 hover:text-white transition-all duration-200">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-white">Detail Buku</h1>
                <p class="text-stone-400 text-sm">Informasi lengkap data buku.</p>
            </div>
        </div>

        {{-- Detail Card --}}
        <div class="bg-stone-900 border border-stone-800 rounded-2xl p-6 max-w-2xl">
            <div class="flex items-start gap-4 mb-6 pb-6 border-b border-stone-800">
                <div class="w-16 h-16 rounded-2xl bg-violet-600/20 flex items-center justify-center text-violet-400 text-2xl shrink-0">
                    <i class="fa-solid fa-book"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white">{{ $buku->judul }}</h2>
                    <p class="text-stone-400 text-sm mt-1">Ditambahkan {{ $buku->created_at->diffForHumans() }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <p class="text-xs text-stone-500 mb-1">Author</p>
                    <p class="text-white font-medium flex items-center gap-2">
                        <i class="fa-solid fa-pen-nib text-fuchsia-400"></i>
                        {{ optional($buku->author)->nama_author ?? '-' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-stone-500 mb-1">Kategori</p>
                    <div class="flex flex-wrap gap-2">
                        @forelse ($buku->kategoris as $kategori)
                            <span class="inline-flex items-center gap-2 px-2.5 py-1 rounded-lg bg-violet-600/20 text-violet-400 text-sm">
                                <i class="fa-solid fa-tag"></i>
                                {{ $kategori->nama_kategori }}
                            </span>
                        @empty
                            <span class="text-stone-500">-</span>
                        @endforelse
                    </div>
                </div>
                <div>
                    <p class="text-xs text-stone-500 mb-1">Stok Tersedia</p>
                    @if ($buku->stock > 0)
                        <p class="text-emerald-400 font-semibold text-lg">{{ $buku->stock }} unit</p>
                    @else
                        <p class="text-red-400 font-semibold text-lg">Habis</p>
                    @endif
                </div>
                <div>
                    <p class="text-xs text-stone-500 mb-1">Terakhir Diperbarui</p>
                    <p class="text-stone-300">{{ $buku->updated_at->format('d M Y, H:i') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-stone-800">
                <a href="{{ route('buku.edit', $buku) }}"
                   class="flex items-center gap-2 bg-violet-600 hover:bg-violet-500 transition-all duration-300 text-white px-5 py-2.5 rounded-xl shadow-lg shadow-violet-900/40">
                    <i class="fa-solid fa-pen"></i> Edit
                </a>
                <a href="{{ route('buku.index') }}"
                   class="px-5 py-2.5 rounded-xl border border-stone-800 text-stone-400 hover:text-white hover:border-stone-600 transition-all duration-200">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</x-app-layout>