<?php

namespace App\Filament\Resources\Questions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;


use Filament\Tables\Table;

class QuestionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
              TextColumn::make('sort_order')
                    ->label('No')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('text')
                    ->label('Pertanyaan')
                    ->wrap() // Agar teks panjang tidak terpotong
                    ->searchable(),

                TextColumn::make('dimension')
                    ->label('Dimensi')
                    ->badge() // Menggunakan badge agar lebih visual
                    ->color(fn (string $state): string => match ($state) {
                        'Materi' => 'info',
                        'Standar' => 'warning',
                        'SDM' => 'success',
                        'Dukungan' => 'danger',
                        default => 'gray',
                    })
                    ->searchable(),

                IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
        
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
