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

        $startDate = Carbon::create($tahun, $bln)->startOfMonth()->format('Y-m-d');
        $endDate = Carbon::create($tahun, $bln)->endOfMonth()->format('Y-m-d');

        $antrians = Antrian::with(['pasien.user', 'dokter.user', 'layanan', 'pembayaran'])
            ->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bln)
            ->orderBy('tanggal')->get();

        $totalAntrian = $antrians->count();
        $antrianSelesai = $antrians->where('status', 'selesai')->count();
        $totalPendapatan = Pembayaran::where('status', 'dibayar')
            ->whereYear('dibayar_pada', $tahun)->whereMonth('dibayar_pada', $bln)->sum('jumlah');

        $pendapatanLayanan = \Illuminate\Support\Facades\DB::table('pembayarans')
            ->join('antrians', 'pembayarans.antrian_id', '=', 'antrians.id')
            ->join('layanans', 'antrians.layanan_id', '=', 'layanans.id')
            ->where('pembayarans.status', 'dibayar')
            ->whereYear('pembayarans.dibayar_pada', $tahun)
            ->whereMonth('pembayarans.dibayar_pada', $bln)
            ->select('layanans.nama', \Illuminate\Support\Facades\DB::raw('count(antrians.id) as jumlah_antrian'), \Illuminate\Support\Facades\DB::raw('sum(pembayarans.jumlah) as total'))
            ->groupBy('layanans.id', 'layanans.nama')
            ->get();

        $pdf = Pdf::loadView('admin.laporan.pdf', compact('antrians', 'bulan', 'startDate', 'endDate', 'totalAntrian', 'antrianSelesai', 'totalPendapatan', 'pendapatanLayanan'));
        return $pdf->download('laporan-' . $bulan . '.pdf');
    }
}
