<?php

namespace App\Filament\Resources\SurveyResponses\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class SurveyResponseInfolist
{
    private const DIMENSIONS = [
        'Materi',
        'Standar',
        'SDM',
        'Dukungan',
    ];

    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            static::analysisSection(),
            static::profileSection(),
            static::dimensionSection(),
            static::suggestionSection(),
            static::locationSection(),
            static::answersSection(),
        ]);
    }

    /* ========================================
     * 1. HEADER ANALISIS
     * ======================================*/
    protected static function analysisSection(): Section
    {
        return Section::make('Hasil Analisis Kepuasan')
            ->description('Kalkulasi indeks berdasarkan seluruh dimensi penilaian.')
            ->columns(4)
            ->schema([
                TextEntry::make('ikm_score')
                    ->label('Indeks Kepuasan (IKM)')
                    ->weight(FontWeight::Black)
                    ->color('primary'),

                TextEntry::make('category')
                    ->label('Kategori Kinerja')
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        str_contains($state ?? '', '(A)') => 'success',
                        str_contains($state ?? '', '(B)') => 'info',
                        str_contains($state ?? '', '(C)') => 'warning',
                        default => 'danger',
                    }),

                TextEntry::make('total_score')
                    ->label('Total Skor'),

                TextEntry::make('tanggal')
                    ->label('Tanggal Survey')
                    ->date('d M Y'),
            ]);
    }

    /* ========================================
     * 2. PROFIL RESPONDEN
     * ======================================*/
    protected static function profileSection(): Section
    {
        return Section::make('Profil Responden')
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
                    ->formatStateUsing(fn ($state) => strtoupper($state ?? '-')),

                TextEntry::make('jenis_layanan')
                    ->label('Kategori Layanan'),
            ]);
    }

    /* ========================================
     * 3. ANALISIS PER DIMENSI
     * ======================================*/
    protected static function dimensionSection(): Section
    {
        return Section::make('Skor Rata-rata Per Dimensi')
            ->description('Nilai performa per masing-masing pilar.')
            ->columns(2)
            ->schema(
                collect(self::DIMENSIONS)
                    ->map(fn ($dim) => self::makeDimensionEntry($dim))
                    ->toArray()
            );
    }

    private static function makeDimensionEntry(string $dimension): TextEntry
    {
        return TextEntry::make("dimension_$dimension")
            ->label("Dimensi $dimension")
            ->state(fn ($record) => self::calculateAverage($record, $dimension))
            ->weight(FontWeight::Bold)
            ->color(fn ($state) => self::dimensionColor($state))
            ->icon('heroicon-m-chart-bar-square');
    }

    private static function calculateAverage($record, string $dimension): string
    {
        $answers = $record->answers ?? collect();

        $avg = $answers
            ->filter(fn ($a) => ($a->question->dimension ?? null) === $dimension)
            ->avg('answer');

        $avg = round((float) $avg, 2);

        return number_format($avg, 2) . ' / 5.00';
    }

    private static function dimensionColor(string $formattedValue): string
    {
        $value = (float) explode(' ', $formattedValue)[0];

        return match (true) {
            $value >= 4.0 => 'success',
            $value < 3.0  => 'danger',
            default       => 'warning',
        };
    }

    /* ========================================
     * 4. SARAN
     * ======================================*/
    protected static function suggestionSection(): Section
    {
        return Section::make('Masukan Responden')
            ->description('Kritik, saran, atau keluhan yang disampaikan oleh responden.')
            ->icon('heroicon-m-chat-bubble-left-right')
            ->schema([
                TextEntry::make('suggestions')
                    ->label('Kritik & Saran')
                    ->prose()
                    ->placeholder('Tidak ada masukan yang diberikan.')
                    ->columnSpanFull(),
            ]);
    }

    /* ========================================
     * 5. LOKASI
     * ======================================*/
    protected static function locationSection(): Section
    {
        return Section::make('Lokasi Survey')
            ->description('Lokasi survey dilakukan.')
            ->icon('heroicon-m-map-pin')
            ->schema([
                TextEntry::make('lokasi_survey')
                    ->label('Lokasi Survey')
                    ->placeholder('Tidak ada lokasi survey yang diberikan.')
                    ->columnSpanFull(),
            ]);
    }

    /* ========================================
     * 6. RINCIAN JAWABAN
     * ======================================*/
    protected static function answersSection(): Section
    {
        return Section::make('Rincian Jawaban Instrumen')
            ->collapsible()
            ->schema([
                RepeatableEntry::make('answers')
                    ->label('')
                    ->schema([
                        TextEntry::make('question.text')
                            ->label('Pertanyaan'),

                        TextEntry::make('question.dimension')
                            ->label('Dimensi')
                            ->badge()
                            ->color('gray'),

                        TextEntry::make('answer')
                            ->label('Skor Diberikan')
                            ->weight(FontWeight::Bold)
                            ->color(fn ($state) =>
                                $state >= 4 ? 'success' : 'primary'
                            ),
                    ])
                    ->columns(3),
            ]);
    }
}