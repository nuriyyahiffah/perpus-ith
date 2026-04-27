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
        // Tambahkan ini
        $table->text('sinopsis')->nullable()->after('judul');
    });
}

public function down(): void
{
    Schema::table('buku', function (Blueprint $table) {
        // Dan ini untuk jaga-jaga jika ingin rollback
        $table->dropColumn('sinopsis');
    });
}
};
