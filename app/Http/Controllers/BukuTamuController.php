<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BukuTamu; // Import Model
use Illuminate\Support\Facades\Auth; // Import Facade Auth
use Carbon\Carbon; // Untuk urusan tanggal yang lebih akurat

class BukuTamuController extends Controller
{
    /**
     * Menampilkan daftar pengunjung hari ini (Untuk Admin & Pustakawan)
     */
    public function index()
    {
        // Pastikan user sudah login dan memiliki role yang sesuai
        if (!Auth::check() || !in_array(Auth::user()->role, ['admin', 'pustakawan'])) {
            abort(403, 'Akses Dibatasi');
        }

        // Mengambil data pengunjung yang created_at nya hari ini
        $pengunjung = BukuTamu::whereDate('created_at', Carbon::today())
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('shared.buku-tamu.index', compact('pengunjung'));
    }

    /**
     * Menampilkan form input pengunjung (Untuk Publik/Layar Depan)
     */
    public function create()
    {
        return view('public.buku-tamu-form');
    }

    /**
     * Menyimpan data kunjungan baru ke database
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama'              => 'required|string|max:255',
            'identitas'         => 'required|string|max:50',
            'status_pengunjung' => 'required|string',
            'instansi_prodi'    => 'nullable|string|max:255',
            'keperluan'         => 'required|string',
        ]);

        // Simpan ke database
        BukuTamu::create($validated);

        // Kembali ke halaman form dengan pesan sukses
        return back()->with('success', 'Terima kasih! Kunjungan Anda telah berhasil dicatat.');
    }
}