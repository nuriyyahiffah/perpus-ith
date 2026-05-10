<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Claim;
use App\Models\Notification; // Pastikan import ini ada
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
            ->where('status', 'dipinjam')
            ->with('buku')
            ->get();

        $totalPinjam = Peminjaman::where('user_id', $userId)->count();

        $terlambat = Peminjaman::where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->where('tgl_kembali', '<', now())
            ->count();

        // 2. Rekomendasi Berdasarkan Prodi (Dari Klaim yang Disetujui)
        // Tetap muncul meski stok 0 karena kita mengambil data dari tabel Claim
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
        // Kita gunakan latest() agar buku terbaru (termasuk yang stoknya 0) tetap muncul
        $semuaBuku = Buku::latest()
            ->take(12)
            ->get();

        // 4. Buku Populer (Berdasarkan Frekuensi Peminjaman)
        $bukuPopuler = Buku::withCount('peminjaman')
            ->orderBy('peminjaman_count', 'desc')
            ->take(5)
            ->get();

        // 5. Notifikasi Terbaru (Pindahkan ke sini agar variabel terdefinisi sebelum return)
        $notifikasi = Notification::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        // 6. Return View (Hanya satu kali di akhir)
        return view('mahasiswa.beranda', compact(
            'bukuProdi',
            'semuaBuku',
            'bukuPopuler',
            'sedangDipinjam',
            'totalPinjam',
            'terlambat',
            'notifikasi'
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
     * Halaman Riwayat Lengkap
     */
    public function riwayat()
    {
        $userId = Auth::id();

        $riwayat = Peminjaman::where('user_id', $userId)
            ->with('buku')
            ->latest()
            ->get();

        $totalPinjam = Peminjaman::where('user_id', $userId)->count();
        $totalDenda = Peminjaman::where('user_id', $userId)->sum('denda_fisik');

        return view('mahasiswa.riwayat', compact('riwayat', 'totalPinjam', 'totalDenda'));
    }

    /**
     * Menampilkan daftar lengkap rekomendasi buku prodi
     */
    public function rekomendasiFull()
    {
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

    /**
     * Menampilkan semua notifikasi
     */
    public function semuaNotifikasi()
    {
        $notifikasi = Notification::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('mahasiswa.notifikasi', compact('notifikasi'));
    }
}
