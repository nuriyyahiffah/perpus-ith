<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
// Gunakan alias agar tidak bentrok dengan class Notification bawaan Laravel
use App\Models\Notification as NotificationModel;

class NotifikasiBuku extends Notification
{
    use Queueable;

    protected $dataNotif;

    /**
     * Konstruktor untuk menerima data notifikasi
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
        // Panggil fungsi simpan ke tabel manual
        $this->simpanKeTabelKustom($notifiable);

        // Kosongkan array agar tidak mencoba masuk ke tabel 'notifications' bawaan Laravel
        return [];
    }

    /**
     * Logika simpan ke database (HeidiSQL)
     */
    protected function simpanKeTabelKustom($notifiable)
    {
        NotificationModel::create([
            'user_id'      => $notifiable->id,
            'judul'        => $this->dataNotif['judul'] ?? 'Notifikasi Baru',
            'pesan'        => $this->dataNotif['pesan'] ?? '',
            'tipe'         => $this->dataNotif['tipe'] ?? 'info',
            'ikon'         => $this->dataNotif['ikon'] ?? 'bi-bell',
            'sudah_dibaca' => 0, // Menggunakan 0 (integer) lebih aman daripada false
            'url_aksi'     => $this->dataNotif['url_aksi'] ?? null,
        ]);
    }
}