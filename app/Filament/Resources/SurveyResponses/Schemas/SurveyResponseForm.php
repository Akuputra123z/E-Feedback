<?php

namespace App\Filament\Resources\SurveyResponses\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SurveyResponseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
              
            ]);
    }
}
