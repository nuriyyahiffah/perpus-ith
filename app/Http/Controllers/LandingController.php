<?php

namespace App\Http\Controllers;

use App\Models\Buku; // Pastikan nama model Buku sudah benar
use App\Models\User;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        // Mengambil data untuk statistik (Referensi UNM)
        $totalBuku = Buku::count();
        // Menghitung user selain admin
        $totalAnggota = User::where('role', '!=', 'admin')->count();

        return view('index', compact('totalBuku', 'totalAnggota'));
    }
}
