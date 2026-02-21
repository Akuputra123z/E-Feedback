<?php

namespace App\Observers;

use App\Models\SurveyResponse;

class SurveyResponseObserver
{
    /**
     * Handle the SurveyResponse "created" event.
     */
    public function created(SurveyResponse $surveyResponse): void
    {
        //
    }
    public function saved(SurveyResponse $surveyResponse): void
    {
        // Untuk menghindari infinite loop saat update score, 
        // kita pastikan hanya berjalan jika answers sudah ada.
        if ($surveyResponse->answers()->exists() && $surveyResponse->ikm_score == 0) {
            $surveyResponse->calculateIkms();
        }
    }

    /**
     * Handle the SurveyResponse "updated" event.
     */
    public function updated(SurveyResponse $surveyResponse): void
    {
        //
    }

    /**
     * Handle the SurveyResponse "deleted" event.
     */
    public function deleted(SurveyResponse $surveyResponse): void
    {
        //
    }

    /**
     * Handle the SurveyResponse "restored" event.
     */
    public function restored(SurveyResponse $surveyResponse): void
    {
        //
    }

    /**
     * Handle the SurveyResponse "force deleted" event.
     */
    public function forceDeleted(SurveyResponse $surveyResponse): void
    {
        //
    }
}
