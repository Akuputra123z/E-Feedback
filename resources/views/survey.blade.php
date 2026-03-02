@extends('layouts.app')

@section('title', 'Survei Kepuasan - Inspektorat Kabupaten Rembang')

@push('styles')
<style>
    :root {
        --primary-blue: #2563eb;
        --error-red: #ef4444;
    }
    .survey-gradient {
        background: radial-gradient(circle at top right, rgba(37, 99, 235, 0.05) 0%, transparent 50%),
                    linear-gradient(to bottom, #ffffff, #f8fafc);
    }
    .step-content { display: none; opacity: 0; }
    .step-content.active { display: block; animation: fadeInUp 0.4s ease-out forwards; }
    
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .rating-input:checked + .rating-box { 
        background-color: var(--primary-blue) !important;
        color: white !important;
        border-color: var(--primary-blue) !important;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }

    .error-highlight {
        border: 2px solid var(--error-red) !important;
        background-color: #fef2f2 !important;
        border-radius: 1rem;
    }
    
    
.animate-fadeInUp {
    animation: fadeInUp 0.5s ease-out forwards;
}

    .rating-box { transition: all 0.2s ease; cursor: pointer; }
</style>
@endpush

@section('content')
<div class="survey-gradient min-h-screen py-12 px-4">
    <div class="max-w-4xl mx-auto">
        <header class="text-center mb-10">
            <div class="inline-block px-4 py-1.5 bg-slate-100 text-slate-600 text-[10px] font-bold tracking-widest uppercase rounded-full border border-slate-200 mb-4">
                Sistem Informasi Survei
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Kuesioner Survei Kepuasan 
OPD terhadap Konsultasi dan 
Pendampingan Inspektorat</h1>
        </header>

        <main class="bg-white shadow-xl shadow-slate-200/50 rounded-[2rem] overflow-hidden border border-slate-100">
            @if(session('success'))
                <div class="p-16 text-center animate-fadeInUp">
        {{-- Animated Success Icon --}}
        <div class="relative w-24 h-24 mx-auto mb-8">
            {{-- Ping Effect --}}
            <div class="absolute inset-0 bg-emerald-400 rounded-full animate-ping opacity-20"></div>
            {{-- Main Icon --}}
            <div class="relative w-24 h-24 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-2xl shadow-emerald-200 border-4 border-white">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>

        <h2 class="text-3xl font-black text-slate-900 mb-4 tracking-tight">Data Berhasil Terkirim!</h2>
        
        <div class="max-w-sm mx-auto mb-10">
            <p class="text-slate-500 leading-relaxed italic">
                "Terima kasih atas partisipasi Anda. Masukan Anda sangat berharga untuk pelayanan kami yang lebih baik."
            </p>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('survey.create') }}" 
               class="w-full sm:w-auto px-10 py-4 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 hover:-translate-y-1 transition-all shadow-xl shadow-blue-100 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Isi Kembali
            </a>
            <a href="/" 
               class="w-full sm:w-auto px-10 py-4 bg-slate-100 text-slate-600 rounded-2xl font-bold hover:bg-slate-200 transition-all">
                Selesai
            </a>
        </div>
    </div>
            @else
                <div class="px-10 pt-8">
                    <div class="flex justify-between mb-2">
                        <span id="stepBadge" class="text-[10px] font-black uppercase tracking-widest text-blue-600"></span>
                        <span id="percentText" class="text-[10px] font-black text-slate-400">0%</span>
                    </div>
                    <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                        <div id="progressBar" class="bg-blue-600 h-full transition-all duration-500" style="width: 0%"></div>
                    </div>
                </div>

                <form method="POST" action="{{ route('survey.store') }}" id="surveyForm" novalidate>
                    @csrf

                   <section class="p-10 step-content" data-step="identity">
    {{-- Header Section dengan Ikon --}}
    <div class="mb-10 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl mb-4 border border-blue-100 shadow-sm">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </div>  
        {{-- <h4 class="section-title-label font-bold text-slate-900 uppercase tracking-wider text-sm mb-2"></h4> --}}
        <h3 class="text-2xl font-black text-slate-900 tracking-tight">Identitas Responden</h3>
        <p class="text-slate-500 text-sm mt-2">Mohon lengkapi data diri Anda untuk memulai survei</p>
    </div>

    <div class="space-y-8">
        {{-- Lokasi Survei - Menggunakan Background Subtle --}}
        <div class="group">
            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-3 group-focus-within:text-blue-600 transition-colors ml-1">
                Lokasi Survei
            </label>
            
            <div class="relative">
                <select name="lokasi_survey" id="lokasiSurvey" required 
                    class="w-full appearance-none !bg-none rounded-2xl px-6 py-5 bg-slate-50 border-2 border-slate-100 
                           focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-50 
                           transition-all outline-none font-semibold text-slate-700 cursor-pointer">
                    <option value="" disabled selected hidden>Pilih Lokasi</option>
                    <option value="Dalam Inspektorat" class="font-sans">Dalam Inspektorat</option>
                    <option value="Luar Inspektorat" class="font-sans">Luar Inspektorat</option>
                </select>
        
                <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 
                            group-focus-within:text-blue-600 group-focus-within:rotate-180 transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Email Resmi --}}
        <div class="group mb-6">
    <label for="email" class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 transition-colors group-focus-within:text-blue-600">
        Email Resmi
    </label>
    <div class="relative">
        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 transition-colors group-focus-within:text-blue-500">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </span>
        <input 
            type="email" 
            name="email" 
            id="email" 
            placeholder="contoh: nama@instansi.go.id"
            required
            autocomplete="email"
            class="w-full rounded-2xl pl-12 pr-4 py-4 bg-slate-50 border-2 border-slate-100 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-50 transition-all outline-none font-semibold text-slate-700 placeholder:text-slate-400 placeholder:font-normal"
        >
    </div>
