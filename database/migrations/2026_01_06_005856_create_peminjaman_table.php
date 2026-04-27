<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    // Ubah 'peminjamans' menjadi 'peminjaman'
    Schema::create('peminjaman', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

        // PASTIKAN: Jika di HeidiSQL namamu 'buku', maka isi 'buku'
        // Jika di HeidiSQL namamu 'bukus', maka isi 'bukus'
        $table->foreignId('buku_id')->constrained('buku')->onDelete('cascade');

        $table->date('tgl_pinjam')->nullable();
        $table->date('tgl_kembali')->nullable();
        $table->string('status')->default('pending');
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('peminjaman');
}

};
