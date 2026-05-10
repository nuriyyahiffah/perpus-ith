<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsulanBuku;
use Illuminate\Support\Facades\Auth;

class UsulanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = strtolower($user->role); // Gunakan strtolower agar aman

        // 1. Admin & Pustakawan
        if ($role === 'pustakawan' || $role === 'admin') {
            $riwayatUsulan = UsulanBuku::with('user')->latest()->get();
            return view('shared.transaksi.usulan_index', compact('riwayatUsulan'));
        }

        // 2. Dosen ATAU Kaprodi ATAU Mahasiswa
        if (in_array($role, ['dosen', 'kaprodi', 'mahasiswa'])) {
            $riwayatUsulan = UsulanBuku::where('user_id', $user->id)->latest()->get();

            // Arahkan ke view dosen jika dia dosen/kaprodi
            $viewPath = ($role === 'dosen' || $role === 'kaprodi') ? 'dosen.riwayat_usulan' : 'mahasiswa.riwayat_usulan';

            return view($viewPath, compact('riwayatUsulan'));
        }

        abort(403);
    }

    public function create()
    {
        $role = strtolower(Auth::user()->role);

        // PERBAIKAN: Tambahkan 'kaprodi' di sini
        if (in_array($role, ['dosen', 'kaprodi', 'mahasiswa'])) {
            $viewPath = ($role === 'dosen' || $role === 'kaprodi') ? 'dosen.usulan' : 'mahasiswa.usulan';
            return view($viewPath);
        }

        abort(403, 'Akses dibatasi.');
    }

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

        $role = strtolower(Auth::user()->role);
        
        // PERBAIKAN: Redirect ke rute dosen jika dia dosen/kaprodi
        $route = ($role === 'dosen' || $role === 'kaprodi') ? 'dosen.usulan.riwayat' : 'mahasiswa.usulan.riwayat';

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
