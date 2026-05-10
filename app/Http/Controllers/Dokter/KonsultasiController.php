<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use Illuminate\Http\Request;

class KonsultasiController extends Controller
{
    public function index()
    {
        $dokter = auth()->user()->dokter;
        $konsultasis = $dokter->konsultasis()
            ->with('pasien.user')
            ->latest()
            ->paginate(15);
        return view('dokter.konsultasi.index', compact('konsultasis'));
    }

    public function show($id)
    {
        $dokter = auth()->user()->dokter;
        $konsultasi = Konsultasi::where('dokter_id', $dokter->id)
            ->with(['pasien.user', 'pesans'])
            ->findOrFail($id);
        $konsultasi->update(['dibaca_dokter' => true]);
        return view('dokter.konsultasi.show', compact('konsultasi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'balasan' => 'required|string|min:10',
            'action'  => 'required|in:dijawab,ditutup',
        ], [
            'balasan.min' => 'Balasan minimal 10 karakter.',
        ]);

        $dokter = auth()->user()->dokter;
        $konsultasi = Konsultasi::where('dokter_id', $dokter->id)->findOrFail($id);

        $konsultasi->update([
            'status'        => $request->action,
            'dibaca_pasien' => false,
        ]);

        $konsultasi->pesans()->create([
            'pengirim' => 'dokter',
            'pesan' => $request->balasan,
        ]);

        return redirect()->route('dokter.konsultasi.index')->with('success', 'Balasan konsultasi berhasil dikirim.');
    }
}
