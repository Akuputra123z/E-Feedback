<?php

namespace App\Filament\Resources\SurveyResponses\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class SurveyResponseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // 1. HEADER (IKM & KATEGORI)
                Section::make('Hasil Analisis Kepuasan')
                    ->description('Kalkulasi indeks berdasarkan seluruh dimensi penilaian.')
                    ->columns(4) // Tetap menggunakan columns pada Section (bawaan Filament)
                    ->schema([
                        TextEntry::make('ikm_score')
                            ->label('Indeks Kepuasan (IKM)')
                            ->weight(FontWeight::Black)
                           
                            ->color('primary'),
                        TextEntry::make('category')
                            ->label('Kategori Kinerja')
                            ->badge()
                            ->color(fn ($state) => match (true) {
                                str_contains($state, '(A)') => 'success',
                                str_contains($state, '(B)') => 'info',
                                str_contains($state, '(C)') => 'warning',
                                default => 'danger',
                            }),
                        TextEntry::make('total_score')
                            ->label('Total Skor'),
                        TextEntry::make('tanggal')
                            ->label('Tanggal Survey')
                            ->date('d M Y'),
                    ]),

                // 2. PROFIL RESPONDEN
                Section::make('Profil Responden')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('email')
                            ->label('Email Resmi')
                            ->icon('heroicon-m-envelope')
                            ->copyable(),
                        TextEntry::make('opd')
                            ->label('Instansi / OPD')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('irban')
                            ->label('Wilayah Irban')
                            ->badge()
                            ->formatStateUsing(fn ($state) => strtoupper($state ?? '')),
                        TextEntry::make('jenis_layanan')
                            ->label('Kategori Layanan'),
                    ]),

                // 3. ANALISIS PER DIMENSI
                Section::make('Skor Rata-rata Per Dimensi')
                    ->description('Nilai performa per masing-masing pilar.')
                    ->columns(2)
                    ->schema([
                        self::makeDimensionEntry('Materi'),
                        self::makeDimensionEntry('Standar'),
                        self::makeDimensionEntry('SDM'),
                        self::makeDimensionEntry('Dukungan'),
                    ]),

                // 4. RINCIAN JAWABAN
                Section::make('Rincian Jawaban Instrumen')
                    ->collapsible()
                    ->schema([
                        RepeatableEntry::make('answers')
                            ->label('')
                            ->schema([
                                // Menggunakan columns di dalam repeatable untuk merapikan baris
                                TextEntry::make('question.text')
                                    ->label('Pertanyaan'),
                                
                                TextEntry::make('question.dimension')
                                    ->label('Dimensi')
                                    ->badge()
                                    ->color('gray'),

                                TextEntry::make('answer')
                                    ->label('Skor Diberikan')
                                    ->weight(FontWeight::Bold)
                                    ->color(fn($state) => $state >= 4 ? 'success' : 'primary'),
                            ])
                            ->columns(3), // Menampilkan Pertanyaan, Dimensi, Skor dalam satu baris
                    ]),
            ]);
    }

    private static function makeDimensionEntry(string $dimension): TextEntry
    {
        return TextEntry::make('dimension_' . strtolower($dimension))
            ->label("Dimensi $dimension")
            ->state(function ($record) use ($dimension) {
                $avg = $record->answers
                    ->filter(fn($a) => ($a->question->dimension ?? '') === $dimension)
                    ->avg('answer');
                
                return $avg ? number_format($avg, 2) . ' / 5.00' : '0.00 / 5.00';
            })
            ->weight(FontWeight::Bold)
            ->color(fn($state) => (float)$state >= 4.0 ? 'success' : ((float)$state < 3.0 ? 'danger' : 'warning'))
            ->icon('heroicon-m-chart-bar-square');
    }
}