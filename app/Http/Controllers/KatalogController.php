<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KatalogController extends Controller
{
  
         public function index(Request $request)
{
    $query = Buku::query();

    // 1. Filter Pencarian Teks
    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('judul', 'like', '%' . $request->search . '%')
              ->orWhere('penulis', 'like', '%' . $request->search . '%');
        });
    }

    // 2. Filter Berdasarkan Prodi (Jika kolom di tabel buku namanya 'prodi')
    if ($request->has('prodi') && is_array($request->prodi)) {
        $query->whereIn('prodi', $request->prodi);
    }

    // 3. Logika Sortir
    if ($request->sort == 'a-z') {
        $query->orderBy('judul', 'asc');
    } else {
        $query->latest();
    }

    // Ambil daftar prodi unik dari tabel buku untuk ditampilkan di pilihan filter
    // Ini supaya filter yang muncul hanya prodi yang memang ada bukunya
    $listProdi = Buku::whereNotNull('prodi')->distinct()->pluck('prodi');

    if (Auth::check()) {
        $buku = $query->paginate(15);
    } else {
        $buku = $query->take(8)->get();
    }

    return view('katalog.index', compact('buku', 'listProdi'));
}

    /**
     * Menampilkan Detail Buku secara Publik
     */
    public function showDetail($id)
    {
        $buku = Buku::findOrFail($id);
        
        // Tambahkan view count jika ada kolomnya di database
        // $buku->increment('views'); 

        return view('katalog.detail', compact('buku'));
    }
}