<?php

namespace App\Http\Controllers;

use App\Models\{Question, SurveyResponse, SurveyAnswer};
use Illuminate\Http\{Request, RedirectResponse};
use Illuminate\Support\Facades\{DB, Log};

class SurveyController extends Controller
{

    public function results()
{
    // Mengambil rata-rata nilai dari semua jawaban yang masuk
    $totalResponses = SurveyResponse::count();
    $averageScore = SurveyAnswer::avg('answer') ?? 0;
    
    // Opsional: Ambil rata-rata per dimensi jika diperlukan
    $dimensionStats = SurveyAnswer::join('questions', 'questions.id', '=', 'survey_answers.question_id')
        ->select('questions.dimension', \DB::raw('avg(answer) as avg_score'))
        ->groupBy('questions.dimension')
        ->get();

    return view('survey-results', compact('totalResponses', 'averageScore', 'dimensionStats'));
}
   public function create()
{
    // 1. Ambil data dan grupkan berdasarkan dimensi
    $questions = Question::active()
        ->orderBy('dimension')
        ->orderBy('id')
        ->get()
        ->groupBy('dimension');

    // 2. LOGIKA SORTING: Taruh di sini agar "Dukungan" selalu di akhir
    $questions = $questions->sortBy(function($items, $key) {
        // Jika kuncinya 'Dukungan', beri angka tinggi (100) agar pindah ke paling bawah
        return $key === 'Dukungan' ? 100 : 0;
    });

    $dimensionLabels = [
        'Materi'   => 'Materi Konsultasi',
        'Standar'  => 'Standar Pelayanan',
        'SDM'      => 'Profesionalisme SDM',
        'Dukungan' => 'Dukungan & Pengelolaan',
    ];

    $roman = [
        1=>'I', 2=>'II', 3=>'III', 4=>'IV',
        5=>'V', 6=>'VI', 7=>'VII'
    ];

    return view('survey', compact(
        'questions',
        'dimensionLabels',
        'roman'
    ));
}

    public function store(Request $request): RedirectResponse
{
    $excludedDimension = 'Dukungan'; // GANTI sesuai kebutuhan

    $allActiveQuestions = Question::active()->get();

    $requiredQuestions = $request->lokasi_survey === 'Luar Inspektorat'
        ? $allActiveQuestions->where('dimension', '!=', $excludedDimension)
        : $allActiveQuestions;

    // 1️⃣ Validasi request
    $validated = $request->validate([
        'email'         => 'required|email:rfc,dns|max:255',
        'opd'           => 'required|string|max:255',
        'lokasi_survey' => 'required|in:Dalam Inspektorat,Luar Inspektorat',
        'irban'         => 'required|in:irban1,irban2,irban3,irban4,irbansus,sekretariat',
        'jenis_layanan' => 'required|string|max:255',
        'tanggal'       => 'required|date|before_or_equal:today',
        'suggestions'   => 'nullable|string|max:1000',
        'answers'       => 'required|array',
        'answers.*'     => 'nullable|integer|min:1|max:5',
    ]);

    // 2️⃣ Sanitasi textarea suggestions
    $validated['suggestions'] = strip_tags($validated['suggestions'] ?? '');

    try {
        DB::transaction(function () use (
            $validated,
            $requiredQuestions,
            $allActiveQuestions,
            $excludedDimension
        ) {
            // Simpan Header
            $response = SurveyResponse::create(
                collect($validated)->except('answers')->toArray()
            );

            $answerData = [];

            foreach ($allActiveQuestions as $question) {
                if (
                    $validated['lokasi_survey'] === 'Luar Inspektorat'
                    && $question->dimension === $excludedDimension
                ) {
                    $answerValue = 5;
                } else {
                    if (!isset($validated['answers'][$question->id])) {
                        throw new \Exception('Semua pertanyaan wajib diisi.');
                    }

                    $answerValue = (int) $validated['answers'][$question->id];
                }

                $answerData[] = [
                    'survey_response_id' => $response->id,
                    'question_id'        => $question->id,
                    'answer'             => $answerValue,
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ];
            }

            SurveyAnswer::insert($answerData);

            $response->calculateIkms();
        });

        return redirect()
            ->route('survey.create')
            ->with('success', 'Survei berhasil dikirim! Terima kasih atas partisipasi Anda.');

    } catch (\Throwable $e) {
        Log::error('Survey Error: ' . $e->getMessage());

        return back()
            ->withErrors('Terjadi kesalahan saat menyimpan data.')
            ->withInput();
    }
}
}