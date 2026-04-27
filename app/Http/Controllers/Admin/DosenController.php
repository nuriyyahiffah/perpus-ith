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
        $dosen = User::where('role', 'dosen')->latest()->get();
        $kategori = KategoriAnggota::all();
        return view('shared.dosen.index', compact('dosen', 'kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nomor_identitas' => 'required|unique:users,nomor_identitas',
            'email' => 'required|email|unique:users,email',
            'prodi' => 'required',
        ], [
            'nomor_identitas.unique' => 'NIP/NIDN SUDAH TERDAFTAR DI SISTEM.',
            'email.unique' => 'EMAIL SUDAH DIGUNAKAN.',
        ]);

        $kategoriDosen = KategoriAnggota::where('nama_kategori', 'Dosen')->first();

        User::create([
            'name'            => $request->name,
            'nomor_identitas' => $request->nomor_identitas,
            'email'           => $request->email,
            'password'        => Hash::make($request->nomor_identitas),
            'role'            => 'dosen',
            'prodi'           => $request->prodi,
            'status_akun'     => 'aktif',
            'kategori_anggota_id' => $kategoriDosen ? $kategoriDosen->id : null,
        ]);

        return redirect()->back()->with('success', 'DATA DOSEN BERHASIL DITAMBAHKAN!');
    }

    public function update(Request $request, $id)
    {
        // Jurus 1: Definisikan tipe secara eksplisit
        /** @var \App\Models\User $user */
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'nomor_identitas' => [
                'required',
                // Jurus 2: Gunakan variabel $id langsung dari parameter fungsi (ini pasti tidak merah)
                Rule::unique('users')->ignore($id), 
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($id),
            ],
            'prodi' => 'required',
        ], [
            'nomor_identitas.unique' => 'NIP/NIDN INI SUDAH DIGUNAKAN DOSEN LAIN.',
        ]);

        $user->name = $request->name;
        $user->nomor_identitas = $request->nomor_identitas;
        $user->email = $request->email;
        $user->prodi = $request->prodi;

        if ($request->has('reset_password')) {
            $user->password = Hash::make($request->nomor_identitas);
        }

        $user->save();

        return redirect()->back()->with('success', 'DATA DOSEN BERHASIL DIPERBARUI!');
    }

    public function destroy($id)
    {
        // Jurus 3: Hindari menghapus akun sendiri — bandingkan dengan Auth::id()
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

        // Cek apakah password lama benar
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'PASSWORD LAMA TIDAK SESUAI!');
        }

        // Update ke password baru
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'PASSWORD BERHASIL DIPERBARUI!');
    }

}