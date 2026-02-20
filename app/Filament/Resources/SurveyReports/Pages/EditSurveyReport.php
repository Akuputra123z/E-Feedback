<?php

namespace App\Filament\Resources\SurveyReports\Pages;

use App\Filament\Resources\SurveyReports\SurveyReportResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditSurveyReport extends EditRecord
{
    protected static string $resource = SurveyReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
