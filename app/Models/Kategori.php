<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    // Beritahu Laravel nama tabelnya
    protected $table = 'kategori';

    // Kolom apa saja yang boleh diisi (mass assignment)
    protected $fillable = ['nama_kategori'];

    // Relasi: Satu kategori bisa memiliki banyak buku
    public function buku()
    {
        return $this->hasMany(Buku::class);
    }
}
