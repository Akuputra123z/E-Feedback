<?php

namespace App\Filament\Widgets;

use App\Models\SurveyResponse;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class SurveyStatsOverview extends BaseWidget
{
    protected static ?int $sort = -2;

    protected ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $user = Auth::user();

        if (!$user) {
            return [];
        }

        // Query dasar
        $query = SurveyResponse::query();

        /**
         * ==========================================
         * ROLE BASED FILTER (SPATIE)
         * ==========================================
         */

        // Jika user adalah IRBAN → hanya lihat data sesuai irban_type
        if ($user->hasRole('irban') && !empty($user->irban_type)) {
            $query->where('irban', $user->irban_type);
        }

        // Jika admin / super_admin → lihat semua data
        // (tidak perlu filter apa pun)

        /**
         * ==========================================
         * AMBIL DATA
         * ==========================================
         */
        $totalResponden = (clone $query)->count();
        $avgIkm = (clone $query)->avg('ikm_score') ?? 0;

        $quality = $this->getServiceQuality($avgIkm);

        /**
         * ==========================================
         * DESKRIPSI DINAMIS
         * ==========================================
         */
        $descriptionText = match (true) {
            $user->hasRole('super_admin'),
            $user->hasRole('admin') => 'Data Seluruh Irban',

            $user->hasRole('irban') => 'Data ' . strtoupper($user->irban_type),

            default => 'Data Survey',
        };

        return [
            Stat::make('Total Responden', number_format($totalResponden) . ' Orang')
                ->description($descriptionText)
                ->descriptionIcon('heroicon-m-user-group')
                ->icon('heroicon-m-users')
                ->color('info'),

            Stat::make('Indeks IKM', number_format($avgIkm, 2))
                ->description('Skala 0 - 100')
                ->descriptionIcon('heroicon-m-presentation-chart-line')
                ->icon('heroicon-m-chart-bar')
                ->color($avgIkm >= 76.61 ? 'success' : 'warning'),

            Stat::make('Mutu Pelayanan', $quality['label'])
                ->description($quality['desc'])
                ->descriptionIcon('heroicon-m-check-badge')
                ->icon('heroicon-m-trophy')
                ->color($quality['color']),
        ];
    }

    protected function getServiceQuality(float $ikm): array
    {
        return match (true) {
            $ikm >= 88.31 => [
                'label' => 'A (Sangat Baik)',
                'desc'  => 'Pelayanan Prima',
                'color' => 'success'
            ],
            $ikm >= 76.61 => [
                'label' => 'B (Baik)',
                'desc'  => 'Kinerja Baik',
                'color' => 'primary'
            ],
            $ikm >= 65.01 => [
                'label' => 'C (Kurang Baik)',
                'desc'  => 'Perlu Perbaikan',
                'color' => 'warning'
            ],
            default => [
                'label' => 'D (Tidak Baik)',
                'desc'  => 'Kinerja Rendah',
                'color' => 'danger'
            ],
        };
    }
}