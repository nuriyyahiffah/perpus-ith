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
        $prodiDipilih = $request->get('prodi'); // Tangkap input filter prodi

        /*
        |--------------------------------------------------------------------------
        | FILTER PRODI (Rekomendasi Kaprodi via Tabel Jembatan)
        |--------------------------------------------------------------------------
        | Kita taruh filter prodi di atas agar pencarian kata kunci bisa mengkerucut
        | di dalam lingkup prodi yang dipilih.
        */
        if ($prodiDipilih) {
            // PERBAIKAN: Menggunakan relasi serbaguna. Pastikan di Model Buku.php
            // ada fungsi bernama bukuProdi() atau prodiRekomendasi().
            // Di sini kita coba pakai 'bukuProdi'. Jika di model Anda bernama 'prodiRekomendasi', silakan ganti teks ini.
            $query->whereHas('bukuProdi', function($q) use ($prodiDipilih) {
                $q->where('nama_prodi', $prodiDipilih);
            });
        }

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
                  ->orWhere('tahun_terbit', 'like', '%' . $search . '%');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | SORTING (Pengurutan)
        |--------------------------------------------------------------------------
        */
        if ($request->sort == 'a-z') {
            $query->orderBy('judul', 'asc');
        }
        elseif ($request->sort == 'z-a') {
            $query->orderBy('judul', 'desc');
        }
        elseif ($request->sort == 'terbaru' || $request->sort == 'baru') {
            $query->orderByRaw('CAST(tahun_terbit AS UNSIGNED) DESC');
        }
        elseif ($request->sort == 'lama') {
            $query->orderByRaw('CAST(tahun_terbit AS UNSIGNED) ASC');
        }
        else {
            $query->latest();
        }

        /*
        |--------------------------------------------------------------------------
        | PAGINATION & DATA FETCHING
        |--------------------------------------------------------------------------
        | PERBAIKAN: Baik Guest maupun Auth HARUS menggunakan ->paginate()
        | agar fungsi ->total() dan ->links() di file Blade tidak memicu error crash!
        */
        if (Auth::check()) {
            // User login bisa melihat 15 buku per halaman
            $buku = $query->paginate(15)->withQueryString();
        } else {
            // Pengunjung umum dibatasi hanya 8 buku, namun tetap berwujud objek paginator
            $buku = $query->paginate(8)->withQueryString();
        }

        // Daftar resmi Program Studi di ITH untuk di-render ke dropdown HTML
        $daftarProdi = [
            'Ilmu Komputer',
            'Sistem Informasi',
            'Teknik Sipil',
            'Matematika'
        ];

        // Masukkan semua variabel ke view
        return view('katalog.index', compact('buku', 'daftarProdi', 'prodiDipilih'));
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
