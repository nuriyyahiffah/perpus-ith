<?php

namespace App\Http\Controllers;

use App\Models\Kategori; // Pastikan model Kategori di-import
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Menampilkan daftar kategori
     */
    public function index()
    {
        $kategori = Kategori::all();
        return view('shared.kategori.index', compact('kategori'));
    }

    /**
     * Menyimpan kategori baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori',
        ], [
            'nama_kategori.unique' => 'Kategori ini sudah ada!',
            'nama_kategori.required' => 'Nama kategori tidak boleh kosong.'
        ]);

        Kategori::create([
            'nama_kategori' => $request->nama_kategori
        ]);

        return redirect()->back()->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    /**
     * Menghapus kategori
     */
    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);

        // Cek apakah kategori ini sedang digunakan oleh buku tertentu
        if ($kategori->bukus()->count() > 0) {
            return redirect()->back()->with('error', 'Kategori tidak bisa dihapus karena masih digunakan oleh beberapa buku.');
        }

        $kategori->delete();
        return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }
}
