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
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;
        $layanan_id = $request->layanan_id;
        $dokter_id = $request->dokter_id;

        // Base query for stats
        $query = Antrian::whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan);
        if ($layanan_id) $query->where('layanan_id', $layanan_id);
        if ($dokter_id) $query->where('dokter_id', $dokter_id);

        $totalAntrian = (clone $query)->count();
        $antriansSelesai = (clone $query)->where('status', 'selesai')->count();
        
        // Base query for revenue
        $revenueQuery = Pembayaran::where('status', 'dibayar')
            ->whereYear('dibayar_pada', $tahun)->whereMonth('dibayar_pada', $bulan);
        
        if ($layanan_id || $dokter_id) {
            $revenueQuery->whereHas('antrian', function($q) use ($layanan_id, $dokter_id) {
                if ($layanan_id) $q->where('layanan_id', $layanan_id);
                if ($dokter_id) $q->where('dokter_id', $dokter_id);
            });
        }
        $pendapatan = $revenueQuery->sum('jumlah');

        // Chart 1: Bar Chart - Antrian per hari
        $daysInMonth = Carbon::create($tahun, $bulan)->daysInMonth;
        $antriansPerHariLabels = [];
        $antriansPerHariData = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $date = Carbon::create($tahun, $bulan, $i)->toDateString();
            $count = Antrian::whereDate('tanggal', $date);
            if ($layanan_id) $count->where('layanan_id', $layanan_id);
            if ($dokter_id) $count->where('dokter_id', $dokter_id);
            
            $antriansPerHariLabels[] = $i;
            $antriansPerHariData[] = $count->count();
        }

        // Chart 2: Pie Chart - Distribusi Layanan
        $distribusiLayanan = \App\Models\Layanan::withCount(['antrians' => function($q) use ($tahun, $bulan, $dokter_id) {
            $q->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan);
            if ($dokter_id) $q->where('dokter_id', $dokter_id);
        }])->get();

        // Table: Pendapatan per Layanan
        $pendapatanLayanan = \App\Models\Layanan::all()->map(function($layanan) use ($tahun, $bulan, $dokter_id) {
            $total = Pembayaran::where('status', 'dibayar')
                ->whereYear('dibayar_pada', $tahun)->whereMonth('dibayar_pada', $bulan)
                ->whereHas('antrian', function($q) use ($layanan, $dokter_id) {
                    $q->where('layanan_id', $layanan->id);
                    if ($dokter_id) $q->where('dokter_id', $dokter_id);
                })->sum('jumlah');
            
            return [
                'nama' => $layanan->nama,
                'total' => $total,
                'count' => Antrian::whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan)
                    ->where('layanan_id', $layanan->id)
                    ->when($dokter_id, fn($q) => $q->where('dokter_id', $dokter_id))
                    ->count()
            ];
        });

        $layanans = \App\Models\Layanan::all();
        $dokters = User::where('role', 'dokter')->get();

        return view('admin.laporan.index', compact(
            'bulan', 'tahun', 'layanan_id', 'dokter_id',
            'totalAntrian', 'antriansSelesai', 'pendapatan',
            'antriansPerHariLabels', 'antriansPerHariData',
            'distribusiLayanan', 'pendapatanLayanan',
            'layanans', 'dokters'
        ));
    }

    public function export(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;
        $layanan_id = $request->layanan_id;
        $dokter_id = $request->dokter_id;

        $startDate = Carbon::create($tahun, $bulan)->startOfMonth();
        $endDate = Carbon::create($tahun, $bulan)->endOfMonth();

        $query = Antrian::with(['pasien.user', 'dokter.user', 'layanan', 'pembayaran'])
            ->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan)
            ->orderBy('tanggal');
        
        if ($layanan_id) $query->where('layanan_id', $layanan_id);
        if ($dokter_id) $query->where('dokter_id', $dokter_id);

        $antrians = $query->get();

        $totalAntrian = $antrians->count();
        $antrianSelesai = $antrians->where('status', 'selesai')->count();
        
        $revenueQuery = Pembayaran::where('status', 'dibayar')
            ->whereYear('dibayar_pada', $tahun)->whereMonth('dibayar_pada', $bulan);
        if ($layanan_id || $dokter_id) {
            $revenueQuery->whereHas('antrian', function($q) use ($layanan_id, $dokter_id) {
                if ($layanan_id) $q->where('layanan_id', $layanan_id);
                if ($dokter_id) $q->where('dokter_id', $dokter_id);
            });
        }
        $totalPendapatan = $revenueQuery->sum('jumlah');

        $pendapatanLayanan = \App\Models\Layanan::all()->map(function($layanan) use ($tahun, $bulan, $dokter_id) {
            return [
                'nama' => $layanan->nama,
                'jumlah_antrian' => Antrian::whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan)
                    ->where('layanan_id', $layanan->id)
                    ->when($dokter_id, fn($q) => $q->where('dokter_id', $dokter_id))
                    ->count(),
                'total' => Pembayaran::where('status', 'dibayar')
                    ->whereYear('dibayar_pada', $tahun)->whereMonth('dibayar_pada', $bulan)
                    ->whereHas('antrian', function($q) use ($layanan, $dokter_id) {
                        $q->where('layanan_id', $layanan->id);
                        if ($dokter_id) $q->where('dokter_id', $dokter_id);
                    })->sum('jumlah')
            ];
        });

        $layanan_nama = $layanan_id ? \App\Models\Layanan::find($layanan_id)->nama : 'Semua Layanan';
        $dokter_nama = $dokter_id ? User::find($dokter_id)->name : 'Semua Dokter';

        $pdf = Pdf::loadView('admin.laporan.pdf', compact(
            'antrians', 'bulan', 'tahun', 'startDate', 'endDate', 
            'totalAntrian', 'antrianSelesai', 'totalPendapatan', 'pendapatanLayanan',
            'layanan_nama', 'dokter_nama'
        ));
        
        return $pdf->download('laporan-' . $tahun . '-' . $bulan . '.pdf');
    }
}
