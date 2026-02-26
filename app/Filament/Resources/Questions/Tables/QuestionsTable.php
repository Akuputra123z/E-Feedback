<?php

namespace App\Filament\Resources\Questions\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Grouping\Group;
use App\Models\Question;
use Filament\Tables\Actions\EditAction;
// use Filament\Tables\Actions\DeleteAction;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;

class QuestionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
        
            ->groups([
                static::dimensionGroup(),
            ])
            ->defaultGroup('dimension')
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
            ->actions([
                DeleteAction::make()
                ->successNotification(
                Notification::make()
                        ->success()
                        ->title('Question deleted')
                        ->body('The question has been deleted successfully.'),
    )
            ])
            ->bulkActions([
               
            ])
            ->striped()
            ->emptyStateHeading('Belum ada pertanyaan');
    }

    protected static function dimensionGroup(): Group
{
    return Group::make('dimension')
        ->label('Dimensi')
        ->collapsible()
        // KUNCI PERBAIKAN:
        // Kita urutkan Group berdasarkan urutan terkecil sort_order di dalamnya
        // dan pastikan query utama selalu mengikuti urutan ini.
        ->orderQueryUsing(fn (Builder $query) => $query
            ->orderBy('dimension', 'asc') // Menjaga agar string yang sama tetap berkumpul
            ->orderBy('sort_order', 'asc') // Menjaga urutan angka di dalam grup
        )
        ->getTitleFromRecordUsing(fn ($record) => static::dimensionLabel($record->dimension));
}

   
    protected static function sortOrderColumn(): TextColumn
    {
        return TextColumn::make('sort_order')
            ->label('No')
            ->numeric()
            ->alignCenter()
            ->getStateUsing(function ($record, TextColumn $column) {
                // Ambil semua record yang tampil di table
                $records = $column->getTable()->getRecords();

                // Filter record yang memiliki dimensi sama
                $sameDimension = $records->filter(fn($r) => $r->dimension === $record->dimension)->values();

                // Cari index record ini
                $index = $sameDimension->search(fn($r) => $r->id === $record->id);

                return $index !== false ? $index + 1 : $record->sort_order;
            });
    }

    protected static function textColumn(): TextColumn
    {
        return TextColumn::make('text')
            ->label('Pertanyaan')
            ->wrap()
            ->limit(100)
            ->tooltip(fn (TextColumn $column): ?string => $column->getState())
            ->searchable()
            ->sortable();
    }

    protected static function dimensionColumn(): TextColumn
    {
        return TextColumn::make('dimension')
            ->label('Dimensi')
            ->badge()
            ->color(fn (string $state): string => match ($state) {
                'Materi'   => 'info',
                'Standar'  => 'warning',
                'SDM'      => 'success',
                'Dukungan' => 'danger',
                default    => 'gray',
            })
            ->sortable();
    }

    protected static function statusColumn(): IconColumn
    {
        return IconColumn::make('is_active')
            ->label('Status')
            ->boolean()
            ->sortable();
    }

    protected static function dimensionFilter(): SelectFilter
    {
        return SelectFilter::make('dimension')
            ->label('Filter Dimensi')
            ->options(static::dimensionOptions())
            ->searchable();
    }

    protected static function statusFilter(): TernaryFilter
    {
        return TernaryFilter::make('is_active')
            ->label('Status Aktif')
            ->placeholder('Semua Status')
            ->trueLabel('Aktif')
            ->falseLabel('Non-Aktif')
            ->native(false);
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
}