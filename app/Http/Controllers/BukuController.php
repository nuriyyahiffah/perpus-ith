<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\SearchLog;
use App\Models\Claim;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BukuController extends Controller
{
    /**
     * TAMPILAN BERANDA (Mahasiswa)
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($search) {
            SearchLog::create(['keyword' => $search]);
        }

        $bukuProdi = collect();
        if ($user && $user->prodi) {
            $bukuProdi = Claim::where('prodi', $user->prodi)
                ->whereIn('status', ['disetujui', 'approved'])
                ->with('buku')
                ->latest()
                ->get();
        }

        $semuaBuku = Buku::latest()->take(10)->get();

        $kataKunciPopuler = SearchLog::select('keyword', DB::raw('count(*) as total'))
            ->groupBy('keyword')
            ->orderBy('total', 'desc')
            ->take(10)
            ->pluck('keyword');

        $bukuPopuler = Buku::where(function($query) use ($kataKunciPopuler) {
            foreach ($kataKunciPopuler as $keyword) {
                $query->orWhere('judul', 'like', '%' . $keyword . '%');
            }
        })->take(5)->get();

        if ($bukuPopuler->isEmpty()) {
            $bukuPopuler = Buku::inRandomOrder()->take(5)->get();
        }

        $semua_buku_paginated = Buku::when($search, function($query) use ($search) {
                $query->where('judul', 'like', "%{$search}%");
            })->paginate(12);

        return view('mahasiswa.beranda', compact(
            'semua_buku_paginated',
            'semuaBuku',
            'bukuPopuler',
            'bukuProdi'
        ));
    }

    /**
     * TAMPILAN DETAIL BUKU
     */
    public function showDetail($id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);
        return view('shared.buku.detail', compact('buku'));
    }

    /**
     * DAFTAR KOLEKSI (Admin)
     */
    public function indexBukuAdmin(Request $request)
    {
        $search = $request->search;
        $semua_buku = Buku::with(['kategori'])
            ->when($search, function ($query) use ($search) {
                return $query->where('judul', 'like', "%{$search}%")
                             ->orWhere('kode_buku', 'like', "%{$search}%");
            })->latest()->get();

        return view('admin.daftar_buku', compact('semua_buku'));
    }

    /**
     * DASHBOARD PUSTAKAWAN
     */
    public function pustakawanDashboard(Request $request)
    {
        $search = $request->search;
        $semua_buku = Buku::when($search, function ($query) use ($search) {
            return $query->where('judul', 'like', "%{$search}%")
                         ->orWhere('kode_buku', 'like', "%{$search}%");
        })->latest()->get();

        $pengajuan = Peminjaman::where('status', 'pending')->get();
        return view('pustakawan.dashboard', compact('semua_buku', 'pengajuan'));
    }

    /**
     * DAFTAR KOLEKSI (Pustakawan)
     */
    public function indexBuku(Request $request)
    {
        $search = $request->search;
        $semua_buku = Buku::with(['kategori'])
            ->when($search, function ($query) use ($search) {
                return $query->where('judul', 'like', "%{$search}%")
                             ->orWhere('kode_buku', 'like', "%{$search}%");
            })->latest()->get();

        return view('pustakawan.daftar_buku', compact('semua_buku'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        return view('pustakawan.tambah_buku', compact('kategori'));
    }

    /**
     * PROSES SIMPAN BUKU
     */
    public function store(Request $request)
{
    $request->validate([
        'judul' => 'required|string|max:255',
        'kode_buku' => 'required', // Sekarang boleh sama
        'gambar_buku' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        'sinopsis' => 'required|string',
        'jumlah_halaman' => 'nullable|numeric',
        'stok' => 'required|integer',
        'no_induk' => 'required|unique:eksemplar,no_induk', // Tambahkan ini agar nomor induk tetap unik
    ]);

        $namaGambar = time() . '_cover.' . $request->gambar_buku->extension();
        $request->gambar_buku->move(public_path('images'), $namaGambar);

        Buku::create([
            'kode_buku'    => $request->kode_buku,
            'judul'        => $request->judul,
            'prodi'   => Auth::user()->prodi,
            'penulis'      => $request->penulis,
            'penerbit'     => $request->penerbit,
            'stok'         => $request->stok,
            'kategori_id'  => $request->kategori_id,
            'gambar_buku'  => $namaGambar,
            'jumlah_halaman' => $request->jumlah_halaman, // Simpan ke DB
            'sinopsis'     => $request->sinopsis,
            'klasifikasi'  => $request->klasifikasi,
            'tahun_terbit' => $request->tahun_terbit ?? date('Y'),            'isbn'         => $request->isbn,
            'no_induk'     => $request->no_induk,
        ]);

        $role = Auth::user()->role;
        return redirect()->route($role . '.daftar_buku')->with('success', 'Buku berhasil ditambahkan!');
    }

    /**
     * FORM EDIT BUKU
     */
    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $kategori = Kategori::all();
        // Menggunakan view di folder pustakawan agar konsisten (bisa diakses admin juga)
        return view('shared.edit_buku', compact('buku', 'kategori'));
    }

    /**
     * PROSES UPDATE BUKU
     */
    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'kode_buku' => 'required|unique:buku,kode_buku,' . $id, // Abaikan ID ini agar tidak error unique
            'sinopsis' => 'required|string',
            'stok' => 'required|integer',
        ]);

        if ($request->hasFile('gambar_buku')) {
            // Hapus gambar lama
            if ($buku->gambar_buku && File::exists(public_path('images/' . $buku->gambar_buku))) {
                File::delete(public_path('images/' . $buku->gambar_buku));
            }
            $namaGambar = time() . '_cover.' . $request->gambar_buku->extension();
            $request->gambar_buku->move(public_path('images'), $namaGambar);
            $buku->gambar_buku = $namaGambar;
        }

        $buku->update([
            'kode_buku'    => $request->kode_buku,
            'judul'        => $request->judul,
            'penulis'      => $request->penulis,
            'penerbit'     => $request->penerbit,
            'stok'         => $request->stok,
            'kategori_id'  => $request->kategori_id,
            'sinopsis'     => $request->sinopsis,
            'klasifikasi'  => $request->klasifikasi,
            'tahun_terbit' => $request->tahun_terbit ?? $buku->tahun_terbit,            'no_induk'     => $request->no_induk,
        ]);

        $role = Auth::user()->role;
        return redirect()->route($role . '.daftar_buku')->with('success', 'Data buku berhasil diperbarui!');
    }

    /**
     * HAPUS BUKU
     */
    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        if ($buku->gambar_buku && File::exists(public_path('images/' . $buku->gambar_buku))) {
            File::delete(public_path('images/' . $buku->gambar_buku));
        }
        $buku->delete();
        return redirect()->back()->with('success', 'Buku berhasil dihapus!');
    }
}
