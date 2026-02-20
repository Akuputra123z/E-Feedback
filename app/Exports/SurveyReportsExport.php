<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents; // Tambahkan ini
use Maatwebsite\Excel\Events\AfterSheet;   // Tambahkan ini
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SurveyReportsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents
{
    protected $records;
    protected $allQuestions;

    public function __construct($records)
    {
        $this->records = $records;

        $this->allQuestions = collect($records)
            ->flatMap(fn($record) => $record->answers)
            ->pluck('question.text')
            ->unique()
            ->values();
    }

    public function collection()
    {
        return $this->records;
    }

    public function headings(): array
    {
        return array_merge(
            ['Email', 'OPD', 'Jenis Layanan', 'Tanggal', 'Total Skor', 'IKM', 'Kategori'],
            $this->allQuestions->toArray()
        );
    }

    public function map($record): array
    {
        $row = [
            $record->email,
            $record->opd,
            $record->jenis_layanan,
            $record->tanggal ? $record->tanggal->format('Y-m-d') : '-',
            $record->total_score,
            number_format($record->ikm_score, 2),
            $record->category,
        ];

        foreach ($this->allQuestions as $question) {
            $answer = $record->answers->firstWhere('question.text', $question);
            $row[] = $answer->answer ?? '-';
        }

        return $row;
    }

    public function styles(Worksheet $sheet)
    {
        $highestColumn = $sheet->getHighestColumn();
        $highestRow = $sheet->getHighestRow();

        // Style Header
        $sheet->getStyle("A1:{$highestColumn}1")->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E2E8F0'],
            ],
        ]);

        // Style Global (Border & Alignment)
        $sheet->getStyle("A1:{$highestColumn}{$highestRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_TOP,
                'wrapText' => true,
            ],
        ]);
    }

    // --- KUNCI UTAMA AUTOFIT ---
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestColumn = $sheet->getHighestColumn();
                $highestRow = $sheet->getHighestRow();

                // 1. Loop semua kolom untuk set AutoSize
                foreach (range('A', $highestColumn) as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // 2. Paksa Excel menghitung lebar kolom saat ini
                $sheet->calculateColumnWidths();

                // 3. Tambahkan sedikit 'napas' (extra width) agar tidak terlalu mepet
                foreach (range('A', $highestColumn) as $col) {
                    $currentWidth = $sheet->getColumnDimension($col)->getWidth();
                    $sheet->getColumnDimension($col)->setAutoSize(false);
                    // Tambahkan buffer +3 unit
                    $sheet->getColumnDimension($col)->setWidth($currentWidth + 3);
                }

                // 4. Set auto row height untuk data yang di-wrap
                for ($row = 1; $row <= $highestRow; $row++) {
                    $sheet->getRowDimension($row)->setRowHeight(-1);
                }
            },
        ];
    }
}