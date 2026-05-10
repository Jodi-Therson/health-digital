<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Models\Pembayaran;
use App\Models\User;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->bulan ?? now()->format('Y-m');
        [$tahun, $bln] = explode('-', $bulan);

        $totalAntrian = Antrian::whereYear('tanggal', $tahun)->whereMonth('tanggal', $bln)->count();
        $antriansSelesai = Antrian::whereYear('tanggal', $tahun)->whereMonth('tanggal', $bln)->where('status', 'selesai')->count();
        $pendapatan = Pembayaran::where('status', 'dibayar')
            ->whereYear('dibayar_pada', $tahun)->whereMonth('dibayar_pada', $bln)->sum('jumlah');
        $pasienBaru = User::where('role', 'pasien')
            ->whereYear('created_at', $tahun)->whereMonth('created_at', $bln)->count();

        $antriansPerHari = collect(range(1, Carbon::create($tahun, $bln)->daysInMonth))->map(function ($day) use ($tahun, $bln) {
            return [
                'hari'  => $day,
                'count' => Antrian::whereDate('tanggal', Carbon::create($tahun, $bln, $day))->count(),
            ];
        });

        $pembayaranPerMetode = Pembayaran::where('status', 'dibayar')
            ->whereYear('dibayar_pada', $tahun)->whereMonth('dibayar_pada', $bln)
            ->selectRaw('metode, count(*) as total, sum(jumlah) as jumlah')
            ->groupBy('metode')->get();

        return view('admin.laporan.index', compact(
            'bulan', 'totalAntrian', 'antriansSelesai', 'pendapatan', 'pasienBaru',
            'antriansPerHari', 'pembayaranPerMetode'
        ));
    }

    public function export(Request $request)
    {
        $bulan = $request->bulan ?? now()->format('Y-m');
        [$tahun, $bln] = explode('-', $bulan);

        $antrians = Antrian::with(['pasien.user', 'dokter.user', 'layanan', 'pembayaran'])
            ->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bln)
            ->orderBy('tanggal')->get();

        $pdf = Pdf::loadView('admin.laporan.pdf', compact('antrians', 'bulan'));
        return $pdf->download('laporan-' . $bulan . '.pdf');
    }
}
