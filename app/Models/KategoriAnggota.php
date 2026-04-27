<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriAnggota extends Model
{
    protected $fillable = ['nama_kategori', 'maksimal_pinjam', 'durasi_pinjam'];
}
