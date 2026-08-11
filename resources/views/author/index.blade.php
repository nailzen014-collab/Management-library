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
                    <i class="fa-solid fa-pen-nib text-fuchsia-400"></i> Data Author
                </h1>
                <p class="text-stone-400 mt-1 text-sm">Kelola data penulis buku.</p>
            </div>
            <a href="{{ route('author.create') }}"
               class="mt-4 md:mt-0 flex items-center gap-2 bg-violet-600 hover:bg-violet-500 transition-all duration-300 text-white px-4 py-2 rounded-xl shadow-lg shadow-violet-900/40 w-fit">
                <i class="fa-solid fa-plus"></i> Tambah Author
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
        <form method="GET" action="{{ route('author.index') }}" class="mb-6">
            <div class="relative max-w-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-stone-500"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama author..."
                       class="w-full bg-stone-900 border border-stone-800 rounded-xl pl-10 pr-4 py-2 text-sm text-white
                              placeholder-stone-500 focus:outline-none focus:border-violet-600/60 transition-all duration-200">
            </div>
        </form>

        {{-- Table --}}
        <div class="bg-stone-900 border border-stone-800 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-stone-950/60 text-stone-400 text-left">
                            <th class="px-5 py-3 font-medium">No</th>
                            <th class="px-5 py-3 font-medium">Nama Author</th>
                            <th class="px-5 py-3 font-medium">Jumlah Buku</th>
                            <th class="px-5 py-3 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-800">
                        @forelse ($authors as $index => $author)
                            <tr class="hover:bg-stone-800/40 transition-all duration-150">
                                <td class="px-5 py-3 text-stone-400">{{ $authors->firstItem() + $index }}</td>
                                <td class="px-5 py-3 font-medium text-white">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-fuchsia-600/20 flex items-center justify-center text-fuchsia-400 text-xs">
                                            {{ strtoupper(substr($author->nama_author, 0, 1)) }}
                                        </div>
                                        {{ $author->nama_author }}
                                    </div>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="px-2 py-1 rounded-lg bg-violet-600/20 text-violet-400 text-xs">
                                        {{ $author->buku_count }} buku
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('author.show', $author) }}"
                                           class="w-8 h-8 flex items-center justify-center rounded-lg bg-stone-800 hover:bg-indigo-600/30 hover:text-indigo-400 text-stone-400 transition-all duration-200"
                                           title="Detail">
                                            <i class="fa-solid fa-eye text-xs"></i>
                                        </a>
                                        <a href="{{ route('author.edit', $author) }}"
                                           class="w-8 h-8 flex items-center justify-center rounded-lg bg-stone-800 hover:bg-violet-600/30 hover:text-violet-400 text-stone-400 transition-all duration-200"
                                           title="Edit">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </a>
                                        <form action="{{ route('author.destroy', $author) }}" method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(this)"
                                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-stone-800 hover:bg-red-600/30 hover:text-red-400 text-stone-400 transition-all duration-200"
                                                    title="Hapus">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center text-stone-500">
                                    <i class="fa-solid fa-inbox text-2xl mb-2 block"></i>
                                    Belum ada data author.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $authors->links() }}
        </div>
    </div>

    <script>
        function confirmDelete(button) {
            Swal.fire({
                title: 'Yakin mau hapus?',
                text: 'Data author ini akan dihapus permanen.',
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