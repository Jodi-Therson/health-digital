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

        // Sort: menunggu (terlama) dulu, lalu dijawab, lalu ditutup
        $konsultasis = $dokter->konsultasis()
            ->with('pasien.user')
            ->orderByRaw("FIELD(status, 'menunggu', 'dijawab', 'ditutup')")
            ->orderBy('created_at', 'asc')  // paling lama menunggu di atas
            ->paginate(15);

        return view('dokter.konsultasi.index', compact('konsultasis'));
    }

    public function show(Request $request, $id)
    {
        $dokter = auth()->user()->dokter;
        $konsultasi = Konsultasi::where('dokter_id', $dokter->id)
            ->with(['pasien.user', 'pasien', 'pesans'])
            ->findOrFail($id);

        if ($request->ajax()) {
            return response()->json([
                'status' => $konsultasi->status,
                'messages_count' => $konsultasi->pesans()->count(),
            ]);
        }

        $konsultasi->update(['dibaca_dokter' => true]);

        return view('dokter.konsultasi.show', compact('konsultasi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:dijawab,ditutup',
        ]);

        $dokter     = auth()->user()->dokter;
        $konsultasi = Konsultasi::where('dokter_id', $dokter->id)->findOrFail($id);

        // Verification check: Pasien must have paid for the consultation
        $pembayaran = \App\Models\Pembayaran::where('konsultasi_id', $konsultasi->id)->first();
        if ($pembayaran && $pembayaran->status !== 'dibayar') {
            return back()->with('error', 'Akses ditolak. Pasien belum menyelesaikan pembayaran untuk sesi konsultasi ini.');
        }

        // Bila hanya tutup (tanpa balas), balasan bisa kosong
        $balasan = $request->balasan;

        if ($request->action === 'dijawab') {
            $request->validate([
                'balasan' => 'required|string|min:10',
            ], [
                'balasan.min' => 'Balasan minimal 10 karakter.',
            ]);
        }

        $konsultasi->update([
            'status'        => $request->action,
            'dibaca_pasien' => false,
        ]);

        // Simpan pesan balasan (hanya jika ada konten bermakna)
        if ($balasan && $balasan !== '-') {
            $konsultasi->pesans()->create([
                'pengirim' => 'dokter',
                'pesan'    => $balasan,
            ]);
        }

        $msg = $request->action === 'ditutup'
            ? 'Konsultasi berhasil ditutup.'
            : 'Balasan berhasil dikirim.';

        if ($request->action === 'ditutup') {
            return redirect()->route('dokter.konsultasi.index')->with('success', $msg);
        }

        return redirect()->route('dokter.konsultasi.show', $konsultasi->id)->with('success', $msg);
    }
}
