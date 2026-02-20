<header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-24">
            <div class="flex items-center">
                <img src="{{ asset('storage/images/logo-rembang.png') }}" 
                     alt="Logo Rembang" 
                     class="w-48 md:w-64 h-auto object-left"> 
            </div>

            <nav class="hidden md:flex items-center gap-8">
                <a href="/" class="text-sm font-bold {{ request()->is('/') ? 'text-blue-600' : 'text-slate-600' }} hover:text-blue-600 transition-colors">Beranda</a>
                <a href="/survey" class="text-sm font-bold {{ request()->is('survey*') ? 'text-blue-600' : 'text-slate-600' }} hover:text-blue-600 transition-colors">Survey</a>
                <a href="/admin" class="text-sm font-bold text-slate-600 hover:text-blue-600 transition-colors">Portal Admin</a>
            </nav>

            <div class="flex items-center gap-4">
                <a href="/survey" class="hidden md:inline-flex bg-blue-600 hover:bg-blue-700 text-white px-7 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-blue-200 transition-all">
                    Isi Survey
                </a>
                <button id="mobile-menu-button" type="button" class="md:hidden p-2 text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 focus:outline-none transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path id="menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div id="mobile-menu" class="hidden bg-white border-b border-slate-200 px-4 py-6 space-y-4 shadow-xl">
        <a href="/" class="block text-base font-bold {{ request()->is('/') ? 'text-blue-600' : 'text-slate-600' }} hover:text-blue-600">Beranda</a>
        <a href="/survey" class="block text-base font-bold {{ request()->is('survey*') ? 'text-blue-600' : 'text-slate-600' }} hover:text-blue-600">Survey</a>
        <a href="/admin" class="block text-base font-bold text-slate-600 hover:text-blue-600">Portal Admin</a>
        <hr class="border-slate-100">
        <a href="/survey" class="block w-full text-center bg-blue-600 text-white py-3 rounded-xl font-bold shadow-md">
            Isi Survey Sekarang
        </a>
    </div>
</header>

