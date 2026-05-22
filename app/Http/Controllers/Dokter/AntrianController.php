<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Services\AntrianService;
use Illuminate\Http\Request;

class AntrianController extends Controller
{
    public function __construct(protected AntrianService $antrianService) {}

    public function index(Request $request)
    {
        $dokter = auth()->user()->dokter;
        
        // Base query for today's antrian
        $baseQuery = $dokter->antrians()->whereDate('tanggal', today());

        // Get status counts for the interactive cards
        $counts = [
            'semua' => (clone $baseQuery)->count(),
            'menunggu' => (clone $baseQuery)->where('status', 'menunggu')->count(),
            'dipanggil' => (clone $baseQuery)->where('status', 'dipanggil')->count(),
            'selesai' => (clone $baseQuery)->where('status', 'selesai')->count(),
            'batal' => (clone $baseQuery)->where('status', 'batal')->count(),
        ];

        $query = $dokter->antrians()->with(['pasien.user', 'layanan'])->whereDate('tanggal', today());

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $antrians = $query->latest()->paginate(15);
        return view('dokter.antrian.index', compact('antrians', 'counts'));
    }

    public function updateStatus(Request $request, $id)
    {
        $dokter = auth()->user()->dokter;
        $antrian = Antrian::where('dokter_id', $dokter->id)->findOrFail($id);

        $request->validate(['status' => 'required|in:dipanggil,selesai,batal']);

        $result = match($request->status) {
            'dipanggil' => $this->antrianService->panggil($antrian),
            'selesai'   => $this->antrianService->selesaikan($antrian),
            'batal'     => $this->antrianService->batalkan($antrian),
        };

        if (!$result) {
            return back()->with('error', 'Perubahan status tidak valid untuk kondisi antrian saat ini.');
        }

        return back()->with('success', 'Status antrian berhasil diperbarui.');
    }
}
