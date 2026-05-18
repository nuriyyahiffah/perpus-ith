<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // WAJIB ADA untuk enkripsi password

class PustakawanController extends Controller
{
    public function index()
    {
        // Mengambil user dengan role 'pustakawan'
        $pustakawan = User::where('role', 'pustakawan')->latest()->get();
        return view('admin.pustakawan.index', compact('pustakawan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name'     => $request->name,
            'nip'      => $request->nip,
            'email'    => $request->email,
            'no_hp'    => $request->no_hp,
            'role'     => 'pustakawan',
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.pustakawan.index')->with('success', 'Akun Pustakawan baru berhasil ditambahkan!');
    }

    /**
     * Memproses Pembaruan Data Pustakawan (FUNGSI YANG HILANG)
     */
    public function update(Request $request, $id)
    {
        // 1. Cari data pustakawan berdasarkan ID di tabel users
        $user = User::findOrFail($id);

        // 2. Validasi input form (Email mengecualikan ID user ini agar tidak dianggap duplikat)
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8', // 'nullable' artinya boleh dikosongkan jika tidak mau ganti password
        ], [
            'email.unique' => 'Email ini sudah digunakan oleh pengguna lain!',
            'password.min' => 'Password baru minimal harus terdiri dari 8 karakter.',
        ]);

        // 3. Siapkan data utama untuk diperbarui
        $updateData = [
            'name'  => $request->name,
            'nip'   => $request->nip,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
        ];

        // 4. Periksa apakah admin mengisi kolom password baru
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        // 5. Eksekusi pembaruan data ke database
        $user->update($updateData);

        // 6. Kembalikan ke halaman index dengan notifikasi sukses
        return redirect()->route('admin.pustakawan.index')->with('success', 'Data pustakawan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('success', 'Data pustakawan berhasil dihapus.');
    }
}
