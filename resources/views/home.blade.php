
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Beranda - Inspektorat Kabupaten Rembang</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#195de6",
                        secondary: "#0f172a",
                        accent: "#38bdf8",
                        backgroundLight: "#f8fafc",
                    },
                    fontFamily: { sans: ["Plus Jakarta Sans", "sans-serif"] },
                },
            },
        }
    </script>
    <style>
    .hero-gradient {
        background: radial-gradient(circle at top right, rgba(25,93,230,0.08) 0%, transparent 50%),
                    radial-gradient(circle at bottom left, rgba(56,189,248,0.05) 0%, transparent 50%);
    }
    .glass-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
</style>
</head>
<body class="bg-backgroundLight text-slate-800 antialiased">

    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-24">
            <div class="flex items-center">
                <img src="https://euadit.kadinrembang.com/storage/images/logo1.png" 
                     alt="Logo Rembang" 
                     class="w-48 md:w-64 h-auto object-left"> 
            </div>

            <nav class="hidden md:flex items-center gap-8">
                <a href="/" class="text-sm font-bold text-blue-600 hover:text-blue-600 transition-colors">Beranda</a>
                {{-- <a href="/survey" class="text-sm font-bold text-slate-600 hover:text-blue-600 transition-colors">Survey</a> --}}
                  <a href="{{ route('survey.results') }}" class="text-sm font-bold text-slate-600 hover:text-blue-600 transition-colors">Hasil Survei</a>
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
        <a href="/" class="block text-base font-bold text-blue-600 hover:text-blue-600">Beranda</a>
        <a href="/survey" class="block text-base font-bold text-slate-600 hover:text-blue-600">Survey</a>
        <a href="/admin" class="block text-base font-bold text-slate-600 hover:text-blue-600">Portal Admin</a>
        <hr class="border-slate-100">
        <a href="/survey" class="block w-full text-center bg-blue-600 text-white py-3 rounded-xl font-bold shadow-md">
            Isi Survey Sekarang
        </a>
    </div>
</header>


    <main>
        <section class="relative min-h-[90vh] flex items-center justify-center hero-gradient px-6 overflow-hidden">
    <div class="max-w-4xl w-full text-center space-y-10">

        <div class="inline-flex items-center gap-2 bg-white/80 backdrop-blur-md px-6 py-3 rounded-full border border-slate-200 shadow-sm mb-8">
         
            <span class="text-2xl font-bold text-slate-700">SIPUAS ITDA </span>
        </div>
         {{-- <h1 class="text-5xl md:text-7xl font-extrabold text-slate-900 tracking-tight leading-[1.1]">
            SIPUAS IPTDA<br>
            
        </h1> --}}

        <h1 class="text-5xl md:text-7xl font-extrabold text-slate-900 tracking-tight leading-[1.1]">
            SUARA ANDA<br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-blue-400">
                INTEGRITAS KAMI
            </span>
        </h1>
        <p class="text-lg md:text-xl text-slate-600 max-w-2xl mx-auto leading-relaxed">
            Kami berkomitmen meningkatkan kualitas pelayanan publik melalui pengawasan profesional di Kabupaten Rembang.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="/survey" class="bg-primary text-white font-bold px-10 py-5 rounded-2xl shadow-2xl shadow-blue-200 hover:bg-blue-700 transition-all">
                Mulai Survei Kepuasan
            </a>
        </div>
    </div>
</section>
    </main>

    <footer class="bg-white text-slate-600 pt-20 pb-10 px-6 border-t border-slate-200">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 mb-16">
            <div class="lg:col-span-5 space-y-6">
                <div class="flex items-center">
                    <img src="https://euadit.kadinrembang.com/storage/images/logo1.png" 
                         alt="Logo Rembang" 
                         class="w-64 md:w-80 h-auto object-contain"> 
                    </div>
                <p class="text-slate-500 max-w-sm leading-relaxed text-sm">
                    Unit kerja yang bertugas melakukan pengawasan internal di lingkungan Pemerintah Kabupaten Rembang untuk mewujudkan tata kelola yang baik.
                </p>
            </div>

            <div class="lg:col-span-3 space-y-6">
                <h4 class="text-xs font-black uppercase tracking-[0.2em] text-blue-600">Tautan Cepat</h4>
                <ul class="space-y-3 text-sm">
                      <li><a href="/" class="hover:text-blue-600 transition-colors">Beranda</a></li>
                    <li><a href="/survey" class="hover:text-blue-600 transition-colors">E-Survei Kepuasan</a></li>
                    <li><a href="/admin" class="hover:text-blue-600 transition-colors">Portal Admin</a></li>
                    <li><a href="https://simantab.rembangkab.go.id/" class="hover:text-blue-600 transition-colors">SIMANTAP</a></li>
                    <li><a href="https://drive-tlaudit.rembangkab.go.id/login" class="hover:text-blue-600 transition-colors">TLAUDIT</a></li>
                    
                </ul>
            </div>
            
            <div class="lg:col-span-4 space-y-6">
                <h4 class="text-xs font-black uppercase tracking-[0.2em] text-blue-600">Kontak Kami</h4>
                <ul class="space-y-4 text-sm">
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Jl. Pangeran Diponegoro No. 83, Rembang</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-slate-100 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
            <p>© 2026 Pemerintah Kabupaten Rembang.</p>
        </div>
    </div>
</footer>
    <script>
    // Gunakan event listener DOMContentLoaded untuk memastikan semua elemen sudah siap
    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('mobile-menu-button');
        const menu = document.getElementById('mobile-menu');

        if (btn && menu) {
            btn.addEventListener('click', function() {
                menu.classList.toggle('hidden');
                console.log('Toggle clicked!'); // Cek di console (F12) untuk tes
            });
        }
    });
</script>
</body>
</html>