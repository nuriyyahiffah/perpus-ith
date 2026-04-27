<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Anggota extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     * Karena namamu 'anggota' (bukan 'anggotas'), baris ini WAJIB ada.
     */
    protected $table = 'anggota';

    /**
     * Atribut yang dapat diisi (Mass Assignment).
     */
    protected $fillable = [
        'user_id',
        'nomor_anggota',
        'tgl_daftar',
        'tgl_kadaluarsa',
        'status',
        'kategori_anggota_id' // Pastikan ini ada jika kamu menggunakan relasi kategori
    ];

    /**
     * Guarded biasanya tidak perlu diisi jika $fillable sudah ada.
     * Cukup gunakan salah satu agar tidak membingungkan.
     */
    protected $guarded = [];

    /**
     * Relasi ke Tabel User
     * Setiap record anggota harus memiliki satu user (Pemilik akun)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Kategori Anggota
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriAnggota::class, 'kategori_anggota_id');
    }

    /**
     * Scope untuk mempermudah filter anggota yang aktif
     * Cara pakai di controller: Anggota::aktif()->get();
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}
