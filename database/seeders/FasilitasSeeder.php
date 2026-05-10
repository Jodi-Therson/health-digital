<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fasilitas;

class FasilitasSeeder extends Seeder
{
    public function run(): void
    {
        $fasilitas = [
            ['nama' => 'IGD (Instalasi Gawat Darurat)', 'deskripsi' => 'Layanan gawat darurat 24 jam dengan tenaga medis siaga dan peralatan lengkap untuk penanganan kasus darurat.'],
            ['nama' => 'ICU (Intensive Care Unit)', 'deskripsi' => 'Unit perawatan intensif dengan monitoring 24 jam untuk pasien dengan kondisi kritis yang membutuhkan pengawasan ketat.'],
            ['nama' => 'Rawat Inap', 'deskripsi' => 'Fasilitas rawat inap nyaman dengan berbagai kelas kamar — dari kelas ekonomi hingga VIP — dilengkapi fasilitas modern.'],
            ['nama' => 'Apotek / Farmasi', 'deskripsi' => 'Apotek lengkap yang menyediakan berbagai obat-obatan dan kebutuhan medis dengan harga terjangkau dan staf farmasi profesional.'],
            ['nama' => 'Radiologi & Imaging', 'deskripsi' => 'Fasilitas pencitraan medis lengkap termasuk X-Ray, USG, CT-Scan, dan MRI dengan hasil cepat dan akurat.'],
        ];

        foreach ($fasilitas as $item) {
            Fasilitas::create($item + ['is_active' => true]);
        }
    }
}
