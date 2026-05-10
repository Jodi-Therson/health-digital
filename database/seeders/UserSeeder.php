<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Pasien;
use App\Models\Dokter;
use App\Models\Perawat;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ADMIN
        User::create([
            'name' => 'Admin', 'email' => 'admin@healthdigital.id',
            'password' => Hash::make('admin123'), 'role' => 'admin', 'is_active' => true,
            'phone' => '082100000001',
        ]);

        // DOKTER
        $dokterData = [
            ['name' => 'Dr. Andi', 'email' => 'andi@healthdigital.id', 'spesialisasi' => 'Dokter Umum', 'no_str' => 'STR-DKT-001', 'tarif' => 100000, 'bio' => 'Dokter umum yang ramah melayani semua keluhan ringan.'],
            ['name' => 'Dr. Budi', 'email' => 'budi@healthdigital.id', 'spesialisasi' => 'Dokter Umum', 'no_str' => 'STR-DKT-002', 'tarif' => 120000, 'bio' => 'Dokter umum senior berpengalaman puluhan tahun.'],
            
            ['name' => 'Drg. Citra', 'email' => 'citra@healthdigital.id', 'spesialisasi' => 'Spesialis Gigi', 'no_str' => 'STR-GIG-001', 'tarif' => 200000, 'bio' => 'Dokter gigi spesialis konservasi gigi anak dan dewasa.'],
            ['name' => 'Drg. Diana', 'email' => 'diana@healthdigital.id', 'spesialisasi' => 'Spesialis Gigi', 'no_str' => 'STR-GIG-002', 'tarif' => 250000, 'bio' => 'Dokter gigi estetik dan implan gigi bersertifikat.'],
            
            ['name' => 'Dr. Eka', 'email' => 'eka@healthdigital.id', 'spesialisasi' => 'Spesialis Anak', 'no_str' => 'STR-ANK-001', 'tarif' => 250000, 'bio' => 'Dokter spesialis anak dengan sub-spesialisasi tumbuh kembang.'],
            ['name' => 'Dr. Fajar', 'email' => 'fajar@healthdigital.id', 'spesialisasi' => 'Spesialis Anak', 'no_str' => 'STR-ANK-002', 'tarif' => 220000, 'bio' => 'Ahli gizi dan perawatan khusus balita sakit kronis.'],
            
            ['name' => 'Dr. Gita', 'email' => 'gita@healthdigital.id', 'spesialisasi' => 'Spesialis Kandungan', 'no_str' => 'STR-KND-001', 'tarif' => 300000, 'bio' => 'Spesialis kebidanan dan kandungan, ahli operasi caesar.'],
            ['name' => 'Dr. Hari', 'email' => 'hari@healthdigital.id', 'spesialisasi' => 'Spesialis Kandungan', 'no_str' => 'STR-KND-002', 'tarif' => 280000, 'bio' => 'Konsultan program hamil dan penanganan infertilitas modern.'],
        ];

        foreach ($dokterData as $d) {
            $user = User::create([
                'name' => $d['name'], 'email' => $d['email'],
                'password' => Hash::make('password'), 'role' => 'dokter', 'is_active' => true,
                'phone' => '0821000000' . rand(10, 99),
            ]);
            Dokter::create([
                'user_id' => $user->id, 'no_str' => $d['no_str'],
                'spesialisasi' => $d['spesialisasi'], 'tarif_konsultasi' => $d['tarif'],
                'bio' => $d['bio'],
                'jadwal' => ['senin' => ['08:00', '12:00'], 'selasa' => ['08:00', '12:00'], 'rabu' => ['13:00', '17:00'], 'kamis' => ['08:00', '12:00'], 'jumat' => ['08:00', '11:00']],
            ]);
        }

        // PERAWAT
        $perawatData = [
            ['name' => 'Perawat', 'email' => 'perawat@healthdigital.id', 'no_str' => 'STR-PRW-001', 'bagian' => 'Rawat Jalan'],
        ];

        foreach ($perawatData as $p) {
            $user = User::create([
                'name' => $p['name'], 'email' => $p['email'],
                'password' => Hash::make('password'), 'role' => 'perawat', 'is_active' => true,
            ]);
            Perawat::create(['user_id' => $user->id, 'no_str' => $p['no_str'], 'bagian' => $p['bagian']]);
        }

        // PASIEN
        $pasienData = [
            ['name' => 'Pasien', 'email' => 'pasien@gmail.com', 'nik' => '3201234567890001', 'tl' => '1990-05-15', 'jk' => 'L', 'kota' => 'Jakarta'],
        ];

        foreach ($pasienData as $p) {
            $user = User::create([
                'name' => $p['name'], 'email' => $p['email'],
                'password' => Hash::make('password'), 'role' => 'pasien', 'is_active' => true,
                'phone' => '0812000000' . rand(10, 99),
            ]);
            Pasien::create([
                'user_id' => $user->id, 'nik' => $p['nik'],
                'tanggal_lahir' => $p['tl'], 'jenis_kelamin' => $p['jk'],
                'kota' => $p['kota'], 'golongan_darah' => ['A', 'B', 'AB', 'O'][rand(0, 3)],
            ]);
        }
    }
}
