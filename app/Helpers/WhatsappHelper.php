<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class WhatsappHelper
{
    /**
     * Mengirim notifikasi WhatsApp menggunakan API Fonnte.
     * Kode ini disesuaikan untuk kebutuhan penelitian perangkat lunas SIPUSTAKA.
     * 
     * @param string $target Nomor tujuan (format: 628...)
     * @param string $pesan Isi pesan teks
     * @return array|mixed Respon dari API Fonnte
     */
    public static function sendNotif($target, $pesan)
{
    // Mengambil token dari database tabel settings (sesuai gambar HeidiSQL kamu)
    $setting = \DB::table('settings')->first();
    $token = $setting->wa_token ?? env('FONNTE_TOKEN'); 
    
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
                'countryCode' => '62', // Default kode negara Indonesia
            ),
            CURLOPT_HTTPHEADER => array(
                "Authorization: $token" // Membawa token autentikasi
            ),
            // Mengabaikan verifikasi SSL untuk koneksi dari localhost
            CURLOPT_SSL_VERIFYPEER => false,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        // Mencatat log aktivitas untuk keperluan debugging skripsi
        if ($err) {
            Log::error("CURL Error (WhatsappHelper): " . $err);
            return [
                'status' => false, 
                'message' => $err
            ];
        } else {
            Log::info("Respon Balasan Fonnte: " . $response);
            return json_decode($response, true);
        }
    }
}