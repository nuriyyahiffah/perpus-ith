<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KatalogController extends Controller
{
    /**
     * Menampilkan halaman katalog buku
     */
    public function index(Request $request)
    {
        $query = Buku::query();

        /*
        |--------------------------------------------------------------------------
        | SEARCH (Pencarian)
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', '%' . $search . '%')
                  ->orWhere('penulis', 'like', '%' . $search . '%')
                  // Kolom 'kategori' dihapus karena menyebabkan error (tidak ada di tabel buku)
                  ->orWhere('tahun_terbit', 'like', '%' . $search . '%');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | SORTING (Pengurutan)
        |--------------------------------------------------------------------------
        */
        if ($request->sort == 'a-z') {
            // Judul A-Z
            $query->orderBy('judul', 'asc');
        } 
        elseif ($request->sort == 'z-a') {
            // Judul Z-A
            $query->orderBy('judul', 'desc');
        } 
        elseif ($request->sort == 'terbaru') {
            // Tahun terbaru (Menggunakan tahun_terbit sesuai database)
            $query->orderByRaw('CAST(tahun_terbit AS UNSIGNED) DESC');
        } 
        elseif ($request->sort == 'lama') {
            // Tahun terlama
            $query->orderByRaw('CAST(tahun_terbit AS UNSIGNED) ASC');
        } 
        else {
            // Default terbaru berdasarkan waktu input (created_at)
            $query->latest();
        }

        /*
        |--------------------------------------------------------------------------
        | PAGINATION (Pembagian Halaman)
        |--------------------------------------------------------------------------
        */
        if (Auth::check()) {
            // User login bisa lihat banyak buku dengan pagination
            $buku = $query->paginate(15)->withQueryString();
        } else {
            // Pengunjung umum hanya lihat 8 buku tanpa pagination
            $buku = $query->take(8)->get();
        }

        return view('katalog.index', compact('buku'));
    }

    /**
     * Menampilkan detail buku
     */
    public function showDetail($id)
    {
        $buku = Buku::findOrFail($id);

        return view('katalog.detail', compact('buku'));
    }
}