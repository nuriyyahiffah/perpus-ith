<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Notification as NotificationModel;

class NotifikasiBuku extends Notification
{
    use Queueable;

    protected $dataNotif;

    /**
     * Konstruktor untuk menerima data notifikasi
     * $dataNotif harus berisi: judul, pesan, tipe, ikon, url_aksi
     */
    public function __construct($dataNotif)
    {
        $this->dataNotif = $dataNotif;
    }

    /**
     * Tentukan metode pengiriman
     */
    public function via($notifiable)
    {
        // Kita panggil fungsi simpan ke tabel kustom kamu
        $this->simpanKeTabelKustom($notifiable);

        // Return kosong agar tidak menggunakan sistem tabel default Laravel
        return [];
    }

    /**
     * Logika untuk memasukkan data ke tabel notifications (HeidiSQL)
     */
    protected function simpanKeTabelKustom($notifiable)
    {
        NotificationModel::create([
            'user_id'      => $notifiable->id,
            'judul'        => $this->dataNotif['judul'] ?? 'Notifikasi Baru',
            'pesan'        => $this->dataNotif['pesan'] ?? '',
            'tipe'         => $this->dataNotif['tipe'] ?? 'info',
            'ikon'         => $this->dataNotif['ikon'] ?? 'bi-bell',
            'sudah_dibaca' => false, // Default 0 (belum dibaca)
            'url_aksi'     => $this->dataNotif['url_aksi'] ?? null,
        ]);
    }
}