</div>

        {{-- Nama Instansi --}}
        <div class="group">
            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-3 group-focus-within:text-blue-600 transition-colors">
                Nama Instansi / OPD
            </label>
            <div class="relative">
                <span class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                </span>
                <input type="text" name="opd" required placeholder="Contoh: Dinas Kesehatan Kab. Rembang"
                    class="w-full rounded-2xl pl-14 pr-6 py-5 bg-slate-50 border-2 border-slate-100 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-50 transition-all outline-none font-semibold text-slate-700 placeholder:text-slate-400 placeholder:font-normal">
            </div>
        </div>
    </div>
</section>
<section class="p-8 step-content template-step" data-step="2">
    {{-- Header Box ala Bagian Penilaian --}}
    <div class="mb-8 p-6 bg-slate-50 border border-slate-200 rounded-2xl">
        <div class="flex items-center gap-3 mb-3">
            <div class="p-2 bg-blue-600 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
           <h4 class="section-title-label font-bold text-slate-900 uppercase tracking-wider text-sm"></h4>
        </div>
        <p class="text-slate-600 text-sm leading-relaxed">
            Silakan pilih unit kerja (Irban) dan tentukan jenis layanan yang telah Anda terima.
        </p>
    </div>

    {{-- Sub-Title dengan Border Left --}}
    <div class="mb-8 border-l-4 border-blue-600 pl-4">
        <h3 class="text-xl font-bold text-slate-900">Detail Pelayanan</h3>
    </div>

    <div class="grid gap-8">
        {{-- Baris: Irban & Tanggal --}}
        <div class="grid md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest">Wilayah Irban</label>
                <select name="irban" id="irbanSelect" required 
                    class="w-full rounded-2xl border-2 border-slate-100 px-6 py-5 outline-none focus:border-blue-600 focus:bg-white transition-all bg-white font-medium text-slate-700">
                    <option value="">Pilih Wilayah...</option>
                    @foreach(['irban1' => 'Irban I', 'irban2' => 'Irban II', 'irban3' => 'Irban III', 'irban4' => 'Irban IV', 'irbansus' => 'Irban Khusus', 'sekretariat' => 'Sekretariat'] as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest">Tanggal Layanan</label>
                <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" required 
                    class="w-full rounded-2xl border-2 border-slate-100 px-6 py-5 outline-none focus:border-blue-600 focus:bg-white transition-all bg-white font-medium text-slate-700">
            </div>
        </div>

        {{-- INFO BOX MODERN --}}
        <div id="irbanInfoBox" class="hidden overflow-hidden rounded-3xl border border-blue-100 bg-white shadow-sm transition-all duration-500 animate-fadeInUp">
            <div class="bg-blue-600 px-6 py-3">
                <h4 id="infoTitle" class="text-[10px] font-bold text-white uppercase tracking-widest"></h4>
            </div>
            
            <div class="p-6">
                <div class="grid gap-6 md:grid-cols-2">
                    <div class="flex gap-4">
                        <div class="flex-none p-2 bg-blue-50 text-blue-500 rounded-xl h-fit">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-tight">Fokus Utama</span>
                            <p id="infoFokus" class="text-sm font-bold text-slate-800 leading-tight mt-0.5"></p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-none p-2 bg-emerald-50 text-emerald-500 rounded-xl h-fit">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-tight">Tugas Utama</span>
                            <p id="infoTugas" class="text-xs text-slate-600 leading-relaxed mt-0.5"></p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 rounded-2xl bg-slate-50 p-5 border border-slate-100">
                    <span class="mb-3 block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Lingkup Kerja / Dokumen Terkait</span>
                    <div id="infoLingkup" class="flex flex-wrap gap-2">
                        {{-- Tags akan masuk ke sini --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- Jenis Layanan --}}
        <div class="space-y-2">
    <label 
        for="jenis_layanan"
        class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
        Jenis Layanan
    </label>

    <select id="jenis_layanan"name="jenis_layanan"required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-medium text-slate-700 shadow-sm transition-all duration-200  focus:border-blue-600 focus:ring-4 focus:ring-blue-100 focus:outline-none  hover:border-slate-400">
        <option value="" disabled selected> Pilih Kategori Layanan...</option>
        <option value="Audit/Reguler Investigatif">Audit / Reguler Investigatif</option>
        <option value="Reviu">Reviu</option>
        <option value="Evaluasi">Evaluasi</option>
        <option value="Pemeriksaan">Pemeriksaan</option>
        <option value="Consulting">Consulting (Sosialisasi, Bimtek, Coaching Clinic, Pendampingan, Asistensi)</option>
    </select>
</div>
    </div>
</section>
@php
    $sectionNumber = 3;
    $globalIndex = 1;
@endphp

@foreach($questions as $dimension => $items)
<section class="p-8 step-content dimension-section" data-step="dimension-{{ $dimension }}" data-dimension="{{ $dimension }}">
    
    {{-- Header Box: Instruksi Penilaian --}}
    <div class="mb-8 p-6 bg-slate-50 border border-slate-200 rounded-2xl">
        <div class="flex items-center gap-3 mb-3">
            <div class="p-2 bg-blue-600 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            
            {{-- 1. TAMBAHKAN CLASS section-title-label DI SINI --}}
            <h4 class="section-title-label font-bold text-slate-900 uppercase tracking-wider text-sm"></h4>
            
            {{-- 2. TAMBAHKAN INPUT HIDDEN UNTUK MENYIMPAN NAMA DIMENSI --}}
            <input type="hidden" class="dimension-name" value="{{ $dimensionLabels[$dimension] ?? $dimension }}">
        </div>
        <p class="text-slate-600 text-sm leading-relaxed mb-4">
            Silakan berikan penilaian Anda dengan memilih angka 1 sampai 5 berdasarkan skala berikut:
        </p>
        
        {{-- Legend Skala Nilai --}}
        <div class="flex flex-wrap gap-2">
            @foreach([
                1 => 'Sangat Tidak Puas',
                2 => 'Tidak Puas',
                3 => 'Cukup Puas',
                4 => 'Puas',
                5 => 'Sangat Puas'
            ] as $val => $label)
            <div class="flex items-center gap-2 px-3 py-1.5 bg-white border border-slate-200 rounded-full shadow-sm">
                <span class="flex items-center justify-center w-5 h-5 bg-slate-900 text-white text-[10px] font-bold rounded-full">{{ $val }}</span>
                <span class="text-[11px] font-medium text-slate-700 uppercase tracking-tighter">{{ $label }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Judul Dimensi --}}
    <div class="mb-8 border-l-4 border-blue-600 pl-4">
        <h3 class="text-xl font-bold text-slate-900">
            {{ $dimensionLabels[$dimension] ?? $dimension }}
        </h3>
    </div>

    {{-- Daftar Pertanyaan --}}
    <div class="space-y-12">
        @foreach($items as $q)
        <div class="question-block group p-4 rounded-2xl transition-all">
            <div class="flex items-start gap-4 mb-5">
                {{-- Nomor Urut --}}
                <span class="flex-none w-7 h-7 bg-blue-50 text-blue-600 text-xs flex items-center justify-center rounded-lg font-bold border border-blue-100">
                    {{ $globalIndex++ }}
                </span>
                <p class="font-medium text-slate-800 text-[15px] leading-relaxed">
                    {{ $q->text }}
                </p>
            </div>

            {{-- Grid Pilihan Angka --}}
            <div class="grid grid-cols-5 gap-4">
                @foreach(range(1, 5) as $val)
                <label class="relative cursor-pointer">
                    <input type="radio" 
                           name="answers[{{ $q->id }}]" 
                           value="{{ $val }}" 
                           required 
                           class="hidden rating-input">
                    <div class="rating-box flex items-center justify-center py-4 rounded-2xl font-bold text-lg bg-white border-2 border-slate-100 text-slate-400 hover:border-blue-200 transition-all">
                        {{ $val }}
                    </div>
                </label>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</section>
