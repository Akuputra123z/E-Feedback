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
            // Tombol Download PDF
           Action::make('download_pdf')
                ->label('Download PDF')
                ->color('danger')
                ->icon(Heroicon::OutlinedDocumentArrowDown)
               ->action(function () {
        // 1. Ambil data dengan eager loading jika perlu untuk performa
        $records = $this->getFilteredTableQuery()->get();

        // 2. Generate PDF
        $pdf = Pdf::loadView('survey_reports.pdf', [
            'records' => $records,
            // Opsional: Kirimkan base64 logo langsung dari sini jika view kesulitan memproses path
            'logo_base64' => base64_encode(file_get_contents(storage_path('app/public/images/logo-rembang.png')))
        ])
        ->setPaper('a4', 'portrait')
        ->setOptions([
            'tempDir' => public_path('temp'), // Folder sementara untuk memproses gambar
            'isRemoteEnabled' => true,        // Wajib untuk logo & font eksternal
            'isHtml5ParserEnabled' => true,
            'chroot' => [storage_path('app/public'), public_path()], // Beri izin akses ke folder logo
        ]);

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'Laporan-Survei-' . now()->format('d-m-Y') . '.pdf'
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
