<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use App\Models\Dokter;
use Illuminate\Http\Request;

class KonsultasiController extends Controller
{
    public function index()
    {
        $pasien = auth()->user()->pasien;
        $konsultasis = $pasien->konsultasis()
            ->with('dokter.user')
            ->latest()
            ->paginate(20);
        return view('pasien.konsultasi.index', compact('konsultasis'));
    }

    public function create()
    {
        $layanans = \App\Models\Layanan::aktif()->get();
        $dokters = Dokter::with('user')->get();
        return view('pasien.konsultasi.create', compact('layanans', 'dokters'));
    }

    /**
     * AJAX: cek apakah pasien punya konsultasi aktif (menunggu) ke dokter ini
     */
    public function cekDuplikat(Request $request)
    {
        $pasien  = auth()->user()->pasien;
        $dokter  = Dokter::with('user')->find($request->dokter_id);

        $ada = Konsultasi::where('pasien_id', $pasien->id)
            ->where('dokter_id', $request->dokter_id)
            ->where('status', 'menunggu')
            ->exists();

        return response()->json([
            'ada'         => $ada,
            'nama_dokter' => $dokter ? 'dr. ' . $dokter->user->name : 'dokter ini',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'dokter_id' => 'required|exists:dokters,id',
            'judul'     => 'required|string|max:255',
            'pesan'     => 'required|string|min:20|max:2000',
        ], [
            'pesan.min' => 'Pesan minimal 20 karakter agar dokter dapat memahami kondisi Anda.',
        ]);

        $pasien = auth()->user()->pasien;

        // Cek duplikat server-side
        $pending = Konsultasi::where('pasien_id', $pasien->id)
            ->where('dokter_id', $request->dokter_id)
            ->where('status', 'menunggu')
            ->first();

        if ($pending) {
            $dokter = Dokter::with('user')->find($request->dokter_id);
            $nama   = $dokter ? 'dr. ' . $dokter->user->name : 'dokter ini';
            return back()
                ->with('error', "Anda masih memiliki konsultasi aktif dengan {$nama}. Tunggu balasan terlebih dahulu.")
                ->withInput();
        }

        $dokter = Dokter::find($request->dokter_id);

        $konsultasi = Konsultasi::create([
            'pasien_id' => $pasien->id,
            'dokter_id' => $request->dokter_id,
            'judul'     => $request->judul,
            'pesan'     => $request->pesan,
            'status'    => 'menunggu',
        ]);

        $konsultasi->pesans()->create([
            'pengirim' => 'pasien',
            'pesan'    => $request->pesan,
        ]);

        // ── PAYMENT GATE: buat pembayaran QRIS untuk konsultasi ini ──
        $noInvoice = 'KONSUL-' . date('Ymd') . '-' . str_pad($konsultasi->id, 6, '0', STR_PAD_LEFT);
        $tarif     = $dokter ? (float) $dokter->tarif_konsultasi : 0;

        $pembayaran = \App\Models\Pembayaran::create([
            'konsultasi_id' => $konsultasi->id,
            'antrian_id'    => null,
            'pasien_id'     => $pasien->id,
            'kode_invoice'  => $noInvoice,
            'jumlah'        => $tarif > 0 ? $tarif : 50000, // tarif default jika 0
            'metode'        => 'qris',
            'status'        => 'menunggu',
        ]);

        return redirect()
            ->route('pasien.pembayaran.show', $pembayaran->id)
            ->with('info', '✅ Konsultasi berhasil dibuat! Selesaikan pembayaran QRIS untuk mulai berkonsultasi dengan dokter.');
    }

    public function show($id)
    {
        $pasien = auth()->user()->pasien;
        $konsultasi = Konsultasi::where('pasien_id', $pasien->id)
            ->with(['dokter.user', 'pesans'])
            ->findOrFail($id);

        // Mark as read by pasien
        if (!$konsultasi->dibaca_pasien && $konsultasi->status === 'dijawab') {
            $konsultasi->update(['dibaca_pasien' => true]);
        }

        // Ambil pembayaran konsultasi ini (jika ada)
        $pembayaranKonsultasi = \App\Models\Pembayaran::where('konsultasi_id', $konsultasi->id)
            ->latest()
            ->first();

        return view('pasien.konsultasi.show', compact('konsultasi', 'pembayaranKonsultasi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['pesan' => 'required|string|min:5']);
        $pasien     = auth()->user()->pasien;
        $konsultasi = Konsultasi::where('pasien_id', $pasien->id)->findOrFail($id);

        if ($konsultasi->status === 'ditutup') {
            return back()->with('error', 'Konsultasi sudah ditutup.');
        }

        $konsultasi->pesans()->create([
            'pengirim' => 'pasien',
            'pesan'    => $request->pesan,
        ]);
        $konsultasi->update([
            'status'       => 'menunggu',
            'dibaca_dokter'=> false,
        ]);

        return back()->with('success', 'Pesan terkirim.');
    }
}
