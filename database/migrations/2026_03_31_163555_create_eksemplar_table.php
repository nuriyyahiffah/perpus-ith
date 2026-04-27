<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Membuat tabel eksemplar untuk mencatat fisik buku individu
     */
    public function up(): void
    {
        Schema::create('eksemplar', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel buku (Foreign Key)
            // Menggunakan onDelete('cascade') agar jika buku dihapus, eksemplarnya ikut terhapus
            $table->foreignId('buku_id')->constrained('buku')->onDelete('cascade');

            // Data Identitas Fisik
            $table->string('no_induk')->unique();    // Nomor unik inventaris
            $table->string('no_barcode')->nullable()->unique();
            $table->string('no_rfid')->nullable()->unique();

            // Status & Informasi Pengadaan
            $table->string('status')->default('Tersedia'); // Tersedia, Dipinjam, Hilang, dll
            $table->string('jenis_sumber')->nullable();    // Pembelian, Hibah, dll
            $table->string('bentuk_fisik')->default('Buku');
            $table->date('tgl_pengadaan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eksemplar');
    }
};
