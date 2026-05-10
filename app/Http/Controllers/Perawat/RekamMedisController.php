<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use App\Models\RekamMedis;
use Illuminate\Http\Request;

class RekamMedisController extends Controller
{
    public function index(Request $request)
    {
        $query = RekamMedis::with(['pasien.user', 'dokter.user']);

        if ($request->search) {
            $query->whereHas('pasien.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $rekamMedis = $query->latest('tanggal_periksa')->paginate(15);
        return view('perawat.rekam-medis.index', compact('rekamMedis'));
    }

    public function show($id)
    {
        $rm = RekamMedis::with(['pasien.user', 'dokter.user', 'antrian.layanan'])->findOrFail($id);
        return view('perawat.rekam-medis.show', compact('rm'));
    }

    public function tambahCatatan(Request $request, $id)
    {
        $request->validate([
            'catatan_perawat' => 'required|string|min:5',
        ], [
            'catatan_perawat.required' => 'Catatan perawat wajib diisi.',
            'catatan_perawat.min'      => 'Catatan minimal 5 karakter.',
        ]);

        $rm = RekamMedis::findOrFail($id);
        // Update antrian catatan perawat
        if ($rm->antrian) {
            $rm->antrian->update(['catatan_perawat' => $request->catatan_perawat]);
        }

        return back()->with('success', 'Catatan perawat berhasil ditambahkan.');
    }
}