@endforeach

           <section class="p-8 step-content" data-step="aspirasi">
    {{-- Header Box ala Bagian I, II, dan III --}}
    <div class="mb-8 p-6 bg-slate-50 border border-slate-200 rounded-2xl">
        <div class="flex items-center gap-3 mb-3">
            <div class="p-2 bg-blue-600 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                </svg>
            </div>
            {{-- PERBAIKAN: Tambahkan class section-title-label agar nomor otomatis muncul --}}
            <h4 class="section-title-label font-bold text-slate-900 uppercase tracking-wider text-sm"></h4>
        </div>
        <p class="text-slate-600 text-sm leading-relaxed">
            Masukan Anda sangat berarti bagi kami untuk terus berbenah dan memberikan pelayanan terbaik ke depannya.
        </p>
    </div>

    {{-- Sub-Title --}}
    <div class="mb-8 border-l-4 border-blue-600 pl-4">
        <h3 class="text-xl font-bold text-slate-900">Kritik & Saran</h3>
        <p class="text-xs text-slate-500 mt-1 uppercase tracking-tighter">Opsional (Boleh dikosongkan)</p>
    </div>

    <div class="relative group">
        {{-- Ikon Floating di sudut textarea --}}
        <div class="absolute top-5 left-6 text-slate-300 group-focus-within:text-blue-500 transition-colors pointer-events-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
        </div>

        <textarea 
            name="suggestions" 
            id="suggestions" 
            rows="6" 
            maxlength="1000" 
            placeholder="Tuliskan kritik, saran, atau apresiasi Anda di sini..."
            class="w-full rounded-[2rem] pl-16 pr-8 py-6 bg-white border-2 border-slate-100 focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-50 outline-none resize-none transition-all font-medium text-slate-700 placeholder:text-slate-400 placeholder:font-normal"
        ></textarea>

        {{-- Character Counter Floating --}}
        <div class="absolute bottom-6 right-8 flex items-center gap-2">
            <div class="h-1.5 w-24 bg-slate-100 rounded-full overflow-hidden hidden sm:block">
                <div id="charProgress" class="h-full bg-blue-600 transition-all duration-300" style="width: 0%"></div>
            </div>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50 px-3 py-1 rounded-full border border-slate-100">
                <span id="charCount" class="text-blue-600">0</span> / 1000
            </span>
        </div>
    </div>
