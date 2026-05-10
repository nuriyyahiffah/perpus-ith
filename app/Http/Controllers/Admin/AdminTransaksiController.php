<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Eksemplar;
use App\Models\Notification; // Pastikan Model Notification di-import
use App\Models\Setting;      // Untuk mengambil Token WA & Pengaturan
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AdminTransaksiController extends Controller
{
    /**
     * Menampilkan daftar sirkulasi (Semua, Dipinjam, atau Dikembalikan)
     */
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'buku', 'eksemplar']);

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
     * Memproses pengembalian buku + Notifikasi Dashboard + Notifikasi WA
     */
    public function kembalikan(Request $request, $id)
    {
        // Load relasi user dan buku untuk kebutuhan notifikasi
        $peminjaman = Peminjaman::with(['user', 'buku', 'eksemplar'])->findOrFail($id);

        // 1. Update data Peminjaman
        $peminjaman->update([
            'status'            => 'dikembalikan',
            'tgl_kembali'       => Carbon::now(),
            'kondisi_kembali'   => $request->kondisi_kembali,
            'denda_fisik'       => $request->denda_fisik ?? 0,
            'catatan_kondisi'   => $request->catatan_kondisi,
        ]);

        // 2. Update Status EKSEMPLAR (Buku Fisik)
        if ($peminjaman->eksemplar) {
            $statusBaru = (strtolower($request->kondisi_kembali) == 'baik') ? 'tersedia' : strtolower($request->kondisi_kembali);

            $peminjaman->eksemplar->update([
                'status' => $statusBaru
            ]);
        }

        // 3. Update Stok di Tabel Buku Utama
        if ($peminjaman->buku && strtolower($request->kondisi_kembali) == 'baik') {
            $peminjaman->buku->increment('stok');
        }

        // 4. BUAT NOTIFIKASI DASHBOARD (Untuk Mahasiswa)
        Notification::create([
            'user_id' => $peminjaman->user_id,
            'judul'   => 'PENGEMBALIAN BERHASIL ✅',
            'pesan'   => "Buku '{$peminjaman->buku->judul}' telah diterima oleh pustakawan pada " . Carbon::now()->format('d/m/Y H:i') . " WITA dengan kondisi {$request->kondisi_kembali}.",
            'tipe'    => 'success',
            'ikon'    => 'bi-check-circle-fill',
            'sudah_dibaca' => 0,
        ]);

        // 5. KIRIM NOTIFIKASI WHATSAPP
        $this->sendWhatsAppReturn($peminjaman);

        return redirect()->back()->with('success', 'Buku berhasil dikembalikan dan notifikasi telah dikirim ke mahasiswa!');
    }

    /**
     * Fungsi Helper: Pengiriman WhatsApp via Fonnte API
     */
    private function sendWhatsAppReturn($peminjaman)
    {
        // Ambil token dari tabel settings atau .env
        $token = Setting::where('key', 'wa_token')->first()->value ?? env('FONNTE_TOKEN');
        $target = $peminjaman->user->no_hp; // Pastikan kolom no_hp ada di tabel users

        if (!$target) return;

        $nama = $peminjaman->user->name;
        $judul = $peminjaman->buku->judul;
        $waktu = Carbon::now()->format('d/m/Y H:i');
        $denda = number_format($peminjaman->denda_fisik, 0, ',', '.');

        $pesan = "Halo *$nama*,\n\nTerima kasih, buku berjudul *'$judul'* telah berhasil dikembalikan ke *Perpustakaan ITH* pada $waktu WITA.\n\nKondisi: *{$peminjaman->kondisi_kembali}*\nDenda Fisik: *Rp $denda*\n\nSimpan pesan ini sebagai bukti digital pengembalian Anda. Terima kasih!";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => $target,
                'message' => $pesan,
            ),
            CURLOPT_HTTPHEADER => array(
                "Authorization: $token"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        // GANTI DD-NYA JADI INI
        dd([
            'token_yang_digunakan' => $token,
            'nomor_tujuan' => $target,
            'response_dari_fonnte' => json_decode($response)
        ]);

        Log::info("WhatsApp Return Log: " . $response);
        return $response;
    }

    /**
     * Form tambah peminjaman manual oleh Admin/Pustakawan
     */
    public function create()
    {
        $mahasiswa = User::where('role', 'mahasiswa')->get();
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
            'eksemplar_id' => 'required',
        ]);

        $peminjaman = Peminjaman::create([
            'user_id'      => $request->user_id,
            'buku_id'      => $request->buku_id,
            'eksemplar_id' => $request->eksemplar_id,
            'tgl_pinjam'   => Carbon::now(),
            'tgl_kembali'  => Carbon::now()->addDays(7),
            'status'       => 'dipinjam',
        ]);

        $eksemplar = Eksemplar::find($request->eksemplar_id);
        if ($eksemplar) {
            $eksemplar->update(['status' => 'dipinjam']);
        }

        $buku = Buku::find($request->buku_id);
        if ($buku) {
            $buku->decrement('stok');
        }

        return redirect()->route('shared.transaksi.index')->with('success', 'Peminjaman berhasil dicatat!');
    }
}
