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
    <div class="stat-card">
        <div style="background:{{ $statColors[$key][1] }};width:2.5rem;height:2.5rem;border-radius:0.625rem;display:flex;align-items:center;justify-content:center;margin-bottom:0.75rem;">
            <div style="font-size:1.25rem;">{{ $key==='menunggu'?'⏳':($key==='dipanggil'?'🔔':($key==='selesai'?'✅':'📋')) }}</div>
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
