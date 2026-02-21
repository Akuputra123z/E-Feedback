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

        // 1. Bersihkan query dari scope agar tidak bentrok
        $query = SurveyResponse::query()->withoutGlobalScopes();

        /**
         * 2. Filter berdasarkan irban_type dari model User
         * Kita asumsikan di tabel survey_responses nama kolomnya adalah 'irban'
         */
        if ($user && $user->role !== 'super_admin') {
            // Gunakan irban_type milik user untuk memfilter kolom irban di tabel survey
            $query->where('irban', $user->irban_type);
        }

        // 3. Ambil Data
        $totalResponden = (clone $query)->count();
        $avgIkm = (clone $query)->avg('ikm_score') ?? 0;

        $quality = $this->getServiceQuality($avgIkm);

        return [
            Stat::make('Total Responden', number_format($totalResponden) . ' Orang')
                ->description('Data masuk untuk ' . ($user->role === 'super_admin' ? 'Seluruh Irban' : strtoupper($user->irban_type)))
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
            $ikm >= 88.31 => ['label' => 'A (Sangat Baik)', 'desc' => 'Pelayanan Prima', 'color' => 'success'],
            $ikm >= 76.61 => ['label' => 'B (Baik)', 'desc' => 'Kinerja Baik', 'color' => 'primary'],
            $ikm >= 65.01 => ['label' => 'C (Kurang Baik)', 'desc' => 'Perlu Perbaikan', 'color' => 'warning'],
            default       => ['label' => 'D (Tidak Baik)', 'desc' => 'Kinerja Rendah', 'color' => 'danger'],
        };
    }
}