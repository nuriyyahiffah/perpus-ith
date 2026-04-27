<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\User;
use App\Models\Eksemplar;
use App\Models\Claim;
use App\Models\Peminjaman; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Str;

class BukuController extends Controller
{
    /**
     * Tampilan Utama Katalog
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        $role = strtolower($user->role);
        $buku = Buku::with('eksemplars')->latest()->get();
        $kategori = DB::table('kategori')->get(); 

        if ($role === 'dosen') {
            return view('dosen.beranda', compact('buku', 'kategori'));
        }

        return view('shared.buku.index', compact('buku', 'kategori'));
    }

    /**
     * Form Edit Buku
     */
    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $kategori = DB::table('kategori')->get(); 
        return view('shared.buku.edit_buku', compact('buku', 'kategori'));
    }

    /**
     * Simpan Perubahan Buku (Update)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul'                => 'required|string|max:255',
            'penulis'              => 'required|string',
            'klasifikasi'          => 'required',
            'tempat_penerbit'             => 'required',
            'tahun_terbit'         => 'required|numeric|digits:4',
            'tipe_pengarang_utama' => 'required|string',
            'peran_tambahan'       => 'nullable|string',
            'pengarang_tambahan'   => 'nullable|string',
            'gambar_buku'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $buku = Buku::findOrFail($id);
        
        try {
            $data = $request->except('gambar_buku');

            if ($request->hasFile('gambar_buku')) {
                // Hapus gambar lama
                if ($buku->gambar_buku && File::exists(public_path('images/' . $buku->gambar_buku))) {
                    File::delete(public_path('images/' . $buku->gambar_buku));
                }

                $file = $request->file('gambar_buku');
                $namaGambar = time() . '_' . Str::slug($request->judul) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images'), $namaGambar);
                $data['gambar_buku'] = $namaGambar;
            }

            $buku->update($data);

            return redirect()->route('shared.buku.index')->with('success', 'Katalog berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal memperbarui data: ' . $e->getMessage()]);
        }
    }

    /**
     * Simpan Buku Baru (Store)
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul'                => 'required|string|max:255',
            'penulis'              => 'required|string',
            'klasifikasi'          => 'required',
            'no_panggil'           => 'required|unique:buku,no_panggil',
            'penerbit'              => 'required',
            'tempat_terbit'         => 'required|string',
            'tahun_terbit'         => 'required|numeric|digits:4',
            'tipe_pengarang_utama' => 'required|string',
            'peran_tambahan'       => 'nullable|string',
            'pengarang_tambahan'   => 'nullable|string',
            'sinopsis' => 'nullable|string', // Tambahkan ini
            'gambar_buku'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $namaGambar = null;
            if ($request->hasFile('gambar_buku')) {
                $file = $request->file('gambar_buku');
                $namaGambar = time() . '_' . Str::slug($request->judul) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images'), $namaGambar);
            }

            $buku = Buku::create([
                'kode_buku'            => 'BK-' . strtoupper(Str::random(5)) . '-' . date('Y'),
                'kategori_id'          => $request->kategori_id,
                'judul'                => $request->judul,
                'penulis'              => $request->penulis,
                'isbn'                 => $request->isbn,
                'klasifikasi'          => $request->klasifikasi,
                'no_panggil'           => $request->no_panggil,
                'penerbit'             => $request->penerbit,
                'tempat_terbit'        => $request->tempat_terbit,
                'tahun_terbit'         => $request->tahun_terbit,
                'stok'                 => 0, 
                'gambar_buku'          => $namaGambar,
                'tipe_pengarang_utama' => $request->tipe_pengarang_utama,
                'peran_tambahan'       => $request->peran_tambahan,
                'pengarang_tambahan'   => $request->pengarang_tambahan,
                'bentuk_fisik'         => $request->bentuk_fisik,
                'jumlah_halaman'       => $request->jumlah_halaman,
                'sinopsis'             => $request->sinopsis, // Tambahkan ini
                'catatan'              => $request->catatan,
            ]);

            DB::commit();

            return redirect()->route('shared.buku.index')->with([
                'success' => 'Katalog berhasil disimpan! Silakan lanjut mendaftarkan eksemplar fisik.',
                'open_eksemplar' => true,
                'selected_buku_id' => $buku->id,
                'selected_buku_judul' => $buku->judul
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }

    /**
     * Simpan Eksemplar (DI-PERBAIKI)
     */
    public function storeEksemplar(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:buku,id', 
            'no_induk' => 'required|array',
            'no_induk.*' => 'required|unique:eksemplar,no_induk', // Mencegah duplikasi nomor induk
        ]);

        try {
            DB::beginTransaction();
            $jumlahBaru = 0;

            foreach ($request->no_induk as $index => $val) {
                if (!empty($val)) {
                    Eksemplar::create([
                        'buku_id'       => $request->buku_id,
                        'no_induk'      => trim($val),
                        // PERBAIKAN: Mengambil array berdasarkan index agar tidak "Array to String Conversion"
                        'no_barcode'    => $request->no_barcode[$index] ?? null, 
                        'no_rfid'       => $request->no_rfid[$index] ?? null,
                        'jenis_sumber'  => $request->jenis_sumber, 
                        'status'        => $request->status ?? 'tersedia',
                        'tgl_pengadaan' => now(),
                    ]);
                    $jumlahBaru++;
                }
            }

            // Update stok di tabel buku secara otomatis
            Buku::findOrFail($request->buku_id)->increment('stok', $jumlahBaru);
            
            DB::commit();
            return redirect()->route('shared.buku.index')->with('success', 'Eksemplar berhasil didaftarkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal simpan eksemplar: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Dashboard Pustakawan
     */
    public function pustakawanDashboard()
    {
        $totalPengguna = User::whereIn('role', ['mahasiswa', 'dosen'])->count();
        $totalKoleksi = Eksemplar::count();
        $pinjamanAktif = Peminjaman::where('status', 'dipinjam')->count();
        
        $peminjamanTerbaru = Peminjaman::with(['user', 'buku'])
                                ->where('status', 'dipinjam')
                                ->latest()
                                ->take(5)
                                ->get();

        $klaimDosen = Claim::with(['user', 'buku'])
                    ->where('status', 'pending')
                    ->latest()
                    ->get();

        return view('pustakawan.dashboard', compact(
            'totalPengguna', 'totalKoleksi', 'pinjamanAktif', 'peminjamanTerbaru', 'klaimDosen'
        ));
    }

    /**
     * Persetujuan Rekomendasi Prodi (Claim)
     */
    public function approvePeminjaman($id) 
    {
        try {
            $claim = Claim::findOrFail($id);
            $claim->update(['status' => 'disetujui']);
            return redirect()->back()->with('success', 'Buku berhasil direkomendasikan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyetujui: ' . $e->getMessage());
        }
    }

    public function rejectPeminjaman($id)
    {
        try {
            Claim::findOrFail($id)->update(['status' => 'ditolak']);
            return redirect()->back()->with('success', 'Rekomendasi ditolak.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menolak.');
        }
    }

    /**
     * Hapus Buku
     */
    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        if ($buku->gambar_buku) { 
            File::delete(public_path('images/' . $buku->gambar_buku)); 
        }
        $buku->eksemplars()->delete();
        $buku->delete();
        return redirect()->route('shared.buku.index')->with('success', 'Data berhasil dihapus.');
    }
}