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
        Schema::table('buku', function (Blueprint $table) {
            // Menghapus kolom catatan dari tabel buku
            if (Schema::hasColumn('buku', 'catatan')) {
                $table->dropColumn('catatan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buku', function (Blueprint $table) {
            // Menambahkan kembali kolom catatan jika migration dibatalkan
            $table->text('catatan')->nullable()->after('sinopsis');
        });
    }
};