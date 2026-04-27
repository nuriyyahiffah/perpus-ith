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
    Schema::table('users', function (Blueprint $table) {
        // Hanya buat jika belum ada
        if (!Schema::hasColumn('users', 'nomor_identitas')) {
            $table->string('nomor_identitas')->nullable()->after('email');
        }

        if (!Schema::hasColumn('users', 'prodi')) {
            $table->string('prodi')->nullable()->after('nomor_identitas');
        }
    });
}
};
