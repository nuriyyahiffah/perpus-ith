<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // Gunakan Model User agar bisa login
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PegawaiController extends Controller
{
    /**
     * Menampilkan daftar pegawai (Pustakawan/Admin)
     */
    public function index()
    {
        // Mengambil user yang rolenya adalah 'pustakawan' atau 'admin'
        // Ini membedakan pegawai dengan 'mahasiswa' atau 'dosen'
        $pegawai = User::whereIn('role', ['pustakawan', 'admin'])
                       ->latest()
                       ->get();

        return view('shared.pegawai.index', compact('pegawai'));
    }

    /**
     * Menyimpan data pegawai ke tabel users
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nip'     => 'required|unique:users,nomor_identitas',
            'nama'    => 'required|string|max:255',
            'jabatan' => 'required', // Input jabatan dari form akan masuk ke kolom 'role'
            'email'   => 'required|email|unique:users,email',
        ], [
            'nip.unique'   => 'NIP sudah terdaftar!',
            'email.unique' => 'Email sudah digunakan!',
        ]);

        try {
            DB::beginTransaction();

            // Simpan ke tabel users
            User::create([
                'name'            => $request->nama,
                'nomor_identitas' => $request->nip,
                'email'           => $request->email,
                // Masukkan nilai jabatan (pustakawan/admin) ke kolom role
                'role'            => strtolower($request->jabatan), 
                // Password otomatis menggunakan NIP
                'password'        => Hash::make($request->nip),
                'status_akun'     => 'aktif',
                'is_active'       => true,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Data Pegawai berhasil ditambahkan ke SIPUSTAKA.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus data pegawai
     */
    public function destroy($id)
    {
        try {
            $pegawai = User::findOrFail($id);
            $pegawai->delete();

            return redirect()->back()->with('success', 'Data Pegawai berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data.');
        }
    }
}