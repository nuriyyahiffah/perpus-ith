<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WhatsappController extends Controller
{
    public function index()
    {
        // Contoh cara mengambil saldo/status dari Fonnte (Opsional)
        // $response = Http::withHeaders([
        //     'Authorization' => env('FONNTE_TOKEN'),
        // ])->post('https://api.fonnte.com/get-devices');

        return view('admin.whatsapp.index', [
            'status' => 'Connected', // Nanti bisa dibuat dinamis
            'quota' => 1000,
            'logs' => [] // Riwayat pesan dari database
        ]);
    }

    public function testSend(Request $request)
    {
        // Logika kirim pesan tes via Fonnte
        return back()->with('success', 'Pesan tes berhasil dikirim!');
    }
}
