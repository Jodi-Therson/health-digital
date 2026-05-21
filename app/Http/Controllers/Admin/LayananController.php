<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'urutan'    => 'integer|min:0',
            'gambar'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.max'   => 'Ukuran gambar maksimal 2MB.',
        ]);

        $data = $request->only('nama', 'deskripsi', 'urutan') + [
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('layanan', 'public');
        }

        Layanan::create($data);

        return redirect()->route('admin.layanan.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit(Layanan $layanan)
    {
        return view('admin.layanan.edit', compact('layanan'));
    }

    public function update(Request $request, Layanan $layanan)
    {
        $request->validate([
            'nama'    => 'required|string|max:255',
            'urutan'  => 'integer|min:0',
            'gambar'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.max'   => 'Ukuran gambar maksimal 2MB.',
        ]);

        $data = [
            'nama'      => $request->nama,
            'deskripsi' => $request->deskripsi,
            'urutan'    => $request->urutan ?? 0,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($layanan->gambar) {
                Storage::disk('public')->delete($layanan->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('layanan', 'public');
        }

        $layanan->update($data);

        return redirect()->route('admin.layanan.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Layanan $layanan)
    {
        // Hapus gambar dari storage jika ada
        if ($layanan->gambar) {
            Storage::disk('public')->delete($layanan->gambar);
        }
        $layanan->delete();
        return back()->with('success', 'Layanan berhasil dihapus.');
    }
}
