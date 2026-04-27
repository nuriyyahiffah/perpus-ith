<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataDosen = [
            [
                'name' => 'Salsabila Adriani Mawar',
                'email' => 'salsabilaadrianimawar@ith.ac.id',
                'role' => 'dosen',
                'kategori_anggota_id' => 2,
                'nomor_identitas' => '199001012020031001', // Ganti dengan NIP asli
                'password' => Hash::make('199001012020031001'), // Password awal adalah NIP
                'prodi' => 'Ilmu Komputer', // Sesuaikan prodi beliau
            ],
            /* [
                'name' => 'Nama Dosen Lain',
                'email' => 'namadosenlain@ith.ac.id',
                'role' => 'dosen',
                'nim_nidn' => 'NIP_DI_SINI',
                'password' => Hash::make('NIP_DI_SINI'),
                'prodi' => 'Ilmu Komputer',
            ], 
            */
        ];

        foreach ($dataDosen as $dosen) {
            // Menggunakan updateOrCreate agar tidak duplikat jika seeder dijalankan ulang
            User::updateOrCreate(
                ['email' => $dosen['email']], 
                $dosen
            );
        }
    }
}