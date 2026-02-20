<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_answers', function (Blueprint $table) {
            $table->id();

            // Relasi ke header survei
            $table->foreignId('survey_response_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Relasi ke master pertanyaan
            $table->foreignId('question_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Jawaban skala 1–5
            $table->unsignedTinyInteger('answer');

            // Mencegah 1 pertanyaan dijawab 2x dalam 1 survey
            $table->unique(['survey_response_id', 'question_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_answers');
    }
};
