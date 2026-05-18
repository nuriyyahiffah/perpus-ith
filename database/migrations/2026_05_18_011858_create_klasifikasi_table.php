<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('klasifikasi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_klass', 10)->unique(); // Menyimpan '000', '100', s/d '900'
            $table->string('nama_klass', 150);          // Menyimpan '000 - Karya Umum', dll
            $table->string('warna', 7)->default('#64748B'); // Menyimpan kode HEX warna seperti '#FF0000'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('klasifikasi');
    }
};