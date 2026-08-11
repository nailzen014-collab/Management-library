<x-app-layout>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    </head>

    <div class="min-h-screen bg-stone-950 text-white p-6">

        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('author.index') }}"
               class="w-10 h-10 flex items-center justify-center rounded-xl bg-stone-900 border border-stone-800 hover:border-violet-600/60 text-stone-400 hover:text-white transition-all duration-200">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-white">Edit Author</h1>
                <p class="text-stone-400 text-sm">Perbarui data "{{ $author->nama_author }}".</p>
            </div>
        </div>

        <div class="bg-stone-900 border border-stone-800 rounded-2xl p-6 max-w-xl">
            <form action="{{ route('author.update', $author) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-stone-300 mb-2">Nama Author</label>
                    <input type="text" name="nama_author" value="{{ old('nama_author', $author->nama_author) }}"
                           class="w-full bg-stone-950 border border-stone-800 rounded-xl px-4 py-2.5 text-white
                                  placeholder-stone-600 focus:outline-none focus:border-violet-600/60 transition-all duration-200">
                    @error('nama_author')
                        <p class="text-red-400 text-xs mt-1"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="flex items-center gap-2 bg-violet-600 hover:bg-violet-500 transition-all duration-300 text-white px-5 py-2.5 rounded-xl shadow-lg shadow-violet-900/40">
                        <i class="fa-solid fa-floppy-disk"></i> Update
                    </button>
                    <a href="{{ route('author.index') }}"
                       class="px-5 py-2.5 rounded-xl border border-stone-800 text-stone-400 hover:text-white hover:border-stone-600 transition-all duration-200">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>