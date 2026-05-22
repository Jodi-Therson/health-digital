@extends('layouts.app')
@section('title', 'Dashboard Perawat')
@section('sidebar')@include('perawat._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Dashboard Perawat</h1><p class="page-subtitle">{{ now()->format('l, d F Y') }}</p></div>
    <a href="{{ route('perawat.antrian.create') }}" class="btn btn-primary">+ Tambah Antrian</a>
</div>

<!-- Stats Hari Ini -->
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:1rem;margin-bottom:1.5rem;">
    @php $statColors = ['menunggu'=>['#f59e0b','#fef3c7'],'dipanggil'=>['#2563eb','#dbeafe'],'selesai'=>['#10b981','#d1fae5'],'total'=>['#8b5cf6','#ede9fe']]; @endphp
    @foreach(['menunggu'=>'Menunggu','dipanggil'=>'Dipanggil','selesai'=>'Selesai','total'=>'Total'] as $key => $label)
        <div style="background:{{ $statColors[$key][1] }};width:2.5rem;height:2.5rem;border-radius:0.625rem;display:flex;align-items:center;justify-content:center;margin-bottom:0.75rem;color:{{ $statColors[$key][0] }};">
            @if($key === 'menunggu')
                <svg style="width:1.25rem;height:1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            @elseif($key === 'dipanggil')
                <svg style="width:1.25rem;height:1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            @elseif($key === 'selesai')
                <svg style="width:1.25rem;height:1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            @else
                <svg style="width:1.25rem;height:1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            @endif
        </div>
        <div style="font-size:1.75rem;font-weight:800;color:#0f172a;">{{ $stats[$key] }}</div>
        <div style="font-size:0.8125rem;color:#64748b;margin-top:0.25rem;">{{ $label }}</div>
    </div>
    @endforeach
</div>

<!-- Daftar antrian -->
<div class="card">
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
        <span>Antrian Hari Ini</span>
        <a href="{{ route('perawat.antrian.index') }}" style="font-size:0.8125rem;color:#2563eb;text-decoration:none;">Lihat semua →</a>
    </div>
    @if($antrianHariIni->isEmpty())
    <div class="empty-state"><div style="color:#94a3b8;">Tidak ada antrian hari ini</div></div>
    @else
    <div class="table-container" style="border:none;">
        <table class="data-table">
            <thead><tr><th>No</th><th>Pasien</th><th>Dokter</th><th>Layanan</th><th>Keluhan</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($antrianHariIni as $a)
                <tr>
                    <td><span style="font-family:monospace;font-weight:700;color:#2563eb;">{{ $a->no_antrian }}</span></td>
                    <td><div style="font-weight:600;">{{ $a->pasien->user->name }}</div></td>
                    <td style="font-size:0.8125rem;">{{ $a->dokter->user->name }}</td>
                    <td>{{ $a->layanan->nama }}</td>
                    <td style="max-width:150px;font-size:0.8125rem;">{{ Str::limit($a->keluhan, 40) }}</td>
                    <td><span class="badge badge-{{ $a->status_badge_color }} {{ $a->status==='dipanggil'?'badge-dipanggil':'' }}">{{ $a->status_label }}</span></td>
                    <td>
                        @if($a->status === 'menunggu')
                        <form method="POST" action="{{ route('perawat.antrian.panggil', $a->id) }}">@csrf @method('PATCH')<button type="submit" class="btn btn-primary btn-sm">Panggil</button></form>
                        @else
                        <a href="{{ route('perawat.antrian.show', $a->id) }}" class="btn btn-secondary btn-sm">Detail</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
