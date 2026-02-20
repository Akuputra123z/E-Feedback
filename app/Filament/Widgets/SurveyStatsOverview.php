<?php

namespace App\Filament\Widgets;

use App\Models\SurveyResponse;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SurveyStatsOverview extends BaseWidget
{
    protected static ?int $sort = -2;
    protected function getStats(): array
    {
        $avgScore = SurveyResponse::avg('ikm_score') ?? 0;
        $maxScore = ($avgScore > 5) ? 100 : 5;
        $indeks = ($avgScore > 0) ? ($avgScore / $maxScore) * 100 : 0;

        return [
            Stat::make('Total Responden', SurveyResponse::count() . ' Orang')
                ->icon('heroicon-m-users')
                ->color('info'),

            Stat::make('Rata-rata Skor', number_format($avgScore, 2))
                ->description('Skala ' . $maxScore)
                ->icon('heroicon-m-presentation-chart-line')
                ->color('warning'),

            Stat::make('Indeks Pelayanan', number_format($indeks, 1) . '%')
                ->description($indeks >= 80 ? 'Mutu: Sangat Baik' : 'Mutu: Baik')
                ->icon('heroicon-m-check-badge')
                ->color($indeks >= 80 ? 'success' : 'primary'),
        ];
    }
}