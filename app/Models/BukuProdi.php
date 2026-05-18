<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuProdi extends Model
{
    use HasFactory;

    // 1. Tegaskan nama tabel karena tidak menggunakan jamak bahasa Inggris (buku_prodis)
    protected $table = 'buku_prodi';

    // 2. Daftarkan kolom yang diizinkan untuk menyimpan data secara massal (Mass Assignment)
    protected $fillable = [
        'buku_id',
        'nama_prodi',
    ];

    /**
     * Relasi ke tabel induk (Buku)
     * Mengizinkan model BukuProdi untuk tahu data lengkap buku yang bersangkutan
     */
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }
}
