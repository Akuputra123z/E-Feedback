<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [

            // =========================
            // MATERI
            // =========================
            [
                'dimension' => 'Materi',
                'text' => 'Sejauh mana materi yang disampaikan dalam konsultasi dan asistensi memenuhi kebutuhan saudara ?',
            ],
            [
                'dimension' => 'Materi',
                'text' => 'Apakah materi konsultasi dan asistensi jelas dan mudah dimengerti ?',
            ],
            [
                'dimension' => 'Materi',
                'text' => 'Bagaimana relevansi materi konsultasi dan asistensi dengan permasalahan atau kebutuhan saudara ?',
            ],
            [
                'dimension' => 'Materi',
                'text' => 'Sejauh mana materi konsultasi dan asistensi membantu saudara memahami isu atau masalah dengan baik ?',
            ],
            [
                'dimension' => 'Materi',
                'text' => 'Apakah materi yang diberikan membantu saudara dalam mengambil keputusan atau langkah selanjutnya ?',
            ],

            // =========================
            // STANDAR
            // =========================
            [
                'dimension' => 'Standar',
                'text' => 'Apakah mekanisme penanganan keluhan atau masukan terhadap layanan konsultasi dan asistensi sudah berjalan baik ?',
            ],
            [
                'dimension' => 'Standar',
                'text' => 'Apakah durasi sesi konsultasi yang diberikan efisien dan tidak membuang waktu entitas ?',
            ],
            [
                'dimension' => 'Standar',
                'text' => 'Apakah daftar persyaratan (dokumen atau data) yang dibutuhkan untuk konsultasi/asistensi telah diinformasikan secara jelas dan lengkap di awal ?',
            ],
            [
                'dimension' => 'Standar',
                'text' => 'Bagaimana tingkat kepuasan entitas terhadap tata cara pelaksanaan konsultasi dan asistensi yang diterapkan ?',
            ],
            [
                'dimension' => 'Standar',
                'text' => 'Apakah mekanisme pelayanan sudah sesuai dengan standar yang ditetapkan ?',
            ],

            // =========================
            // SDM
            // =========================
            [
                'dimension' => 'SDM',
                'text' => 'Apakah petugas menunjukkan kepedulian dan kemauan untuk memahami kebutuhan spesifik entitas ?',
            ],
            [
                'dimension' => 'SDM',
                'text' => 'Sejauh mana saudara menilai pengetahuan petugas dalam memberikan layanan konsultasi dan asistensi ?',
            ],
            [
                'dimension' => 'SDM',
                'text' => 'Bagaimana penilaian saudara terhadap responsivitas dan kemampuan petugas dalam menjawab pertanyaan selama konsultasi ?',
            ],
            [
                'dimension' => 'SDM',
                'text' => 'Bagaimana penilaian saudara terhadap sikap profesionalisme dan etika petugas dalam pelaksanaan konsultasi dan asistensi ?',
            ],
            [
                'dimension' => 'SDM',
                'text' => 'Apakah petugas menyampaikan informasi dan penjelasan dengan cara yang mudah dipahami dan jelas ?',
            ],

            // =========================
            // DUKUNGAN
            // =========================
            [
                'dimension' => 'Dukungan',
                'text' => 'Apakah ruang nyaman dan memadai (jika dilakukan tatap muka) untuk dilaksanakannya konsultasi atau diskusi ?',
            ],
            [
                'dimension' => 'Dukungan',
                'text' => 'Bagaimana penilaian saudara terhadap ketersediaan fasilitas pendukung di lokasi (parkir, toilet, mushola, ruang laktasi, ramah disabilitas, area tunggu) ?',
            ],
            [
                'dimension' => 'Dukungan',
                'text' => 'Sejauh mana penilaian saudara terhadap layanan koneksi internet yang digunakan dalam proses konsultasi/asistensi ?',
            ],
            [
                'dimension' => 'Dukungan',
                'text' => 'Bagaimana penilaian saudara terhadap peralatan atau perangkat keras (proyektor, komputer, scanner, audio video) yang digunakan selama konsultasi/asistensi ?',
            ],
            [
                'dimension' => 'Dukungan',
                'text' => 'Bagaimana penilaian saudara terhadap kepuasan secara keseluruhan layanan Inspektorat Daerah ?',
            ],
        ];

        foreach ($questions as $index => $question) {
            Question::updateOrCreate(
                ['text' => $question['text']],
                [
                    'dimension' => $question['dimension'],
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]
            );
        }
    }
}
