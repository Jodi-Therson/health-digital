<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Antrian;
use App\Models\Pembayaran;
use App\Models\Pasien;
use App\Models\Konsultasi;
use App\Models\Layanan;
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

        $pendapatanTotalAll = Pembayaran::where('status', 'dibayar')->sum('jumlah');

        $pembayaranPending = Pembayaran::where('status', 'menunggu')->count();
        $konsultasiPending = Konsultasi::where('status', 'menunggu')->count();

        $antriansRecent = Antrian::with(['pasien.user', 'dokter.user', 'layanan'])
            ->latest()
            ->take(7)
            ->get();

        // Chart: Antrian 7 hari terakhir
        $chartData = collect(range(6, 0))->map(function ($i) {
            $date = Carbon::now()->subDays($i);
            return [
                'date'  => $date->format('d/m'),
                'count' => Antrian::whereDate('tanggal', $date)->count(),
            ];
        });

        // Chart: Pendapatan 7 hari terakhir (area chart)
        $pendapatanHarian = collect(range(6, 0))->map(function ($i) {
            $date = Carbon::now()->subDays($i);
            return [
                'date'   => $date->format('d/m'),
                'jumlah' => (float) Pembayaran::where('status', 'dibayar')
                    ->whereDate('dibayar_pada', $date)->sum('jumlah'),
            ];
        });

        // Chart: Distribusi status antrian bulan ini (donut)
        $statusDistribusi = [
            'menunggu'  => Antrian::whereMonth('tanggal', now()->month)->where('status', 'menunggu')->count(),
            'dipanggil' => Antrian::whereMonth('tanggal', now()->month)->where('status', 'dipanggil')->count(),
            'selesai'   => Antrian::whereMonth('tanggal', now()->month)->where('status', 'selesai')->count(),
            'batal'     => Antrian::whereMonth('tanggal', now()->month)->where('status', 'batal')->count(),
        ];

        // Chart: Top layanan (bar horizontal)
        $layananDistribusi = Layanan::withCount(['antrians' => function ($q) {
            $q->whereMonth('tanggal', now()->month);
        }])->orderByDesc('antrians_count')->take(6)->get();

        return view('admin.dashboard', compact(
            'totalPasien', 'totalDokter', 'totalPerawat',
            'antrianHariIni', 'antriansAktif',
            'pendapatanBulanIni', 'pendapatanTotalAll',
            'pembayaranPending', 'konsultasiPending',
            'antriansRecent', 'chartData',
            'pendapatanHarian', 'statusDistribusi', 'layananDistribusi'
        ));
    }
}
