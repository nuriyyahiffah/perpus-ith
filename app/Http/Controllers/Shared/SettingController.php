<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        // Mengambil semua data setting dan mengubahnya jadi array agar mudah dipanggil di view
        $settings = Setting::pluck('value', 'key')->all();
        return view('shared.setting.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // Ambil semua data dari form kecuali token CSRF
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            // Update jika key sudah ada, Create jika belum ada
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'category' => $this->getCategory($key)]
            );
        }

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui!');
    }

    // Helper sederhana untuk menentukan kategori secara otomatis
    private function getCategory($key)
    {
        $categories = [
            'nama_perpus' => 'profil',
            'alamat' => 'profil',
            'max_buku' => 'aturan',
            'durasi_pinjam' => 'aturan',
            'denda_harian' => 'aturan',
            'wa_token' => 'api',
        ];

        return $categories[$key] ?? 'umum';
    }
}