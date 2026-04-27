<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            // Kita tambahkan kolom denda_fisik setelah kolom status
            // Default 0 supaya data lama tidak error
            if (!Schema::hasColumn('peminjaman', 'denda_fisik')) {
                $table->integer('denda_fisik')->default(0)->after('status');
            }
        });
    }

    public function down()
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn('denda_fisik');
        });
    }
};