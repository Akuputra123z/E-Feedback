<?php

namespace App\Filament\Widgets;

use App\Models\SurveyResponse;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class MonthlySurveyChart extends ChartWidget
{
    protected static ?int $sort = 2;
    protected ?string $heading = 'Tren Responden Bulanan';

    protected function getData(): array
    {
        $data = SurveyResponse::select(
                DB::raw('COUNT(id) as total'),
                DB::raw('MONTH(created_at) as month')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->get()
            ->pluck('total', 'month')
            ->all();

        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $data[$i] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Survei',
                    'data' => $chartData,
                    'backgroundColor' => '#195de6',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}