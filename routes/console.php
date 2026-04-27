<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;

// Menjalankan perintah pengingat setiap hari jam 08:00 pagi
Schedule::command('app:send-return-reminder')->dailyAt('08:00');
