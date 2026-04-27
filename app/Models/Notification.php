<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'judul',        // Mengganti 'title'
        'pesan',        // Mengganti 'message'
        'tipe',         // Mengganti 'type'
        'ikon',         // Mengganti 'icon'
        'sudah_dibaca', // Mengganti 'read'
        'url_aksi',     // Mengganti 'action_url'
    ];

    protected $casts = [
        'sudah_dibaca' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope untuk notifikasi yang belum dibaca
     */
    public function scopeBelumDibaca($query)
    {
        return $query->where('sudah_dibaca', false);
    }

    /**
     * Scope untuk notifikasi dari user tertentu
     */
    public function scopeUntukUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Menandai notifikasi sebagai sudah dibaca
     */
    public function markAsRead()
    {
        $this->update(['sudah_dibaca' => true]);
        return $this;
    }

    /**
     * Mendapatkan class warna berdasarkan tipe
     */
    public function getColorClass()
    {
        return match($this->tipe) { // Menggunakan $this->tipe
            'success' => 'bg-emerald-50 border-emerald-200 text-emerald-700',
            'warning' => 'bg-amber-50 border-amber-200 text-amber-700',
            'danger' => 'bg-rose-50 border-rose-200 text-rose-700',
            'info' => 'bg-blue-50 border-blue-200 text-blue-700',
            default => 'bg-slate-50 border-slate-200 text-slate-700',
        };
    }

    /**
     * Mendapatkan warna badge berdasarkan tipe
     */
    public function getBadgeColor()
    {
        return match($this->tipe) { // Menggunakan $this->tipe
            'success' => 'bg-emerald-500',
            'warning' => 'bg-amber-500',
            'danger' => 'bg-rose-500',
            'info' => 'bg-blue-500',
            default => 'bg-slate-500',
        };
    }
}