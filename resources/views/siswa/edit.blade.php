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
                        Edit Siswa
                    </h1>

                    <p class="text-stone-400 text-sm mt-1">
                        Perbarui data siswa.
                    </p>

                </div>

            </div>

        </div>


        {{-- Form --}}
        <div class="max-w-2xl bg-stone-900 border border-stone-800 rounded-2xl p-6">

            <form action="{{ route('siswa.update', $siswa) }}" method="POST">

                @csrf
                @method('PUT')


                {{-- Nama --}}
                <div class="mb-5">

                    <label for="nama"
                           class="block text-sm font-medium text-stone-300 mb-2">
                        Nama Siswa
                    </label>

                    <input
                        type="text"
                        id="nama"
                        name="nama"
                        value="{{ old('nama', $siswa->nama) }}"
                        placeholder="Masukkan nama siswa"
                        class="w-full bg-stone-950 border border-stone-800 rounded-xl px-4 py-3 text-sm text-white placeholder-stone-600 focus:outline-none focus:border-violet-600 transition"
                    >

                    @error('nama')
                        <p class="text-red-400 text-xs mt-2">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- NIS --}}
                <div class="mb-5">

                    <label for="nis"
                           class="block text-sm font-medium text-stone-300 mb-2">
                        NIS
                    </label>

                    <input
                        type="text"
                        id="nis"
                        name="nis"
                        value="{{ old('nis', $siswa->nis) }}"
                        placeholder="Masukkan NIS"
                        class="w-full bg-stone-950 border border-stone-800 rounded-xl px-4 py-3 text-sm text-white placeholder-stone-600 focus:outline-none focus:border-violet-600 transition"
                    >

                    @error('nis')
                        <p class="text-red-400 text-xs mt-2">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Kelas --}}
                <div class="mb-6">

                    <label for="kelas_id"
                           class="block text-sm font-medium text-stone-300 mb-2">
                        Kelas
                    </label>

                    <select
                        id="kelas_id"
                        name="kelas_id"
                        class="w-full bg-stone-950 border border-stone-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-violet-600 transition"
                    >

                        <option value="">
                            -- Pilih Kelas --
                        </option>

                        @foreach ($kelasList as $kelas)

                            <option value="{{ $kelas->id }}"
                                {{ old('kelas_id', $siswa->kelas_id) == $kelas->id ? 'selected' : '' }}>

                                {{ $kelas->nama_kelas }} - {{ $kelas->tingkat }}

                            </option>

                        @endforeach

                    </select>

                    @error('kelas_id')
                        <p class="text-red-400 text-xs mt-2">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Button --}}
                <div class="flex items-center justify-end gap-3">

                    <a href="{{ route('siswa.index') }}"
                       class="px-4 py-2 rounded-xl bg-stone-800 hover:bg-stone-700 text-stone-300 transition">
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="flex items-center gap-2 px-4 py-2 rounded-xl bg-violet-600 hover:bg-violet-500 transition shadow-lg shadow-violet-900/30">

                        <i class="fa-solid fa-save"></i>
                        Simpan Perubahan

                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>