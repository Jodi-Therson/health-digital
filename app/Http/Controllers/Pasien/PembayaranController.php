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

    public function show($id)
    {
        $pasien = auth()->user()->pasien;
        $pembayaran = Pembayaran::where('pasien_id', $pasien->id)
            ->with(['antrian.dokter.user', 'antrian.layanan', 'antrian.rekamMedis'])
            ->findOrFail($id);
        return view('pasien.pembayaran.show', compact('pembayaran'));
    }

    public function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'bukti_bayar' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ], [
            'bukti_bayar.required' => 'Bukti pembayaran wajib diupload.',
            'bukti_bayar.mimes'    => 'File harus berupa JPG, PNG, atau PDF.',
            'bukti_bayar.max'      => 'Ukuran file maksimal 2MB.',
        ]);

        $pasien = auth()->user()->pasien;
        $pembayaran = Pembayaran::where('pasien_id', $pasien->id)
            ->whereIn('status', ['menunggu', 'ditolak'])
            ->findOrFail($id);

        $path = $request->file('bukti_bayar')->store('bukti-bayar', 'public');
        $pembayaran->update([
            'bukti_bayar' => $path,
            'status'      => 'menunggu_verifikasi',
            'alasan_tolak'=> null,
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil dikirim! Admin akan memverifikasi dalam 1×24 jam.');
    }
}
