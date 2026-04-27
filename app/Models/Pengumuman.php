<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    // Tambahkan baris ini untuk "membuka kunci" laci database kamu
    protected $fillable = ['judul', 'gambar', 'is_active'];

    // Pastikan nama tabelnya benar jika kamu tidak pakai nama jamak
    protected $table = 'pengumuman';
}
