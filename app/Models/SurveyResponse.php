<?php

namespace App\Models;

use App\Models\Question;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class SurveyResponse extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'email',
        'opd',
        'irban',
        'jenis_layanan',
        'tanggal',
        'lokasi_survey',
        'suggestions',
        'total_score',
        'ikm_score',
        'category',
    ];

    protected $casts = [
        'tanggal'     => 'date',
        'total_score' => 'integer',
        'ikm_score'   => 'decimal:2',
    ];

    /* ======================================================
     | GLOBAL SCOPE (DATA SECURITY)
     ====================================================== */
    protected static function booted(): void
    {
        static::addGlobalScope('irban_scope', function (Builder $builder) {

            if (app()->runningInConsole() || !Auth::check()) {
                return;
            }

            $user = Auth::user();

            if ($user->role !== 'admin') {
                $builder->where('irban', $user->irban);
            }
        });
    }

    /* ======================================================
     | RELATIONS
     ====================================================== */
    public function answers(): HasMany
    {
        return $this->hasMany(SurveyAnswer::class);
    }

    /* ======================================================
     | EXTERNAL SURVEY AUTO RULE
     ====================================================== */
    public function applyExternalSurveyRule(): void
    {
        if ($this->lokasi_survey !== 'Luar Inspektorat') {
            return;
        }

        $excludedDimension = 'Dukungan';

        $questionIds = Question::active()
            ->where('dimension', $excludedDimension)
            ->pluck('id');

        foreach ($questionIds as $questionId) {
            $this->answers()->updateOrCreate(
                ['question_id' => $questionId],
                ['answer' => 5]
            );
        }

        $this->calculateIkms();
    }

    /* ======================================================
     | IKM CALCULATION
     ====================================================== */
    public function calculateIkms(): void
    {
        $answers = $this->answers()
            ->with('question:id,dimension')
            ->get()
            ->filter(fn ($a) => $a->question?->dimension !== null);

        if ($answers->isEmpty()) {
            $this->updateQuietly([
                'total_score' => 0,
                'ikm_score'   => 0,
                'category'    => $this->resolveCategory(0),
            ]);
            return;
        }

        $weights = $this->dimensionWeights();
        $grouped = $answers->groupBy(fn ($a) => $a->question->dimension);

        $weightedScore   = 0;
        $totalWeightUsed = 0;

        foreach ($weights as $dimension => $weight) {

            if (!$grouped->has($dimension)) {
                continue;
            }

            $avg = $grouped[$dimension]->avg('answer');

            $weightedScore   += ($avg * 20) * $weight;
            $totalWeightUsed += $weight;
        }

        $finalIkm = $totalWeightUsed > 0
            ? $weightedScore / $totalWeightUsed
            : 0;

        $this->updateQuietly([
            'total_score' => $answers->sum('answer'),
            'ikm_score'   => round($finalIkm, 2),
            'category'    => $this->resolveCategory($finalIkm),
        ]);
    }

    /* ======================================================
     | CONFIGURATION
     ====================================================== */
    protected function dimensionWeights(): array
    {
        return [
            'Materi'   => 0.30,
            'Standar'  => 0.25,
            'SDM'      => 0.30,
            'Dukungan' => 0.15,
        ];
    }

    protected function resolveCategory(float $ikm): string
    {
        return match (true) {
            $ikm >= 88.31 => 'Sangat Baik (A)',
            $ikm >= 76.61 => 'Baik (B)',
            $ikm >= 65.01 => 'Kurang Baik (C)',
            default       => 'Tidak Baik (D)',
        };
    }
}