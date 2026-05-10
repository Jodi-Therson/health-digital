<?php

namespace App\Services;

use App\Models\Antrian;
use App\Models\Layanan;
use Carbon\Carbon;

class AntrianService
{
    public function generateNomor(int $layananId, string $tanggal): string
    {
        $layanan = Layanan::findOrFail($layananId);
        
        // Buat kode dari nama layanan (3 huruf awal)
        $kode = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $layanan->nama), 0, 3));
        
        $tgl = Carbon::parse($tanggal)->format('Ymd');
        
        // Hitung urutan antrian hari ini untuk layanan ini
        $urutan = Antrian::where('layanan_id', $layananId)
            ->whereDate('tanggal', $tanggal)
            ->count() + 1;
        
        return $kode . '-' . $tgl . '-' . str_pad($urutan, 3, '0', STR_PAD_LEFT);
    }

    public function batalkan(Antrian $antrian): bool
    {
        if (!in_array($antrian->status, ['menunggu', 'dipanggil'])) {
            return false;
        }
        $antrian->update(['status' => 'batal']);
        return true;
    }

    public function panggil(Antrian $antrian): bool
    {
        if ($antrian->status !== 'menunggu') return false;
        $antrian->update(['status' => 'dipanggil']);
        return true;
    }

    public function selesaikan(Antrian $antrian): bool
    {
        if ($antrian->status !== 'dipanggil') return false;
        $antrian->update(['status' => 'selesai']);
        return true;
    }
}
