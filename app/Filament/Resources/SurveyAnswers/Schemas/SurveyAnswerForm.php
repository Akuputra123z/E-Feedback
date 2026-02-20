<?php

namespace App\Filament\Resources\SurveyAnswers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SurveyAnswerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Select::make('survey_response_id')
                    ->label('Survey')
                    ->relationship(
                        name: 'surveyResponse',
                        titleAttribute: 'email' // ganti ke field yang lebih informatif jika perlu
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('question_id')
                    ->label('Pertanyaan')
                    ->relationship(
                        name: 'question',
                        titleAttribute: 'text'
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('answer')
                    ->label('Jawaban (1-5)')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(5)
                    ->required(),
            ]);
    }
}
