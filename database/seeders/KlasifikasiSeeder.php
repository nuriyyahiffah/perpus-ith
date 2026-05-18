<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KlasifikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['kode_klass' => '000', 'nama_klass' => '000 - Karya Umum', 'warna' => '#0000FF'],
            ['kode_klass' => '100', 'nama_klass' => '100 - Filsafat dan Psikologi', 'warna' => '#FFFF00'],
            ['kode_klass' => '200', 'nama_klass' => '200 - Agama', 'warna' => '#008000'],
            ['kode_klass' => '300', 'nama_klass' => '300 - Ilmu Sosial', 'warna' => '#9C3397'],
            ['kode_klass' => '400', 'nama_klass' => '400 - Bahasa', 'warna' => '#EA3699'],
            ['kode_klass' => '500', 'nama_klass' => '500 - Ilmu Murni', 'warna' => '#E6429C'],
            ['kode_klass' => '600', 'nama_klass' => '600 - Ilmu Terapan (Teknologi)', 'warna' => '#01B568'],
            ['kode_klass' => '700', 'nama_klass' => '700 - Kesenian dan Olahraga', 'warna' => '#FF0000'],
            ['kode_klass' => '800', 'nama_klass' => '800 - Sastra', 'warna' => '#F59E0B'],
            ['kode_klass' => '900', 'nama_klass' => '900 - Sejarah dan Geografi', 'warna' => '#64748B'],
        ];

        foreach ($data as $item) {
            // updateOrInsert mencegah error duplikat jika seeder dijalankan ulang
            DB::table('klasifikasi')->updateOrInsert(
                ['kode_klass' => $item['kode_klass']],
                [
                    'nama_klass' => $item['nama_klass'],
                    'warna'      => $item['warna'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}