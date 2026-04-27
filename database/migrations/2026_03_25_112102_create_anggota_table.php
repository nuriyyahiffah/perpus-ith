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
    Schema::create('anggota', function (Blueprint $table) {
        $table->id();
        // Relasi ke tabel users yang sudah ada kategori_anggota_id-nya
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

        // Data unik untuk skripsi: Nomor Anggota & Masa Berlaku
        $table->string('nomor_anggota')->unique();
        $table->date('tgl_daftar');
        $table->date('tgl_kadaluarsa');
        $table->enum('status', ['aktif', 'nonaktif', 'suspen'])->default('nonaktif');
        $table->timestamps();
    });
}
};
