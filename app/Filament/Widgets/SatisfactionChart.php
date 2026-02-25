<?php

namespace App\Filament\Widgets;

use App\Models\SurveyResponse;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class SatisfactionChart extends ChartWidget
{
    protected ?string $heading = 'Tingkat Kepuasan Masyarakat';
    
    protected ?string $maxHeight = '300px';

    protected static ?int $sort = 1;

    protected function getData(): array
    {
        $user = Auth::user();

        // 1. Inisialisasi query dan bersihkan scope agar tidak bentrok
        $baseQuery = SurveyResponse::query()->withoutGlobalScopes();

        // 2. Filter berdasarkan irban_type milik user (Sesuai model User Anda)
        if ($user && $user->role !== 'admin') {
            $baseQuery->where('irban', $user->irban_type);
        }

        /** * 3. Ambil Data (Menggunakan standar IKM skala 100)
         * Sangat Baik/Puas: >= 88.31
         * Baik/Cukup: 76.61 - 88.30
         * Kurang: < 76.61
         */
        $puas = (clone $baseQuery)->where('ikm_score', '>=', 88.31)->count();
        $cukup = (clone $baseQuery)->where('ikm_score', '>=', 76.61)
                                   ->where('ikm_score', '<', 88.31)->count();
        $kurang = (clone $baseQuery)->where('ikm_score', '<', 76.61)->count();

        return [
            'datasets' => [
                [
                    'label' => 'Responden',
                    'data' => [$puas, $cukup, $kurang],
                    'backgroundColor' => [
                        '#10b981', // Hijau (Puas)
                        '#3b82f6', // Biru (Cukup)
                        '#ef4444'  // Merah (Kurang)
                    ],
                ],
            ],
            'labels' => ['Sangat Baik', 'Baik', 'Kurang/Tidak Baik'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}