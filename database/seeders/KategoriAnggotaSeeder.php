<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriAnggota;

class KategoriAnggotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $data = [
        ['nama_kategori' => 'Mahasiswa', 'maksimal_pinjam' => 3, 'durasi_pinjam' => 7],
        ['nama_kategori' => 'Dosen', 'maksimal_pinjam' => 10, 'durasi_pinjam' => 30],
        ['nama_kategori' => 'Pegawai', 'maksimal_pinjam' => 5, 'durasi_pinjam' => 14],
    ];

    foreach ($data as $item) {
        \App\Models\KategoriAnggota::updateOrCreate(['nama_kategori' => $item['nama_kategori']], $item);
    }
}
}
