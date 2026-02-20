<?php

namespace App\Filament\Resources\SurveyAnswers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SurveyAnswersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('surveyResponse.email')
                    ->label('Email Responden')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('question.text')
                    ->label('Pertanyaan')
                    ->limit(50)
                    ->wrap()
                    ->searchable(),

                TextColumn::make('answer')
                    ->label('Nilai')
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state >= 4 => 'success',
                        $state == 3 => 'warning',
                        default => 'danger',
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Tanggal Jawab')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                //
            ])

            ->recordActions([
                ViewAction::make(),
                // EditAction::make(),
            ])

            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ])

            ->defaultSort('created_at', 'desc');
    }
}
