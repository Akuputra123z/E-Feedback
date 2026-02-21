<?php

namespace App\Filament\Resources\SurveyResponses\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ViewAction;

class SurveyResponsesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([

                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('opd')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('irban')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('jenis_layanan')
                    ->label('Jenis Layanan')
                    ->searchable(),

                TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),

                TextColumn::make('total_score')
                    ->label('Total')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('ikm_score')
                    ->label('Nilai IKM')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),

                TextColumn::make('category')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'Sangat Baik' => 'success',
                        'Baik' => 'primary',
                        'Cukup' => 'warning',
                        'Kurang' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Waktu Submit')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                SelectFilter::make('irban')
                    ->options([
                        'irban1' => 'Irban 1',
                        'irban2' => 'Irban 2',
                        'irban3' => 'Irban 3',
                        'irbansus' => 'Irban Khusus',
                    ]),
            ])

            ->recordActions([
                ViewAction::make(),
            ])

            ->bulkActions([]); // kosongkan bulk action
    }
}
