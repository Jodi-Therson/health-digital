<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\RekamMedis;
use Barryvdh\DomPDF\Facade\Pdf;

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

        // IDOR protection: hanya boleh melihat milik sendiri
        $rm = RekamMedis::where('pasien_id', $pasien->id)
            ->with(['dokter.user', 'antrian.layanan'])
            ->find($id);

        if (!$rm) {
            abort(403, 'Akses Ditolak. Rekam medis ini bukan milik Anda.');
        }

        return view('pasien.rekam-medis.show', compact('rm'));
    }

    public function downloadPdf($id)
    {
        $pasien = auth()->user()->pasien;

        // IDOR protection
        $rm = RekamMedis::where('pasien_id', $pasien->id)
            ->with(['dokter.user', 'pasien.user', 'antrian.layanan'])
            ->find($id);

        if (!$rm) {
            abort(403, 'Akses Ditolak. Rekam medis ini bukan milik Anda.');
        }

        $pdf = Pdf::loadView('pasien.rekam-medis.pdf', compact('rm'))
            ->setPaper('A4', 'portrait');

        $filename = 'rekam-medis-' . str_replace(' ', '-', strtolower($rm->pasien->user->name))
                    . '-' . $rm->tanggal_periksa->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }
}
