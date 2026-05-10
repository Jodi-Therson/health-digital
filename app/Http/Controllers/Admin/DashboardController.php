<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Antrian;
use App\Models\Pembayaran;
use App\Models\Pasien;
use App\Models\Konsultasi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPasien   = User::where('role', 'pasien')->count();
        $totalDokter   = User::where('role', 'dokter')->count();
        $totalPerawat  = User::where('role', 'perawat')->count();
        $antrianHariIni = Antrian::whereDate('tanggal', today())->count();
        $antriansAktif = Antrian::whereDate('tanggal', today())->whereIn('status', ['menunggu', 'dipanggil'])->count();
        $pendapatanBulanIni = Pembayaran::where('status', 'dibayar')
            ->whereMonth('dibayar_pada', now()->month)
            ->sum('jumlah');
        $pembayaranPending = Pembayaran::where('status', 'menunggu')->whereNotNull('bukti_bayar')->count();
        $konsultasiPending = Konsultasi::where('status', 'menunggu')->count();

        $antriansRecent = Antrian::with(['pasien.user', 'dokter.user', 'layanan'])
            ->latest()
            ->take(7)
            ->get();

        // Chart data antrian 7 hari terakhir
        $chartData = collect(range(6, 0))->map(function ($i) {
            $date = Carbon::now()->subDays($i);
            return [
                'date'  => $date->format('d/m'),
                'count' => Antrian::whereDate('tanggal', $date)->count(),
            ];
        });

        return view('admin.dashboard', compact(
            'totalPasien', 'totalDokter', 'totalPerawat',
            'antrianHariIni', 'antriansAktif', 'pendapatanBulanIni',
            'pembayaranPending', 'konsultasiPending',
            'antriansRecent', 'chartData'
        ));
    }
}
