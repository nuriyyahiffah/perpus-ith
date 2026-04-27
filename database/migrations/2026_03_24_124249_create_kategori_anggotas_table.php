<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kita cek dulu agar tidak error jika kolom sudah ada
            if (!Schema::hasColumn('users', 'kategori_anggota_id')) {
                $table->foreignId('kategori_anggota_id')
                      ->nullable()
                      ->after('role')
                      ->constrained('kategori_anggotas')
                      ->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['kategori_anggota_id']);
            $table->dropColumn('kategori_anggota_id');
        });
    }
};
