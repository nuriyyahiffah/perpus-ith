<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Peminjaman extends Model
{
    use HasFactory;

    // Beritahu Laravel nama tabel yang benar
    protected $table = 'peminjaman';

    // Sesuaikan fillable dengan database dan controller
    protected $fillable = [
        'user_id',
        'buku_id', // 
        'eksemplar_id', // Tambahkan ini
        'no_induk',
        'tgl_pinjam',
        'tgl_kembali',  // Gunakan tgl_kembali agar konsisten
        'kondisi_kembali',
        'denda_fisik',
        'catatan_kondisi',
        'status'
    ];

    // Relasi ke User
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function buku()
{
    // Sesuaikan 'buku_id' dengan nama kolom foreign key di tabel peminjaman kamu
    return $this->belongsTo(Buku::class, 'buku_id');
}

    // Relasi ke Eksemplar (Buku Fisik)
    public function eksemplar() {
        return $this->belongsTo(Eksemplar::class, 'eksemplar_id');
    }
}