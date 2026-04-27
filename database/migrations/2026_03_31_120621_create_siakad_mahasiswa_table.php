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
        Schema::create('siakad_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->string('nim')->unique(); // NIM sebagai kunci unik
            $table->string('nama');
            $table->string('email')->unique(); // Email kampus
            $table->string('prodi')->default('Ilmu Komputer'); // Default prodi sesuai data ITH
            $table->string('angkatan')->nullable();
            $table->timestamps(); // Mencatat waktu data dibuat/diubah
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siakad_mahasiswa');
    }
};
