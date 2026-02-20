@extends('layouts.app')

@section('title', 'Survei Kepuasan - Inspektorat Kabupaten Rembang')

@push('styles')
<style>
    .survey-gradient {
        background: radial-gradient(circle at top right, rgba(25,93,230,0.05) 0%, transparent 50%),
                    radial-gradient(circle at bottom left, rgba(56,189,248,0.03) 0%, transparent 50%);
    }
    .step-content { display: none; }
    .step-content.active { display: block; animation: fadeInUp 0.5s ease-out forwards; }
    
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Radio Button Custom Styling */
    .rating-label {
        border: 2px solid #f1f5f9;
        transition: all 0.2s ease;
    }
    input[type="radio"]:checked + .rating-label { 
        border-color: #2563eb; 
        background-color: #eff6ff;
        color: #2563eb;
        transform: scale(1.05);
        box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.1);
    }

    .question-block.error-highlight {
        border-left: 4px solid #ef4444;
        background-color: #fef2f2;
        padding: 1.5rem;
        border-radius: 1rem;
    }
</style>
@endpush

@section('content')
<div class="survey-gradient min-h-screen py-12 px-4">
    <div class="max-w-4xl mx-auto">
        
        <div class="text-center mb-12">
            <span class="px-4 py-1.5 bg-slate-100 text-slate-600 text-[10px] font-bold tracking-[0.2em] uppercase rounded-full border border-slate-200">
                Sistem Informasi Survei
            </span>
            <h2 class="text-3xl font-extrabold text-slate-900 mt-4 tracking-tight">
                Formulir Survei Kepuasan Layanan
            </h2>
        </div>

        <div class="bg-white shadow-2xl shadow-slate-200/60 rounded-[2rem] overflow-hidden border border-slate-100">
            
            <div class="px-10 pt-8">
                <div class="flex justify-between mb-3">
                    <span id="stepBadge" class="text-[10px] font-black uppercase tracking-widest text-blue-600">Langkah 1 dari 4</span>
                    <span id="percentText" class="text-[10px] font-black text-slate-400">25%</span>
                </div>
                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                    <div id="progressBar" class="bg-blue-600 h-full transition-all duration-700 ease-in-out" style="width: 25%"></div>
                </div>
            </div>

            <form method="POST" action="{{ route('survey.store') }}" id="surveyForm">
                @csrf

                <div class="p-10 step-content active" id="step1">
                    <div class="mb-10 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl mb-4 border border-blue-100">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900">Identitas Responden</h3>
                        <p class="text-sm text-slate-500 mt-1">Gunakan alamat email resmi untuk validasi sistem.</p>
                    </div>

                    <div class="space-y-8">
                        <div class="relative group">
                            <label class="flex items-center gap-2 text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-3 group-focus-within:text-blue-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                Alamat Email Resmi
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required 
                                   placeholder="nama@rembangkab.go.id"
                                   class="w-full rounded-2xl px-6 py-5 bg-slate-50 border-2 {{ $errors->has('email') ? 'border-red-400 ring-4 ring-red-50' : 'border-slate-100' }} focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-500/5 transition-all outline-none text-slate-700 font-medium">
                            @error('email')
                                <div class="flex items-center gap-2 mt-3 text-red-600">
                                    <p class="text-xs font-bold italic">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>

                        <div class="relative group">
                            <label class="flex items-center gap-2 text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-3 group-focus-within:text-blue-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                Nama Instansi / OPD
                            </label>
                            <input type="text" name="opd" value="{{ old('opd') }}" required 
                                   placeholder="Masukkan nama lengkap OPD Anda"
                                   class="w-full rounded-2xl px-6 py-5 bg-slate-50 border-2 border-slate-100 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-500/5 transition-all outline-none text-slate-700 font-medium">
                        </div>
                    </div>
                </div>

                <div class="p-10 step-content" id="step2">
                    <div class="mb-8 border-l-4 border-blue-600 pl-4">
                        <h3 class="text-xl font-bold text-slate-900">02. Detail Layanan</h3>
                        <p class="text-sm text-slate-400">Unit kerja dan waktu pelaksanaan.</p>
                    </div>

                    <div class="grid gap-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-500 uppercase tracking-wider">Wilayah Inspektur Pembantu</label>
                                <select name="irban" id="irbanSelect" required onchange="updateIrbanInfo()"
                                        class="w-full rounded-xl border-slate-200 px-5 py-4 focus:ring-4 focus:ring-blue-500/5 focus:border-blue-600 transition-all outline-none">
                                    <option value="">Pilih Wilayah...</option>
                                    <option value="irban1">Irban I</option>
                                    <option value="irban2">Irban II</option>
                                    <option value="irban3">Irban III</option>
                                    <option value="irban4">Irban IV</option>
                                    <option value="irbansus">Irban Khusus</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-500 uppercase tracking-wider">Tanggal Responden</label>
                                <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required
                                       class="w-full rounded-xl border-slate-200 px-5 py-4 focus:ring-4 focus:ring-blue-500/5 focus:border-blue-600 transition-all outline-none">
                            </div>
                        </div>

                        <div id="irbanInfoBox" class="hidden">
                            <div class="bg-blue-50/50 border border-blue-100 p-5 rounded-xl">
                                <h4 id="irbanTitle" class="font-black text-blue-700 text-xs uppercase mb-2 tracking-tight"></h4>
                                <div id="irbanContent" class="text-[11px] text-slate-600 leading-relaxed space-y-1"></div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-500 uppercase tracking-wider">Jenis Layanan</label>
                            <select name="jenis_layanan" required
                                    class="w-full rounded-xl border-slate-200 px-5 py-4 focus:ring-4 focus:ring-blue-500/5 focus:border-blue-600 transition-all outline-none">
                                <option value="">Pilih Kategori...</option>
                                @foreach(['Konsultasi Tata Kelola', 'Pendampingan Reviu', 'Asistensi Teknis', 'Audit Investigatif'] as $layanan)
                                    <option value="{{ $layanan }}">{{ $layanan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

             <div class="p-4 md:p-8 step-content" id="step3">
    <div class="mb-5 border-l-4 border-blue-600 pl-3">
        <h3 class="text-base md:text-lg font-bold text-slate-900 leading-tight">03. Kualitas Layanan</h3>
        <p class="text-[11px] md:text-xs text-slate-500 mt-1">Skala 1 (Kurang) sampai 5 (Sangat Baik)</p>
    </div>

    <div class="space-y-8">
        @forelse($questions as $index => $question)
            <div class="question-block pb-2">
                <div class="flex items-start gap-3 mb-4">
                    <span class="flex-none w-6 h-6 bg-blue-600 text-white text-[10px] flex items-center justify-center rounded-md font-bold shrink-0 mt-0.5">
                        {{ $index + 1 }}
                    </span>
                    <p class="font-medium text-slate-800 text-[14px] md:text-[15px] leading-snug">
                        {{ $question->text }}
                    </p>
                </div>
                
                <div class="grid grid-cols-5 gap-2 md:gap-3">
                    @foreach([1, 2, 3, 4, 5] as $value)
                        <label class="relative cursor-pointer">
                            <input type="radio" name="answers[{{ $question->id }}]" value="{{ $value }}" required class="hidden peer">
                            <div class="flex flex-col items-center justify-center py-3.5 md:py-3 rounded-xl font-bold text-base bg-white border-2 border-slate-100 text-slate-400 transition-all duration-200 
                                peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 peer-checked:shadow-lg peer-checked:shadow-blue-200
                                active:scale-95">
                                {{ $value }}
                            </div>
                        </label>
                    @endforeach
                </div>
                
                <div class="flex justify-between mt-2 px-1 text-[10px] text-slate-400 font-medium uppercase tracking-wider">
                    <span>Kurang</span>
                    <span>Sangat Baik</span>
                </div>
            </div>
        @empty
            <div class="text-center py-10">
                <p class="text-sm text-slate-400 italic">Pertanyaan belum tersedia.</p>
            </div>
        @endforelse
    </div>
</div>

                <div class="p-10 step-content text-center" id="step4">
                    <div class="py-12 flex flex-col items-center">
                        <div class="w-20 h-20 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mb-6 border-2 border-emerald-100">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h2 class="text-2xl font-black text-slate-900">Data Sudah Lengkap?</h2>
                        <p class="text-slate-500 mt-3 max-w-sm leading-relaxed text-sm">Respon Anda bersifat permanen dan akan digunakan untuk perbaikan layanan kami. Klik tombol kirim di bawah.</p>
                    </div>
                </div>

                <div class="px-10 py-8 bg-slate-50 border-t border-slate-100 flex justify-between items-center">
                    <button type="button" id="prevBtn" onclick="nextPrev(-1)"
                            class="invisible text-slate-400 font-black text-xs uppercase tracking-widest hover:text-slate-900 transition-all">
                        ← Kembali
                    </button>
                    
                    <div class="flex gap-4">
                        <button type="button" id="nextBtn" onclick="nextPrev(1)"
                                class="bg-blue-600 text-white px-10 py-4 rounded-2xl font-black text-sm shadow-xl shadow-blue-200 hover:bg-blue-700 hover:-translate-y-0.5 transition-all outline-none">
                            Langkah Selanjutnya
                        </button>
                        <button type="submit" id="submitBtn" class="hidden bg-emerald-600 text-white px-12 py-4 rounded-2xl font-black text-sm shadow-xl shadow-emerald-200 hover:bg-emerald-700 hover:-translate-y-0.5 transition-all outline-none">
                            Kirim Jawaban
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Logic Stepper & Validasi
let currentStep = 0;
const steps = document.getElementsByClassName("step-content");

function showStep(n) {
    steps[n].classList.add("active");
    document.getElementById("prevBtn").style.visibility = (n === 0) ? "hidden" : "visible";
    
    if (n === (steps.length - 1)) {
        document.getElementById("nextBtn").style.display = "none";
        document.getElementById("submitBtn").style.display = "inline-flex";
    } else {
        document.getElementById("nextBtn").style.display = "inline-flex";
        document.getElementById("submitBtn").style.display = "none";
    }

    const progress = ((n + 1) / steps.length) * 100;
    document.getElementById("progressBar").style.width = progress + "%";
    document.getElementById("stepBadge").innerText = `Langkah ${n + 1} dari ${steps.length}`;
    document.getElementById("percentText").innerText = `${Math.round(progress)}%`;
}

function nextPrev(n) {
    if (n === 1 && !validateForm()) return false;
    steps[currentStep].classList.remove("active");
    currentStep += n;
    showStep(currentStep);
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function validateForm() {
    let valid = true;
    const container = steps[currentStep];
    
    container.querySelectorAll("input[required], select[required]").forEach(input => {
        if (!input.value) {
            input.classList.add("border-red-500", "bg-red-50");
            valid = false;
        } else {
            input.classList.remove("border-red-500", "bg-red-50");
        }
    });

    if (currentStep === 2) {
        container.querySelectorAll(".question-block").forEach(block => {
            if (!block.querySelector("input[type='radio']:checked")) {
                block.classList.add("error-highlight");
                valid = false;
            } else {
                block.classList.remove("error-highlight");
            }
        });
    }

    if (!valid) {
        Swal.fire({
            icon: 'warning',
            title: '<span class="text-xl font-black uppercase tracking-tight text-slate-900">Informasi Belum Lengkap</span>',
            html: '<p class="text-sm text-slate-500">Beberapa kolom wajib masih kosong. Mohon lengkapi data Anda sebelum melanjutkan.</p>',
            confirmButtonText: 'SAYA MENGERTI',
            confirmButtonColor: '#2563eb',
            background: '#ffffff',
            padding: '2rem',
            borderRadius: '1.5rem',
            customClass: { confirmButton: 'px-10 py-3 rounded-xl font-bold text-xs tracking-widest' }
        });
    }
    return valid;
}

// Data Irban Info
const irbanData = {
    irban1: { title: "Irban I: Perencanaan & Tata Kelola", focus: "Mengawasi administrasi dan rencana kerja.", lingkup: "Dokumen perencanaan (Renja, RKA).", tugas: "Memastikan kepatuhan aturan." },
    irban2: { title: "Irban II: Keuangan & Pembangunan", focus: "Mengawasi penggunaan uang negara.", lingkup: "Dinas, Dana BOS, dan Proyek Fisik.", tugas: "Memastikan efisiensi anggaran." },
    irban3: { title: "Irban III: Pemerintahan Desa", focus: "Mengawasi tata kelola desa.", lingkup: "Seluruh Desa se-Kabupaten Rembang.", tugas: "Membimbing penggunaan Dana Desa." },
    irban4: { title: "Irban IV: Reformasi Birokrasi", focus: "Mengawasi pelayanan publik.", lingkup: "Zona Integritas & Anti Korupsi.", tugas: "Mendorong sistem kerja bersih." },
    irbansus: { title: "Irban Khusus: Investigasi", focus: "Menangani laporan pengaduan.", lingkup: "Kasus pelanggaran & Whistleblowing.", tugas: "Mencari bukti penyimpangan." }
};

function updateIrbanInfo() {
    const val = document.getElementById('irbanSelect').value;
    const box = document.getElementById('irbanInfoBox');
    if (val && irbanData[val]) {
        document.getElementById('irbanTitle').innerText = irbanData[val].title;
        document.getElementById('irbanContent').innerHTML = `
            <p>🎯 <strong>Fokus:</strong> ${irbanData[val].focus}</p>
            <p>🏢 <strong>Lingkup:</strong> ${irbanData[val].lingkup}</p>
            <p>✅ <strong>Tugas:</strong> ${irbanData[val].tugas}</p>
        `;
        box.classList.remove('hidden');
    } else { box.classList.add('hidden'); }
}

window.onload = () => {
    @if ($errors->any())
        Swal.fire({ icon: 'error', title: 'Oops...', text: '{{ $errors->first() }}', confirmButtonColor: '#2563eb' });
    @endif
};

showStep(currentStep);
</script>
@endpush
@endsection