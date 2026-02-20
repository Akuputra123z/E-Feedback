@extends('layouts.app')

@section('title', 'Beranda - Inspektorat Kabupaten Rembang')

@push('styles')
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
@endpush

@section('content')
<section class="relative min-h-[90vh] flex items-center justify-center hero-gradient px-6 overflow-hidden">
    <div class="max-w-4xl w-full text-center space-y-10">
        <h1 class="text-5xl md:text-7xl font-extrabold text-slate-900 tracking-tight leading-[1.1]">
            Wujudkan Pemerintahan <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-blue-400">
                Bersih & Akuntabel
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
@endsection