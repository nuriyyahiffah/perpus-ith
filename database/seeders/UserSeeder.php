<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 1. Akun Admin Utama (Update jika ada, Create jika tidak ada)
        User::updateOrCreate(
            ['email' => 'admin@ith.ac.id'], // Kolom kunci pencarian
            [
                'name' => 'Admin ITH',
                'password' => Hash::make('admin123'),
                'no_telp' => '628123456789',
                'role' => 'admin',
                'status_akun' => 'aktif', // Pastikan kolom ini ada di migration
            ]
        );

        // 2. Akun Pustakawan Baru
        User::updateOrCreate(
            ['email' => 'pustakawan@ith.ac.id'], // Kolom kunci pencarian
            [
                'name' => 'Pustakawan ITH',
                'password' => Hash::make('pustakawan123'),
                'no_telp' => '6288970333006',
                'role' => 'pustakawan',
                'status_akun' => 'aktif',
            ]
        );

        $this->command->info('Data Admin & Pustakawan ITH Berhasil Diperbarui/Dibuat!');
    }
}
