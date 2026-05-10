<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit karena namannya 'buku' (bukan jamak 'bukus')
    protected $table = 'buku';

    protected $fillable = [
        'judul',
        'isbn',
        'kode_buku',
        'no_induk',
        'penulis',
        'sinopsis',
        'jumlah_halaman',
        'tipe_pengarang_utama', 
        'peran_tambahan',      
        'pengarang_tambahan',   
        'klasifikasi',
        'no_panggil',
        'penerbit',
        'tahun_terbit',
        'tempat_terbit',
        'jumlah_halaman',
        'bahasa',
        'kategori_id',
        'gambar_buku',
        'stok',
        'prodi'
    ];

    public function kategori()
{
    // Gunakan belongsTo karena satu buku biasanya memiliki satu kategori
    return $this->belongsTo(Kategori::class, 'kategori_id');
}

// app/Models/Buku.php

public function claims()
{
    // Relasi ke model Claim menggunakan foreign key 'buku_id'
    return $this->hasMany(Claim::class, 'buku_id');
}
    /**
     * Relasi ke model Eksemplar.
     * Nama fungsi diubah menjadi jamak (eksemplars) agar sesuai dengan 
     * standar Laravel 'Has Many' dan panggilan di file Blade.
     */
    public function eksemplars()
    {
        // Pastikan foreign key 'buku_id' sesuai dengan yang ada di tabel eksemplars
        return $this->hasMany(Eksemplar::class, 'buku_id');
    }

    public function peminjaman()
{
    return $this->hasMany(Peminjaman::class, 'buku_id');
}
}