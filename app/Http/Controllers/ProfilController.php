<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profil.edit', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Validasi Input
        $request->validate([
            'email_pribadi' => 'nullable|email|max:255',
            'no_telp'       => 'required|numeric|digits_between:9,15',
            'alamat'        => 'nullable|string|max:500',
            'password'      => 'nullable|min:8|confirmed',
        ], [
            'no_telp.required'        => 'NOMOR WHATSAPP WAJIB DIISI UNTUK NOTIFIKASI.',
            'no_telp.numeric'         => 'NOMOR WHATSAPP HANYA BOLEH BERISI ANGKA.',
            'no_telp.digits_between'  => 'NOMOR WHATSAPP HARUS ANTARA 9-15 DIGIT ANGKA.',
            'password.min'            => 'PASSWORD MINIMAL HARUS 8 KARAKTER.',
            'password.confirmed'      => 'KONFIRMASI PASSWORD TIDAK COCOK.',
            'email_pribadi.email'     => 'FORMAT EMAIL PRIBADI TIDAK VALID.',
        ]);

        // 2. Siapkan Data untuk Update
        // Kita tidak perlu normalisasi manual di sini karena sudah ada Mutator di Model User.php
        $dataUpdate = [
            'email_pribadi' => $request->email_pribadi,
            'no_telp'       => $request->no_telp, // Model User akan otomatis mengubah ini jadi 62...
            'alamat'        => $request->alamat,
        ];

        // 3. Update Password jika diisi
        if ($request->filled('password')) {
            $dataUpdate['password'] = Hash::make($request->password);
        }

        // 4. Eksekusi Update
        // Menggunakan update() pada object $user yang diambil dari Auth
        $user->update($dataUpdate);

        return back()->with('success', 'PROFIL SIPUSTAKA BERHASIL DIPERBARUI!');
    }
}
