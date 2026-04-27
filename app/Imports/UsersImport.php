<?php

namespace App\Imports;

use App\Models\User;
use App\Models\KategoriAnggota;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation; // Tambahkan ini
use Illuminate\Validation\Rule; // Tambahkan ini

class UsersImport implements ToModel, WithStartRow, WithValidation
{
    /**
     * Kita mulai baca dari baris ke-2 (karena baris 1 adalah judul kolom)
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * ATURAN VALIDASI:
     * Menghalangi data masuk jika NIM (index kolom 1) sudah ada di database.
     */
    public function rules(): array
    {
        return [
            '1' => Rule::unique('users', 'nomor_identitas'),
        ];
    }

    /**
     * PESAN ERROR CUSTOM:
     * Muncul di layar dashboard jika ada NIM yang kembar.
     */
    public function customValidationMessages()
    {
        return [
            '1.unique' => 'NIM :input sudah terdaftar. Data pada baris ini dilewati.',
        ];
    }

    public function model(array $row)
    {
        // Logika Angkatan: Ambil 2 angka pertama dari NIM (Misal 2210... -> 2022)
        $duaAngkaDepan = substr($row[1], 0, 2);
        $tahunAngkatan = '20' . $duaAngkaDepan;

        // Cari ID Kategori 'Mahasiswa'
        $kategoriMhs = KategoriAnggota::where('nama_kategori', 'Mahasiswa')->first();

        return new User([
            'name'                => $row[0], // Kolom A
            'nomor_identitas'     => $row[1], // Kolom B (NIM)
            'prodi'               => $row[2], // Kolom C
            'email'               => $row[3], // Kolom D
            'role'                => $row[4] ?? 'mahasiswa', // Kolom E (Default mahasiswa jika kosong)
            'password'            => Hash::make($row[1]), // Password default pake NIM saja supaya mudah diingat
            'angkatan'            => $tahunAngkatan,
            'kategori_anggota_id' => $kategoriMhs ? $kategoriMhs->id : null,
            'status_akun'         => 'aktif',
            'is_active'           => true,
        ]);
    }
}