<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Layanan;

class LayananSeeder extends Seeder
{
    public function run(): void
    {
        $layanans = [
            ['nama' => 'Umum', 'deskripsi' => 'Layanan pemeriksaan kesehatan umum untuk semua usia oleh dokter umum berpengalaman.', 'ikon' => 'stethoscope', 'urutan' => 1],
            ['nama' => 'Gigi', 'deskripsi' => 'Perawatan gigi dan mulut lengkap termasuk scaling, tambal gigi, dan pencabutan.', 'ikon' => 'tooth', 'urutan' => 2],
            ['nama' => 'Anak', 'deskripsi' => 'Layanan kesehatan khusus anak dari bayi hingga remaja dengan dokter spesialis anak.', 'ikon' => 'baby', 'urutan' => 3],
            ['nama' => 'Kandungan', 'deskripsi' => 'Layanan kebidanan dan kandungan untuk kesehatan ibu hamil dan masalah reproduksi.', 'ikon' => 'female', 'urutan' => 4],
        ];

        foreach ($layanans as $layanan) {
            Layanan::create($layanan + ['is_active' => true]);
        }
    }
}
