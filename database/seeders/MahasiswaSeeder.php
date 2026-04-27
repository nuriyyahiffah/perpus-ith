<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $mahasiswa = [
            // KELAS A
            ['nim' => '221011001', 'nama' => 'THOARIQ MUSADDIK', 'email' => 'thoariq.m@ith.ac.id', 'prodi' => 'Ilmu Komputer', 'angkatan' => '2022'],
            ['nim' => '221011004', 'nama' => 'FADHIYA MUTHIA ANNISA', 'email' => 'fadhiya.m@ith.ac.id', 'prodi' => 'Ilmu Komputer', 'angkatan' => '2022'],
            ['nim' => '221011009', 'nama' => 'AHMAD IRFANDI', 'email' => 'irfandi.a@ith.ac.id', 'prodi' => 'Ilmu Komputer', 'angkatan' => '2022'],
            ['nim' => '221011010', 'nama' => 'IBNU AL-GAZALI BURHAN', 'email' => 'ibnu.a@ith.ac.id', 'prodi' => 'Ilmu Komputer', 'angkatan' => '2022'],
            ['nim' => '221011016', 'nama' => 'MUHAMMAD DZAKY', 'email' => 'dzaky.m@ith.ac.id', 'prodi' => 'Ilmu Komputer', 'angkatan' => '2022'],
            ['nim' => '221011021', 'nama' => 'MUH. ABUBAKAR TUNRU', 'email' => 'abubakar.t@ith.ac.id', 'prodi' => 'Ilmu Komputer', 'angkatan' => '2022'],
            ['nim' => '221011024', 'nama' => 'ANDI MUHAMMAD MARIO HUSAYFA', 'email' => 'mario.h@ith.ac.id', 'prodi' => 'Ilmu Komputer', 'angkatan' => '2022'],
            ['nim' => '221011027', 'nama' => 'PUTRI FELIZA RAMADHANI', 'email' => 'putri.f@ith.ac.id', 'prodi' => 'Ilmu Komputer', 'angkatan' => '2022'],
            ['nim' => '221011031', 'nama' => 'OSAMA IYAD AL GHOZY', 'email' => 'osama.i@ith.ac.id', 'prodi' => 'Ilmu Komputer', 'angkatan' => '2022'],
            ['nim' => '221011032', 'nama' => 'REYNALDI PRASETYA RAHMAN', 'email' => 'reynaldi.p@ith.ac.id', 'prodi' => 'Ilmu Komputer', 'angkatan' => '2022'],

            // KELAS B
            ['nim' => '221011002', 'nama' => 'MUHAIMIN NUZUL', 'email' => 'muhaimin.n@ith.ac.id', 'prodi' => 'Ilmu Komputer', 'angkatan' => '2022'],
            ['nim' => '221011005', 'nama' => 'LAYLI ROSALINA', 'email' => 'layli.r@ith.ac.id', 'prodi' => 'Ilmu Komputer', 'angkatan' => '2022'],
            ['nim' => '221011007', 'nama' => 'SYAHRAENI SALSABILA SIRAJUDDIN', 'email' => 'syahraeni.s@ith.ac.id', 'prodi' => 'Ilmu Komputer', 'angkatan' => '2022'],
            ['nim' => '221011012', 'nama' => 'AINUN FATWA', 'email' => 'ainun.f@ith.ac.id', 'prodi' => 'Ilmu Komputer', 'angkatan' => '2022'],
            ['nim' => '221011017', 'nama' => 'WIDYA PUSPITA SARI', 'email' => 'widya.p@ith.ac.id', 'prodi' => 'Ilmu Komputer', 'angkatan' => '2022'],

            // KELAS C
            ['nim' => '221011006', 'nama' => 'MUHAMMAD AHYAWARA', 'email' => 'ahyawara.m@ith.ac.id', 'prodi' => 'Ilmu Komputer', 'angkatan' => '2022'],
            ['nim' => '221011008', 'nama' => 'ARYANDI', 'email' => 'aryandi@ith.ac.id', 'prodi' => 'Ilmu Komputer', 'angkatan' => '2022'],
            ['nim' => '221011011', 'nama' => 'ST. NUR. AISYAH. S', 'email' => 'aisyah.s@ith.ac.id', 'prodi' => 'Ilmu Komputer', 'angkatan' => '2022'],

            // KELAS D
            ['nim' => '221011003', 'nama' => 'MUHAMMAD ALFARIZI RIDWAN GUZASIAH', 'email' => 'alfarizi.m@ith.ac.id', 'prodi' => 'Ilmu Komputer', 'angkatan' => '2022'],
            ['nim' => '221011013', 'nama' => 'NURIYYAH IFFAH ARMANSYAH', 'email' => 'nuriyyah.i@ith.ac.id', 'prodi' => 'Ilmu Komputer', 'angkatan' => '2022'],
            ['nim' => '221011015', 'nama' => 'MUHAMMAD FARUQ AL-FAUZI S.', 'email' => 'faruq.m@ith.ac.id', 'prodi' => 'Ilmu Komputer', 'angkatan' => '2022'],
        ];

        foreach ($mahasiswa as $mhs) {
            DB::table('siakad_mahasiswa')->updateOrInsert(
                ['nim' => $mhs['nim']], // Unik berdasarkan NIM
                [
                    'nama' => $mhs['nama'],
                    'email' => $mhs['email'],
                    'prodi' => $mhs['prodi'],
                    'angkatan' => $mhs['angkatan'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
