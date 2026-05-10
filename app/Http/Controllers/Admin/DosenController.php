<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\KategoriAnggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DosenController extends Controller
{
    public function index()
    {
        // Mengambil data user yang memiliki role 'dosen' atau 'kaprodi'
        // Diurutkan berdasarkan nama agar rapi
        $dosen = User::whereIn('role', ['dosen', 'kaprodi'])
                      ->orderBy('name', 'asc')
                      ->get();

        // Mengambil data kategori untuk dropdown jika diperlukan di modal tambah
        $kategoriDosen = KategoriAnggota::where('nama_kategori', 'Dosen')->first();

        // Mengarahkan ke file view (Pastikan file ini ada di folder resources/views/admin/dosen/)
        return view('shared.dosen.index', compact('dosen', 'kategoriDosen'));
    }


   public function store(Request $request)
{
    // 1. Validasi dengan aturan yang benar
    $request->validate([
        'name'            => 'required|string|max:255|unique:users,name',
        'nomor_identitas' => 'required|unique:users,nomor_identitas',
        'email'           => 'required|email|unique:users,email',
        'prodi'           => 'required',
        'role'            => 'required|in:dosen,kaprodi', // Gunakan 'in' untuk membatasi pilihan
        'no_telp'         => 'nullable|string|max:15',
    ], [
        'name.unique'            => 'NAMA DOSEN SUDAH TERDAFTAR.',
        'nomor_identitas.unique' => 'NIP/NIDN SUDAH TERDAFTAR.',
        'email.unique'           => 'EMAIL SUDAH DIGUNAKAN.',
    ]);

    // 2. Cari ID Kategori untuk Dosen
    $kategoriDosen = KategoriAnggota::where('nama_kategori', 'Dosen')->first();

    // 3. Simpan data ke database
    User::create([
        'name'                => $request->name,
        'nomor_identitas'     => $request->nomor_identitas,
        'email'               => $request->email,
        'password'            => Hash::make(trim($request->nomor_identitas)),
        'role'                => $request->role, // Ini akan menyimpan 'kaprodi' atau 'dosen'
        'prodi'               => $request->prodi,
        'no_telp'             => $request->no_telp,
        'status_akun'         => 'aktif',
        'kategori_anggota_id' => $kategoriDosen ? $kategoriDosen->id : null,
    ]);

    return redirect()->back()->with('success', 'DATA PENGAJAR BERHASIL DITAMBAHKAN!');
}



    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($id)],
            'nomor_identitas' => ['required', Rule::unique('users')->ignore($id)],
            'email' => ['required', 'email', Rule::unique('users')->ignore($id)],
            'prodi' => 'required',
            'role' => 'required|in:dosen,kaprodi', // Pastikan role juga divalidasi saat update
        ], [
            'name.unique'            => 'NAMA DOSEN INI SUDAH ADA DI DATA LAIN.',
            'nomor_identitas.unique' => 'NIP/NIDN INI SUDAH DIGUNAKAN DOSEN LAIN.',
        ]);

        $user->name = $request->name;
        $user->nomor_identitas = $request->nomor_identitas;
        $user->email = $request->email;
        $user->prodi = $request->prodi;

        // PERBAIKAN 3: Update role agar bisa berubah dari Dosen ke Kaprodi atau sebaliknya
        $user->role = $request->role;

        if ($request->has('reset_password')) {
            $user->password = Hash::make($request->nomor_identitas);
        }

        $user->save();

        return redirect()->back()->with('success', 'DATA BERHASIL DIPERBARUI!');
    }

    public function destroy($id)
    {
        if ((int) $id === Auth::id()) {
            return redirect()->back()->with('error', 'ANDA TIDAK BISA MENGHAPUS AKUN SENDIRI!');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'DATA DOSEN BERHASIL DIHAPUS!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'PASSWORD LAMA TIDAK SESUAI!');
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'PASSWORD BERHASIL DIPERBARUI!');
    }
}
