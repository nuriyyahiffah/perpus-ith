<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom bibliografis untuk standar SIPUSTAKA
     */
    public function up(): void
    {
        Schema::table('buku', function (Blueprint $table) {
            // Menambahkan kolom setelah kolom 'penerbit' agar urutannya rapi
            $table->string('isbn')->nullable()->after('penerbit');
            $table->string('klasifikasi')->nullable()->after('isbn');
            
            // no_panggil dibuat unik agar tidak ada duplikasi kode rak
            $table->string('no_panggil')->nullable()->unique()->after('klasifikasi');
            
            $table->string('tempat_terbit')->nullable()->after('tahun_terbit');
            $table->string('jumlah_halaman')->nullable()->after('tempat_terbit');
            $table->string('bahasa')->default('Indonesia')->after('jumlah_halaman');
            $table->string('bentuk_karya')->nullable()->after('bahasa');
            $table->text('catatan')->nullable()->after('bentuk_karya');
        });
    }

    /**
     * Reverse the migrations.
     * Menghapus kolom jika migration di-rollback
     */
    public function down(): void
    {
        Schema::table('buku', function (Blueprint $table) {
            $table->dropColumn([
                'isbn', 
                'klasifikasi', 
                'no_panggil', 
                'tempat_terbit', 
                'jumlah_halaman', 
                'bahasa', 
                'bentuk_karya', 
                'catatan'
            ]);
        });
    }
};