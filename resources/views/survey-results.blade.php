@extends('layouts.app')

@section('title', 'Hasil Survei - Inspektorat Kabupaten Rembang')

@section('content')
<main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8">
    <section class="relative overflow-hidden rounded-xl mb-10">
        <div class="absolute inset-0 bg-gradient-to-br from-primary/10 to-primary/5 dark:from-primary/20 dark:to-transparent"></div>
        <div class="relative px-8 py-12 md:py-16 text-center max-w-3xl mx-auto">
            <span class="inline-block px-3 py-1 bg-primary/10 text-primary text-xs font-bold rounded-full mb-4 tracking-widest uppercase">Laporan Publik {{ date('Y') }}</span>
            <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 dark:text-white mb-6 tracking-tight">
                Hasil Survei Kepuasan Masyarakat
            </h1>
            <p class="text-lg text-slate-600 dark:text-slate-400 leading-relaxed">
                Data transparan mengenai tingkat kepuasan layanan publik di Inspektorat Kabupaten Rembang untuk peningkatan kualitas berkelanjutan.
            </p>
        </div>
    </section>

    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8 bg-white dark:bg-slate-900/50 p-4 rounded-xl border border-slate-200 dark:border-slate-800">
        <div class="flex items-center gap-3 w-full md:w-auto">
            <div class="flex items-center gap-2 bg-slate-100 dark:bg-slate-800 px-4 py-2 rounded-lg text-slate-700 dark:text-slate-300 border border-transparent hover:border-primary/30 transition-all cursor-pointer">
                <span class="material-symbols-outlined text-lg">calendar_today</span>
                <span class="text-sm font-semibold">Akumulasi Real-time</span>
            </div>
        </div>
        <p class="text-slate-500 dark:text-slate-400 text-sm italic">Terakhir diperbarui: {{ now()->format('d M Y, H:i') }} WIB</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-xl p-8 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row items-center gap-8 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-110"></div>
            
            <div class="relative flex flex-col items-center justify-center p-6 bg-primary/5 dark:bg-primary/10 rounded-2xl w-full md:w-48 h-48 border border-primary/20">
                <span id="mainScore" class="text-5xl font-black text-primary">{{ number_format($averageScore * 20, 1) }}</span>
                <span class="text-xs font-bold text-primary/60 uppercase tracking-tighter mt-1">Skor IKM</span>
            </div>

            <div class="flex-1 text-center md:text-left">
                <div class="inline-flex items-center gap-1.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 px-3 py-1 rounded-full text-xs font-bold mb-4">
                    <span class="material-symbols-outlined text-sm">verified</span>
                    @if(($averageScore * 20) >= 88.31) Sangat Baik (Kualitas A)
                    @elseif(($averageScore * 20) >= 76.61) Baik (Kualitas B)
                    @else Cukup (Kualitas C)
                    @endif
                </div>
                <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Indeks Kepuasan Masyarakat</h3>
                <p class="text-slate-600 dark:text-slate-400 leading-relaxed mb-6">
                    Berdasarkan hasil pengolahan data dari <strong>{{ $totalResponses }} responden aktif</strong>, unit pelayanan mendapatkan nilai mutu pelayanan yang stabil dengan tren positif.
                </p>
                <div class="flex gap-4 justify-center md:justify-start">
                    <div class="flex flex-col">
                        <span class="text-xs text-slate-400 font-medium uppercase">Skala 1-5</span>
                        <span class="text-primary font-bold text-lg">{{ number_format($averageScore, 2) }}</span>
                    </div>
                    <div class="w-px h-10 bg-slate-200 dark:bg-slate-800"></div>
                    <div class="flex flex-col">
                        <span class="text-xs text-slate-400 font-medium uppercase">Status</span>
                        <span class="text-emerald-600 font-bold text-lg">Aktif</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm group">
                <div class="flex justify-between items-start mb-2">
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-semibold">Total Responden</p>
                    <span class="material-symbols-outlined text-primary group-hover:scale-110 transition-transform">groups</span>
                </div>
                <p class="text-3xl font-bold text-slate-900 dark:text-white counter" data-target="{{ $totalResponses }}">0</p>
                <div class="mt-2 flex items-center text-emerald-600 text-xs font-bold">
                    <span class="material-symbols-outlined text-xs">verified_user</span>
                    <span>Data Terverifikasi</span>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm group">
                <div class="flex justify-between items-start mb-2">
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-semibold">Nilai Rata-rata</p>
                    <span class="material-symbols-outlined text-primary group-hover:scale-110 transition-transform">analytics</span>
                </div>
                <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($averageScore, 2) }}</p>
                <div class="mt-2 flex items-center text-primary text-xs font-bold">
                    <span>Skala Likert 1 - 5</span>
                </div>
            </div>
        </div>
    </div>

    <section class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden mb-12">
        <div class="p-6 border-b border-slate-100 dark:border-slate-800">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white">Detail Unsur Pelayanan</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8 p-8">
            @foreach($dimensionStats as $stat)
            <div class="flex flex-col gap-2">
                <div class="flex justify-between items-end">
                    <span class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $stat->dimension }}</span>
                    <span class="text-sm font-black text-primary">{{ number_format($stat->avg_score, 2) }}</span>
                </div>
                <div class="h-2 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                    <div class="h-full bg-primary rounded-full chart-bar" style="width: {{ ($stat->avg_score / 5) * 100 }}%;"></div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
</main>

<script>
    // Animasi Counter untuk Responden
    document.addEventListener('DOMContentLoaded', () => {
        const counters = document.querySelectorAll('.counter');
        counters.forEach(counter => {
            const target = +counter.getAttribute('data-target');
            const count = () => {
                const current = +counter.innerText;
                const inc = target / 30; 
                if (current < target) {
                    counter.innerText = Math.ceil(current + inc);
                    setTimeout(count, 50);
                } else {
                    counter.innerText = target;
                }
            };
            count();
        });
    });
</script>
@endsection