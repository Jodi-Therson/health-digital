<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use App\Models\Antrian;

class DashboardController extends Controller
{
    public function index()
    {
        $antrianHariIni = Antrian::whereDate('tanggal', today())
            ->with(['pasien.user', 'dokter.user', 'layanan'])
            ->orderBy('no_antrian')
            ->get();

        $stats = [
            'menunggu'  => $antrianHariIni->where('status', 'menunggu')->count(),
            'dipanggil' => $antrianHariIni->where('status', 'dipanggil')->count(),
            'selesai'   => $antrianHariIni->where('status', 'selesai')->count(),
            'total'     => $antrianHariIni->count(),
        ];

        return view('perawat.dashboard', compact('antrianHariIni', 'stats'));
    }
}
