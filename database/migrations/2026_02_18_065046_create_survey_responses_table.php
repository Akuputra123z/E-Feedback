<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('survey_responses', function (Blueprint $table) {
            $table->id();

            // Identitas Responden
            $table->string('email')->index();
            $table->string('opd');
            $table->string('irban')->index(); 
            $table->string('jenis_layanan');
            $table->date('tanggal')->index();

            // Skor
            $table->unsignedInteger('total_score')->default(0); // skor mentah
            $table->decimal('ikm_score', 5, 2)->default(0); // hasil 0–100
            $table->string('category')->nullable()->index();

            // Mencegah duplikasi pengisian di hari yang sama (opsional)
            $table->unique(['email', 'tanggal']);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_responses');
    }
};
