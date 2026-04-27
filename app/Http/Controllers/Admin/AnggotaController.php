<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    /**
     * Menampilkan daftar anggota yang sudah aktif
     */
    public function index()
    {
        // has('user') memastikan kita tidak menarik data anggota yang user-nya sudah dihapus
        $anggota = Anggota::has('user')->with('user')->latest()->get();
        return view('shared.anggota.index', compact('anggota'));
    }

    /**
     * Menampilkan daftar user yang menunggu aktivasi
     */
    public function aktivasi()
    {
        // Mengambil user (mahasiswa/dosen) yang belum terdaftar di tabel anggota
        $calonAnggota = User::whereIn('role', ['mahasiswa', 'dosen'])
                            ->whereDoesntHave('anggota')
                            ->get();

        return view('shared.anggota.aktivasi', compact('calonAnggota'));
    }

    /**
     * Proses Aktivasi Anggota (Fungsi yang tadi error)
     */
    public function store(Request $request)
    {
        // 1. Validasi input: pastikan user_id dikirim
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        // 2. Ambil data user dari database (Ini untuk mendefinisikan variabel $user)
        $user = User::findOrFail($request->user_id);

        // 3. Cek apakah user ini sudah pernah diaktivasi sebelumnya
        $exists = Anggota::where('user_id', $user->id)->exists();
        if ($exists) {
            return redirect()->back()->with('error', 'User ini sudah menjadi anggota!');
        }

        // 4. Buat Nomor Anggota unik (Contoh: AGT-202603300001)
        // Menggunakan ID user sebagai akhiran agar pasti unik
        $nomorAnggota = 'AGT-' . date('Ymd') . sprintf("%04d", $user->id);

        // 5. Simpan ke tabel anggota
        Anggota::create([
            'user_id'        => $user->id, // Sekarang $user sudah dikenal
            'nomor_anggota'  => $nomorAnggota,
            'tgl_daftar'     => now(),
            'tgl_kadaluarsa' => now()->addYear(), // Berlaku 1 tahun
            'status'         => 'aktif',
        ]);

        return redirect()->route('shared.anggota.index')
                         ->with('success', 'Anggota ' . $user->name . ' berhasil diaktifkan!');
    }

    /**
     * Menghapus anggota
     */
    public function destroy($id)
    {
        $anggota = Anggota::findOrFail($id);
        $anggota->delete();

        return redirect()->back()->with('success', 'Anggota berhasil dihapus.');
    }

    /**
     * Halaman perpanjangan masa berlaku anggota
     */
    public function perpanjangan()
    {
        $anggota = Anggota::with('user')->get();
        return view('shared.anggota.perpanjangan', compact('anggota'));
    }
}
