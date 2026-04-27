<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    /**
     * Menampilkan halaman form edit profil
     */
    public function edit()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Return ke folder profil file edit.blade.php
        return view('profil.edit', compact('user'));
    }

    /**
     * Memproses update data profil dan password
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Validasi Input
        $request->validate([
            'email_pribadi' => 'nullable|email|max:255',
            'no_telp'       => 'required|string|min:9|max:15', 
            'alamat'        => 'nullable|string|max:500',
            'password'      => 'nullable|min:8|confirmed',
        ], [
            'no_telp.required'    => 'NOMOR WHATSAPP WAJIB DIISI UNTUK NOTIFIKASI.',
            'password.min'        => 'PASSWORD MINIMAL HARUS 8 KARAKTER.',
            'password.confirmed'  => 'KONFIRMASI PASSWORD TIDAK COCOK.',
            'email_pribadi.email' => 'FORMAT EMAIL PRIBADI TIDAK VALID.',
        ]);

        // 2. Normalisasi Nomor Telepon (Format 62 untuk WhatsApp API)
        $nomor = preg_replace('/[^0-9]/', '', $request->no_telp);

        if (str_starts_with($nomor, '0')) {
            $nomor = '62' . substr($nomor, 1);
        } elseif (str_starts_with($nomor, '8')) {
            $nomor = '62' . $nomor;
        }
        // Jika sudah 62, biarkan saja

        // 3. Siapkan Data untuk Update
        $dataUpdate = [
            'email_pribadi' => $request->email_pribadi,
            'no_telp'       => $nomor,
            'alamat'        => $request->alamat,
        ];

        // 4. Update Password hanya jika kolom password diisi oleh dosen
        if ($request->filled('password')) {
            $dataUpdate['password'] = Hash::make($request->password);
        }

        // 5. Eksekusi Update
        $user->update($dataUpdate);

        // Kembali ke halaman sebelumnya dengan notifikasi sukses
        return back()->with('success', 'PROFIL SIPUSTAKA BERHASIL DIPERBARUI!');
    }
}