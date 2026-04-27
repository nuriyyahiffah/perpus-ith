<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

{
    $this->call([
        KategoriAnggotaSeeder::class,
        // Tambahkan seeder lain di sini jika ada (UserSeeder, dll)
    ]);

    // 1. Akun Admin (Sudah ada di database kamu)
    // Tetap biarkan jika ingin digunakan sebagai Super Admin

    // 2. Membuat Akun Pustakawan Baru
    \App\Models\User::create([
        'name' => 'Staf Pustakawan',
        'email' => 'pustakawan@ith.ac.id', // Gunakan email berbeda dari admin
        'password' => \Illuminate\Support\Facades\Hash::make('pustakawan123'),
        'no_telp' => '62889703330', // Isi dengan nomor WA pustakawan
        'role' => 'pustakawan',        // Role khusus pustakawan
    ]);
}



    }

}
