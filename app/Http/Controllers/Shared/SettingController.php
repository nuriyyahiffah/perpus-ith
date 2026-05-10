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
    public function backup()
{
    try {
        $filename = "backup-sipustaka-" . date('Y-m-d_H-i-s') . ".sql";
        $path = storage_path('app/' . $filename);

        // JIKA KAMU PAKAI XAMPP, gunakan path ini:
        $mysqldumpPath = 'C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysqldump.exe';
        
        // JIKA KAMU PAKAI LARAGON, biasanya:
        // $mysqldumpPath = 'C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysqldump.exe';

        $command = sprintf(
            '"%s" --user=%s --password=%s --host=%s %s > "%s"',
            $mysqldumpPath,
            env('DB_USERNAME'),
            env('DB_PASSWORD'),
            env('DB_HOST'),
            env('DB_DATABASE'),
            $path
        );

        exec($command);

        // Cek apakah file ada dan ukurannya lebih dari 0
        if (file_exists($path) && filesize($path) > 0) {
            return response()->download($path)->deleteFileAfterSend(true);
        }

        return back()->with('error', 'Gagal memproses data. Pastikan path mysqldump benar.');
    } catch (\Exception $e) {
        return back()->with('error', 'Error: ' . $e->getMessage());
    }
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