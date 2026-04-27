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
        Schema::table('peminjaman', function (Blueprint $table) {
            // Menambahkan foreign key ke tabel eksemplar
            $table->unsignedBigInteger('eksemplar_id')->nullable()->after('buku_id');
            
            // Menambahkan kolom no_induk untuk histori fisik buku
            $table->string('no_induk')->nullable()->after('eksemplar_id');

            // Opsional: Buat foreign key constraint agar data konsisten
            $table->foreign('eksemplar_id')->references('id')->on('eksemplar')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            // Hapus constraint dan kolom jika rollback
            $table->dropForeign(['eksemplar_id']);
            $table->dropColumn(['eksemplar_id', 'no_induk']);
        });
    }
};