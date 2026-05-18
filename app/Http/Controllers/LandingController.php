<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\User;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $totalBuku = Buku::count();
        $totalAnggota = User::where('role', '!=', 'admin')->count();
        $bukuTerbaru = Buku::latest()->get();

        return view('beranda', compact('totalBuku', 'totalAnggota', 'bukuTerbaru'));
    }

    // TAMBAHKAN METHOD INI:
    public function katalog(Request $request)
    {
        // Mengambil semua data buku agar bisa difilter di halaman katalog khusus
        $semuaBuku = Buku::latest()->get();

        return view('katalog_publik', compact('semuaBuku'));
    }
}
