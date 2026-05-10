<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Models\Pasien;
use App\Models\Dokter;
use App\Models\Layanan;
use App\Services\AntrianService;
use Illuminate\Http\Request;

class AntrianController extends Controller
{
    public function __construct(protected AntrianService $antrianService) {}

    public function index(Request $request)
    {
        $query = Antrian::with(['pasien.user', 'dokter.user', 'layanan']);

        $tanggal = $request->tanggal ?? today()->format('Y-m-d');
        $query->whereDate('tanggal', $tanggal);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $antrians = $query->orderBy('no_antrian')->paginate(20);
        return view('perawat.antrian.index', compact('antrians', 'tanggal'));
    }

    public function create()
    {
        $pasiens = Pasien::with('user')->get();
        $dokters = Dokter::with('user')->get();
        $layanans = Layanan::aktif()->get();
        return view('perawat.antrian.create', compact('pasiens', 'dokters', 'layanans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pasien_id'  => 'required|exists:pasiens,id',
            'dokter_id'  => 'required|exists:dokters,id',
            'layanan_id' => 'required|exists:layanans,id',
            'tanggal'    => 'required|date|after_or_equal:today',
            'keluhan'    => 'required|string|min:5',
        ]);

        $noAntrian = $this->antrianService->generateNomor($request->layanan_id, $request->tanggal);

        $antrian = Antrian::create([
            'pasien_id'  => $request->pasien_id,
            'dokter_id'  => $request->dokter_id,
            'layanan_id' => $request->layanan_id,
            'tanggal'    => $request->tanggal,
            'no_antrian' => $noAntrian,
            'keluhan'    => $request->keluhan,
            'status'     => 'menunggu',
        ]);

        $layanan = Layanan::find($request->layanan_id);
        \App\Models\Pembayaran::create([
            'antrian_id' => $antrian->id,
            'kode_invoice' => 'INV-' . time() . '-' . rand(100, 999),
            'jumlah' => $layanan ? $layanan->harga_dasar : 0,
            'status' => 'menunggu',
            'metode' => 'transfer',
        ]);

        return redirect()->route('perawat.antrian.index')->with('success', 'Antrian berhasil ditambahkan. No: ' . $noAntrian);
    }

    public function show($id)
    {
        $antrian = Antrian::with(['pasien.user', 'dokter.user', 'layanan', 'rekamMedis'])->findOrFail($id);
        return view('perawat.antrian.show', compact('antrian'));
    }

    public function panggil(Request $request, $id)
    {
        $antrian = Antrian::findOrFail($id);
        $result = $this->antrianService->panggil($antrian);

        if (!$result) {
            return back()->with('error', 'Antrian tidak bisa dipanggil saat ini.');
        }

        return back()->with('success', 'Pasien ' . $antrian->pasien->user->name . ' berhasil dipanggil.');
    }
}
