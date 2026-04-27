<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('buku', function (Blueprint $table) {
            // Kita tambah kolom prodi setelah kolom judul
            // Dibuat nullable agar buku umum yang belum diklaim tidak error
            $table->string('prodi')->nullable()->after('judul');
        });
    }

    public function down(): void
    {
        Schema::table('buku', function (Blueprint $table) {
            $table->dropColumn('prodi');
        });
    }
};