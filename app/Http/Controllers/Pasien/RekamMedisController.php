<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\RekamMedis;

class RekamMedisController extends Controller
{
    public function index()
    {
        $pasien = auth()->user()->pasien;
        $rekamMedis = $pasien->rekamMedis()
            ->with(['dokter.user', 'antrian.layanan'])
            ->latest('tanggal_periksa')
            ->paginate(10);
        return view('pasien.rekam-medis.index', compact('rekamMedis'));
    }

    public function show($id)
    {
        $pasien = auth()->user()->pasien;
        $rm = RekamMedis::where('pasien_id', $pasien->id)
            ->with(['dokter.user', 'antrian.layanan'])
            ->findOrFail($id);
        return view('pasien.rekam-medis.show', compact('rm'));
    }
}
