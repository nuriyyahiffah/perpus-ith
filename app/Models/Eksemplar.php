<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eksemplar extends Model
{
    use HasFactory;

    protected $table = 'eksemplar';

    protected $fillable = [
        'buku_id',
        'no_induk',
        'no_barcode',
        'no_rfid',
        'status',
        'jenis_sumber',
        'bentuk_fisik',
        'tgl_pengadaan'
    ];

    protected $casts = [
        'tgl_pengadaan' => 'date',
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }
}