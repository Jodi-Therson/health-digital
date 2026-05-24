<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Models\Konsultasi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
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

        $totalPasien  = $dokter->antrians()->distinct('pasien_id')->count('pasien_id');
        $totalHariIni = $dokter->antrians()->whereDate('tanggal', today())->count();
        $totalSelesai = $dokter->antrians()->where('status', 'selesai')->count();

        // Chart: Antrian per hari (7 hari terakhir)
        $antrianPerHari = collect(range(6, 0))->map(function ($i) use ($dokter) {
            $date = Carbon::now()->subDays($i);
            return [
                'date'  => $date->format('d/m'),
                'count' => $dokter->antrians()->whereDate('tanggal', $date)->count(),
            ];
        });

        // Chart: Distribusi status antrian bulan ini (donut)
        $distribusiStatus = [
            'Menunggu'  => $dokter->antrians()->whereMonth('tanggal', now()->month)->where('status', 'menunggu')->count(),
            'Selesai'   => $dokter->antrians()->whereMonth('tanggal', now()->month)->where('status', 'selesai')->count(),
            'Batal'     => $dokter->antrians()->whereMonth('tanggal', now()->month)->where('status', 'batal')->count(),
        ];

        // 5 pasien terbaru yang ditangani
        $pasienTerbaru = $dokter->antrians()
            ->with('pasien.user')
            ->where('status', 'selesai')
            ->latest()
            ->take(5)
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'totalHariIni' => $totalHariIni,
                'totalSelesai' => $totalSelesai,
                'konsultasiPending' => $konsultasiPending->count(),
            ]);
        }

        return view('dokter.dashboard', compact(
            'dokter', 'antrianHariIni', 'konsultasiPending',
            'totalPasien', 'totalHariIni', 'totalSelesai',
            'antrianPerHari', 'distribusiStatus', 'pasienTerbaru'
        ));
    }
}
