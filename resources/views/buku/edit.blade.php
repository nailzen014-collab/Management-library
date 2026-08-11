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
                <h1 class="text-2xl font-bold text-white">Edit Buku</h1>
                <p class="text-stone-400 text-sm">Perbarui data buku "{{ $buku->judul }}".</p>
            </div>
        </div>

        {{-- Form --}}
        <div class="bg-stone-900 border border-stone-800 rounded-2xl p-6 max-w-xl">
            <form action="{{ route('buku.update', $buku) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                {{-- Judul --}}
                <div>
                    <label class="block text-sm font-medium text-stone-300 mb-2">Judul Buku</label>
                    <input type="text" name="judul" value="{{ old('judul', $buku->judul) }}"
                           class="w-full bg-stone-950 border border-stone-800 rounded-xl px-4 py-2.5 text-white
                                  placeholder-stone-600 focus:outline-none focus:border-violet-600/60 transition-all duration-200">
                    @error('judul')
                        <p class="text-red-400 text-xs mt-1"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                {{-- Author --}}
                <div>
                    <label class="block text-sm font-medium text-stone-300 mb-2">Author</label>
                    <select name="author_id"
                            class="w-full bg-stone-950 border border-stone-800 rounded-xl px-4 py-2.5 text-white
                                   focus:outline-none focus:border-violet-600/60 transition-all duration-200">
                        <option value="">-- Pilih Author --</option>
                        @foreach ($authors as $author)
                            <option value="{{ $author->id }}" {{ old('author_id', $buku->author_id) == $author->id ? 'selected' : '' }}>
                                {{ $author->nama_author }}
                            </option>
                        @endforeach
                    </select>
                    @error('author_id')
                        <p class="text-red-400 text-xs mt-1"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kategori --}}

<div>
    <label class="block text-sm font-medium text-stone-300 mb-2">
        Kategori
    </label>

    <select name="kategori_id[]" multiple
            class="w-full bg-stone-950 border border-stone-800 rounded-xl px-4 py-2.5 text-white
                   focus:outline-none focus:border-violet-600/60 transition-all duration-200">

        @foreach ($kategoris as $kategori)
            <option value="{{ $kategori->id }}"
                {{ $buku->kategoris->pluck('id')->contains($kategori->id) ? 'selected' : '' }}>
                {{ $kategori->nama_kategori }}
            </option>
        @endforeach

    </select>

    <p class="text-stone-500 text-xs mt-1">
        Tekan <b>Ctrl</b> + klik untuk memilih beberapa kategori.
    </p>

    @error('kategori_id')
        <p class="text-red-400 text-xs mt-1">
            <i class="fa-solid fa-circle-exclamation me-1"></i>
            {{ $message }}
        </p>
    @enderror

    @error('kategori_id.*')
        <p class="text-red-400 text-xs mt-1">
            <i class="fa-solid fa-circle-exclamation me-1"></i>
            {{ $message }}
        </p>
    @enderror
</div>

                {{-- Stock --}}
                <div>
                    <label class="block text-sm font-medium text-stone-300 mb-2">Stok</label>
                    <input type="number" name="stock" value="{{ old('stock', $buku->stock) }}" min="0"
                           class="w-full bg-stone-950 border border-stone-800 rounded-xl px-4 py-2.5 text-white
                                  focus:outline-none focus:border-violet-600/60 transition-all duration-200">
                    @error('stock')
                        <p class="text-red-400 text-xs mt-1"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="flex items-center gap-2 bg-violet-600 hover:bg-violet-500 transition-all duration-300 text-white px-5 py-2.5 rounded-xl shadow-lg shadow-violet-900/40">
                        <i class="fa-solid fa-floppy-disk"></i> Update
                    </button>
                    <a href="{{ route('buku.index') }}"
                       class="px-5 py-2.5 rounded-xl border border-stone-800 text-stone-400 hover:text-white hover:border-stone-600 transition-all duration-200">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>