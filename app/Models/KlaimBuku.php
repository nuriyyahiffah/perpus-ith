<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KlaimBuku extends Model
{
    // Tambahkan baris ini agar Laravel tidak mencari 'klaim_bukus'
    protected $table = 'klaim_buku';

    protected $fillable = ['user_id', 'buku_id', 'prodi', 'alasan', 'status'];

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }
}
