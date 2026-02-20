<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyAnswer extends Model
{
    protected $fillable = [
        'survey_response_id',
        'question_id',
        'answer',
    ];

    protected $casts = [
        'answer' => 'integer',
    ];

    /**
     * Event model untuk auto recalculation IKM
     */
    protected static function booted(): void
    {
        static::saved(function (SurveyAnswer $answer) {
            $answer->recalculateSurvey();
        });

        static::deleted(function (SurveyAnswer $answer) {
            $answer->recalculateSurvey();
        });
    }

    /**
     * Hitung ulang IKM pada header survey terkait
     */
    protected function recalculateSurvey(): void
    {
        if (!$this->survey_response_id) {
            return;
        }

        $survey = $this->surveyResponse()->first();

        if ($survey) {
            $survey->calculateIkms();
        }
    }

    /**
     * Relasi ke header survey
     */
    public function surveyResponse(): BelongsTo
    {
        return $this->belongsTo(SurveyResponse::class);
    }

    /**
     * Relasi ke master pertanyaan
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
