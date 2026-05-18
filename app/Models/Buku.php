<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit karena namanya 'buku' (bukan jamak 'bukus')
    protected $table = 'buku';

    protected $fillable = [
        'judul',
        'isbn',
        'kode_buku',
        'no_induk',
        'penulis',
        'sinopsis',
        'jumlah_halaman', // PERBAIKAN: Duplikasi baris jumlah_halaman yang di bawah sudah dihapus
        'tipe_pengarang_utama',
        'peran_tambahan',
        'pengarang_tambahan',
        'klasifikasi',
        'no_panggil',
        'penerbit',
        'tahun_terbit',
        'tempat_terbit',
        'bahasa',
        'kategori_id',
        'gambar_buku',
        'stok',
        'prodi'
    ];

    /**
     * Relasi ke model Kategori
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    /**
     * Relasi ke model Claim
     */
    public function claims()
    {
        return $this->hasMany(Claim::class, 'buku_id');
    }

    /**
     * Relasi ke model Eksemplar
     */
    public function eksemplars()
    {
        return $this->hasMany(Eksemplar::class, 'buku_id');
    }

    /**
     * Relasi ke model Peminjaman
     */
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'buku_id');
    }

    /**
     * Relasi ke tabel jembatan buku_prodi
     * PERBAIKAN: Kita satukan fungsionalitasnya di sini agar seragam dengan KatalogController
     * dan mendukung pemanggilan via prodiRekomendasi jika ada view lain yang membutuhkannya.
     */
    public function bukuProdi()
    {
        return $this->hasMany(BukuProdi::class, 'buku_id', 'id');
    }

    /**
     * Alias relasi untuk keamanan kompabilitas kode lama Anda
     */
    public function prodiRekomendasi()
    {
        return $this->hasMany(BukuProdi::class, 'buku_id', 'id');
    }
}
