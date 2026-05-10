@extends('layouts.app')
@section('title', 'Kelola Antrian')
@section('sidebar')@include('perawat._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Kelola Antrian</h1></div>
    <a href="{{ route('perawat.antrian.create') }}" class="btn btn-primary">+ Tambah Antrian</a>
</div>
<div class="card" style="margin-bottom:1rem;"><div class="card-body" style="padding:1rem;">
    <form method="GET" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
        <div><label class="form-label" style="margin-bottom:0.25rem;">Tanggal</label><input type="date" name="tanggal" value="{{ $tanggal }}" class="form-input" style="width:auto;"></div>
        <div><label class="form-label" style="margin-bottom:0.25rem;">Status</label>
            <select name="status" class="form-input" style="width:auto;">
                <option value="">Semua</option>
                <option value="menunggu" {{ request('status')=='menunggu'?'selected':'' }}>Menunggu</option>
                <option value="dipanggil" {{ request('status')=='dipanggil'?'selected':'' }}>Dipanggil</option>
                <option value="selesai" {{ request('status')=='selesai'?'selected':'' }}>Selesai</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
    </form>
</div></div>
<div class="card">
    @if($antrians->isEmpty())
    <div class="empty-state"><div style="color:#94a3b8;">Tidak ada antrian untuk filter ini</div></div>
    @else
    <div class="table-container" style="border:none;">
        <table class="data-table">
            <thead><tr><th>No</th><th>Pasien</th><th>Dokter</th><th>Layanan</th><th>Keluhan</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($antrians as $a)
                <tr>
                    <td><span style="font-family:monospace;font-weight:700;color:#2563eb;">{{ $a->no_antrian }}</span></td>
                    <td><div style="font-weight:600;">{{ $a->pasien->user->name }}</div><div style="font-size:0.75rem;color:#94a3b8;">{{ $a->pasien->nik }}</div></td>
                    <td style="font-size:0.875rem;">{{ $a->dokter->user->name }}</td>
                    <td>{{ $a->layanan->nama }}</td>
                    <td style="max-width:150px;font-size:0.8125rem;">{{ Str::limit($a->keluhan, 40) }}</td>
                    <td><span class="badge badge-{{ $a->status_badge_color }} {{ $a->status==='dipanggil'?'badge-dipanggil':'' }}">{{ $a->status_label }}</span></td>
                    <td>
                        <div style="display:flex;gap:0.5rem;">
                            @if($a->status === 'menunggu')
                            <form method="POST" action="{{ route('perawat.antrian.panggil', $a->id) }}">@csrf @method('PATCH')<button type="submit" class="btn btn-primary btn-sm">Panggil</button></form>
                            @endif
                            <a href="{{ route('perawat.antrian.show', $a->id) }}" class="btn btn-secondary btn-sm">Detail</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="padding:1rem 1.5rem;">{{ $antrians->links() }}</div>
    @endif
</div>
@endsection
