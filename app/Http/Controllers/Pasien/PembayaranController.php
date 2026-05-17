<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index()
    {
        $pasien = auth()->user()->pasien;
        $pembayarans = $pasien->pembayarans()
            ->with('antrian.dokter.user')
            ->latest()
            ->paginate(10);
        return view('pasien.pembayaran.index', compact('pembayarans'));
    }

    public function show(Request $request, $id)
    {
        $pasien = auth()->user()->pasien;
        $pembayaran = Pembayaran::where('pasien_id', $pasien->id)
            ->with(['antrian.dokter.user', 'antrian.layanan', 'antrian.rekamMedis'])
            ->findOrFail($id);
            
        if ($request->ajax()) {
            return response()->json(['status' => $pembayaran->status]);
        }

        return view('pasien.pembayaran.show', compact('pembayaran'));
    }

    public function bayar(Request $request, $id)
    {
        $pasien = auth()->user()->pasien;
        $pembayaran = Pembayaran::where('pasien_id', $pasien->id)
            ->where('status', 'menunggu')
            ->findOrFail($id);

        $pembayaran->update([
            'status'       => 'dibayar',
            'dibayar_pada' => now(),
        ]);

        return back()->with('success', 'Pembayaran berhasil dilakukan!');
    }
    public function qrisScan(Request $request, $reference)
    {
        if (! $request->hasValidSignature()) {
            return response()->view('qris.expired', [], 403);
        }

        $pembayaran = Pembayaran::where('reference', $reference)->firstOrFail();
        
        if ($pembayaran->status === 'menunggu') {
            $pembayaran->update([
                'status'       => 'dibayar',
                'dibayar_pada' => now(),
            ]);
        }

        return view('qris.success', compact('pembayaran'));
    }
}
