@extends('layouts.app')
@section('title', 'Manajemen Layanan')
@section('sidebar')@include('admin._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Manajemen Layanan</h1><p class="page-subtitle">Kelola poli dan layanan kesehatan</p></div>
    <a href="{{ route('admin.layanan.create') }}" class="btn btn-primary">+ Tambah Layanan</a>
</div>
<div class="card">
    @if($layanans->isEmpty())
    <div class="empty-state">
        <svg style="width:3rem;height:3rem;color:#cbd5e1;margin:0 auto 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        <div style="color:#94a3b8;">Belum ada data layanan. <a href="{{ route('admin.layanan.create') }}" style="color:#2563eb;">Tambah sekarang →</a></div>
    </div>
    @else
    <div class="table-container" style="border:none;">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:60px; text-align:center;">Urutan</th>
                    <th style="width:90px;">Gambar</th>
                    <th>Nama Layanan</th>
                    <th>Deskripsi</th>
                    <th>Status</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($layanans as $l)
                <tr>
                    <td style="text-align:center; font-weight:700; color:#64748b;">#{{ $l->urutan }}</td>
                    <td>
                        @if($l->gambar_url)
                        <img src="{{ $l->gambar_url }}" alt="{{ $l->nama }}"
                             style="width:56px; height:56px; object-fit:cover; border-radius:0.5rem; border:1px solid #e2e8f0;">
                        @else
                        <div style="background:#f1f5f9; width:56px; height:56px; border-radius:0.5rem; display:flex; align-items:center; justify-content:center; color:#94a3b8; border:1px solid #e2e8f0;">
                            <svg style="width:1.5rem;height:1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:600; color:#1e293b;">{{ $l->nama }}</div>
                    </td>
                    <td><div style="max-width:300px; font-size:0.8125rem; color:#64748b;">{{ Str::limit($l->deskripsi, 80) }}</div></td>
                    <td>
                        <form method="POST" action="{{ route('admin.layanan.update', $l->id) }}" x-ref="toggleForm{{ $l->id }}">
                            @csrf @method('PUT')
                            <input type="hidden" name="nama" value="{{ $l->nama }}">
                            <input type="hidden" name="urutan" value="{{ $l->urutan }}">
                            <input type="hidden" name="is_active" value="{{ $l->is_active ? '0' : '1' }}">
                            <button type="button"
                                @click="triggerConfirm(
                                    '{{ $l->is_active ? 'Nonaktifkan Layanan' : 'Aktifkan Layanan' }}',
                                    '{{ $l->is_active ? 'Pasien tidak bisa mendaftar antrian layanan ini setelah dinonaktifkan.' : 'Pasien dapat kembali mendaftar antrian untuk layanan ini.' }}',
                                    () => { $refs.toggleForm{{ $l->id }}.submit() },
                                    '{{ $l->is_active ? 'danger' : 'primary' }}'
                                )"
                                class="badge {{ $l->is_active ? 'badge-success' : 'badge-danger' }}"
                                style="border:none; cursor:pointer;">
                                {{ $l->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </form>
                    </td>
                    <td>
                        <div style="display:flex; gap:0.5rem; justify-content:flex-end;">
                            <a href="{{ route('admin.layanan.edit', $l->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form method="POST" action="{{ route('admin.layanan.destroy', $l->id) }}" x-ref="deleteForm{{ $l->id }}">
                                @csrf @method('DELETE')
                                <button type="button"
                                    @click="triggerConfirm(
                                        'Hapus Layanan',
                                        'Anda yakin ingin menghapus layanan {{ $l->nama }}? Gambar juga akan dihapus. Tindakan ini tidak dapat dibatalkan.',
                                        () => { $refs.deleteForm{{ $l->id }}.submit() },
                                        'danger'
                                    )"
                                    class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="padding:1rem 1.5rem;">{{ $layanans->links() }}</div>
    @endif
</div>
@endsection
