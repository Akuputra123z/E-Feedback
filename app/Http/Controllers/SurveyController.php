<?php

namespace App\Http\Controllers;

use App\Models\{Question, SurveyResponse, SurveyAnswer};
use Illuminate\Http\{Request, RedirectResponse};
use Illuminate\Support\Facades\{DB, Log};
use Illuminate\View\View;

class SurveyController extends Controller
{
    /**
     * Tampilkan form survey dengan pertanyaan aktif.
     */
    public function create(): View
    {
        return view('survey', [
            'questions' => Question::active()->orderBy('id')->get()
        ]);
    }

    /**
     * Simpan respon survey ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $questionIds = Question::active()->pluck('id');
        
        // Validasi Langsung (atau gunakan php artisan make:request SurveyRequest)
        $validated = $request->validate([
            'email'         => 'required|email|max:255|unique:survey_responses,email',
            'opd'           => 'required|string|max:255',
            'irban'         => 'required|in:irban1,irban2,irban3,irban4,irbansus',
            'jenis_layanan' => 'required|string|max:255',
            'tanggal'       => 'required|date|before_or_equal:today',
            'answers'       => ['required', 'array', 'size:' . $questionIds->count()],
            'answers.*'     => 'required|integer|min:1|max:5',
        ], [
            'email.unique'  => 'Email ini sudah pernah digunakan untuk mengisi survei sebelumnya.',
            'answers.size'  => 'Mohon lengkapi semua jawaban pertanyaan.',
        ]);

        try {
            DB::transaction(function () use ($validated, $questionIds) {
                // 1. Create Header
                $response = SurveyResponse::create(
                    collect($validated)->except('answers')->toArray()
                );

                // 2. Prepare Bulk Insert Jawaban
                $answersData = $questionIds->map(fn ($id) => [
                    'survey_response_id' => $response->id,
                    'question_id'        => $id,
                    'answer'             => (int) $validated['answers'][$id],
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ])->all();

                SurveyAnswer::insert($answersData);

                // 3. Hitung IKM (Method di Model SurveyResponse)
                $response->calculateIkms();
            });

            return back()->with('success', 'Survei berhasil dikirim! Terima kasih atas partisipasi Anda.');

        } catch (\Exception $e) {
            Log::error("Survey Store Error: {$e->getMessage()}", ['user' => $validated['email'] ?? 'unknown']);
            
            return back()
                ->withErrors('Gagal menyimpan data karena kendala teknis. Silakan coba lagi.')
                ->withInput();
        }
    }
}