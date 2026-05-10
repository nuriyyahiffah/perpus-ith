<?php

namespace App\Http\Controllers\Admin;

use App\Exports\MahasiswaExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\KategoriAnggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Imports\UsersImport;
use Illuminate\Support\Str;

class MahasiswaController extends Controller
{
    /**
     * 1. Menampilkan daftar mahasiswa
     */
    public function index()
    {
        $mahasiswa = User::where('role', 'mahasiswa')
                         ->with('kategori')
                         ->latest()
                         ->get();

        $kategori = KategoriAnggota::all();

        return view('shared.mahasiswa.index', compact('mahasiswa', 'kategori'));
    }

    /**
     * 2. Menyimpan data mahasiswa (Format Email: namalengkap.nim@mahasiswa.ith.ac.id)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nomor_identitas' => 'required|string|unique:users,nomor_identitas',
            'prodi' => 'required|string|max:100',
        ]);

        try {
            DB::beginTransaction();

            // 1. Logika Angkatan
            $duaAngkaDepan = substr($request->nomor_identitas, 0, 2);
            $tahunAngkatan = '20' . $duaAngkaDepan;

            // 2. Logika Format Email Baru
            // strtolower mengubah ke huruf kecil semua
            // str_replace(' ', '', ...) menghapus semua spasi agar nama tersambung utuh
            $namaTanpaSpasi = str_replace(' ', '', strtolower($request->name));
            $emailOtomatis = $namaTanpaSpasi . '.' . $request->nomor_identitas . '@mahasiswa.ith.ac.id';

            $kategoriMhs = KategoriAnggota::where('nama_kategori', 'Mahasiswa')->first();

            User::create([
                'name'                => $request->name,
                'nomor_identitas'     => $request->nomor_identitas,
                'email'               => $emailOtomatis, 
                'password'            => Hash::make($request->nomor_identitas),
                'role'                => 'mahasiswa',
                'prodi'               => $request->prodi,
                'angkatan'            => $tahunAngkatan,
                'kategori_anggota_id' => $kategoriMhs ? $kategoriMhs->id : null,
                'status_akun'         => 'aktif',
                'is_active'           => true,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Mahasiswa ' . $request->name . ' Berhasil Ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambah data: ' . $e->getMessage());
        }
    }

    /**
     * 3. Update data mahasiswa
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nomor_identitas' => 'required|string|unique:users,nomor_identitas,' . $id,
            'prodi' => 'required|string|max:100',
        ]);

        try {
            $mahasiswa = User::findOrFail($id);
            
            $duaAngkaDepan = substr($request->nomor_identitas, 0, 2);
            $tahunAngkatan = '20' . $duaAngkaDepan;
            
            // Generate ulang email dengan nama tanpa spasi
            $namaTanpaSpasi = str_replace(' ', '', strtolower($request->name));
            $emailOtomatis = $namaTanpaSpasi . '.' . $request->nomor_identitas . '@mahasiswa.ith.ac.id';

            $mahasiswa->update([
                'name'            => $request->name,
                'nomor_identitas' => $request->nomor_identitas,
                'email'           => $emailOtomatis,
                'prodi'           => $request->prodi,
                'angkatan'        => $tahunAngkatan,
            ]);

            return redirect()->back()->with('success', 'Data ' . $mahasiswa->name . ' berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data.');
        }
    }

    /**
     * 4. Menghapus data mahasiswa
     */
    public function destroy($id)
    {
        try {
            $mahasiswa = User::findOrFail($id);
            $mahasiswa->delete();
            return redirect()->back()->with('success', 'Data mahasiswa berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data.');
        }
    }

    /**
     * 5. Import Data via Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            if ($request->hasFile('file_excel')) {
                Excel::import(new UsersImport, $request->file('file_excel'));
                return redirect()->back()->with('success', 'Import Berhasil!');
            }
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * 6. Export Excel
     */
    public function export()
    {
        $fileName = 'Daftar_Mahasiswa_ITH_' . date('Ymd_His') . '.xlsx';
        return Excel::download(new MahasiswaExport, $fileName);
    }
}