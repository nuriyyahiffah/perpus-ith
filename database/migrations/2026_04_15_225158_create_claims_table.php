<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            // Relasi ke User (Kaprodi)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Relasi ke tabel Buku (Pastikan nama tabel di database kamu 'buku' atau 'bukus')
            $table->foreignId('buku_id')->constrained('buku')->onDelete('cascade');
            
            $table->string('prodi');
            $table->string('no_induk_prodi')->nullable();
            
            // Default langsung 'disetujui' sesuai permintaan bimbingan
            $table->enum('status', ['diajukan', 'disetujui', 'ditolak'])->default('disetujui');
            
            $table->text('catatan_dosen')->nullable();
            $table->timestamps();

            // Penting: Agar tidak ada klaim ganda untuk buku yang sama di prodi yang sama
            $table->unique(['buku_id', 'prodi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};