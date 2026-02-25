<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
// Pastikan semua model di-import jika berbeda namespace
use App\Models\SurveyResponse; 
use App\Models\Question;

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

   

    public function recalculateSurvey(): void
    {
        // Gunakan withoutGlobalScopes agar kalkulasi tetap jalan 
        // meskipun dipicu oleh user yang berbeda Irban (misal: Admin)
        $survey = SurveyResponse::withoutGlobalScopes()->find($this->survey_response_id);

        if ($survey && method_exists($survey, 'calculateIkms')) {
            $survey->calculateIkms();
        }
    }

    public function surveyResponse(): BelongsTo
    {
        return $this->belongsTo(SurveyResponse::class, 'survey_response_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}