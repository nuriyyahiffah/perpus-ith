<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Klasifikasi extends Model
{
    use HasFactory;

    // Menegaskan nama tabel jika tidak menggunakan jamak bahasa inggris baku
    protected $table = 'klasifikasi'; 

    protected $fillable = [
        'kode_klass',
        'nama_klass',
        'warna'
    ];

    /**
     * Hubungan Relasi (Optional): 
     * Satu klasifikasi DDC bisa dimiliki oleh banyak data Buku
     */
    public function bukus()
    {
        // Sesuaikan dengan foreign key di tabel buku Anda, misalnya 'klasifikasi' atau 'klasifikasi_id'
        return $this->hasMany(Buku::class, 'klasifikasi', 'kode_klass'); 
    }
}