</section>
                    <footer class="px-10 py-6 bg-slate-50 border-t flex justify-between">
                        <button type="button" id="prevBtn" class="invisible text-slate-400 font-bold text-xs uppercase tracking-widest">← Kembali</button>
                        <div class="flex gap-4">
                            <button type="button" id="nextBtn" class="bg-blue-600 text-white px-8 py-3 rounded-xl font-bold">Lanjutkan</button>
                            <button type="submit" id="submitBtn" class="hidden bg-emerald-600 text-white px-8 py-3 rounded-xl font-bold">Kirim Survei</button>
                        </div>
                    </footer>
                </form>
            @endif
        </main>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('surveyForm');
    const steps = Array.from(document.querySelectorAll('.step-content'));
    const lokSelect = document.getElementById('lokasiSurvey');
    const irbanSelect = document.getElementById('irbanSelect');
    const suggestions = document.getElementById('suggestions');
    const charCount = document.getElementById('charCount');
    const charProgress = document.getElementById('charProgress');
    let currentStep = 0;

    const irbanData = {
        irban1: {
            title: "Irban Wilayah I",
            fokus: "Pemerintahan & Perencanaan",
            tugas: "Evaluasi dan reviu kepatuhan peraturan pada instansi perencanaan.",
            lingkup: ["RKA","DPA","LKJIP","Renstra","Renja","RPJMD","LPPD","SAKIP"]
        },
        irban2: {
            title: "Irban Wilayah II",
            fokus: "Akuntabilitas & Infrastruktur",
            tugas: "Memastikan proyek infrastruktur sesuai spek dan anggaran diawasi ketat.",
            lingkup: ["Audit Kinerja","Dana BOS","BUMD","BLUD","PBJ","Infrastruktur"]
        },
        irban3: {
            title: "Irban Wilayah III",
            fokus: "Pemerintahan Desa",
            tugas: "Mengaudit dana dan pengawasan penyelenggaraan pemerintah desa.",
            lingkup: ["Dana Desa","ADD","Aset Desa","Administrasi Desa","Bumdes"]
        },
        irban4: {
            title: "Irban Wilayah IV",
            fokus: "Reformasi Birokrasi",
            tugas: "Mengawal perubahan birokrasi, integritas, dan budaya kerja.",
            lingkup: ["Zona Integritas","Benturan Kepentingan","Budaya Kerja","Pelayanan Publik"]
        },
        irbansus: {
            title: "Irban Khusus",
            fokus: "Investigasi & Pengaduan",
            tugas: "Menangani tindak pidana korupsi dan pelanggaran disiplin berat.",
            lingkup: ["Audit Investigatif","Siber Pungli","LHKPN","Whistleblowing"]
        },
        sekretariat: {
            title: "Sekretariat",
            fokus: "Administrasi & Tindak Lanjut",
            tugas: "Mengoordinasikan pelaporan dan pemantauan tindak lanjut hasil audit.",
            lingkup: ["TL BPK","TL BPKP","MCP KPK","LHKASN","TL Desa"]
        }
    };

    // --- LOGIKA UTAMA ---

    function getActiveSteps() {
        return steps.filter(step => {
            if (lokSelect.value === 'Luar Inspektorat' && step.dataset.dimension === 'Dukungan') return false;
            return true;
        });
    }

    function updateUI() {
        const activeSteps = getActiveSteps();
        const roman = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII'];

        steps.forEach(s => {
            s.style.display = 'none';
            s.classList.remove('active');
        });

        if (currentStep >= activeSteps.length) currentStep = activeSteps.length - 1;
        const currentEl = activeSteps[currentStep];
        currentEl.style.display = 'block';
        setTimeout(() => currentEl.classList.add('active'), 10);

        // Update Label Bagian
        activeSteps.forEach((step, index) => {
            const label = step.querySelector('.section-title-label');
            if (label) {
                const dimensionNameInput = step.querySelector('.dimension-name');
                if (dimensionNameInput) {
                    label.textContent = `Bagian ${roman[index]} : ${dimensionNameInput.value}`;
                } else if (step.dataset.step === 'aspirasi') {
                    label.textContent = `Bagian ${roman[index]} : Aspirasi`;
                } else if (step.dataset.step === 'identity') {
                    label.textContent = `Bagian ${roman[index]} : Identitas Responden`;
                } else if (step.classList.contains('template-step')) {
                    label.textContent = `Bagian ${roman[index]} : Informasi Layanan`;
                }
            }
        });

        // Update Progress
        const progress = ((currentStep + 1) / activeSteps.length) * 100;
        document.getElementById('progressBar').style.width = `${progress}%`;
        document.getElementById('stepBadge').textContent = `Langkah ${currentStep + 1} dari ${activeSteps.length}`;
        document.getElementById('percentText').textContent = `${Math.round(progress)}%`;

        // Navigasi
        document.getElementById('prevBtn').style.visibility = currentStep === 0 ? 'hidden' : 'visible';
        if (currentStep === activeSteps.length - 1) {
            document.getElementById('nextBtn').classList.add('hidden');
            document.getElementById('submitBtn').classList.remove('hidden');
        } else {
            document.getElementById('nextBtn').classList.remove('hidden');
            document.getElementById('submitBtn').classList.add('hidden');
        }
    }

    // --- VALIDASI ---

    function validateStep() {
        const activeSteps = getActiveSteps();
        const currentEl = activeSteps[currentStep];
        let valid = true;

        currentEl.querySelectorAll('[required]:not([type="radio"])').forEach(i => {
            const isEmail = i.type === 'email';
            const emailPattern = /^[a-z0-9][a-z0-9._-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i;
            let isFieldValid = true;

            if (isEmail) {
                if (!i.value.trim() || !emailPattern.test(i.value)) isFieldValid = false;
            } else {
                if (!i.value.trim()) isFieldValid = false;
            }

            if (!isFieldValid) {
                i.classList.add('border-red-500', 'ring-2', 'ring-red-100');
                valid = false;
            }
        });

        currentEl.querySelectorAll('.question-block').forEach(block => {
            if (!block.querySelector('input:checked')) {
                block.classList.add('error-highlight');
                valid = false;
            }
        });

        return valid;
    }

    // --- EVENT LISTENERS ---

    // Auto-clear error highlights
    form.querySelectorAll('input, select, textarea').forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('border-red-500', 'ring-2', 'ring-red-100');
            this.closest('.question-block')?.classList.remove('error-highlight');
        });
    });

    // Character Counter
    if(suggestions) {
        suggestions.addEventListener('input', () => {
            const length = suggestions.value.length;
            charCount.textContent = length;
            charProgress.style.width = (length / 1000 * 100) + '%';
        });
    }

    document.getElementById('nextBtn').addEventListener('click', () => {
        if (!validateStep()) {
            Swal.fire('Perhatian', 'Mohon lengkapi semua isian yang wajib diisi dengan benar.', 'warning');
        } else {
            currentStep++;
            updateUI();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });

    document.getElementById('prevBtn').addEventListener('click', () => {
        if (currentStep > 0) {
            currentStep--;
            updateUI();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });

    irbanSelect.addEventListener('change', () => {
        const data = irbanData[irbanSelect.value];
        if (data) {
            document.getElementById('infoTitle').textContent = data.title;
            document.getElementById('infoFokus').textContent = data.fokus;
            document.getElementById('infoTugas').textContent = data.tugas;
            document.getElementById('infoLingkup').innerHTML = data.lingkup
                .map(l => `<span class="px-2 py-1 bg-white border border-blue-200 rounded-lg text-[10px] font-bold text-blue-700">${l}</span>`)
                .join('');
            document.getElementById('irbanInfoBox').classList.remove('hidden');
        } else {
            document.getElementById('irbanInfoBox').classList.add('hidden');
        }
    });

    // Proteksi Double Submit
    form.addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin h-5 w-5 text-white inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg> Mengirim...`;
    });

    updateUI();
});
</script>
@endpush
@endsection