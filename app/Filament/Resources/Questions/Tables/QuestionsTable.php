<?php

namespace App\Filament\Resources\Questions\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

class QuestionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->groups([
                static::dimensionGroup(),
            ])
            ->defaultGroup('dimension') // otomatis grouping aktif
            ->persistFiltersInSession()
            ->columns([
                static::sortOrderColumn(),
                static::textColumn(),
                static::dimensionColumn(),
                static::statusColumn(),
            ])
            ->filters([
                static::dimensionFilter(),
                static::statusFilter(),
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

    protected static function dimensionGroup(): Group
    {
        return Group::make('dimension')
            ->label('Dimensi')
            ->collapsible() // bisa collapse per group
            ->getTitleFromRecordUsing(fn ($record) => static::dimensionLabel($record->dimension))
            ->orderQueryUsing(fn ($query, $direction) =>
                $query->orderBy('dimension', $direction)
            );
    }

    protected static function sortOrderColumn(): TextColumn
    {
        return TextColumn::make('sort_order')
            ->label('No')
            ->numeric()
            ->sortable()
            ->alignCenter();
    }

    protected static function textColumn(): TextColumn
    {
        return TextColumn::make('text')
            ->label('Pertanyaan')
            ->wrap()
            ->searchable();
    }

    protected static function dimensionColumn(): TextColumn
    {
        return TextColumn::make('dimension')
            ->label('Dimensi')
            ->badge()
            ->color(fn (string $state) => static::dimensionColor($state))
            ->sortable();
    }

    protected static function statusColumn(): IconColumn
    {
        return IconColumn::make('is_active')
            ->label('Status')
            ->boolean()
            ->trueIcon('heroicon-o-check-circle')
            ->falseIcon('heroicon-o-x-circle')
            ->sortable();
    }

    protected static function dimensionFilter(): SelectFilter
    {
        return SelectFilter::make('dimension')
            ->label('Filter Dimensi')
            ->options(static::dimensionOptions())
            ->native(false);
    }

    protected static function statusFilter(): TernaryFilter
    {
        return TernaryFilter::make('is_active')
            ->label('Status Aktif')
            ->placeholder('Semua Status')
            ->trueLabel('Hanya Aktif')
            ->falseLabel('Hanya Non-Aktif');
    }

    protected static function dimensionOptions(): array
    {
        return [
            'Materi'   => 'Materi Konsultasi',
            'Standar'  => 'Standar Pelayanan',
            'SDM'      => 'Profesionalisme SDM',
            'Dukungan' => 'Dukungan & Pengelolaan',
        ];
    }

    protected static function dimensionLabel(string $value): string
    {
        return static::dimensionOptions()[$value] ?? $value;
    }

    protected static function dimensionColor(string $state): string
    {
        return match ($state) {
            'Materi'   => 'info',
            'Standar'  => 'warning',
            'SDM'      => 'success',
            'Dukungan' => 'danger',
            default    => 'gray',
        };
    }
}