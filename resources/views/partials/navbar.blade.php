<nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100 shadow-sm transition-all duration-300"
    id="navbar">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                @if(isset($settings) && $settings->logo)
                    <img src="{{ Storage::url($settings->logo) }}" alt="Logo Sekolah"
                        class="h-10 w-auto object-contain group-hover:scale-105 transition-transform duration-300">
                @else
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-lg flex items-center justify-center text-white font-bold text-xl shadow-md group-hover:shadow-lg transition-all duration-300 transform group-hover:-translate-y-0.5">
                        S
                    </div>
                @endif
                <!-- <div>
                    <h1 class="text-xl font-bold text-gray-900 leading-tight group-hover:text-blue-700 transition-colors">{{ $settings->nama_sekolah ?? 'SD Negeri 1 Passo' }}</h1>
                    <p class="text-xs text-gray-500 font-medium">Unggul, Berkarakter & Berprestasi</p>
                </div> -->
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex space-x-8">
                <a href="{{ route('home') }}"
                    class="text-gray-900 font-medium hover:text-blue-600 transition-colors border-b-2 {{ request()->routeIs('home') ? 'border-blue-600' : 'border-transparent' }} py-1">Beranda</a>
                <a href="{{ route('profil') }}"
                    class="text-gray-600 hover:text-blue-600 font-medium transition-colors py-1 border-b-2 {{ request()->routeIs('profil') ? 'border-blue-600' : 'border-transparent' }} hover:border-blue-300">Profil</a>
                <a href="{{ route('berita.index') }}"
                    class="text-gray-600 hover:text-blue-600 font-medium transition-colors py-1 border-b-2 {{ request()->routeIs('berita.*') ? 'border-blue-600' : 'border-transparent' }} hover:border-blue-300">Berita</a>
                <a href="{{ route('galeri') }}"
                    class="text-gray-600 hover:text-blue-600 font-medium transition-colors py-1 border-b-2 {{ request()->routeIs('galeri') ? 'border-blue-600' : 'border-transparent' }} hover:border-blue-300">Galeri</a>
                @if(isset($pendaftaran) && $pendaftaran->link_pendaftaran)
                    <a href="{{ route('pendaftaran') }}"
                        class="text-blue-600 font-bold hover:text-blue-800 transition-colors py-1 border-b-2 {{ request()->routeIs('pendaftaran') ? 'border-blue-600' : 'border-transparent' }} hover:border-blue-300">Pendaftaran</a>
                @endif
            </div>

            <!-- CTA & Mobile Toggle -->
            <div class="flex items-center gap-4">
                @if(Auth::guard('ortu')->check())
                    <a href="{{ route('portal.ortu.dashboard') }}"
                        class="hidden md:inline-flex items-center justify-center px-5 py-2.5 border border-transparent text-sm font-bold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm hover:shadow-md transition-all active:scale-95">
                        Dashboard Anak
                    </a>
                @else
                    <a href="{{ route('login.ortu') }}"
                        class="hidden md:inline-flex items-center justify-center px-4 py-2.5 text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors">
                        Portal Orang Tua
                    </a>
                    <a href="/admin/login"
                        class="hidden md:inline-flex items-center justify-center px-5 py-2.5 border border-transparent text-sm font-bold rounded-lg text-white bg-blue-600 hover:bg-blue-700 shadow-sm hover:shadow-md transition-all active:scale-95">
                        Guru / Admin
                    </a>
                @endif

                <!-- Mobile Menu Button -->
                <button type="button"
                    class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"
                    aria-expanded="false">
                    <span class="sr-only">Buka menu utama</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>