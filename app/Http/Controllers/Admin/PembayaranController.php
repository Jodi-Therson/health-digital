<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{

    public function index(Request $request)
    {
        $query = Pembayaran::with(['pasien.user', 'antrian.dokter.user', 'antrian.layanan']);

        if ($request->status)  $query->where('status', $request->status);

        if ($request->tanggal) $query->whereDate('created_at', $request->tanggal);
        if ($request->search) {
            $query->where('kode_invoice', 'like', '%' . $request->search . '%')
                ->orWhereHas('pasien.user', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'));
        }

        $pembayarans = $query->latest()->paginate(15);
        return view('admin.pembayaran.index', compact('pembayarans'));
    }


}
