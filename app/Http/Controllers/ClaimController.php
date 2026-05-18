<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Claim;
use App\Models\BukuProdi; // 1. TAMBAHKAN MODEL INI
use Illuminate\Support\Facades\Auth;

class ClaimController extends Controller
{
    /**
     * Menampilkan halaman klaim dengan 2 sisi (Judul & Centang) serta sistem Tab.
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $prodiUser = $user->prodi;
        $tab = $request->query('tab', 'semua');
        $search = $request->input('search');

        // Ambil semua ID buku yang sudah dicentang oleh prodi ini
        $claimedIds = Claim::where('prodi', $prodiUser)->pluck('buku_id')->toArray();

        // Mulai Query Buku
        $query = Buku::query();

        // Fitur pencarian
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('judul', 'LIKE', "%{$search}%")
                  ->orWhere('penulis', 'LIKE', "%{$search}%");
            });
        }

        // Logika Tab
        if ($tab == 'prodi') {
            $query->whereIn('id', $claimedIds);
        } elseif ($tab == 'belum') {
            $query->whereNotIn('id', $claimedIds);
        }

        // Eksekusi Pagination dengan limit 5 buku
        $bukus = $query->latest()
                       ->paginate(5)
                       ->appends(['tab' => $tab, 'search' => $search]);

        return view('dosen.claim', compact('bukus', 'claimedIds', 'tab', 'search'));
    }

    /**
     * Logika Toggle AJAX untuk centang otomatis
     */
    public function toggle(Request $request)
    {
        $user = Auth::user();
        $bukuId = $request->buku_id;
        $prodi = $user->prodi;

        $claim = Claim::where('buku_id', $bukuId)
                      ->where('prodi', $prodi)
                      ->first();

        if ($claim) {
            // JIKA DICENTANG ULANG (UNCHECK / APUS KLAIM)
            $claim->delete();

            // 2. PERBAIKAN: Hapus juga datanya dari tabel jembatan buku_prodi
            BukuProdi::where('buku_id', $bukuId)
                     ->where('nama_prodi', $prodi)
                     ->delete();

            return response()->json(['status' => 'removed', 'message' => 'Buku dihapus dari prodi']);
        } else {
            // JIKA BARU DICENTANG (CHECK / BUAT KLAIM BARU)
            Claim::create([
                'user_id' => $user->id,
                'buku_id' => $bukuId,
                'prodi'   => $prodi,
                'no_induk_prodi' => $user->nomor_identitas,
                'status'  => 'disetujui',
            ]);

            // 3. PERBAIKAN: Masukkan data duplikat ke tabel jembatan buku_prodi agar terbaca di katalog
            BukuProdi::create([
                'buku_id'    => $bukuId,
                'nama_prodi' => $prodi
            ]);

            return response()->json(['status' => 'added', 'message' => 'Buku berhasil diklaim']);
        }
    }

    /**
     * MENAMPILKAN HALAMAN LAPORAN REFERENSI PRODI (RIWAYAT)
     */
    public function riwayat()
    {
        // Mengambil data klaim milik prodi user yang login
        $riwayatKlaim = Claim::where('prodi', Auth::user()->prodi)
            ->with('buku')
            ->latest()
            ->get();

        return view('dosen.riwayat_klaim', compact('riwayatKlaim'));
    }
}
