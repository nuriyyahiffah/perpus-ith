<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MahasiswaExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
    * Mengambil data mahasiswa dari database
    */
    public function collection()
    {
        return User::where('role', 'mahasiswa')
                    ->latest()
                    ->get();
    }

    /**
    * Mengatur Header (Judul Kolom) di Excel
    */
    public function headings(): array
    {
        return [
            ['LAPORAN DATA MAHASISWA - SIPUSTAKA ITH'], // Baris 1: Judul Laporan
            ['Dicetak pada: ' . now()->format('d-m-Y H:i')], // Baris 2: Waktu Cetak
            [], // Baris 3: Kosong (Spasi)
            [
                'No',
                'Nama Lengkap',
                'NIM',
                'Program Studi',
                'Angkatan',
                'Email',
                'Status Akun'
            ]
        ];
    }

    /**
    * Memetakan data dari database ke kolom Excel
    */
    private $rowNumber = 0;
    public function map($mahasiswa): array
    {
        return [
            ++$this->rowNumber,
            $mahasiswa->name,
            "'" . $mahasiswa->nomor_identitas, // Tambahkan kutip agar NIM tidak berubah jadi format scientific/angka di Excel
            $mahasiswa->prodi,
            $mahasiswa->angkatan ?? '-',
            $mahasiswa->email,
            strtoupper($mahasiswa->status_akun ?? 'AKTIF'),
        ];
    }

    /**
    * Memberikan gaya (styling) agar Excel terlihat profesional
    */
    public function styles(Worksheet $sheet)
    {
        return [
            // Tebalkan Judul Utama
            1    => ['font' => ['bold' => true, 'size' => 14]],
            // Tebalkan Header Tabel (Baris ke-4)
            4    => ['font' => ['bold' => true]],
            // Beri border otomatis untuk seluruh data bisa diatur di sini jika diperlukan
        ];
    }
}
