<?php

namespace App\Filament\Resources\SurveyAnswers\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SurveyAnswerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('surveyResponse.id')
                    ->label('Survey response'),
                TextEntry::make('question.id')
                    ->label('Question'),
                TextEntry::make('answer')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
