<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use App\Models\Setting;
use Carbon\Carbon;

class SendReturnReminder extends Command
{
    protected $signature = 'app:send-return-reminder';
    protected $description = 'Mengirim pengingat pengembalian buku via WhatsApp';

    public function handle()
    {
        // 1. Cari buku yang tgl_tenggat-nya adalah BESOK
        $besok = Carbon::tomorrow()->format('Y-m-d');

        // Kita filter: status harus 'dipinjam' (abaikan 'pending' atau 'kembali')
        // Kita tambah cek kolom 'wa_sent' agar tidak kirim double (pastikan kolom ini ada di database)
        $pinjaman = Peminjaman::with('user', 'buku')
            ->where('status', 'dipinjam')
            ->whereDate('tgl_tenggat', $besok)
            ->where('wa_notif_return_sent', 0) // Hanya kirim jika belum pernah dikirim
            ->get();

        if ($pinjaman->isEmpty()) {
            $this->info("Tidak ada pengembalian (status dipinjam) untuk tanggal $besok atau notifikasi sudah dikirim.");
            return;
        }

        // 2. Ambil Token Fonnte dari Setting
        $token = Setting::where('key', 'wa_token')->first()->value ?? env('FONNTE_TOKEN');

        foreach ($pinjaman as $item) {
            $phone = $item->user->no_telp;
            if (!$phone) continue;

            $pesan = "🔔 *PENGINGAT PENGEMBALIAN BUKU*\n\nHalo *{$item->user->name}*,\nBuku: *{$item->buku->judul}*\nHarus dikembalikan besok: *" . Carbon::parse($item->tgl_tenggat)->format('d-m-Y') . "*.\n\nMohon segera dikembalikan ke perpustakaan ya.";

            // Kirim ke Fonnte
            $response = $this->kirimWA($phone, $pesan, $token);

            // 3. Tandai di database bahwa WA sudah terkirim untuk ID ini
            $item->update(['wa_notif_return_sent' => 1]);

            $this->info("WA terkirim ke: " . $item->user->name);
        }
    }

    private function kirimWA($target, $pesan, $token)
    {
        $target = preg_replace('/[^0-9]/', '', $target);
        if (str_starts_with($target, '0')) $target = '62' . substr($target, 1);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => ['target' => $target, 'message' => $pesan],
            CURLOPT_HTTPHEADER => ['Authorization: ' . $token],
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
}
