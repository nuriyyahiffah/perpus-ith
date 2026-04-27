<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Buku;
use App\Models\Eksemplar; // WAJIB: Import model Eksemplar untuk menghitung fisik
use App\Models\Peminjaman; 
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        // 1. Menghitung Data Statistik Dasar

        // AGAR HASILNYA 27:
        // Kita menghitung semua user kecuali yang memiliki role 'admin'.
        // Jika total user di database adalah 28 (termasuk 1 admin), maka hasilnya akan pas 27.
        $totalAnggota = User::where('role', '!=', 'admin')->count();

        // AGAR HASILNYA 7:
        // Kita menghitung baris di tabel eksemplar (unit fisik buku yang terdaftar).
        // Ini lebih akurat daripada Buku::sum('stok') jika data stok di tabel buku tidak sinkron.
        $totalKoleksi =\App\Models\Eksemplar::count();

        // Menghitung jumlah peminjaman yang dilakukan hari ini
        $pinjamHariIni = Peminjaman::whereDate('created_at', Carbon::today())->count();

        // 2. Menghitung Akun yang sedang Tersuspend
        $countSuspend = User::where('status_akun', 'suspended')->count();

        // 3. Statistik Buku Terlambat (Angka untuk Card)
        $bukuTerlambat = Peminjaman::where('status', 'dipinjam')
            ->where('tgl_kembali', '<', Carbon::now())
            ->count();

        // 4. List User yang Terlambat (Untuk Tabel Dashboard)
        $listTerlambat = Peminjaman::with(['user', 'buku'])
            ->where('status', 'dipinjam')
            ->where('tgl_kembali', '<', Carbon::now())
            ->get();

        // Mengirimkan variabel ke view admin.dashboard
        return view('admin.dashboard', compact(
            'totalAnggota',
            'totalKoleksi', // Pastikan di file Blade memanggil {{ $totalKoleksi }}
            'pinjamHariIni',
            'countSuspend',
            'bukuTerlambat',
            'listTerlambat'
        ));
    }
}