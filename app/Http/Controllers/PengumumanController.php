<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengumumanController extends Controller
{
    // Menampilkan halaman daftar pengumuman di panel pustakawan
    public function index()
    {
        $pengumumans = Pengumuman::latest()->get();
        return view('pustakawan.pengumuman.index', compact('pengumumans'));
    }

    // Proses simpan gambar poster
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
        ]);

        // Simpan gambar ke folder storage/app/public/pengumuman
        $path = $request->file('gambar')->store('pengumuman', 'public');

        Pengumuman::create([
            'judul' => $request->judul,
            'gambar' => $path,
            'is_active' => true,
        ]);

        return back()->with('success', 'Poster pengumuman berhasil diunggah!');
    }

    // Menghapus pengumuman
    public function destroy($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);

        // Hapus file gambarnya dari folder storage
        Storage::disk('public')->delete($pengumuman->gambar);

        $pengumuman->delete();

        return back()->with('success', 'Pengumuman telah dihapus.');
    }
}
