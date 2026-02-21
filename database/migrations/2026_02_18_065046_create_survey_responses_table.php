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
            // Hapus unique, tambahkan nullable jika email tidak wajib
            $table->string('email')->index(); 
            $table->string('opd');
            
            // Sesuaikan dengan data irban (irban1, irban2, dst)
            $table->string('irban')->index(); 
            $table->string('jenis_layanan');
            $table->date('tanggal')->index();

            // Skor
            $table->unsignedInteger('total_score')->default(0);
            $table->decimal('ikm_score', 5, 2)->default(0); 
            $table->string('category')->nullable()->index();

            // Gunakan Index biasa saja untuk performa pencarian, bukan Unique
            $table->index(['email', 'tanggal']);

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
