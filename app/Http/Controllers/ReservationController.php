<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Buku;
use App\Models\Setting;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ReservationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = strtolower($user->role);

        // Mengambil data dengan relasi lengkap
        $query = Reservation::with(['user', 'buku'])->latest();

        if ($role == 'admin' || $role == 'pustakawan') {
            $reservasi = $query->get();
        } else {
            $reservasi = $query->where('user_id', $user->id)->get();
        }

        return view('shared.reservasi.index', compact('reservasi'));
    }

    public function store($buku_id)
    {
        $cek = Reservation::where('user_id', Auth::id())
                          ->where('buku_id', $buku_id)
                          ->whereIn('status', ['menunggu', 'tersedia'])
                          ->first();

        if ($cek) {
            return redirect()->back()->with('error', 'Anda sudah memiliki reservasi aktif untuk buku ini.');
        }

        Reservation::create([
            'user_id' => Auth::id(),
            'buku_id' => $buku_id,
            'status'  => 'menunggu',
            'created_at' => now(), // Memastikan waktu antre adalah waktu sekarang
        ]);

        return redirect()->back()->with('success', 'Berhasil melakukan reservasi. Anda akan menerima WhatsApp jika buku tersedia.');
    }

    public function konfirmasi($id)
    {
        $reservasi = Reservation::with(['user', 'buku'])->findOrFail($id);
        $reservasi->update(['status' => 'tersedia']);

        $this->pushNotification($reservasi);

        return redirect()->back()->with('success', 'Status diperbarui dan notifikasi telah dikirim.');
    }

    public function destroy($id)
    {
        $reservasiBatal = Reservation::findOrFail($id);
        $bukuId = $reservasiBatal->buku_id;
        $statusBatal = $reservasiBatal->status;

        $reservasiBatal->delete();

        if ($statusBatal === 'tersedia') {
            $nextAntrean = Reservation::where('buku_id', $bukuId)
                                        ->where('status', 'menunggu')
                                        ->orderBy('created_at', 'asc')
                                        ->first();

            if ($nextAntrean) {
                $nextAntrean->update(['status' => 'tersedia']);
                $this->pushNotification($nextAntrean);
            }
        }

        return redirect()->back()->with('success', 'Reservasi berhasil dibatalkan.');
    }

    private function pushNotification($reservasi)
    {
        $nama = $reservasi->user->name;
        $no_telp = $reservasi->user->no_telp;
        $judulBuku = $reservasi->buku->judul;

        Notification::create([
            'user_id'      => $reservasi->user_id,
            'judul'        => 'Buku Siap Diambil 📚',
            'pesan'        => "Halo $nama, buku '$judulBuku' sudah tersedia di perpustakaan.",
            'tipe'         => 'info',
            'ikon'         => 'bi-bell',
            'sudah_dibaca' => 0
        ]);

        $isNotifActive = Setting::where('key', 'notif_reservation')->first()->value ?? '0';
        $token = Setting::where('key', 'wa_token')->first()->value ?? env('FONNTE_TOKEN');

        if ($isNotifActive == '1' && $no_telp && $token) {
            $pesan = "📚 *KABAR GEMBIRA: RESERVASI TERSEDIA*\n\nHalo *$nama*,\n\nBuku yang Anda tunggu:\n*[$judulBuku]*\n\nSudah tersedia dan dapat diambil di Perpustakaan ITH sekarang. Slot tersedia selama 24 jam. Terima kasih!";

            $no_telp = preg_replace('/[^0-9]/', '', $no_telp);
            if (str_starts_with($no_telp, '0')) $no_telp = '62' . substr($no_telp, 1);

            Http::withHeaders(['Authorization' => $token])
                ->post('https://api.fonnte.com/send', [
                    'target'  => $no_telp,
                    'message' => $pesan,
                ]);
        }
    }
}
