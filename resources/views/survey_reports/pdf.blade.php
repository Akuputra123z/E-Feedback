<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        /* Pengaturan Margin Kertas A4 Presisi */
        @page { 
            size: a4 portrait; 
            margin: 1.5cm; 
        }
        
        body { 
            margin: 0; padding: 0; 
            font-family: 'Helvetica', sans-serif; 
            font-size: 10pt; color: #334155;
            line-height: 1.4;
        }

        .pdf-page { page-break-after: always; width: 100%; }
        .w-full { width: 100%; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        
        /* Kartu Ringkasan */
        .summary-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .summary-table td { padding: 5px; }
        .card { border: 1px solid #e2e8f0; padding: 15px 5px; border-radius: 10px; text-align: center; }
        .card-blue { background-color: #195de6; color: white; border: none; }

        /* Box Analisis */
        .analysis-box { 
            border: 1px solid #e2e8f0; 
            border-radius: 12px; 
            padding: 15px; 
            margin-bottom: 30px;
            background-color: #f8fafc;
        }

        /* Tabel Data Detail */
        .data-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .data-table th { background-color: #f1f5f9; border: 1px solid #e2e8f0; padding: 10px; font-size: 8pt; color: #64748b; text-transform: uppercase; }
        .data-table td { border: 1px solid #e2e8f0; padding: 8px; font-size: 9pt; word-wrap: break-word; vertical-align: top; }

        .footer-sign { margin-top: 40px; float: right; width: 250px; text-align: center; }
    </style>
</head>
<body>

<div class="pdf-page text-center">
  <div style="margin-top: 50px;">
    <img src="{{ public_path('storage/images/logo-rembang.png') }}" style="width: 80px;">
    
    <p style="margin-top: 15px; font-size: 11pt; color: #64748b; letter-spacing: 2px;">PEMERINTAH KABUPATEN REMBANG</p>
    <h1 style="margin: 5px 0; font-size: 18pt; text-transform: uppercase;">Inspektorat Daerah</h1>
</div>

    <div style="margin: 120px 0;">
        <div style="height: 4px; width: 60px; background: #195de6; margin: 0 auto 25px;"></div>
        <h2 style="font-size: 26pt; margin: 0; line-height: 1.2;">LAPORAN HASIL SURVEI<br>KEPUASAN MASYARAKAT</h2>
        <p style="font-size: 16pt; color: #195de6; font-weight: bold; margin-top: 15px;">(e-Feedback)</p>
        <h3 style="margin-top: 35px; font-weight: normal; color: #64748b;">Tahun {{ date('Y') }}</h3>
    </div>
</div>

<div class="pdf-page">
    <h3 style="margin-bottom: 15px; border-left: 4px solid #195de6; padding-left: 10px;">Ringkasan Eksekutif</h3>
    
    <table class="summary-table">
        <tr>
            <td width="33%">
                <div class="card">
                    <div style="font-size: 7pt; color: #195de6; font-weight: bold;">TOTAL RESPONDEN</div>
                    <div style="font-size: 18pt; font-weight: bold;">{{ $records->count() }}</div>
                </div>
            </td>
            <td width="34%">
                <div class="card card-blue">
                    <div style="font-size: 7pt; font-weight: bold;">RATA-RATA SKOR</div>
                    <div style="font-size: 18pt; font-weight: bold;">{{ number_format($records->avg('ikm_score'), 2) }}</div>
                </div>
            </td>
            <td width="33%">
                <div class="card">
                    <div style="font-size: 7pt; color: #195de6; font-weight: bold;">INDEKS PELAYANAN</div>
                    <div style="font-size: 18pt; font-weight: bold;">
                        @php
                            $average = $records->avg('ikm_score') ?? 0;
                            // Deteksi otomatis: jika rata-rata > 5, maka pakai skala 100, jika tidak pakai skala 5
                            $divider = ($average > 5) ? 100 : 5;
                            $indeks = ($average / $divider) * 100;
                        @endphp
                        {{ number_format($indeks, 1) }}%
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <div class="analysis-box">
        <p class="font-bold" style="font-size: 9pt; margin: 0 0 5px 0;">Analisis Strategis</p>
        <p style="font-size: 9pt; color: #475569; text-align: justify; margin: 0;">
            Berdasarkan data survei digital (e-Feedback) yang dikumpulkan, kualitas pelayanan pada Inspektorat Kabupaten Rembang menunjukkan tingkat kepuasan yang stabil dengan capaian indeks {{ number_format($indeks, 1) }}%. Mayoritas responden memberikan penilaian positif yang mencerminkan profesionalisme petugas.
        </p>
    </div>

    <h3 style="margin-bottom: 10px; border-left: 4px solid #195de6; padding-left: 10px;">Detail Data Survei</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th width="8%">NO</th>
                <th width="42%">OPD / EMAIL</th>
                <th width="35%">JENIS LAYANAN</th>
                <th width="15%">SKOR</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($records as $index => $record)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <div class="font-bold">{{ $record->opd }}</div>
                    <div style="font-size: 7pt; color: #64748b;">{{ $record->email }}</div>
                </td>
                <td>{{ $record->jenis_layanan }}</td>
                <td class="text-center font-bold">{{ number_format($record->ikm_score, 1) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer-sign">
        <p style="margin-bottom: 50px; font-size: 9pt;">Rembang, {{ date('d F Y') }}<br>Inspektur Kabupaten Rembang,</p>
        <p class="font-bold" style="border-bottom: 1px solid #000; display: inline-block; padding: 0 5px;">NAMA PEJABAT PENGAMPU</p>
        <p style="margin-top: 5px; font-size: 9pt;">NIP. 19780512 200501 1 002</p>
    </div>
</div>

</body>
</html>