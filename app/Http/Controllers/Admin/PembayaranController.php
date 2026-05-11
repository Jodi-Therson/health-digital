<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Services\PembayaranService;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function __construct(protected PembayaranService $service) {}

    public function index(Request $request)
    {
        $query = Pembayaran::with(['pasien.user', 'antrian.dokter.user', 'antrian.layanan']);

        if ($request->status)  $query->where('status', $request->status);
        if ($request->metode)  $query->where('metode', $request->metode);
        if ($request->tanggal) $query->whereDate('created_at', $request->tanggal);
        if ($request->search) {
            $query->where('kode_invoice', 'like', '%' . $request->search . '%')
                ->orWhereHas('pasien.user', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'));
        }

        $pembayarans = $query->latest()->paginate(15);
        $pendingCount = Pembayaran::whereIn('status', ['menunggu_verifikasi'])->count();

        return view('admin.pembayaran.index', compact('pembayarans', 'pendingCount'));
    }

    public function verifikasi(Request $request, $id)
    {
        $request->validate(['action' => 'required|in:lunas,tolak']);

        $pembayaran = Pembayaran::findOrFail($id);

        if ($request->action === 'lunas') {
            if (!in_array($pembayaran->status, ['menunggu', 'menunggu_verifikasi'])) {
                return back()->with('error', 'Pembayaran sudah diverifikasi atau statusnya tidak valid.');
            }
            $pembayaran->update([
                'status'      => 'dibayar',
                'verified_by' => auth()->id(),
                'dibayar_pada'=> now(),
            ]);
            return back()->with('success', "Invoice {$pembayaran->kode_invoice} berhasil dikonfirmasi sebagai LUNAS.");
        }

        // action = tolak
        $request->validate([
            'alasan_tolak' => 'required|string|min:10',
        ], [
            'alasan_tolak.required' => 'Alasan penolakan wajib diisi.',
            'alasan_tolak.min'      => 'Alasan penolakan minimal 10 karakter.',
        ]);

        $pembayaran->update([
            'status'       => 'ditolak',
            'alasan_tolak' => $request->alasan_tolak,
        ]);

        return back()->with('success', "Invoice {$pembayaran->kode_invoice} ditolak. Pasien akan diminta upload ulang bukti.");
    }
}
