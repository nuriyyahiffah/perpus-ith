<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Claim;
use App\Models\Peminjaman; // Tambahkan ini
// use App\Models\Reservasi; // Buka komentar ini jika sudah ada Model Reservasi
use Illuminate\Support\Facades\Auth;

class DosenController extends Controller
{
    /**
     * Menampilkan Beranda Dosen
     */
    public function index()
{
    /** @var \App\Models\User $user */
    $user = Auth::user();

    // 1. Mengambil data buku yang diklaim oleh prodi
    $bukuSaya = Claim::with('buku')
        ->where('prodi', $user->prodi)
        ->latest()
        ->get();

    // 2. Mengambil koleksi terbaru
    $bukuTerbaru = Buku::latest()
        ->take(4) 
        ->get();

    // 3. Fix Buku Populer (Langsung join ke tabel peminjaman)
$bukuPopuler = Buku::select('buku.*')
    ->join('peminjaman', 'buku.id', '=', 'peminjaman.buku_id') 
    ->groupBy('buku.id')
    ->orderByRaw('COUNT(peminjaman.id) DESC')
    ->take(6)
    ->get();

// 4. Fix Riwayat Pinjam (Langsung ambil relasi buku)
$riwayatPinjam = Peminjaman::where('user_id', $user->id)
    ->with('buku') // Pastikan di model Peminjaman sudah ada public function buku()
    ->latest()
    ->take(10)
    ->get();

    // 5. Antrean Reservasi (Array kosong agar tidak error variable undefined)
    $antreanReservasi = []; 

    return view('dosen.beranda', compact(
        'bukuSaya', 
        'bukuTerbaru', 
        'bukuPopuler', 
        'riwayatPinjam', 
        'antreanReservasi'
    ));
}

    /**
     * Menampilkan Detail Buku untuk Dosen
     */
    public function showBuku($id)
    {
        $buku = Buku::findOrFail($id);
        $claim = Claim::where('id_buku', $id)
                    ->where('prodi', Auth::user()->prodi)
                    ->first();

        return view('dosen.show', compact('buku', 'claim'));
    }
}