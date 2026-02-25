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

            // --- Identitas Responden & Lokasi ---
            $table->string('email')->index(); 
            $table->string('opd');
            $table->string('lokasi_survey')
                ->index()
                ->comment('Luar Inspektorat / Dalam Inspektorat');

            // --- Atribut Survey ---
            $table->string('irban')->index(); 
            $table->string('jenis_layanan');
            $table->date('tanggal')->index();
            $table->text('suggestions')->nullable();

            // --- Skor & Hasil Analisis ---
            $table->unsignedInteger('total_score')->default(0);
            $table->decimal('ikm_score', 5, 2)->default(0); 
            $table->string('category')->nullable()->index();

            // --- Optimization Indexes ---
            // Index komposit sangat bagus untuk performa query WHERE multi-kolom
            $table->index(['email', 'tanggal']);
            $table->index(['irban', 'lokasi_survey']);

            // --- System Columns ---
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
