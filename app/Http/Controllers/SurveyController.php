<?php

namespace App\Http\Controllers;

use App\Models\{Question, SurveyResponse, SurveyAnswer};
use Illuminate\Http\{Request, RedirectResponse};
use Illuminate\Support\Facades\{DB, Log};

class SurveyController extends Controller
{
    public function create()
    {
        $questions = Question::active()->orderBy('dimension')->orderBy('sort_order')->get();
        return view('survey', compact('questions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $activeQuestions = Question::active()->pluck('id');

        $validated = $request->validate([
            'email'         => 'required|email|max:255',
            'opd'           => 'required|string|max:255',
            'irban'         => 'required|in:irban1,irban2,irban3,irban4,irbansus',
            'jenis_layanan' => 'required|string|max:255',
            'tanggal'       => 'required|date|before_or_equal:today',
            'answers'       => 'required|array|size:' . $activeQuestions->count(),
            'answers.*'     => 'required|integer|min:1|max:5',
        ]);

        try {
            DB::transaction(function () use ($validated, $activeQuestions) {
                // 1. Simpan Header
                $response = SurveyResponse::create(collect($validated)->except('answers')->toArray());

                // 2. Simpan Jawaban (Bulk Insert)
                $answers = $activeQuestions->map(fn($id) => [
                    'survey_response_id' => $response->id,
                    'question_id'        => $id,
                    'answer'             => (int) $validated['answers'][$id],
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ])->toArray();

                SurveyAnswer::insert($answers);

                // 3. Panggil perhitungan secara eksplisit agar lebih terkontrol
                $response->calculateIkms();
            });

            return redirect()->route('survey.create')
                ->with('success', 'Survei berhasil dikirim! Terima kasih atas partisipasi Anda.');

        } catch (\Exception $e) {
            Log::error("Survey Error: " . $e->getMessage());
            return back()->withErrors('Terjadi kesalahan teknis.')->withInput();
        }
    }
}