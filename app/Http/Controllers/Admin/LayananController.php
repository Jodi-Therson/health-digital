<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Models\Fasilitas;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index()
    {
        $layanans = Layanan::orderBy('urutan')->paginate(10);
        return view('admin.layanan.index', compact('layanans'));
    }

    public function create()
    {
        return view('admin.layanan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'ikon'      => 'nullable|string|max:100',
            'urutan'    => 'integer|min:0',
        ]);

        Layanan::create($request->only('nama', 'deskripsi', 'ikon', 'urutan', 'is_active') + [
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.layanan.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit(Layanan $layanan)
    {
        return view('admin.layanan.edit', compact('layanan'));
    }

    public function update(Request $request, Layanan $layanan)
    {
        $request->validate([
            'nama'   => 'required|string|max:255',
            'urutan' => 'integer|min:0',
        ]);

        $layanan->update([
            'nama'      => $request->nama,
            'deskripsi' => $request->deskripsi,
            'ikon'      => $request->ikon,
            'urutan'    => $request->urutan ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.layanan.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Layanan $layanan)
    {
        $layanan->delete();
        return back()->with('success', 'Layanan berhasil dihapus.');
    }
}
