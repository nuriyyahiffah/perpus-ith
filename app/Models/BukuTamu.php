<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuTamu extends Model
{
    use HasFactory;

    // Menentukan nama tabel (opsional jika nama tabelnya 'buku_tamus')
    protected $table = 'buku_tamus';

    // Kolom yang boleh diisi (Mass Assignment)
    // Sesuaikan dengan input yang kita buat tadi
    protected $fillable = [
        'nama',
        'identitas',
        'status_pengunjung', // Mahasiswa, Dosen, Tendik, dll
        'instansi_prodi',
        'keperluan'
    ];
}