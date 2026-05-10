<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'no_telp',
        'role',
        'prodi',
        'angkatan',
        'email_pribadi',
        'alamat',
        'nomor_identitas',
        'status_akun',
        'kategori_anggota_id',
        'is_suspended_until',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_suspended_until' => 'datetime',
        ];
    }

    /**
     * Relasi ke Tabel Kategori Anggota (Mahasiswa, Dosen, dll)
     */
    public function kategori(): BelongsTo
    {
        // Pastikan model KategoriAnggota sudah ada
        return $this->belongsTo(KategoriAnggota::class, 'kategori_anggota_id');
    }

    /**
     * Relasi ke Tabel Anggota (Detail Aktivasi Perpustakaan)
     */
    public function anggota(): HasOne
    {
        // Parameter kedua adalah foreign key di tabel anggotas
        return $this->hasOne(Anggota::class, 'user_id');
    }

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    /**
     * Helper untuk mengecek role admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isKaprodi()
{
    return $this->role === 'kaprodi';
}

public function isDosen()
{
    return $this->role === 'dosen';
}

    /**
     * Mutator WhatsApp API (Otomatis 08... ke 628...)
     * Berguna untuk integrasi notifikasi WA SIPUSTAKA
     */
   // Tambahkan di bagian atas jika belum ada

// Ganti fungsi noTelp yang lama dengan ini
protected function noTelp(): Attribute
{
    return Attribute::make(
        // Getter: Memastikan data no_telp bisa dibaca secara normal
        get: fn ($value) => $value,

        // Setter: Otomatis mengubah 08... menjadi 628... saat data disimpan/diupdate
        set: function ($value) {
            if (!$value) return null;

            $nomor = preg_replace('/[^0-9]/', '', $value);

            if (str_starts_with($nomor, '0')) {
                return '62' . substr($nomor, 1);
            } elseif (str_starts_with($nomor, '8')) {
                return '62' . $nomor;
            }

            return $nomor;
        },
    );
}
}
