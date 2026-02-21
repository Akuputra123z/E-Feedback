<?php

namespace App\Filament\Resources\SurveyReports\Pages;

use App\Filament\Resources\SurveyReports\SurveyReportResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SurveyReportsExport;
use Illuminate\Support\Facades\Response; // nanti kita buat

use Filament\Support\Icons\Heroicon;
class ListSurveyReports extends ListRecords
{
    protected static string $resource = SurveyReportResource::class;
    public function getTitle(): string 
{
    return 'Laporan Survey';
}

    protected function getHeaderActions(): array
    {
        return [
          Action::make('download_pdf')
            ->label('Download PDF')
            ->color('danger')
            ->icon('heroicon-o-document-arrow-down')
            ->action(function () {
                $records = $this->getFilteredTableQuery()->get();

                // 1. Ambil path logo (Pastikan file ada di storage/app/public/images/logo.webp)
                // Jika file ada di public/storage/images/logo.webp, gunakan public_path
                $logoPath = public_path('storage/images/logo-rembang.png');
                $logoBase64 = '';

                if (file_exists($logoPath)) {
                    $type = pathinfo($logoPath, PATHINFO_EXTENSION);
                    $data = file_get_contents($logoPath);
                    $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                }

                $pdf = Pdf::loadView('survey_reports.pdf', [
                    'records' => $records,
                    'logo_base64' => $logoBase64, // Kirim variabel base64
                ])->setPaper('a4', 'portrait');

                return response()->streamDownload(
                    fn () => print($pdf->output()),
                    'Laporan-Survei.pdf'
                );
            }),


            // Tombol Download Excel
          Action::make('download_excel')
            ->label('Download Excel')
            ->color('success')
            ->icon(Heroicon::OutlinedDocumentArrowDown)
            ->action(function () {
                $records = $this->getFilteredTableQuery()->with('answers.question')->get();
                return Excel::download(new SurveyReportsExport($records), 'survey_reports.xlsx');
            }),

        ];
    }
    
    protected function getEloquentQuery(): Builder
{
    $query = parent::getEloquentQuery();

    if (auth()->user()->hasRole('irban')) {
        $query->where('irban_id', auth()->id());
    }

    return $query;
}
}
