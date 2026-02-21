<?php

namespace App\Models;

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
        'total_score',
        'ikm_score',
        'category',
    ];

    protected $casts = [
        'tanggal'     => 'date',
        'total_score' => 'integer',
        'ikm_score'   => 'decimal:2',
    ];

    /**
     * 🔒 Global Scope: Keamanan Data Otomatis
     * Irban hanya bisa melihat data miliknya, Admin bisa melihat semua.
     */
   protected static function booted(): void
{
    static::addGlobalScope('irban_scope', function (Builder $builder) {
        // Hanya jalankan filter jika diakses melalui Request Web/Filament (bukan Console/Terminal)
        if (!app()->runningInConsole() && Auth::check()) {
            $user = Auth::user();
            if ($user->role !== 'admin' && !empty($user->irban)) {
                $builder->where('irban', $user->irban);
            }
        }
    });
}

    /**
     * Relasi ke jawaban survey
     */
    public function answers(): HasMany
    {
        return $this->hasMany(SurveyAnswer::class);
    }

    /**
     * 🔥 Hitung IKM berdasarkan bobot dimensi secara otomatis
     */
    public function calculateIkms(): void
    {
        // Gunakan withAggregate atau Eager Loading untuk performa
        $this->loadMissing('answers.question');

        $answers = $this->getValidAnswers();

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
        
        $weightedScore = 0;

        foreach ($weights as $dimension => $weight) {
            if (isset($grouped[$dimension])) {
                $avg = $grouped[$dimension]->avg('answer'); 
                $weightedScore += ($avg * 20) * $weight; // Normalisasi ke 100
            }
        }

        $this->updateQuietly([
            'total_score' => $answers->sum('answer'),
            'ikm_score'   => round($weightedScore, 2),
            'category'    => $this->resolveCategory($weightedScore),
        ]);
    }

    protected function getValidAnswers(): Collection
    {
        return $this->answers->filter(fn ($a) => optional($a->question)->dimension !== null);
    }

    /**
     * Konfigurasi Bobot IKM
     */
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