<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use Carbon\Carbon;

class SendReturnReminder extends Command
{
    // Nama perintah yang akan dijalankan di terminal
    protected $signature = 'app:send-return-reminder';
    protected $description = 'Mengirim pengingat pengembalian buku H-1 via WhatsApp';

    public function handle()
    {
        // 1. Cari data yang tenggatnya (tgl_kembali) adalah BESOK
        $besok = Carbon::tomorrow()->format('Y-m-d');
        $peminjaman = Peminjaman::with(['user', 'buku'])
            ->whereDate('tgl_kembali', $besok)
            ->where('status', 'disetujui')
            ->get();

        foreach ($peminjaman as $item) {
            $nomor = $item->user->no_telp;
            $pesan = "🔔 *PENGINGAT PENGEMBALIAN BUKU*\n\n" .
                     "Halo *" . $item->user->name . "*,\n" .
                     "Masa peminjaman buku *" . $item->buku->judul . "* akan berakhir *BESOK* (" . Carbon::parse($item->tgl_kembali)->format('d-m-Y') . ").\n\n" .
                     "Mohon segera lakukan pengembalian ke perpustakaan untuk menghindari denda. Terima kasih!";

            // 2. Kirim ke Fonnte
            $this->kirimFonnte($nomor, $pesan);
        }

        $this->info('Pengingat berhasil dikirim ke ' . $peminjaman->count() . ' mahasiswa.');
    }

    private function kirimFonnte($target, $pesan) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => array('target' => $target, 'message' => $pesan),
            CURLOPT_HTTPHEADER => array('Authorization: ' . env('FONNTE_TOKEN')),
        ));
        curl_exec($curl);

    }
}
