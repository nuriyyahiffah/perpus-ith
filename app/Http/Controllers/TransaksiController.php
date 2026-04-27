<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Peminjaman; // Pastikan model ini sudah ada
use App\Models\Koleksi;    // Pastikan model ini sudah ada

class TransaksiController extends Controller
{
    // 1. Dashboard Mahasiswa
    public function index()
    {
        $user = Auth::user();
        return view('mahasiswa.dashboard', compact('user'));
    }

    // 2. Halaman Riwayat Peminjaman untuk MAHASISWA
    public function riwayatPeminjaman()
    {
        $user = Auth::user();

        // Mengambil data peminjaman milik user yang sedang login
        $peminjaman = Peminjaman::where('user_id', $user->id)
                        ->with('buku') // Eager loading agar tidak error saat panggil $item->buku->judul
                        ->orderBy('created_at', 'desc')
                        ->get();

        // PERBAIKAN: Nama file adalah 'riwayat', bukan 'riwayat_peminjaman'
        return view('transaksi.riwayat', compact('user', 'peminjaman'));
    }

    // 3. Halaman Riwayat Klaim untuk DOSEN
    public function riwayatKlaim()
    {
        $user = Auth::user();
        // Pastikan filenya ada di resources/views/transaksi/riwayat_klaim.blade.php
        return view('transaksi.riwayat_klaim', compact('user'));
    }

    // 4. Halaman Koleksi Buku untuk MAHASISWA
    public function koleksi()
    {
        $user = Auth::user();

        // Mengambil data koleksi/wishlist
        $koleksi = Koleksi::where('user_id', $user->id)
                    ->with('buku')
                    ->get();

        return view('transaksi.koleksi', compact('user', 'koleksi'));
    }
}
