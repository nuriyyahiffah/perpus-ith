<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('buku', function (Blueprint $table) {
            // Menghapus kolom file_pdf
            $table->dropColumn('file_pdf');
        });
    }

    public function down(): void
    {
        Schema::table('buku', function (Blueprint $table) {
            // Jika ingin dibatalkan (rollback), kolom akan muncul lagi
            $table->string('file_pdf')->nullable();
        });
    }
};
