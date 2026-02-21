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
                    radial-gradient(circle at bottom left, rgba(56, 189, 248, 0.03) 0%, transparent 50%);
    }
    .step-content { display: none; opacity: 0; }
    .step-content.active { display: block; animation: fadeInUp 0.5s ease-out forwards; }
    
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Standard CSS for Checked State (Fixed Click Issue) */
    .rating-input:checked + .rating-box { 
        background-color: var(--primary-blue) !important;
        color: white !important;
        border-color: var(--primary-blue) !important;
        box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.2);
        transform: scale(1.05);
    }

    .error-highlight {
        border-left: 4px solid var(--error-red) !important;
        background-color: #fef2f2 !important;
    }

    .rating-box { transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
</style>
@endpush

@section('content')
<div class="survey-gradient min-h-screen py-12 px-4">
    <div class="max-w-4xl mx-auto">
        
        <header class="text-center mb-12">
            <span class="px-4 py-1.5 bg-slate-100 text-slate-600 text-[10px] font-bold tracking-[0.2em] uppercase rounded-full border border-slate-200">
                Sistem Informasi Survei
            </span>
            <h1 class="text-3xl font-extrabold text-slate-900 mt-4 tracking-tight">
                Formulir Survei Kepuasan Layanan
            </h1>
        </header>

        <main class="bg-white shadow-2xl shadow-slate-200/60 rounded-[2rem] overflow-hidden border border-slate-100">
            {{-- Progress Indicator --}}
            <div class="px-10 pt-8">
                <div class="flex justify-between mb-3">
                    <span id="stepBadge" class="text-[10px] font-black uppercase tracking-widest text-blue-600"></span>
                    <span id="percentText" class="text-[10px] font-black text-slate-400"></span>
                </div>
                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                    <div id="progressBar" class="bg-blue-600 h-full transition-all duration-700 ease-in-out" style="width: 0%"></div>
                </div>
            </div>

            <form method="POST" action="{{ route('survey.store') }}" id="surveyForm" autocomplete="off">
                @csrf

                {{-- STEP 1: IDENTITAS --}}
                <section class="p-10 step-content active">
                    <div class="mb-10 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl mb-4 border border-blue-100">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900">Identitas Responden</h3>
                        <p class="text-sm text-slate-500 mt-1" >Gunakan alamat email resmi untuk validasi.</p>

                    </div>

                    <div class="space-y-8">
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-3">Alamat Email Resmi</label>
                            <input type="email" name="email" value="{{ old('email') }}" required 
                                   placeholder="nama@gmail.com"
                                   class="w-full rounded-2xl px-6 py-5 bg-slate-50 border-2 border-slate-100 focus:bg-white focus:border-blue-600 transition-all outline-none font-medium">
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-3">Nama Instansi / OPD</label>
                            <input type="text" name="opd" value="{{ old('opd') }}" required
                                   placeholder="Masukan Nama Lengkap OPD Anda"
                                   class="w-full rounded-2xl px-6 py-5 bg-slate-50 border-2 border-slate-100 focus:bg-white focus:border-blue-600 transition-all outline-none font-medium">
                        </div>
                    </div>
                </section>

                {{-- STEP 2: DETAIL LAYANAN --}}
                <section class="p-10 step-content">
                    <div class="mb-8 border-l-4 border-blue-600 pl-4">
                        <h3 class="text-xl font-bold text-slate-900">Detail Layanan</h3>
                    </div>
                    <div class="grid gap-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-500 uppercase tracking-wider">Wilayah Irban</label>
                                <select name="irban" required class="w-full rounded-xl border-slate-200 px-5 py-4 outline-none focus:border-blue-600 transition-all bg-white">
                                    <option value="">Pilih Wilayah...</option>
                                    @foreach(['irban1' => 'Irban I', 'irban2' => 'Irban II', 'irban3' => 'Irban III', 'irban4' => 'Irban IV', 'irbansus' => 'Irban Khusus'] as $val => $label)
                                        <option value="{{ $val }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-500 uppercase tracking-wider">Tanggal</label>
                                <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" required class="w-full rounded-xl border-slate-200 px-5 py-4 outline-none bg-white">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-500 uppercase tracking-wider">Jenis Layanan</label>
                            <select name="jenis_layanan" required class="w-full rounded-xl border-slate-200 px-5 py-4 outline-none focus:border-blue-600 transition-all bg-white">
                                <option value="">Pilih Kategori...</option>
                                @foreach(['Konsultasi Tata Kelola', 'Pendampingan Reviu', 'Asistensi Teknis', 'Audit Investigatif'] as $layanan)
                                    <option value="{{ $layanan }}">{{ $layanan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </section>

                {{-- DYNAMIC QUESTIONS --}}
                @php
                    $dimensionLabels = [
                        'Materi'   => 'Materi Konsultasi',
                        'Standar'  => 'Standar Pelayanan',
                        'SDM'      => 'Profesionalisme SDM',
                        'Dukungan' => 'Dukungan & Pengelolaan',
                    ];
                    $globalIndex = 1;
                @endphp

                @foreach($questions->groupBy('dimension') as $dimension => $items)
                <section class="p-8 step-content">
                    <div class="mb-8 border-l-4 border-blue-600 pl-4">
                        <h3 class="text-xl font-bold text-slate-900">{{ $dimensionLabels[$dimension] ?? $dimension }}</h3>
                    </div>
                    <div class="space-y-12">
                        @foreach($items as $question)
                        <div class="question-block group p-4 rounded-2xl transition-all">
                            <div class="flex items-start gap-4 mb-5">
                                <span class="flex-none w-7 h-7 bg-blue-50 text-blue-600 text-xs flex items-center justify-center rounded-lg font-bold border border-blue-100">{{ $globalIndex++ }}</span>
                                <p class="font-medium text-slate-800 text-[15px] leading-relaxed">{{ $question->text }}</p>
                            </div>
                            <div class="grid grid-cols-5 gap-4">
                                @foreach(range(1, 5) as $value)
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $value }}" required class="hidden rating-input">
                                    <div class="rating-box flex items-center justify-center py-4 rounded-2xl font-bold text-lg bg-white border-2 border-slate-100 text-slate-400 hover:border-blue-200">
                                        {{ $value }}
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>
                @endforeach

                {{-- FINAL STEP --}}
                <section class="p-10 step-content text-center">
                    <div class="py-12 flex flex-col items-center">
                        <div class="w-20 h-20 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mb-6 border-2 border-emerald-100">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h2 class="text-2xl font-black text-slate-900">Data Sudah Benar?</h2>
                        <p class="text-slate-500 mt-3 max-w-sm text-sm">Terima kasih telah berpartisipasi. Klik tombol di bawah untuk mengirimkan penilaian Anda.</p>
                    </div>
                </section>

                <footer class="px-10 py-8 bg-slate-50 border-t border-slate-100 flex justify-between items-center">
                    <button type="button" id="prevBtn" class="invisible text-slate-400 font-black text-xs uppercase tracking-[0.2em] hover:text-slate-900 transition-all">
                        ← Kembali
                    </button>
                    <div class="flex gap-4">
                        <button type="button" id="nextBtn" class="bg-blue-600 text-white px-10 py-4 rounded-2xl font-black text-sm shadow-xl hover:bg-blue-700 transition-all">
                            Lanjutkan
                        </button>
                        <button type="submit" id="submitBtn" class="hidden bg-emerald-600 text-white px-12 py-4 rounded-2xl font-black text-sm shadow-xl hover:bg-emerald-700 transition-all">
                            Kirim Jawaban
                        </button>
                    </div>
                </footer>
            </form>
        </main>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('surveyForm');
        const steps = Array.from(document.querySelectorAll('.step-content'));
        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');
        const submitBtn = document.getElementById('submitBtn');
        const progressBar = document.getElementById('progressBar');
        const stepBadge = document.getElementById('stepBadge');
        const percentText = document.getElementById('percentText');

        let currentStep = 0;

        function updateUI() {
            steps.forEach((step, idx) => {
                step.classList.toggle('active', idx === currentStep);
            });

            prevBtn.style.visibility = currentStep === 0 ? 'hidden' : 'visible';
            
            if (currentStep === steps.length - 1) {
                nextBtn.classList.add('hidden');
                submitBtn.classList.remove('hidden');
            } else {
                nextBtn.classList.remove('hidden');
                submitBtn.classList.add('hidden');
            }

            const progress = ((currentStep + 1) / steps.length) * 100;
            progressBar.style.width = `${progress}%`;
            stepBadge.textContent = `Langkah ${currentStep + 1} dari ${steps.length}`;
            percentText.textContent = `${Math.round(progress)}%`;
        }

        function validateStep() {
            const currentEl = steps[currentStep];
            let isValid = true;

            // Validate Text/Selects
            const inputs = currentEl.querySelectorAll('input[required]:not([type="radio"]), select[required]');
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('border-red-500');
                    isValid = false;
                } else {
                    input.classList.remove('border-red-500');
                }
            });

            // Validate Radio Buttons
            const blocks = currentEl.querySelectorAll('.question-block');
            blocks.forEach(block => {
                const checked = block.querySelector('input[type="radio"]:checked');
                if (!checked) {
                    block.classList.add('error-highlight');
                    isValid = false;
                } else {
                    block.classList.remove('error-highlight');
                }
            });

            if (!isValid) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Belum Lengkap',
                    text: 'Mohon isi semua bagian yang diperlukan di halaman ini.',
                    confirmButtonColor: '#2563eb'
                });
            }
            return isValid;
        }

        nextBtn.addEventListener('click', () => {
            if (validateStep()) {
                currentStep++;
                updateUI();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });

        prevBtn.addEventListener('click', () => {
            if (currentStep > 0) {
                currentStep--;
                updateUI();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });

        // SUCCESS HANDLER (Ucapan Terima Kasih & Isi Kembali)
        @if(session('success'))
            Swal.fire({
                title: 'Terima Kasih!',
                text: "{{ session('success') }}",
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Isi Kembali',
                cancelButtonText: 'Selesai',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('survey.create') }}";
                }
            });
        @endif

        form.addEventListener('submit', () => {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="animate-pulse">Memproses...</span>';
        });

        updateUI();
    });
</script>
@endpush
@endsection