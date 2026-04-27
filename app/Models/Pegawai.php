<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'pegawais';

    // Properti fillable agar bisa simpan data secara massal
    protected $fillable = [
        'nip',
        'nama',
        'jabatan',
        'email',
        'telepon'
    ];
}