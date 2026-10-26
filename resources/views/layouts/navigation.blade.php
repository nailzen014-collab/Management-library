<nav x-data="{ open: false }" class="bg-stone-950 border-b border-stone-800 sticky top-0 z-50 backdrop-blur">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-white font-bold text-lg">
                        <i class="fa-solid fa-book-open text-violet-400"></i>
                        <span>Perpus<span class="text-violet-400">Ku</span></span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-10 sm:flex sm:items-center">
                    <a href="{{ route('dashboard') }}"
                       class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('dashboard') ? 'bg-violet-600/20 text-violet-400' : 'text-stone-400 hover:text-white hover:bg-stone-800' }}">
                        <i class="fa-solid fa-gauge me-1"></i> Dashboard
                    </a>
                    <a href="{{ route('buku.index') }}"
                       class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('buku.*') ? 'bg-violet-600/20 text-violet-400' : 'text-stone-400 hover:text-white hover:bg-stone-800' }}">
                        <i class="fa-solid fa-book me-1"></i> Buku
                    </a>
                    <a href="{{ route('author.index') }}"
                       class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('author.*') ? 'bg-violet-600/20 text-violet-400' : 'text-stone-400 hover:text-white hover:bg-stone-800' }}">
                        <i class="fa-solid fa-pen-nib me-1"></i> Author
                    </a>
                    <a href="{{ route('kategori.index') }}"
                       class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('kategori.*') ? 'bg-violet-600/20 text-violet-400' : 'text-stone-400 hover:text-white hover:bg-stone-800' }}">
                        <i class="fa-solid fa-tags me-1"></i> Kategori
                    </a>
                    <a href="{{ route('kelas.index') }}"
                       class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('kelas.*') ? 'bg-violet-600/20 text-violet-400' : 'text-stone-400 hover:text-white hover:bg-stone-800' }}">
                        <i class="fa-solid fa-chalkboard me-1"></i> Kelas
                    </a>
                    <a href="{{ route('siswa.index') }}"
                       class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('siswa.*') ? 'bg-violet-600/20 text-violet-400' : 'text-stone-400 hover:text-white hover:bg-stone-800' }}">
                        <i class="fa-solid fa-user-graduate me-1"></i> Siswa
                    </a>
                    <a href="{{ route('api.docs') }}"
                       class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('api.docs') ? 'bg-violet-600/20 text-violet-400' : 'text-stone-400 hover:text-white hover:bg-stone-800' }}">
                        <i class="fa-solid fa-code me-1"></i> Dokumentasi API
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-medium
                                       text-stone-300 bg-stone-900 border border-stone-800 hover:border-violet-600/50
                                       focus:outline-none transition-all duration-200">
                            <div class="w-6 h-6 rounded-full bg-violet-600/20 flex items-center justify-center text-violet-400 text-xs">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div>{{ Auth::user()->name }}</div>
                            <i class="fa-solid fa-chevron-down text-xs text-stone-500"></i>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            <i class="fa-solid fa-user me-2"></i>{{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                <i class="fa-solid fa-right-from-bracket me-2"></i>{{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-stone-400 hover:text-white hover:bg-stone-800 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-stone-950 border-t border-stone-800">
        <div class="pt-2 pb-3 space-y-1 px-2">
            <a href="{{ route('dashboard') }}"
               class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-violet-600/20 text-violet-400' : 'text-stone-400 hover:bg-stone-800 hover:text-white' }}">
                <i class="fa-solid fa-gauge me-2 w-4"></i> Dashboard
            </a>
            <a href="{{ route('buku.index') }}"
               class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('buku.*') ? 'bg-violet-600/20 text-violet-400' : 'text-stone-400 hover:bg-stone-800 hover:text-white' }}">
                <i class="fa-solid fa-book me-2 w-4"></i> Buku
            </a>
            <a href="{{ route('author.index') }}"
               class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('author.*') ? 'bg-violet-600/20 text-violet-400' : 'text-stone-400 hover:bg-stone-800 hover:text-white' }}">
                <i class="fa-solid fa-pen-nib me-2 w-4"></i> Author
            </a>
            <a href="{{ route('kategori.index') }}"
               class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('kategori.*') ? 'bg-violet-600/20 text-violet-400' : 'text-stone-400 hover:bg-stone-800 hover:text-white' }}">
                <i class="fa-solid fa-tags me-2 w-4"></i> Kategori
            </a>
            <a href="{{ route('kelas.index') }}"
               class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('kelas.*') ? 'bg-violet-600/20 text-violet-400' : 'text-stone-400 hover:bg-stone-800 hover:text-white' }}">
                <i class="fa-solid fa-chalkboard me-2 w-4"></i> Kelas
            </a>
            <a href="{{ route('siswa.index') }}"
               class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('siswa.*') ? 'bg-violet-600/20 text-violet-400' : 'text-stone-400 hover:bg-stone-800 hover:text-white' }}">
                <i class="fa-solid fa-user-graduate me-2 w-4"></i> Siswa
            </a>
            <a href="{{ route('api.docs') }}"
               class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('api.docs') ? 'bg-violet-600/20 text-violet-400' : 'text-stone-400 hover:bg-stone-800 hover:text-white' }}">
                <i class="fa-solid fa-code me-2 w-4"></i> Dokumentasi API
            </a>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-3 border-t border-stone-800">
            <div class="px-4 flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-violet-600/20 flex items-center justify-center text-violet-400 text-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-stone-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class=" bg-violet-600/20 mt-3 space-y-1 px-2">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-stone-400 hover:text-white">
                    <i class="fa-solid fa-user me-2"></i>{{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            class="text-stone-400 hover:text-white">
                        <i class="fa-solid fa-right-from-bracket me-2"></i>{{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>