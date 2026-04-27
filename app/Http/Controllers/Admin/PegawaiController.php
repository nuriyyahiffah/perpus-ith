<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    /**
     * Menampilkan halaman daftar pegawai.
     * Halaman ini akan dipanggil oleh view yang ada di folder shared.
     */
    public function index()
    {
        // Mengambil data pegawai, diurutkan dari yang terbaru
        $pegawai = Pegawai::latest()->get();

        // Mengarahkan ke file resources/views/shared/pegawai/index.blade.php
        return view('shared.pegawai.index', compact('pegawai'));
    }

    /**
     * Menyimpan data pegawai baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nip'     => 'required|unique:pegawais,nip',
            'nama'    => 'required|string|max:255',
            'jabatan' => 'required',
            'email'   => 'required|email|unique:pegawais,email',
            'telepon' => 'nullable',
        ], [
            // Custom pesan error jika diperlukan
            'nip.unique'   => 'NIP sudah terdaftar di sistem!',
            'email.unique' => 'Email sudah digunakan oleh pegawai lain!',
        ]);

        try {
            // Simpan data menggunakan mass assignment
            Pegawai::create([
                'nip'     => $request->nip,
                'nama'    => $request->nama,
                'jabatan' => $request->jabatan,
                'email'   => $request->email,
                'telepon' => $request->telepon,
            ]);

            return redirect()->back()->with('success', 'Data Pegawai berhasil ditambahkan ke SIPUSTAKA.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    /**
     * Menghapus data pegawai.
     */
    public function destroy($id)
    {
        try {
            $pegawai = Pegawai::findOrFail($id);
            $pegawai->delete();

            return redirect()->back()->with('success', 'Data Pegawai berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data.');
        }
    }
}