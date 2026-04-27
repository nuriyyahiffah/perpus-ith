<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\KategoriAnggota;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriAnggotaController extends Controller
{
    /**
     * Menampilkan Daftar Kebijakan Kategori (Shared: Admin & Pustakawan)
     */
    public function index()
    {
        $kategori = KategoriAnggota::latest()->get();
        // Pastikan nama folder menggunakan underscore sesuai fisik folder kamu
        return view('shared.kategori.kategori_anggota.index', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|unique:kategori_anggotas,nama_kategori',
            'maksimal_pinjam' => 'required|numeric|min:1',
            'durasi_pinjam' => 'required|numeric|min:1',
        ]);

        KategoriAnggota::create($request->all());

        // Mengarahkan kembali ke index shared
        return redirect()->route('shared.kategori-anggota.index')
                         ->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $kategori = KategoriAnggota::findOrFail($id);

        $request->validate([
            'nama_kategori' => 'required|unique:kategori_anggotas,nama_kategori,' . $id,
            'maksimal_pinjam' => 'required|numeric|min:1',
            'durasi_pinjam' => 'required|numeric|min:1',
        ]);

        $kategori->update($request->all());

        return redirect()->route('shared.kategori-anggota.index')
                         ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kategori = KategoriAnggota::findOrFail($id);

        // Cek apakah ada user yang masih menggunakan kategori ini
        if (User::where('kategori_anggota_id', $id)->exists()) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih digunakan oleh anggota!');
        }

        $kategori->delete();
        return redirect()->route('shared.kategori-anggota.index')
                         ->with('success', 'Kategori berhasil dihapus!');
    }

    /*
    |--------------------------------------------------------------------------
    | MANAJEMEN DATA ANGGOTA (Hanya Admin)
    |--------------------------------------------------------------------------
    */

    public function indexAnggota(Request $request)
    {
        $search = $request->get('search');

        $query = User::query()->whereNotNull('kategori_anggota_id')
                    ->where('role', '!=', 'admin')
                    ->with(['kategori', 'anggota']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('nomor_identitas', 'LIKE', "%{$search}%")
                  ->orWhereHas('anggota', function($sq) use ($search) {
                      $sq->where('nomor_anggota', 'LIKE', "%{$search}%");
                  });
            });
        }

        $users = $query->latest()->get();

        return view('shared.anggota.index', compact('users', 'search'));
    }

    public function aktivasi()
    {
        $calonAnggota = User::whereNotNull('kategori_anggota_id')
                            ->where('role', '!=', 'admin')
                            ->whereDoesntHave('anggota')
                            ->with('kategori')
                            ->get();

        return view('shared.anggota.aktivasi', compact('calonAnggota'));
    }

    public function updateStatus(Request $request, $id)
    {
        $anggota = Anggota::findOrFail($id);

        $request->validate([
            'status' => 'required|in:aktif,nonaktif,suspen'
        ]);

        $anggota->update([
            'status' => $request->status
        ]);

        $user = User::find($anggota->user_id);
        $namaUser = $user ? $user->name : 'Anggota';

        return back()->with('success', "Status anggota {$namaUser} berhasil diubah menjadi " . strtoupper($request->status));
    }

    public function prosesAktivasi($userId)
    {
        $user = User::findOrFail($userId);

        // 1. Tentukan Masa Berlaku Berdasarkan Kategori
        $nama_kategori = strtolower($user->kategori->nama_kategori);

        if (str_contains($nama_kategori, 'mahasiswa')) {
            $tgl_kadaluarsa = now()->addYears(4); // Mahasiswa biasanya 4 tahun
        } elseif (str_contains($nama_kategori, 'dosen') || str_contains($nama_kategori, 'pegawai')) {
            $tgl_kadaluarsa = now()->addYears(10); // Dosen/Pegawai lebih lama
        } else {
            $tgl_kadaluarsa = now()->addYear();
        }

        // 2. Generate Nomor Anggota (AGT-2026-0001)
        $tahun = date('Y');
        // Cari nomor terakhir di tahun ini
        $lastAnggota = Anggota::where('nomor_anggota', 'LIKE', "AGT-$tahun-%")
                              ->orderBy('nomor_anggota', 'desc')
                              ->first();

        if ($lastAnggota) {
            // Ambil 4 angka terakhir
            $lastNumber = intval(substr($lastAnggota->nomor_anggota, -4));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $nomorAnggota = 'AGT-' . $tahun . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // 3. Simpan ke Tabel Anggota
        Anggota::create([
            'user_id' => $user->id,
            'nomor_anggota' => $nomorAnggota,
            'tgl_daftar' => now(),
            'tgl_kadaluarsa' => $tgl_kadaluarsa,
            'status' => 'aktif'
        ]);

        return redirect()->route('admin.anggota.index')
                         ->with('success', "Anggota {$user->name} berhasil diaktivasi. ID: {$nomorAnggota}");
    }

    public function perpanjangan()
    {
        $anggota = Anggota::with(['user', 'user.kategori'])->latest()->get();
        return view('admin.anggota.perpanjangan', compact('anggota'));
    }
}
