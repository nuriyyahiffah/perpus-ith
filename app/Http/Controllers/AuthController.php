<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\KategoriAnggota;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        // 1. Validasi Input (Pastikan @csrf ada di view login.blade.php)
        $request->validate([
            'email' => 'required|email',
            'password' => 'required', 
        ]);

        $email = $request->email;
        $passwordInput = $request->password;

        // 2. CEK KE TABEL SIAKAD MOCKUP (Khusus Mahasiswa)
        $mhsSiakad = DB::table('siakad_mahasiswa')
                        ->where('email', $email)
                        ->where('nim', $passwordInput)
                        ->first();

        if ($mhsSiakad) {
            $user = User::where('email', $email)->first();

            if (!$user) {
                $cekKategori = KategoriAnggota::where('nama_kategori', 'Mahasiswa')->first();
                $kategoriId = $cekKategori ? $cekKategori->id : null;

                $user = User::create([
                    'name' => ucwords(strtolower($mhsSiakad->nama)),
                    'email' => $mhsSiakad->email,
                    'nomor_identitas' => $mhsSiakad->nim,
                    'password' => Hash::make($mhsSiakad->nim),
                    'role' => 'mahasiswa',
                    'status_akun' => 'aktif',
                    'prodi' => $mhsSiakad->prodi,
                    'angkatan' => $mhsSiakad->angkatan,
                    'kategori_anggota_id' => $kategoriId,
                ]);
            }

            Auth::login($user);
            return $this->authenticatedRedirect($request, $user);
        }

        // 3. JIKA BUKAN MAHASISWA (ADMIN, PUSTAKAWAN, DOSEN)
        if (Auth::attempt(['email' => $email, 'password' => $passwordInput])) {
            $user = Auth::user();
            return $this->authenticatedRedirect($request, $user);
        }

        return back()->with('loginError', 'Email atau Password salah!');
    }

    /**
     * Helper untuk Redirect Berdasarkan Role
     */
    protected function authenticatedRedirect(Request $request, $user)
    {
        $request->session()->regenerate();

        // Cek status akun terlebih dahulu
        if ($user->status_akun == 'suspended') {
            Auth::logout();
            return redirect()->route('login')->with('loginError', 'Akun Anda ditangguhkan.');
        }

        // Paksa ke huruf kecil untuk menghindari error "Dosen" vs "dosen"
        $role = strtolower($user->role);

        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        } 
        
        if ($role === 'pustakawan') {
            return redirect()->route('pustakawan.dashboard');
        } 
        
        if ($role === 'dosen') {
            // Pastikan di web.php sudah ada ->name('dosen.beranda')
            return redirect()->route('dosen.beranda');
        }

        if ($role === 'mahasiswa') {
            return redirect()->route('mahasiswa.beranda');
        }

        // Jika role tidak dikenali, logout dan kembali ke login
        Auth::logout();
        return redirect()->route('login')->with('loginError', 'Role pengguna tidak valid.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}