<x-app-layout>

    <head>
        <link rel="stylesheet"
              href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>

    <div class="min-h-screen bg-stone-950 text-white p-6">

        {{-- Header --}}
        <div class="mb-6">

            <div class="flex items-center gap-3">

                <a href="{{ route('siswa.index') }}"
                   class="w-9 h-9 flex items-center justify-center rounded-lg bg-stone-800 hover:bg-stone-700 transition">

                    <i class="fa-solid fa-arrow-left text-sm"></i>

                </a>

                <div>

                    <h1 class="text-2xl font-bold">
                        Detail Siswa
                    </h1>

                    <p class="text-stone-400 text-sm mt-1">
                        Informasi lengkap siswa.
                    </p>

                </div>

            </div>

        </div>


        {{-- Profile Card --}}
        <div class="max-w-3xl">

            <div class="bg-stone-900 border border-stone-800 rounded-2xl overflow-hidden">


                {{-- Profile Header --}}
                <div class="p-6 border-b border-stone-800">

                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">

                        <div class="w-16 h-16 rounded-2xl bg-violet-600/20 flex items-center justify-center">

                            <i class="fa-solid fa-user-graduate text-2xl text-violet-400"></i>

                        </div>


                        <div>

                            <h2 class="text-xl font-bold text-white">
                                {{ $siswa->nama }}
                            </h2>

                            <p class="text-stone-500 text-sm mt-1">
                                NIS: {{ $siswa->nis }}
                            </p>

                        </div>

                    </div>

                </div>


                {{-- Data --}}
                <div class="p-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                        {{-- Nama --}}
                        <div class="bg-stone-950 border border-stone-800 rounded-xl p-4">

                            <div class="flex items-center gap-2 text-stone-500 text-xs mb-2">

                                <i class="fa-solid fa-user"></i>

                                Nama Siswa

                            </div>

                            <p class="text-white font-medium">
                                {{ $siswa->nama }}
                            </p>

                        </div>


                        {{-- NIS --}}
                        <div class="bg-stone-950 border border-stone-800 rounded-xl p-4">

                            <div class="flex items-center gap-2 text-stone-500 text-xs mb-2">

                                <i class="fa-solid fa-id-card"></i>

                                NIS

                            </div>

                            <p class="text-white font-medium">
                                {{ $siswa->nis }}
                            </p>

                        </div>


                        {{-- Kelas --}}
                        <div class="bg-stone-950 border border-stone-800 rounded-xl p-4">

                            <div class="flex items-center gap-2 text-stone-500 text-xs mb-2">

                                <i class="fa-solid fa-chalkboard"></i>

                                Kelas

                            </div>

                            @if ($siswa->kelas)

                                <span class="inline-flex px-3 py-1 rounded-lg bg-purple-600/20 text-purple-400 text-sm">

                                    {{ $siswa->kelas->nama_kelas }}

                                </span>

                            @else

                                <span class="text-red-400 text-sm">
                                    Belum memiliki kelas
                                </span>

                            @endif

                        </div>


                        {{-- Tingkat --}}
                        <div class="bg-stone-950 border border-stone-800 rounded-xl p-4">

                            <div class="flex items-center gap-2 text-stone-500 text-xs mb-2">

                                <i class="fa-solid fa-layer-group"></i>

                                Tingkat

                            </div>

                            @if ($siswa->kelas)

                                <span class="inline-flex px-3 py-1 rounded-lg bg-violet-600/20 text-violet-400 text-sm">

                                    {{ $siswa->kelas->tingkat }}

                                </span>

                            @else

                                <span class="text-stone-500 text-sm">
                                    -
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- Actions --}}
                    <div class="flex flex-wrap items-center justify-end gap-3 mt-6 pt-6 border-t border-stone-800">

                        <a href="{{ route('siswa.index') }}"
                           class="flex items-center gap-2 px-4 py-2 rounded-xl bg-stone-800 hover:bg-stone-700 text-stone-300 transition">

                            <i class="fa-solid fa-arrow-left"></i>

                            Kembali

                        </a>


                        <a href="{{ route('siswa.edit', $siswa) }}"
                           class="flex items-center gap-2 px-4 py-2 rounded-xl bg-violet-600 hover:bg-violet-500 transition">

                            <i class="fa-solid fa-pen"></i>

                            Edit

                        </a>


                        <form action="{{ route('siswa.destroy', $siswa) }}"
                              method="POST"
                              id="deleteForm">

                            @csrf
                            @method('DELETE')

                            <button
                                type="button"
                                onclick="confirmDelete()"
                                class="flex items-center gap-2 px-4 py-2 rounded-xl bg-red-600/20 hover:bg-red-600 text-red-400 hover:text-white transition">

                                <i class="fa-solid fa-trash"></i>

                                Hapus

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Delete Confirmation --}}
    <script>

        function confirmDelete() {

            Swal.fire({

                title: 'Yakin mau hapus?',
                text: 'Data siswa ini akan dihapus permanen.',
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

                    document.getElementById('deleteForm').submit();

                }

            });

        }

    </script>

</x-app-layout>