<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Menambahkan data row baru ke dalam tabel settings
        DB::table('settings')->insert([
            'key' => 'notif_borrow',
            'value' => '1',
            'category' => 'umum',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        // Menghapus data jika migrasi di-rollback
        DB::table('settings')->where('key', 'notif_borrow')->delete();
    }
};