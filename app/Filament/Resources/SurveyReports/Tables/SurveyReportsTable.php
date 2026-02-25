<?php

namespace App\Filament\Resources\SurveyReports\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Tables\Filters\DateFilter;
use Filament\Tables\Filters\SelectFilter;   
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

use Filament\Tables\Columns\TextColumn;

use Filament\Tables\Columns\BadgeColumn;
use App\Models\SurveyReport;

class SurveyReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('opd')
                    ->label('OPD')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('jenis_layanan')
                    ->label('Jenis Layanan')
                    ->sortable(),

                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),

                TextColumn::make('total_score')
                    ->label('Total Skor')
                    ->sortable(),
                TextColumn::make('irban')
                    ->label('Irban')
                    ->sortable(),

                TextColumn::make('ikm_score')
                    ->label('IKM')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => number_format($state, 2)),

                BadgeColumn::make('category')
                    ->label('Kategori')
                    ->colors([
                        'success' => 'Sangat Baik (A)',
                        'primary' => 'Baik (B)',
                        'warning' => 'Kurang Baik (C)',
                        'danger'  => 'Tidak Baik (D)',
                    ])
                    ->sortable(),

               TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/y H:i') // Format pendek: 19/02/26 21:15
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Sembunyikan default agar tabel tidak penuh


            ])
            ->stackedOnMobile()


->filters([
    // 1. Rentang Tanggal
        Filter::make('created_at')
            ->label('Periode')
            ->form([
                DatePicker::make('dari_tanggal')->label('Dari'),
                DatePicker::make('sampai_tanggal')->label('Sampai'),
            ])
            ->columns(2) // 'Dari' & 'Sampai' bersebelahan
            ->query(function (Builder $query, array $data): Builder {
                return $query
                    ->when($data['dari_tanggal'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                    ->when($data['sampai_tanggal'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
            }),

    // 2. Irban
        SelectFilter::make('irban')
            ->label('Irban')
            ->options([
                'irban1'   => 'Irban 1',
                'irban2'   => 'Irban 2',
                'irban3'   => 'Irban 3',
                'irbansus' => 'Irban Khusus',
                'sekretariat' => 'Sekretariat',
            ]),

    // 3. Jenis Layanan
        SelectFilter::make('jenis_layanan')
            ->label('Jenis Layanan')
            ->options([
               'Audit/Reguler Investigatif' => 'Audit / Reguler Investigatif',
               'Reviuw'                     => 'Reviuw',
               'Evaluasi'                   => 'Evaluasi',
               'Pemeriksaan'                => 'Pemeriksaan',
               'Consulting'                 => 'Consulting (Sosialisasi, Bimtek, Coaching Clinic, Pendampingan, Asistensi)',
            ]),

    // 4. Kategori IKM
        SelectFilter::make('category')
            ->label('Kategori IKM')
            ->options([
                'Sangat Baik (A)' => 'Sangat Baik (A)',
                'Baik (B)'        => 'Baik (B)',
                'Kurang Baik (C)' => 'Kurang Baik (C)',
                'Tidak Baik (D)'  => 'Tidak Baik (D)',
            ]),
        ], layout: FiltersLayout::AboveContent)
        ->filtersFormColumns(4)
            ->recordActions([
               
            ])
            ->toolbarActions([
                
            ]);
    }
}
