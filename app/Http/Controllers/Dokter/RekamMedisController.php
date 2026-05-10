<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\RekamMedis;
use App\Models\Antrian;
use App\Models\Pasien;
use Illuminate\Http\Request;

class RekamMedisController extends Controller
{
    public function index(Request $request)
    {
        $dokter = auth()->user()->dokter;
        $query = $dokter->rekamMedis()->with(['pasien.user', 'antrian.layanan']);

        if ($request->search) {
            $query->whereHas('pasien.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $rekamMedis = $query->latest('tanggal_periksa')->paginate(15);
        return view('dokter.rekam-medis.index', compact('rekamMedis'));
    }

    public function create(Request $request)
    {
        $dokter = auth()->user()->dokter;
        $antrian = null;
        if ($request->antrian_id) {
            $antrian = Antrian::where('dokter_id', $dokter->id)->with('pasien.user')->findOrFail($request->antrian_id);
        }
        $pasiens = \App\Models\Pasien::with('user')->get();
        return view('dokter.rekam-medis.create', compact('antrian', 'pasiens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'antrian_id'    => 'required|exists:antrians,id',
            'pasien_id'     => 'required|exists:pasiens,id',
            'tanggal_periksa' => 'required|date',
            'diagnosa'      => 'required|string',
            'anamnesis'     => 'nullable|string',
            'tindakan'      => 'nullable|string',
            'tekanan_darah' => 'nullable|string|max:20',
            'berat_badan'   => 'nullable|numeric|min:1|max:300',
            'tinggi_badan'  => 'nullable|numeric|min:1|max:300',
            'suhu_tubuh'    => 'nullable|numeric|min:30|max:45',
        ], [
            'diagnosa.required' => 'Diagnosa wajib diisi.',
        ]);

        $dokter = auth()->user()->dokter;

        // Proses resep
        $resep = [];
        if ($request->has('resep_obat')) {
            foreach ($request->resep_obat as $i => $obat) {
                if (!empty($obat)) {
                    $resep[] = [
                        'obat'   => $obat,
                        'dosis'  => $request->resep_dosis[$i] ?? '',
                        'aturan' => $request->resep_aturan[$i] ?? '',
                    ];
                }
            }
        }

        RekamMedis::create([
            'antrian_id'    => $request->antrian_id,
            'pasien_id'     => $request->pasien_id,
            'dokter_id'     => $dokter->id,
            'tanggal_periksa' => $request->tanggal_periksa,
            'anamnesis'     => $request->anamnesis,
            'diagnosa'      => $request->diagnosa,
            'tindakan'      => $request->tindakan,
            'resep'         => $resep ?: null,
            'tekanan_darah' => $request->tekanan_darah,
            'berat_badan'   => $request->berat_badan,
            'tinggi_badan'  => $request->tinggi_badan,
            'suhu_tubuh'    => $request->suhu_tubuh,
            'catatan'       => $request->catatan,
        ]);

        // Update status antrian jadi selesai
        $antrian = Antrian::find($request->antrian_id);
        if ($antrian && $antrian->status === 'dipanggil') {
            $antrian->update(['status' => 'selesai']);
        }

        return redirect()->route('dokter.rekam-medis.index')->with('success', 'Rekam medis berhasil disimpan.');
    }

    public function show($id)
    {
        $dokter = auth()->user()->dokter;
        $rm = RekamMedis::where('dokter_id', $dokter->id)
            ->with(['pasien.user', 'antrian.layanan'])
            ->findOrFail($id);
        return view('dokter.rekam-medis.show', compact('rm'));
    }

    public function edit($id)
    {
        $dokter = auth()->user()->dokter;
        $rm = RekamMedis::where('dokter_id', $dokter->id)->findOrFail($id);
        return view('dokter.rekam-medis.edit', compact('rm'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'diagnosa' => 'required|string',
        ]);

        $dokter = auth()->user()->dokter;
        $rm = RekamMedis::where('dokter_id', $dokter->id)->findOrFail($id);

        $resep = [];
        if ($request->has('resep_obat')) {
            foreach ($request->resep_obat as $i => $obat) {
                if (!empty($obat)) {
                    $resep[] = [
                        'obat'   => $obat,
                        'dosis'  => $request->resep_dosis[$i] ?? '',
                        'aturan' => $request->resep_aturan[$i] ?? '',
                    ];
                }
            }
        }

        $rm->update($request->merge(['resep' => $resep ?: null])->only([
            'anamnesis', 'diagnosa', 'tindakan', 'catatan',
            'tekanan_darah', 'berat_badan', 'tinggi_badan', 'suhu_tubuh',
        ]) + ['resep' => $resep ?: null]);

        return redirect()->route('dokter.rekam-medis.show', $rm->id)->with('success', 'Rekam medis berhasil diperbarui.');
    }
}
