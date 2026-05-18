<?php

use Illuminate\Support\Facades\Schedule;
use App\Models\Setting; // Pastikan model Setting sudah ada

// Mengambil jam dari database. Jika tidak ada, default ke 08:00
$notifTime = Setting::where('key', 'notif_time')->first()->value ?? '08:00';

// Jalankan perintah berdasarkan jam dari database
Schedule::command('app:send-return-reminder')->dailyAt($notifTime);
