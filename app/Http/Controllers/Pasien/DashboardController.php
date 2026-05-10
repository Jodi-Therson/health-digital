<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $pasien = $user->pasien;

        if (!$pasien) {
            return view('pasien.dashboard', ['pasien' => null, 'antrians' => collect(), 'konsultasis' => collect(), 'pembayarans' => collect()]);
        }

        $antrians = $pasien->antrians()
            ->with(['dokter.user', 'layanan'])
            ->latest()
            ->take(5)
            ->get();

        $konsultasis = $pasien->konsultasis()
            ->with('dokter.user')
            ->where('status', 'menunggu')
            ->latest()
            ->take(3)
            ->get();

        $pembayarans = $pasien->pembayarans()
            ->where('status', 'menunggu')
            ->latest()
            ->take(3)
            ->get();

        $antrianHariIni = $pasien->antrians()
            ->whereDate('tanggal', today())
            ->whereIn('status', ['menunggu', 'dipanggil'])
            ->with(['dokter.user', 'layanan'])
            ->first();

        return view('pasien.dashboard', compact(
            'pasien', 'antrians', 'konsultasis', 'pembayarans', 'antrianHariIni'
        ));
    }
}
