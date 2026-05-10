@extends('layouts.app')
@section('title', 'Manajemen Fasilitas')
@section('sidebar')@include('admin._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Manajemen Fasilitas</h1></div>
    <a href="{{ route('admin.fasilitas.create') }}" class="btn btn-primary">+ Tambah Fasilitas</a>
</div>
<div class="card">
    @if($fasilitas->isEmpty())
    <div class="empty-state"><div style="color:#94a3b8;">Belum ada data fasilitas</div></div>
    @else
    <div class="table-container" style="border:none;">
        <table class="data-table">
            <thead><tr><th>Foto</th><th>Nama Fasilitas</th><th>Kategori</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($fasilitas as $f)
                <tr>
                    <td>
                        @if($f->foto)<img src="{{ asset('storage/'.$f->foto) }}" style="width:4rem;height:3rem;object-fit:cover;border-radius:0.375rem;">
                        @else<div style="width:4rem;height:3rem;background:#f1f5f9;border-radius:0.375rem;display:flex;align-items:center;justify-content:center;color:#94a3b8;font-size:0.75rem;">No Img</div>@endif
                    </td>
                    <td><div style="font-weight:600;">{{ $f->nama }}</div><div style="font-size:0.75rem;color:#64748b;max-width:200px;">{{ Str::limit($f->deskripsi, 40) }}</div></td>
                    <td><span class="badge badge-neutral" style="text-transform:capitalize;">{{ $f->kategori }}</span></td>
                    <td><span class="badge {{ $f->is_active ? 'badge-success' : 'badge-danger' }}">{{ $f->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td>
                        <div style="display:flex;gap:0.5rem;">
                            <a href="{{ route('admin.fasilitas.edit', $f->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form method="POST" action="{{ route('admin.fasilitas.destroy', $f->id) }}" onsubmit="return confirm('Hapus fasilitas ini?');">
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
