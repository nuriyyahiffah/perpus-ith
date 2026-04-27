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
        $table->string('tipe_pengarang_utama')->nullable()->after('penulis'); // Nama Orang/Badan/Pertemuan
        $table->string('peran_tambahan')->nullable()->after('tipe_pengarang_utama'); // Penyunting/Ilustrator
        $table->string('pengarang_tambahan')->nullable()->after('peran_tambahan'); // Nama orangnya            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buku', function (Blueprint $table) {
            //
        });
    }
};
