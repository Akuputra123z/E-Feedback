<footer class="bg-white text-slate-600 pt-20 pb-10 px-6 border-t border-slate-200">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 mb-16">
            <div class="lg:col-span-5 space-y-6">
                <div class="flex items-center">
                    <img src="{{ asset('storage/images/logo1.png') }}" 
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
                    <li><a href="https://simantab.rembangkab.go.id/" class="hover:text-blue-600 transition-colors">SIMANTAP</a></li>
                    <li><a href="https://drive-tlaudit.rembangkab.go.id/login" class="hover:text-blue-600 transition-colors">TLAUDIT</a></li>
                    <li><a href="/admin" class="hover:text-blue-600 transition-colors">Portal Admin</a></li>
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
            <p>© {{ date('Y') }} Pemerintah Kabupaten Rembang.</p>
        </div>
    </div>
</footer>