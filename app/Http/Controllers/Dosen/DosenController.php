<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Claim; // Pastikan Model Claim sudah ada
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

        // Mengambil data buku yang diklaim oleh prodi dosen tersebut
        // Menggunakan with('buku') agar gambar dan detail buku terbawa (Eager Loading)
        $bukuSaya = Claim::with('buku')
            ->where('prodi', $user->prodi)
            ->latest()
            ->get();

            // 2. MENGAMBIL KOLEKSI TERBARU (Data yang tadi hilang di view)
        // Kita ambil 4-8 buku terbaru yang ada di database secara umum
        $bukuTerbaru = Buku::latest()
            ->take(4) 
            ->get();

        // 3. Buku Populer (Berdasarkan Frekuensi Peminjaman)
        $bukuPopuler = Buku::select('buku.*')
            ->join('peminjaman', 'buku.id', '=', 'peminjaman.buku_id')
            ->groupBy('buku.id')
            ->orderByRaw('COUNT(peminjaman.id) DESC')
            ->take(6)
            ->get();

        // Mengirim data ke view dosen.beranda
        return view('dosen.beranda', compact('bukuSaya', 'bukuTerbaru', 'bukuPopuler'));
    }

    /**
     * Menampilkan Detail Buku untuk Dosen
     */
    public function showBuku($id)
    {
        // 1. Cari data buku berdasarkan ID, jika tidak ada akan muncul error 404
        $buku = Buku::findOrFail($id);
        
        // 2. Cari data klaim yang spesifik untuk buku ini di Prodi Dosen tersebut
        // Ini untuk menampilkan label 'Mata Kuliah' di halaman detail
        $claim = Claim::where('id_buku', $id)
                    ->where('prodi', Auth::user()->prodi)
                    ->first();

        // 3. Kirim ke view dosen.show (Sesuai struktur folder yang kamu minta)
        return view('dosen.show', compact('buku', 'claim'));
    }
}