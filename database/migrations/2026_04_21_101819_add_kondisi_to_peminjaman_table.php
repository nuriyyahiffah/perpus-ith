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
        // Status kondisi saat buku dipulangkan
        $table->enum('kondisi_kembali', ['Baik', 'Rusak', 'Hilang'])->nullable();
        $table->text('catatan_kondisi')->nullable(); // Penjelasan kerusakan
        $table->decimal('denda_fisik', 10, 2)->default(0); // Nominal ganti rugi
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            //
        });
    }
};
