@extends('layouts.app')
@section('title', 'Antrian Saya')
@section('sidebar')@include('pasien._sidebar')@endsection
@section('breadcrumb')
<a href="{{ route('pasien.dashboard') }}">Dashboard</a><span>/</span><span>Antrian Saya</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Antrian Saya</h1>
        <p class="page-subtitle">Riwayat dan status antrian Anda</p>
    </div>
    <a href="{{ route('pasien.antrian.create') }}" class="btn btn-primary">
        <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Daftar Antrian Baru
    </a>
</div>

<div class="card">
    @if($antrians->isEmpty())
    <div class="empty-state">
        <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        <div style="font-weight:600; color:#94a3b8; margin-bottom:0.75rem;">Belum ada antrian</div>
        <a href="{{ route('pasien.antrian.create') }}" class="btn btn-primary btn-sm">Daftar Antrian Sekarang</a>
    </div>
    @else
    <div class="table-container" style="border:none;">
        <table class="data-table">
            <thead><tr><th>No. Antrian</th><th>Dokter</th><th>Layanan</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($antrians as $a)
                <tr>
                    <td><span style="font-family:monospace;font-weight:700;color:#2563eb;">{{ $a->no_antrian }}</span></td>
                    <td><div style="font-weight:500;">{{ $a->dokter->user->name }}</div><div style="font-size:0.75rem;color:#94a3b8;">{{ $a->dokter->spesialisasi }}</div></td>
                    <td>{{ $a->layanan->nama }}</td>
                    <td>{{ $a->tanggal->format('d M Y') }}</td>
                    <td><span class="badge badge-{{ $a->status_badge_color }} {{ $a->status==='dipanggil'?'badge-dipanggil':'' }}">{{ $a->status_label }}</span></td>
                    <td><a href="{{ route('pasien.antrian.show', $a->id) }}" class="btn btn-secondary btn-sm">Detail</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="padding:1rem 1.5rem;">{{ $antrians->links() }}</div>
    @endif
</div>
@endsection
