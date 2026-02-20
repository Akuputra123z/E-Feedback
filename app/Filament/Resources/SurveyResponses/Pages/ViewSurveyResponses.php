<?php

namespace App\Filament\Resources\SurveyResponses\Pages;

use App\Filament\Resources\SurveyResponses\SurveyResponseResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSurveyResponses extends ViewRecord
{
    protected static string $resource = SurveyResponseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // EditAction::make(),
        ];
    }
}
