<x-app-layout>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>

    <div class="min-h-screen bg-stone-950 text-white p-6">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-tags text-indigo-400"></i> Data Kategori
                </h1>
                <p class="text-stone-400 mt-1 text-sm">Kelola kategori buku.</p>
            </div>
            <a href="{{ route('kategori.create') }}"
               class="mt-4 md:mt-0 flex items-center gap-2 bg-violet-600 hover:bg-violet-500 transition-all duration-300 text-white px-4 py-2 rounded-xl shadow-lg shadow-violet-900/40 w-fit">
                <i class="fa-solid fa-plus"></i> Tambah Kategori
            </a>
        </div>

        {{-- Flash messages --}}
        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success', title: 'Berhasil!', text: @json(session('success')),
                    background: '#1c1917', color: '#fff', confirmButtonColor: '#7c3aed',
                    timer: 2000, showConfirmButton: false,
                });
            </script>
        @endif
        @if (session('error'))
            <script>
                Swal.fire({
                    icon: 'error', title: 'Gagal!', text: @json(session('error')),
                    background: '#1c1917', color: '#fff', confirmButtonColor: '#7c3aed',
                });
            </script>
        @endif

        {{-- Search --}}
        <form method="GET" action="{{ route('kategori.index') }}" class="mb-6">
            <div class="relative max-w-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-stone-500"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kategori..."
                       class="w-full bg-stone-900 border border-stone-800 rounded-xl pl-10 pr-4 py-2 text-sm text-white
                              placeholder-stone-500 focus:outline-none focus:border-violet-600/60 transition-all duration-200">
            </div>
        </form>

        {{-- Grid Cards (beda dari table, biar layout gak monoton sama Buku/Author) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($kategoris as $kategori)
                <div class="bg-stone-900 border border-stone-800 rounded-2xl p-5 hover:border-indigo-600/60 transition-all duration-300">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-11 h-11 flex items-center justify-center rounded-xl bg-indigo-600/20 text-indigo-400">
                            <i class="fa-solid fa-tags"></i>
                        </div>
                        <span class="px-2 py-1 rounded-lg bg-violet-600/20 text-violet-400 text-xs">
                            {{ $kategori->buku_count }} buku
                        </span>
                    </div>
                    <h3 class="font-semibold text-white">{{ $kategori->nama_kategori }}</h3>
                    <p class="text-stone-500 text-xs mt-1">Dibuat {{ $kategori->created_at->diffForHumans() }}</p>

                    <div class="flex items-center gap-2 mt-4 pt-4 border-t border-stone-800">
                        <a href="{{ route('kategori.show', $kategori) }}"
                           class="flex-1 flex items-center justify-center gap-1 py-2 rounded-lg bg-stone-800 hover:bg-indigo-600/30 hover:text-indigo-400 text-stone-400 text-xs transition-all duration-200">
                            <i class="fa-solid fa-eye"></i> Detail
                        </a>
                        <a href="{{ route('kategori.edit', $kategori) }}"
                           class="flex-1 flex items-center justify-center gap-1 py-2 rounded-lg bg-stone-800 hover:bg-violet-600/30 hover:text-violet-400 text-stone-400 text-xs transition-all duration-200">
                            <i class="fa-solid fa-pen"></i> Edit
                        </a>
                        <form action="{{ route('kategori.destroy', $kategori) }}" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmDelete(this)"
                                    class="w-full flex items-center justify-center gap-1 py-2 rounded-lg bg-stone-800 hover:bg-red-600/30 hover:text-red-400 text-stone-400 text-xs transition-all duration-200">
                                <i class="fa-solid fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-stone-500 py-16">
                    <i class="fa-solid fa-inbox text-3xl mb-3 block"></i>
                    Belum ada data kategori.
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $kategoris->links() }}
        </div>
    </div>

    <script>
        function confirmDelete(button) {
            Swal.fire({
                title: 'Yakin mau hapus?',
                text: 'Data kategori ini akan dihapus permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#57534e',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
                background: '#1c1917',
                color: '#fff',
            }).then((result) => {
                if (result.isConfirmed) {
                    button.closest('form').submit();
                }
            });
        }
    </script>
</x-app-layout>