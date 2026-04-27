<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Buku;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        return $this->bulanan($request);
    }

    public function bulanan(Request $request)
    {
        $bulan = $request->get('bulan', Carbon::now()->format('m'));
        $tahun = $request->get('tahun', Carbon::now()->format('Y'));

        $statistikBulanan = [
            'total_peminjaman' => Peminjaman::whereYear('tgl_pinjam', $tahun)
                ->whereMonth('tgl_pinjam', $bulan)->count(),
            'total_pengembalian' => Peminjaman::whereYear('tgl_kembali', $tahun)
                ->whereMonth('tgl_kembali', $bulan)
                ->where('status', 'dikembalikan')->count(),
            'total_terlambat' => Peminjaman::where('status', 'dipinjam')
                ->whereDate('tgl_kembali', '<', Carbon::now())->count(),
            'total_denda' => Peminjaman::whereYear('tgl_kembali', $tahun)
                ->whereMonth('tgl_kembali', $bulan)->sum('denda_fisik'),
            'buku_terpopuler' => $this->getBukuTerpopuler($bulan, $tahun),
            'anggota_aktif' => $this->getAnggotaAktif($bulan, $tahun),
        ];

            $dataTransaksi = Peminjaman::with(['user', 'eksemplar.buku']) // Perhatikan tanda titik (.)     
            ->whereYear('tgl_pinjam', $tahun)  
            ->whereMonth('tgl_pinjam', $bulan)
            ->latest() // Mengurutkan data terbaru di atas
            ->get();

        $trenHarian = $this->getTrenHarian($bulan, $tahun);

        return view('shared.laporan.bulanan', compact(
            'bulan', 'tahun', 'statistikBulanan', 'dataTransaksi', 'trenHarian'
        ));
    }

    // Pastikan nama method camelCase sesuai web.php
public function exportPdf(Request $request)
{
    $bulan = $request->get('bulan', date('m'));
    $tahun = $request->get('tahun', date('Y'));

    // Eager loading relasi eksemplar dan buku
    $data = Peminjaman::with(['user', 'eksemplar.buku'])
        ->whereYear('tgl_pinjam', $tahun)
        ->whereMonth('tgl_pinjam', $bulan)
        ->latest()
        ->get();

    $setting = Setting::where('key', 'nama_perpus')->first();
    $nama_perpus = $setting ? $setting->value : 'SIPUSTAKA ITH';

    $pdf = Pdf::loadView('shared.laporan.pdf_bulanan', [
        'data' => $data,
        'bulan' => $bulan,
        'tahun' => $tahun,
        'nama_perpus' => $nama_perpus,
        'tgl_cetak' => \Carbon\Carbon::now()->translatedFormat('d F Y')
    ]);

    return $pdf->setPaper('a4', 'landscape')->download("Laporan_Perpustakaan_{$bulan}.pdf");
}

    private function getTrenHarian($bulan, $tahun)
    {
        $startDate = Carbon::create($tahun, $bulan, 1);
        $endDate = $startDate->copy()->endOfMonth();
        $tren = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $tanggal = $currentDate->format('Y-m-d');
            $tren[] = [
                'tanggal' => $currentDate->format('d'),
                'peminjaman' => Peminjaman::whereDate('tgl_pinjam', $tanggal)->count(),
                'pengembalian' => Peminjaman::whereYear('tgl_kembali', $tahun)
                                    ->whereMonth('tgl_kembali', $bulan)
                                    ->whereDate('tgl_kembali', $tanggal)
                                    ->where('status', 'dikembalikan')->count(),
            ];
            $currentDate->addDay();
        }
        return $tren;
    }

    private function getBukuTerpopuler($bulan, $tahun)
    {
        return Buku::select('buku.*', DB::raw('COUNT(peminjaman.id) as total_pinjam'))
            ->join('peminjaman', 'buku.id', '=', 'peminjaman.buku_id')
            ->whereYear('peminjaman.tgl_pinjam', $tahun)
            ->whereMonth('peminjaman.tgl_pinjam', $bulan)
            ->groupBy('buku.id')
            ->orderBy('total_pinjam', 'desc')
            ->take(5)
            ->get();
    }

    private function getAnggotaAktif($bulan, $tahun)
    {
        return User::select('users.*', DB::raw('COUNT(peminjaman.id) as total_pinjam'))
            ->join('peminjaman', 'users.id', '=', 'peminjaman.user_id')
            ->whereYear('peminjaman.tgl_pinjam', $tahun)
            ->whereMonth('peminjaman.tgl_pinjam', $bulan)
            ->groupBy('users.id')
            ->orderBy('total_pinjam', 'desc')
            ->take(5)
            ->get();
    }
}