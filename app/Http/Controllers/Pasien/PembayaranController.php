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
            ->with(['antrian.dokter.user', 'antrian.layanan'])
            ->findOrFail($id);
        return view('pasien.pembayaran.show', compact('pembayaran'));
    }

    public function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'bukti_bayar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'bukti_bayar.required' => 'Bukti pembayaran wajib diupload.',
            'bukti_bayar.image'    => 'File harus berupa gambar.',
            'bukti_bayar.max'      => 'Ukuran file maksimal 2MB.',
        ]);

        $pasien = auth()->user()->pasien;
        $pembayaran = Pembayaran::where('pasien_id', $pasien->id)
            ->where('status', 'menunggu')
            ->findOrFail($id);

        $path = $request->file('bukti_bayar')->store('bukti-bayar', 'public');
        $pembayaran->update(['bukti_bayar' => $path]);

        return back()->with('success', 'Bukti pembayaran berhasil diupload. Admin akan memverifikasi segera.');
    }
}
