<?php

namespace App\Http\Controllers;

use App\Models\{Question, SurveyResponse, SurveyAnswer};
use Illuminate\Http\{Request, RedirectResponse};
use Illuminate\Support\Facades\{DB, Log};

class SurveyController extends Controller
{
    public function create()
    {
        $questions = Question::active()->get();

        return view('survey', compact('questions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $excludedDimension = 'Dukungan'; // GANTI sesuai kebutuhan

        // Ambil semua pertanyaan aktif
        $allActiveQuestions = Question::active()->get();

        // Ambil pertanyaan yang wajib dijawab user
        $requiredQuestions = $request->lokasi_survey === 'Luar Inspektorat'
            ? $allActiveQuestions->where('dimension', '!=', $excludedDimension)
            : $allActiveQuestions;

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

        try {
            DB::transaction(function () use (
                $validated,
                $requiredQuestions,
                $allActiveQuestions,
                $excludedDimension
            ) {

                // 1️⃣ Simpan Header
                $response = SurveyResponse::create(
                    collect($validated)->except('answers')->toArray()
                );

                $answerData = [];

                foreach ($allActiveQuestions as $question) {

                    // Jika lokasi luar dan termasuk dimensi yang di-skip → auto 5
                    if (
                        $validated['lokasi_survey'] === 'Luar Inspektorat'
                        && $question->dimension === $excludedDimension
                    ) {
                        $answerValue = 5;
                    } else {
                        // Pastikan pertanyaan wajib benar-benar diisi
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

                // 2️⃣ Insert Sekali (Lebih Cepat)
                SurveyAnswer::insert($answerData);

                // 3️⃣ Hitung IKM Sekali
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