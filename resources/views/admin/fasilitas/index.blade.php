@extends('layouts.app')
@section('title', 'Manajemen Fasilitas')
@section('sidebar')@include('admin._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Manajemen Fasilitas</h1></div>
    <a href="{{ route('admin.fasilitas.create') }}" class="btn btn-primary">+ Tambah Fasilitas</a>
</div>
<div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:1.5rem;">
    @foreach($fasilitas as $f)
    <div class="card" style="padding:0; overflow:hidden; display:flex; flex-direction:column;">
        <div style="position:relative; height:180px; background:#f1f5f9;">
            @if($f->foto)
            <img src="{{ asset('storage/'.$f->foto) }}" style="width:100%; height:100%; object-fit:cover;">
            @else
            <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#94a3b8;">
                <svg style="width:3rem;height:3rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            @endif
            <div style="position:absolute; top:0.75rem; right:0.75rem;">
                <span class="badge {{ $f->is_active ? 'badge-success' : 'badge-danger' }}" style="box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                    {{ $f->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>
        </div>
        <div class="card-body" style="flex:1; display:flex; flex-direction:column;">
            <h3 style="margin:0 0 0.5rem 0; font-size:1.125rem; font-weight:700; color:#1e293b;">{{ $f->nama }}</h3>
            <p style="font-size:0.875rem; color:#64748b; margin-bottom:1.25rem; flex:1;">{{ Str::limit($f->deskripsi, 100) }}</p>
            <div style="display:flex; gap:0.5rem; justify-content:flex-end; border-top:1px solid #f1f5f9; padding-top:1rem;">
                <a href="{{ route('admin.fasilitas.edit', $f->id) }}" class="btn btn-secondary btn-sm" style="background:#f8fafc;">Edit</a>
                <form method="POST" action="{{ route('admin.fasilitas.destroy', $f->id) }}" onsubmit="return confirm('Hapus fasilitas ini?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" style="background:#fee2e2; color:#ef4444; border:none;">Hapus</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>

@if($fasilitas->isEmpty())
<div class="empty-state">
    <div style="color:#94a3b8; font-size:1.125rem;">Belum ada fasilitas yang ditambahkan</div>
    <a href="{{ route('admin.fasilitas.create') }}" class="btn btn-primary" style="margin-top:1rem;">Tambah Sekarang</a>
</div>
@endif

<div style="margin-top:2rem;">
    {{ $fasilitas->links() }}
</div>
@endsection
