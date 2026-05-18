<?php

namespace App\Http\Controllers;

use App\Models\Klasifikasi; // Pastikan model sudah dibuat
use Illuminate\Http\Request;

class KlasifikasiController extends Controller
{
    public function index()
{
    $klasifikasi = Klasifikasi::orderBy('kode_klass', 'asc')->get();

    // Kirim variabel $klasifikasis ke folder view Anda
    return view('shared.klasifikasi.index', compact('klasifikasi'));
}
}