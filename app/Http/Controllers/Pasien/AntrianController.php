<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Models\Dokter;
use App\Models\Layanan;
use App\Services\AntrianService;
use Illuminate\Http\Request;

class AntrianController extends Controller
{
    public function __construct(protected AntrianService $antrianService) {}

    public function index(Request $request)
    {
        $pasien = auth()->user()->pasien;

        if ($request->ajax()) {
            $activeAntrian = $pasien->antrians()
                ->whereDate('tanggal', today())
                ->whereIn('status', ['menunggu', 'dipanggil'])
                ->pluck('status', 'id');
            return response()->json($activeAntrian);
        }

        $antrians = $pasien->antrians()
            ->with(['dokter.user', 'layanan'])
            ->latest()
            ->paginate(10);
        return view('pasien.antrian.index', compact('antrians'));
    }

    public function create()
    {
        $layanans = Layanan::aktif()->get();
        $dokters = Dokter::with('user')->get();
        return view('pasien.antrian.create', compact('layanans', 'dokters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dokter_id'  => 'required|exists:dokters,id',
            'layanan_id' => 'required|exists:layanans,id',
            'tanggal'    => 'required|date|after_or_equal:today',
            'keluhan'    => 'required|string|min:10|max:500',
        ], [
            'tanggal.after_or_equal' => 'Tanggal pendaftaran tidak boleh di masa lalu.',
            'keluhan.min'            => 'Keluhan minimal 10 karakter.',
            'keluhan.required'       => 'Keluhan wajib diisi.',
        ]);

        $pasien = auth()->user()->pasien;

        // Cek duplikat antrian hari yang sama
        $existing = Antrian::where('pasien_id', $pasien->id)
            ->where('dokter_id', $request->dokter_id)
            ->whereDate('tanggal', $request->tanggal)
            ->whereIn('status', ['menunggu', 'dipanggil'])
            ->exists();

        if ($existing) {
            return back()->with('error', 'Anda sudah memiliki antrian dengan dokter ini pada tanggal tersebut.')->withInput();
        }

        $noAntrian = $this->antrianService->generateNomor($request->layanan_id, $request->tanggal);

        $antrian = Antrian::create([
            'pasien_id'  => $pasien->id,
            'dokter_id'  => $request->dokter_id,
            'layanan_id' => $request->layanan_id,
            'tanggal'    => $request->tanggal,
            'no_antrian' => $noAntrian,
            'keluhan'    => $request->keluhan,
            'status'     => 'menunggu',
        ]);

        $dokter = Dokter::find($request->dokter_id);
        \App\Models\Pembayaran::create([
            'antrian_id'   => $antrian->id,
            'pasien_id'    => $pasien->id,
            'kode_invoice' => 'INV-' . date('Ymd') . '-' . str_pad(\App\Models\Pembayaran::whereDate('created_at', today())->count() + 1, 6, '0', STR_PAD_LEFT),
            'jumlah'       => $dokter ? $dokter->tarif_konsultasi : 0,
            'status'       => 'menunggu',
            'metode'       => 'qris',
        ]);

        return redirect()->route('pasien.antrian.index')->with('success', 'Antrian berhasil dibuat! Nomor antrian Anda: ' . $noAntrian);
    }

    public function show(Request $request, $id)
    {
        $pasien = auth()->user()->pasien;
        $antrian = Antrian::where('pasien_id', $pasien->id)
            ->with(['dokter.user', 'layanan', 'rekamMedis', 'pembayaran'])
            ->findOrFail($id);

        if ($request->ajax()) {
            return response()->json(['status' => $antrian->status]);
        }

        return view('pasien.antrian.show', compact('antrian'));
    }

    public function update(Request $request, $id)
    {
        $pasien = auth()->user()->pasien;
        $antrian = Antrian::where('pasien_id', $pasien->id)->findOrFail($id);

        if ($request->action === 'batal') {
            $this->antrianService->batalkan($antrian);
            return back()->with('success', 'Antrian berhasil dibatalkan.');
        }

        return back();
    }
}
