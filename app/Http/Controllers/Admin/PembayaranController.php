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
        $query = Pembayaran::with(['pasien.user', 'antrian.dokter.user']);

        if ($request->status) $query->where('status', $request->status);
        if ($request->search) {
            $query->where('kode_invoice', 'like', '%' . $request->search . '%')
                ->orWhereHas('pasien.user', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'));
        }

        $pembayarans = $query->latest()->paginate(15);
        return view('admin.pembayaran.index', compact('pembayarans'));
    }

    public function verifikasi(Request $request, $id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $result = $this->service->verifikasi($pembayaran, auth()->id());

        if (!$result) {
            return back()->with('error', 'Pembayaran sudah diverifikasi atau statusnya tidak valid.');
        }

        return back()->with('success', "Invoice {$pembayaran->kode_invoice} berhasil diverifikasi.");
    }
}
