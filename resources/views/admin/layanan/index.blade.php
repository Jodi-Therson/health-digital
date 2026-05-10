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
    <div class="empty-state"><div style="color:#94a3b8;">Belum ada data layanan</div></div>
    @else
    <div class="table-container" style="border:none;">
        <table class="data-table">
            <thead><tr><th>Nama Layanan</th><th>Deskripsi</th><th>Harga Dasar</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($layanans as $l)
                <tr>
                    <td><div style="font-weight:600;">{{ $l->nama }}</div></td>
                    <td style="max-width:250px;">{{ Str::limit($l->deskripsi, 60) }}</td>
                    <td style="font-weight:600;color:#1e293b;">Rp {{ number_format($l->harga_dasar, 0, ',', '.') }}</td>
                    <td><span class="badge {{ $l->is_active ? 'badge-success' : 'badge-danger' }}">{{ $l->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td>
                        <div style="display:flex;gap:0.5rem;">
                            <a href="{{ route('admin.layanan.edit', $l->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form method="POST" action="{{ route('admin.layanan.destroy', $l->id) }}" onsubmit="return confirm('Hapus layanan ini?');">
                                @csrf @method('DELETE')<button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
