<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Eksemplar; // Pastikan Model Eksemplar di-import
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminTransaksiController extends Controller
{
    /**
     * Menampilkan daftar sirkulasi (Semua, Dipinjam, atau Dikembalikan)
     */
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'buku', 'eksemplar']);

        // Filter berdasarkan status jika ada (dipinjam / dikembalikan)
        if ($request->has('status')) {
            $query->where('status', strtolower($request->status));
        }

        $transaksi = $query->latest()->get();

        return view('shared.transaksi.index', compact('transaksi'));
    }

    /**
     * Menampilkan riwayat buku yang sudah dikembalikan
     */
    public function riwayat()
    {
        $riwayat = Peminjaman::with(['user', 'buku', 'eksemplar'])
                    ->where('status', 'dikembalikan')
                    ->latest()
                    ->get();

        return view('admin.buku.riwayat', compact('riwayat'));
    }

    /**
     * Memproses pengembalian buku
     * Inilah jantung perbaikannya agar status eksemplar berubah
     */
    public function kembalikan(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // 1. Update data Peminjaman
        $peminjaman->update([
            'status'            => 'dikembalikan',
            'tgl_kembali'       => Carbon::now(), // Mencatat tanggal realisasi kembali
            'kondisi_kembali'   => $request->kondisi_kembali,
            'denda_fisik'       => $request->denda_fisik ?? 0,
            'catatan_kondisi'   => $request->catatan_kondisi,
        ]);

        // 2. Update Status EKSEMPLAR (Buku Fisik)
        // Logika: Jika kondisi 'baik', maka 'tersedia'. Jika tidak, ikuti kondisi (rusak/hilang)
        if ($peminjaman->eksemplar) {
            $statusBaru = (strtolower($request->kondisi_kembali) == 'baik') ? 'tersedia' : strtolower($request->kondisi_kembali);
            
            $peminjaman->eksemplar->update([
                'status' => $statusBaru
            ]);
        }

        // 3. Update Stok di Tabel Buku Utama (Opsional, jika kamu pakai sistem stok akumulatif)
        if ($peminjaman->buku && strtolower($request->kondisi_kembali) == 'baik') {
            $peminjaman->buku->increment('stok');
        }

        return redirect()->back()->with('success', 'Buku berhasil dikembalikan dan status eksemplar diperbarui!');
    }

    /**
     * Form tambah peminjaman manual oleh Admin/Pustakawan
     */
    public function create()
    {
        $mahasiswa = User::where('role', 'mahasiswa')->get();
        // Hanya tampilkan buku yang memiliki eksemplar tersedia
        $buku = Buku::whereHas('eksemplars', function($q) {
            $q->where('status', 'tersedia');
        })->get();

        return view('shared.transaksi.create', compact('mahasiswa', 'buku'));
    }

    /**
     * Menyimpan data peminjaman baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'buku_id' => 'required',
            'eksemplar_id' => 'required', // Tambahkan ini di form create kamu nanti
        ]);

        Peminjaman::create([
            'user_id'      => $request->user_id,
            'buku_id'      => $request->buku_id,
            'eksemplar_id' => $request->eksemplar_id,
            'tgl_pinjam'   => Carbon::now(),
            'tgl_kembali'  => Carbon::now()->addDays(7), // Ini Deadline
            'status'       => 'dipinjam', // Gunakan standar huruf kecil
        ]);

        // Update status eksemplar menjadi dipinjam
        $eksemplar = Eksemplar::find($request->eksemplar_id);
        if ($eksemplar) {
            $eksemplar->update(['status' => 'dipinjam']);
        }

        // Kurangi stok buku utama
        $buku = Buku::find($request->buku_id);
        if ($buku) {
            $buku->decrement('stok');
        }

        return redirect()->route('shared.transaksi.index')->with('success', 'Peminjaman berhasil dicatat!');
    }
}