<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Buku;
use App\Models\Notification; // Pastikan model ini ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; // Untuk WhatsApp API

class ReservationController extends Controller
{
    /**
     * Menampilkan daftar reservasi.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role == 'admin' || $user->role == 'pustakawan') {
            $reservasi = Reservation::with(['user', 'buku'])->latest()->get();
        } else {
            $reservasi = Reservation::where('user_id', $user->id)
                                    ->with('buku')
                                    ->latest()
                                    ->get();
        }

        return view('shared.reservasi.index', compact('reservasi'));
    }

    /**
     * Mahasiswa: Melakukan reservasi buku.
     */
    public function store($buku_id)
    {
        $cek = Reservation::where('user_id', Auth::id())
                          ->where('buku_id', $buku_id)
                          ->where('status', 'menunggu')
                          ->first();

        if ($cek) {
            return redirect()->back()->with('error', 'Anda sudah mengantre untuk buku ini.');
        }

        Reservation::create([
            'user_id' => Auth::id(),
            'buku_id' => $buku_id,
            'status'  => 'menunggu',
        ]);

        return redirect()->back()->with('success', 'Berhasil melakukan reservasi.');
    }

    /**
     * Admin/Pustakawan: Mengonfirmasi bahwa buku sudah tersedia untuk diambil.
     */
    public function konfirmasi($id)
    {
        $reservasi = Reservation::with(['user', 'buku'])->findOrFail($id);
        
        // Update status sesuai enum di database
        $reservasi->update(['status' => 'tersedia']);

        // Kirim Notifikasi Internal & WA
        $this->pushNotification($reservasi);

        return redirect()->back()->with('success', 'Status diperbarui dan notifikasi telah dikirim ke mahasiswa.');
    }

    /**
     * Admin/Pustakawan/Mahasiswa: Menghapus/Membatalkan antrean.
     * Logika Otomatisasi Antrean (FIFO) ada di sini.
     */
    public function destroy($id)
    {
        $reservasiBatal = Reservation::findOrFail($id);
        $bukuId = $reservasiBatal->buku_id;
        
        // Hapus data yang dibatalkan
        $reservasiBatal->delete();

        // LOGIKA OTOMATISASI: Cari antrean berikutnya (First-In First-Out)
        $nextAntrean = Reservation::where('buku_id', $bukuId)
                                    ->where('status', 'menunggu')
                                    ->orderBy('created_at', 'asc')
                                    ->first();

        if ($nextAntrean) {
            // Naikkan status antrean berikutnya
            $nextAntrean->update(['status' => 'tersedia']);

            // Kirim Notifikasi Internal & WA ke antrean baru
            $this->pushNotification($nextAntrean);
        }

        return redirect()->back()->with('success', 'Antrean berhasil diperbarui secara otomatis.');
    }

    /**
     * Helper Function: Mengirim Notifikasi ke Database dan WhatsApp.
     */
    private function pushNotification($reservasi)
    {
        $nama = $reservasi->user->name;
        $no_hp = $reservasi->user->no_hp;
        $judulBuku = $reservasi->buku->judul;

        // 1. Simpan ke Tabel Notifications (Sesuai image_{11C60EFC...}.png)
        Notification::create([
            'user_id'      => $reservasi->user_id,
            'judul'        => 'Buku Siap Diambil',
            'pesan'        => "Halo $nama, buku '$judulBuku' sudah tersedia di perpustakaan.",
            'tipe'         => 'info',
            'ikon'         => 'bi-bell',
            'sudah_dibaca' => 0
        ]);

        // 2. Kirim WhatsApp API (Contoh menggunakan Fonnte)
        if ($no_hp) {
            $token = "cQRneJqHWTw6h54YpQWC"; // Ganti dengan token Fonnte/Wablas Anda
            $pesan = "Halo *$nama*,\n\nKabar baik dari *PERPUSTAKAAN ITH*! Buku yang Anda reservasi:\n\n*[$judulBuku]*\n\nSudah tersedia dan dapat diambil sekarang. Terima kasih!";

            Http::withHeaders(['Authorization' => $token])
                ->post('https://api.fonnte.com/send', [
                    'target'  => $no_hp,
                    'message' => $pesan,
                ]);
        }
    }
}