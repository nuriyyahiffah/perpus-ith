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
        Schema::table('peminjaman', function (Blueprint $table) {
        $table->boolean('wa_notif_return_sent')->default(false);
        $table->timestamp('wa_notif_return_sent_at')->nullable();            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('peminjaman', function (Blueprint $table) {
        $table->dropColumn(['wa_notif_return_sent', 'wa_notif_return_sent_at']);            //
        });
    }
};
