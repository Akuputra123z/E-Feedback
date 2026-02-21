<?php

namespace App\Filament\Widgets;

use App\Models\SurveyResponse;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MonthlySurveyChart extends ChartWidget
{
    protected static ?int $sort = 2;
    protected ?string $heading = 'Tren Responden Bulanan';

    public static function canView(): bool
    {
        return Auth::check(); 
    }

    protected function getData(): array
    {
        $user = Auth::user();

        // 1. Gunakan withoutGlobalScopes() dan filter manual agar hasil PASTI akurat
        $query = SurveyResponse::query()->withoutGlobalScopes();

        // 2. Filter berdasarkan role super_admin dan kolom irban_type
        if ($user && $user->role !== 'super_admin') {
            $query->where('irban', $user->irban_type);
        }

        $data = $query->select([
                DB::raw('COUNT(id) as total'),
                DB::raw('MONTH(created_at) as month')
            ])
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->get()
            ->pluck('total', 'month')
            ->all();

        // 3. Menyiapkan data untuk 12 bulan
        $chartData = collect(range(1, 12))->map(fn ($month) => $data[$month] ?? 0)->all();

        // 4. Label dinamis menggunakan irban_type
        $irbanName = ($user->role === 'super_admin') 
            ? 'Seluruh Irban' 
            : strtoupper($user->irban_type ?? 'Irban');

        return [
            'datasets' => [
                [
                    'label' => "Jumlah Survei ($irbanName)",
                    'data' => $chartData,
                    'backgroundColor' => '#195de6',
                    'borderRadius' => 4,
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