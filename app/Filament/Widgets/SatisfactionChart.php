<?php

namespace App\Filament\Widgets;


use App\Models\SurveyResponse;
use Filament\Widgets\ChartWidget;

class SatisfactionChart extends ChartWidget
{
    protected ?string $heading = 'Tingkat Kepuasan Masyarakat';
    
    protected ?string $maxHeight = '300px';

    protected static ?int $sort = 1;

    protected function getData(): array
    {
        $avgScore = SurveyResponse::avg('ikm_score') ?? 0;
        $isScale100 = $avgScore > 5;

        $tPuas = $isScale100 ? 80 : 4;
        $tCukup = $isScale100 ? 60 : 3;

        $puas = SurveyResponse::where('ikm_score', '>=', $tPuas)->count();
        $cukup = SurveyResponse::where('ikm_score', '>=', $tCukup)->where('ikm_score', '<', $tPuas)->count();
        $kurang = SurveyResponse::where('ikm_score', '<', $tCukup)->count();

        return [
            'datasets' => [
                [
                    'label' => 'Responden',
                    'data' => [$puas, $cukup, $kurang],
                    'backgroundColor' => ['#195de6', '#60a5fa', '#e2e8f0'],
                ],
            ],
            'labels' => ['Puas', 'Cukup', 'Kurang'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}