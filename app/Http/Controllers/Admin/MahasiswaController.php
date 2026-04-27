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
use App\Imports\UsersImport; // Pastikan ini di-import

class MahasiswaController extends Controller
{
    /**
     * 1. Menampilkan daftar mahasiswa
     */
    public function index()
    {
        // Menampilkan data mahasiswa dengan relasi kategori
        $mahasiswa = User::where('role', 'mahasiswa')
                         ->with('kategori')
                         ->latest()
                         ->get();

        $kategori = KategoriAnggota::all();

        return view('shared.mahasiswa.index', compact('mahasiswa', 'kategori'));
    }

    /**
     * 2. Menyimpan data mahasiswa manual (Store)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nomor_identitas' => 'required|string|unique:users,nomor_identitas',
            'prodi' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
        ]);

        try {
            DB::beginTransaction();

            $duaAngkaDepan = substr($request->nomor_identitas, 0, 2);
            $tahunAngkatan = '20' . $duaAngkaDepan;

            $kategoriMhs = KategoriAnggota::where('nama_kategori', 'Mahasiswa')->first();

            User::create([
                'name'                => $request->name,
                'nomor_identitas'     => $request->nomor_identitas,
                'email'               => $request->email,
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
     * 3. Import Data via Excel (Perbaikan Utama)
     */
    public function import(Request $request)
    {
        // Validasi input file
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:2048'
        ], [
            'file_excel.required' => 'Silakan pilih file terlebih dahulu.',
            'file_excel.mimes' => 'Format file harus .xlsx atau .xls'
        ]);

        try {
            // Pastikan file terbaca
            if ($request->hasFile('file_excel')) {
                Excel::import(new UsersImport, $request->file('file_excel'));
                return redirect()->back()->with('success', 'Import Berhasil! Data mahasiswa telah diperbarui.');
            }
            
            return redirect()->back()->with('error', 'File tidak ditemukan.');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             // Menangkap error jika isi Excel tidak sesuai validasi di UsersImport
             $failures = $e->failures();
             $errorMsg = "Gagal Import baris ke: ";
             foreach ($failures as $failure) {
                 $errorMsg .= $failure->row() . " (" . implode(", ", $failure->errors()) . ") ";
             }
             return redirect()->back()->with('error', $errorMsg);

        } catch (\Exception $e) {
            // Menampilkan error umum (misal: NIM duplikat di database)
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * 4. Export Excel
     */
    public function export()
    {
        $fileName = 'Daftar_Mahasiswa_ITH_' . date('Ymd_His') . '.xlsx';
        return Excel::download(new MahasiswaExport, $fileName);
    }

    /**
     * 5. Update data
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nomor_identitas' => 'required|string|unique:users,nomor_identitas,' . $id,
            'prodi' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        try {
            $mahasiswa = User::findOrFail($id);
            $duaAngkaDepan = substr($request->nomor_identitas, 0, 2);
            $tahunAngkatan = '20' . $duaAngkaDepan;

            $mahasiswa->update([
                'name'            => $request->name,
                'nomor_identitas' => $request->nomor_identitas,
                'prodi'           => $request->prodi,
                'email'           => $request->email,
                'angkatan'        => $tahunAngkatan,
            ]);

            return redirect()->back()->with('success', 'Data ' . $mahasiswa->name . ' berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data.');
        }
    }

    /**
     * 6. Menghapus data
     */
    public function destroy($id)
    {
        try {
            $mahasiswa = User::findOrFail($id);
            $mahasiswa->delete();
            return redirect()->back()->with('success', 'Data mahasiswa berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data tidak ditemukan atau gagal dihapus.');
        }
    }
}