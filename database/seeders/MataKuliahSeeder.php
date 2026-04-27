<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MataKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel terlebih dahulu untuk menghindari error duplicate entry saat seeding ulang
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // Gunakan ini jika ada relasi foreign key
        MataKuliah::truncate();
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'kode_mk' => 'IK101',
                'nama_mk' => 'Algoritma dan Pemrograman',
                'prodi'   => 'Ilmu Komputer',
                'semester' => 1
            ],
            [
                'kode_mk' => 'IK102',
                'nama_mk' => 'Basis Data',
                'prodi'   => 'Ilmu Komputer',
                'semester' => 2
            ],
            [
                'kode_mk' => 'IK103',
                'nama_mk' => 'Pemrograman Web',
                'prodi'   => 'Ilmu Komputer',
                'semester' => 3
            ],
            [
                'kode_mk' => 'IK104',
                'nama_mk' => 'Rekayasa Perangkat Lunak',
                'prodi'   => 'Ilmu Komputer',
                'semester' => 4
            ],
            [
                'kode_mk' => 'IK105',
                'nama_mk' => 'Kecerdasan Buatan',
                'prodi'   => 'Ilmu Komputer',
                'semester' => 5
            ],
            // Kamu bisa menambahkan mata kuliah ITH lainnya di sini
        ];

        foreach ($data as $item) {
            MataKuliah::create($item);
        }
        
        $this->command->info('Seeding Mata Kuliah Ilmu Komputer berhasil!');
    }
}