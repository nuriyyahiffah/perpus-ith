<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsulanBuku extends Model

{
    protected $fillable = ['user_id', 'judul', 'penulis', 'tahun', 'alasan', 'status'];

    // Relasi balik ke User (Dosen)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
