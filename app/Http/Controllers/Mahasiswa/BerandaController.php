<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Claim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BerandaController extends Controller
{
    /**
     * Menampilkan halaman beranda mahasiswa (Dashboard).
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $userId = Auth::id();

        // 1. Statistik Utama (Card Beranda)
        $sedangDipinjam = Peminjaman::where('user_id', $userId)
            ->where('status', 'dipinjam') // Case-insensitive safe
            ->with('buku')
            ->get();

        $totalPinjam = Peminjaman::where('user_id', $userId)->count();
        
        $terlambat = Peminjaman::where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->where('tgl_kembali', '<', now())
            ->count();

        // 2. Rekomendasi Berdasarkan Prodi (Dari Klaim yang Disetujui)
        $bukuProdi = collect();
        if ($user && $user->prodi) {
            $bukuProdi = Claim::where('prodi', $user->prodi)
                ->whereIn('status', ['disetujui', 'approved'])
                ->with('buku')
                ->latest()
                ->take(8)
                ->get();
        }

        // 3. Koleksi Terbaru (Umum)
        $semuaBuku = Buku::latest()
            ->take(12)
            ->get();

        // 4. Buku Populer (Berdasarkan Frekuensi Peminjaman)
        $bukuPopuler = Buku::select('buku.*')
            ->join('peminjaman', 'buku.id', '=', 'peminjaman.buku_id')
            ->groupBy('buku.id')
            ->orderByRaw('COUNT(peminjaman.id) DESC')
            ->take(8)
            ->get();

        return view('mahasiswa.beranda', compact(
            'bukuProdi', 
            'semuaBuku', 
            'bukuPopuler',
            'sedangDipinjam', 
            'totalPinjam', 
            'terlambat'
        ));
    }

    /**
     * Halaman khusus daftar buku yang sedang dipegang mahasiswa
     */
    public function pinjamanAktif()
    {
        $userId = Auth::id();

        $pinjaman = Peminjaman::where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->with(['buku'])
            ->latest('tgl_pinjam')
            ->get();

        return view('mahasiswa.pinjaman', compact('pinjaman'));
    }

    /**
     * Halaman Riwayat Lengkap (Termasuk yang sudah kembali & denda)
     */
    public function riwayat()
    {
        $userId = Auth::id();

        // Mengambil semua data peminjaman agar mahasiswa bisa melihat riwayat buku 
        // yang baru saja dikembalikan beserta nominal denda fisiknya.
        $riwayat = Peminjaman::where('user_id', $userId)
            ->with('buku')
            ->latest()
            ->get();

        // Statistik tambahan jika diperlukan di halaman riwayat
        $totalPinjam = Peminjaman::where('user_id', $userId)->count();
        
        $totalDenda = Peminjaman::where('user_id', $userId)->sum('denda_fisik');

        return view('mahasiswa.riwayat', compact('riwayat', 'totalPinjam', 'totalDenda'));
    }

    /**
     * Menampilkan daftar lengkap rekomendasi buku prodi
     */
    public function rekomendasiFull()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $bukuRekomendasi = collect();
        if ($user && $user->prodi) {
            $bukuRekomendasi = Claim::where('prodi', $user->prodi)
                ->whereIn('status', ['disetujui', 'approved'])
                ->with('buku')
                ->latest()
                ->get();
        }

        return view('mahasiswa.rekomendasi_full', compact('bukuRekomendasi'));
    }
}