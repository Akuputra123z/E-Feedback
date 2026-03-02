<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">

    <style>
        /* ==========================
           PAGE SETUP
        ========================== */
        @page {
            size: A4 portrait;
            margin: 1.5cm;
        }

        body {
            margin: 0;
            font-family: Helvetica, Arial, sans-serif;
            font-size: 10pt;
            color: #334155;
            line-height: 1.5;
        }

        .page-break {
            page-break-after: always;
        }

        /* ==========================
           UTILITIES
        ========================== */
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .text-muted { color: #64748b; }

        /* ==========================
           COVER
        ========================== */
        .cover {
            text-align: center;
            padding-top: 60px;
        }

        .cover-divider {
            height: 4px;
            width: 60px;
            background: #195de6;
            margin: 25px auto;
        }

        /* ==========================
           CARD SUMMARY
        ========================== */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .summary-table td {
            padding: 6px;
        }

        .card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }

        .card-primary {
            background: #195de6;
            color: #fff;
            border: none;
        }

        .card-label {
            font-size: 8pt;
            margin-bottom: 5px;
        }

        .card-value {
            font-size: 18pt;
            font-weight: bold;
        }

        /* ==========================
           ANALYSIS
        ========================== */
        .analysis {
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 30px;
        }

        /* ==========================
           TABLE DATA
        ========================== */
        .table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .table th {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            padding: 8px;
            font-size: 8pt;
            text-transform: uppercase;
            color: #64748b;
        }

        .table td {
            border: 1px solid #e2e8f0;
            padding: 8px;
            font-size: 9pt;
            vertical-align: top;
            word-break: break-word;
        }

        /* ==========================
           SIGNATURE
        ========================== */
        .signature {
            width: 320px;
            margin-left: 55%;
            margin-top: 60px;
            text-align: center;
            page-break-inside: avoid;
        }

        .signature-space {
            margin-bottom: 55px;
        }

        .signature-name {
            border-bottom: 1px solid #000;
            display: inline-block;
            padding-bottom: 2px;
            font-weight: bold;
            white-space: nowrap;
        }

        .signature-nip {
            margin-top: 2px;
            font-size: 9pt;
        }

        /* ==========================
           SECTION TITLE
        ========================== */
        .section-title {
            border-left: 4px solid #195de6;
            padding-left: 10px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

    <!-- ==========================
         COVER PAGE
    ========================== -->
    <div class="cover page-break">
        <img src="{{ public_path('img/logo.webp') }}" width="80">

        <p class="text-muted" style="margin-top:15px; letter-spacing:2px;">
            PEMERINTAH KABUPATEN REMBANG
        </p>

        <h1 style="margin:5px 0; text-transform:uppercase;">
            Inspektorat Daerah
        </h1>

        <div style="margin:150px 0;">
            <div class="cover-divider"></div>

            <h2 style="font-size:26pt; margin:0;">
                LAPORAN HASIL SURVEI<br>
                KEPUASAN MASYARAKAT
            </h2>

            <p style="font-size:16pt; color:#195de6; font-weight:bold; margin-top:15px;">
                (e-Feedback)
            </p>

            <p class="text-muted" style="margin-top:35px;">
                Tahun {{ date('Y') }}
            </p>
        </div>
    </div>

    <!-- ==========================
         CONTENT PAGE
    ========================== -->
    <div>

        <h3 class="section-title">Ringkasan Eksekutif</h3>

        <table class="summary-table">
            <tr>
                <td width="33%">
                    <div class="card">
                        <div class="card-label text-bold">TOTAL RESPONDEN</div>
                        <div class="card-value">{{ $records->count() }}</div>
                    </div>
                </td>

                <td width="34%">
                    <div class="card card-primary">
                        <div class="card-label text-bold">RATA-RATA SKOR</div>
                        <div class="card-value">
                            {{ number_format($records->avg('ikm_score'), 2) }}
                        </div>
                    </div>
                </td>

                <td width="33%">
                    @php
                        $average = $records->avg('ikm_score') ?? 0;
                        $divider = ($average > 5) ? 100 : 5;
                        $indeks = ($average / $divider) * 100;
                    @endphp

                    <div class="card">
                        <div class="card-label text-bold">INDEKS PELAYANAN</div>
                        <div class="card-value">
                            {{ number_format($indeks, 1) }}%
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="analysis">
            <p class="text-bold" style="margin:0 0 5px 0;">
                Analisis Strategis
            </p>
            <p style="margin:0; text-align:justify;">
                Berdasarkan data survei digital (e-Feedback), kualitas pelayanan
                Inspektorat Kabupaten Rembang menunjukkan tingkat kepuasan
                dengan capaian indeks {{ number_format($indeks, 1) }}%.
            </p>
        </div>

        <h3 class="section-title">Detail Data Survei</h3>

        <table class="table">
            <thead>
                <tr>
                    <th width="8%">No</th>
                    <th width="42%">OPD / Email</th>
                    <th width="35%">Jenis Layanan</th>
                    <th width="15%">Skor</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($records as $index => $record)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <div class="text-bold">{{ $record->opd }}</div>
                        <div class="text-muted" style="font-size:8pt;">
                            {{ $record->email }}
                        </div>
                    </td>
                    <td>{{ $record->jenis_layanan }}</td>
                    <td class="text-center text-bold">
                        {{ number_format($record->ikm_score, 1) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- ==========================
             SIGNATURE
        ========================== -->
        <div class="signature">
            <p class="signature-space">
                Rembang, {{ date('d F Y') }}<br>
                Inspektur Kabupaten Rembang,
            </p>

            <p class="signature-name">
                {{ strtoupper('Imung Tri Wijayanti, S.P., M.T., M.A., CGCAE.') }}
            </p>

            <p class="signature-nip">
                NIP. 197411281999032003
            </p>
        </div>

    </div>

</body>
</html>