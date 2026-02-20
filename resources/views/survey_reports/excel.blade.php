@php
    // Pastikan $records sudah tersedia (Collection dari SurveyResponse)
    // Ambil semua pertanyaan unik dari seluruh records
    $allQuestions = collect($records)
        ->flatMap(fn($record) => $record->answers) // ambil semua answers
        ->pluck('question.text')                   // ambil teks pertanyaan
        ->unique()                                 // hanya pertanyaan unik
        ->values();                                // reset index
@endphp

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Email</th>
            <th>OPD</th>
            <th>Jenis Layanan</th>
            <th>Tanggal</th>
            <th>Total Skor</th>
            <th>IKM</th>
            <th>Kategori</th>
            @foreach ($allQuestions as $question)
                <th>{{ $question }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($records as $record)
            <tr>
                <td>{{ $record->email }}</td>
                <td>{{ $record->opd }}</td>
                <td>{{ $record->jenis_layanan }}</td>
                <td>{{ $record->tanggal->format('Y-m-d') }}</td>
                <td>{{ $record->total_score }}</td>
                <td>{{ number_format($record->ikm_score, 2) }}</td>
                <td>{{ $record->category }}</td>

                @foreach ($allQuestions as $question)
                    @php
                        $answer = $record->answers
                            ->firstWhere('question.text', $question);
                    @endphp
                    <td>{{ $answer->answer ?? '-' }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
