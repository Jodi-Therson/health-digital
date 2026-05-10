<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Models\Konsultasi;

class DashboardController extends Controller
{
    public function index()
    {
        $dokter = auth()->user()->dokter;

        $antrianHariIni = $dokter->antrians()
            ->whereDate('tanggal', today())
            ->whereIn('status', ['menunggu', 'dipanggil'])
            ->with(['pasien.user', 'layanan'])
            ->get();

        $konsultasiPending = $dokter->konsultasis()
            ->where('status', 'menunggu')
            ->with('pasien.user')
            ->latest()
            ->take(5)
            ->get();

        $totalPasien = $dokter->antrians()->distinct('pasien_id')->count('pasien_id');
        $totalHariIni = $dokter->antrians()->whereDate('tanggal', today())->count();
        $totalSelesai = $dokter->antrians()->where('status', 'selesai')->count();

        return view('dokter.dashboard', compact(
            'dokter', 'antrianHariIni', 'konsultasiPending',
            'totalPasien', 'totalHariIni', 'totalSelesai'
        ));
    }
}
