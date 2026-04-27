<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsulanBuku;
use Illuminate\Support\Facades\Auth;

class UsulanController extends Controller
{
    /**
     * 1. Menampilkan DAFTAR & RIWAYAT
     */
    public function index()
    {
        $user = Auth::user();

        // Jika Pustakawan/Admin: Lihat semua usulan dari semua user
        if ($user->role === 'pustakawan' || $user->role === 'admin') {
            $usulan = UsulanBuku::with('user')->latest()->get();
            return view('shared.transaksi.usulan_index', compact('usulan'));
        }

        // Jika Dosen atau Mahasiswa: Lihat riwayat miliknya sendiri
        if ($user->role === 'dosen' || $user->role === 'mahasiswa') {
            // Kita gunakan nama variabel 'riwayatUsulan' agar sesuai dengan error di gambar tadi
            $riwayatUsulan = UsulanBuku::where('user_id', $user->id)->latest()->get();
            
            // Arahkan ke view sesuai role masing-masing
            $viewPath = ($user->role === 'dosen') ? 'dosen.riwayat_usulan' : 'mahasiswa.riwayat_usulan';
            
            return view($viewPath, compact('riwayatUsulan'));
        }

        abort(403);
    }

    /**
     * 2. Menampilkan FORM INPUT USULAN
     */
    public function create()
    {
        $user = Auth::user();

        // Izinkan Dosen dan Mahasiswa
        if ($user->role === 'dosen' || $user->role === 'mahasiswa') {
            $viewPath = ($user->role === 'dosen') ? 'dosen.usulan' : 'mahasiswa.usulan';
            return view($viewPath);
        }

        abort(403, 'Akses dibatasi.');
    }

    /**
     * 3. Menyimpan data usulan ke Database
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'tahun' => 'nullable|numeric',
            'alasan' => 'nullable|string',
        ]);

        UsulanBuku::create([
            'user_id' => Auth::id(),
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'tahun' => $request->tahun,
            'alasan' => $request->alasan,
            'status' => 'pending',
        ]);

        // Cek kemana harus diarahkan setelah simpan
        $route = (Auth::user()->role === 'dosen') ? 'dosen.usulan.riwayat' : 'mahasiswa.usulan.riwayat';

        return redirect()->route($route)->with('success', 'USULAN BUKU BERHASIL DIKIRIM!');
    }

    /**
     * 4. Update status (Khusus Admin/Pustakawan)
     */
    public function updateStatus(Request $request, $id)
    {
        $item = UsulanBuku::findOrFail($id);
        $item->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'STATUS USULAN BERHASIL DIPERBARUI!');
    }
}