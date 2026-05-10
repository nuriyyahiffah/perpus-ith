<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use App\Models\Setting; // Tambahkan ini
use Carbon\Carbon;

class SendReturnReminder extends Command
{
    protected $signature = 'app:send-return-reminder';
    protected $description = 'Mengirim pengingat otomatis berdasarkan pengaturan hari di dashboard';

    public function handle()
    {
        // 1. Ambil jumlah hari pengingat dari database (Tabel settings ID 8)
        // Jika tidak ada, default ke 1 hari
        $days = intval(Setting::where('key', 'reminder_days')->first()->value ?? 1);
        // 2. Cari data yang jatuh tempo tepat X hari lagi
        // Contoh: Jika reminder_days = 5, maka mencari yang tgl_kembali = hari ini + 5 hari
        $targetDate = Carbon::now()->addDays($days)->format('Y-m-d');

        // Pastikan status yang dicari sesuai dengan di sistem Anda ('dipinjam' atau 'disetujui')
        $peminjaman = Peminjaman::with(['user', 'buku'])
            ->whereDate('tgl_kembali', $targetDate)
            ->where('status', 'dipinjam') 
            ->get();

        if ($peminjaman->isEmpty()) {
            $this->info("Tidak ada jadwal pengembalian untuk $targetDate.");
            return;
        }

        foreach ($peminjaman as $item) {
            $nomor = $item->user->no_telp;
            
            // Format pesan agar lebih informatif mengikuti jumlah hari
            $pesan = "⏳ *PENGINGAT PENGEMBALIAN BUKU*\n\n" .
                     "Halo *" . $item->user->name . "*,\n" .
                     "Buku *" . $item->buku->judul . "* akan jatuh tempo dalam *" . $days . " hari* lagi (" . Carbon::parse($item->tgl_kembali)->format('d-m-Y') . ").\n\n" .
                     "Silakan lakukan pengembalian tepat waktu di Perpustakaan ITH. Terima kasih!\n->.<";

            $this->kirimFonnte($nomor, $pesan);
        }

        $this->info("Pengingat berhasil dikirim ke " . $peminjaman->count() . " mahasiswa.");
    }

    private function kirimFonnte($target, $pesan) 
    {
        // Ambil token secara dinamis dari tabel settings (ID 7) atau dari .env
        $token = Setting::where('key', 'wa_token')->first()->value ?? env('FONNTE_TOKEN');

        // Pastikan nomor telp berformat 62
        if (substr($target, 0, 1) === '0') {
            $target = '62' . substr($target, 1);
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => array(
                'target' => $target, 
                'message' => $pesan,
                'countryCode' => '62'
            ),
            CURLOPT_HTTPHEADER => array('Authorization: ' . $token),
        ));
        curl_exec($curl);
        curl_close($curl);
    }
}