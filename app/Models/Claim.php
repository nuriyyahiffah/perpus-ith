<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database (pastikan jamak/plural sesuai migrasi)
     */
    protected $table = 'claims';

    /**
     * Kolom yang boleh diisi secara massal.
     * Menggunakan fillable lebih aman daripada guarded kosong.
     */
    protected $fillable = [
        'user_id',
        'buku_id',
        'prodi',
        'no_induk_prodi',
        'status',
        'mata_kuliah'
    ];

    /**
     * Relasi ke User (Dosen yang melakukan klaim)
     * Dosen memilik banyak klaim, satu klaim dimiliki satu dosen.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Buku yang diklaim
     * Satu klaim merujuk pada satu buku tertentu.
     */
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    /**
     * Scope untuk memudahkan filter status di Controller nantinya
     * Contoh penggunaan: Claim::status('diajukan')->get();
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}