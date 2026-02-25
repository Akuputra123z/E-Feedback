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

    // 1. Gunakan Model SurveyReport (sesuai file Table Anda sebelumnya)
    // Jika nama model aslinya adalah SurveyResponse, silakan ganti kembali
    $query = SurveyResponse::query()->withoutGlobalScopes();

    // 2. Filter Role yang lebih fleksibel (Super Admin & Admin bisa lihat semua)
    $userRole = strtolower($user->role);
    $isAdmin = in_array($userRole, ['super_admin', 'admin', 'superadmin']);

    if (!$isAdmin) {
        // Jika bukan admin, filter berdasarkan irban_type user
        $query->where('irban', $user->irban_type);
    }

    // 3. Ambil data dengan DB Raw (Pastikan import DB di atas: use Illuminate\Support\Facades\DB;)
    $data = $query->select([
            \Illuminate\Support\Facades\DB::raw('COUNT(id) as total'),
            \Illuminate\Support\Facades\DB::raw('MONTH(created_at) as month')
        ])
        ->whereYear('created_at', now()->year)
        ->groupBy('month')
        ->get()
        ->pluck('total', 'month')
        ->all();

    // 4. Menyiapkan data untuk 12 bulan (isi 0 jika bulan kosong)
    $chartData = collect(range(1, 12))->map(fn ($month) => $data[$month] ?? 0)->toArray();

    // 5. Label dinamis untuk judul Chart
    $irbanName = $isAdmin 
        ? 'Seluruh Irban' 
        : strtoupper($user->irban_type ?? 'Wilayah');

    return [
        'datasets' => [
            [
                'label' => "Jumlah Survei " . now()->year . " ($irbanName)",
                'data' => $chartData,
                'fill' => 'start',
                'borderColor' => '#195de6',
                'backgroundColor' => 'rgba(25, 93, 230, 0.1)', // Efek bayangan di bawah garis
                'tension' => 0.3, // Membuat garis sedikit melengkung (smooth)
                'pointBackgroundColor' => '#195de6',
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