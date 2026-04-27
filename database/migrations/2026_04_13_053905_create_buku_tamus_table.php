<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('buku_tamus', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->string('identitas'); // Untuk NIM atau NIDN
        $table->string('status_pengunjung'); // Mahasiswa, Dosen, Tendik, Umum
        $table->string('instansi_prodi')->nullable(); // Contoh: Teknik Informatika
        $table->text('keperluan');
        $table->timestamps(); // Ini otomatis membuat created_at (waktu kunjung)
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_tamus');
    }
};
