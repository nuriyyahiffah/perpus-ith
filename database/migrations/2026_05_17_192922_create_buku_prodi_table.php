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
       Schema::create('buku_prodi', function (Blueprint $table) {
    $table->id();
    $table->foreignId('buku_id')->constrained('buku')->onDelete('cascade');
    $table->string('nama_prodi'); // Menyimpan nama prodi yang mencentang (e.g. 'Ilmu Komputer')
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_prodi');
    }
};
