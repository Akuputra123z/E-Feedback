<?php

namespace App\Filament\Resources\SurveyAnswers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;
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
                TextColumn::make('question.dimension')
                    ->label('Dimensi')
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state == 'Materi' => 'info',
                        $state == 'Standar' => 'warning',
                        $state == 'SDM' => 'success',
                        $state == 'Dukungan' => 'danger',
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
              SelectFilter::make('dimension')
    ->label('Dimensi Pertanyaan')
    ->options([
        'Materi'   => '📚 Materi Konsultasi',
        'Standar'  => '📋 Standar Pelayanan',
        'SDM'      => '👨‍💼 Profesionalisme SDM',
        'Dukungan' => '🛠️ Dukungan & Pengelolaan',
    ])
    ->query(function (Builder $query, array $data): Builder {
        if (empty($data['value'])) {
            return $query;
        }

        // Filter tabel jawaban berdasarkan dimensi yang ada di tabel pertanyaan
        return $query->whereHas('question', function (Builder $query) use ($data) {
            $query->where('dimension', $data['value']);
        });
    })
    ->native(false),

                // 2. Filter berdasarkan Skor Jawaban (1-5)
                SelectFilter::make('answer')
                    ->label('Skor Jawaban')
                    ->options([
                        '5' => '5 (Sangat Baik)',
                        '4' => '4 (Baik)',
                        '3' => '3 (Cukup)',
                        '2' => '2 (Kurang)',
                        '1' => '1 (Sangat Kurang)',
                    ]),
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
