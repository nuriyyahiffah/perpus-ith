<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel users (mahasiswa/dosen)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->string('judul'); // Sebelumnya 'title'
            $table->text('pesan'); // Sebelumnya 'message'
            
            // Kategori notifikasi: info, peringatan, sukses, bahaya
            $table->string('tipe')->default('info'); 
            
            // Ikon menggunakan Bootstrap Icons
            $table->string('ikon')->default('bi-bell'); 
            
            // Status apakah sudah dibaca atau belum
            $table->boolean('sudah_dibaca')->default(false); 
            
            // Tautan/URL jika notifikasi diklik
            $table->string('url_aksi')->nullable(); 
            
            $table->timestamps();
            
            // Index untuk mempercepat pencarian data
            $table->index('user_id');
            $table->index('sudah_dibaca');
        });
    }

    /**
     * Batalkan migrasi (hapus tabel).
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};