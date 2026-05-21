@extends('layouts.app')

@section('title', 'Dashboard Pasien')

@section('sidebar')
@php $menu = [
    ['route'=>'pasien.dashboard', 'label'=>'Dashboard', 'icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
    ['route'=>'pasien.antrian.index', 'label'=>'Antrian Saya', 'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
    ['route'=>'pasien.konsultasi.index', 'label'=>'Konsultasi', 'icon'=>'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
    ['route'=>'pasien.pembayaran.index', 'label'=>'Pembayaran', 'icon'=>'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
]; @endphp
<div style="padding:0 0 1rem; border-bottom:1px solid #f1f5f9; margin:0 0.5rem 0.75rem;">
    <div style="display:flex; align-items:center; gap:0.625rem; padding:0.5rem;">
        <img src="{{ auth()->user()->avatar_url }}" style="width:2.5rem;height:2.5rem;border-radius:50%;object-fit:cover;border:2px solid #bfdbfe;">
        <div>
            <div style="font-size:0.8125rem; font-weight:700; color:#1e293b;">{{ Str::limit(auth()->user()->name, 18) }}</div>
            <div style="font-size:0.75rem; color:#10b981; font-weight:500;">● Pasien</div>
        </div>
    </div>
</div>
@foreach($menu as $item)
<a href="{{ route($item['route']) }}" class="sidebar-link {{ request()->routeIs($item['route']) ? 'active' : '' }}">
    <svg style="width:1.125rem;height:1.125rem;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/></svg>
    {{ $item['label'] }}
</a>
@endforeach
<div style="border-top:1px solid #f1f5f9; margin:0.75rem 0.5rem 0; padding-top:0.75rem;">
    <button type="button" @click="showLogoutModal = true" class="sidebar-link" style="width:100%; color:#ef4444; background:none; border:none; cursor:pointer; text-align:left;">
        <svg style="width:1.125rem;height:1.125rem;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
        Keluar
    </button>
</div>
@endsection

@section('breadcrumb')
<a href="{{ route('home') }}">Beranda</a>
<span>/</span>
<span>Dashboard</span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard Pasien</h1>
        <p class="page-subtitle">Selamat datang, {{ auth()->user()->name }}!</p>
    </div>
    <a href="{{ route('pasien.antrian.create') }}" class="btn btn-primary">
        <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Daftar Antrian
    </a>
</div>

@if(!$pasien)
<div class="card" style="border-left:4px solid #f59e0b; margin-bottom:1.5rem;">
    <div class="card-body" style="display:flex; align-items:center; gap:1rem;">
        <div style="background:#fef3c7; width:3rem; height:3rem; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
            <svg style="width:1.5rem;height:1.5rem;color:#f59e0b;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <div>
            <div style="font-weight:600; color:#92400e;">Profil Belum Lengkap</div>
            <div style="font-size:0.875rem; color:#78350f;">Harap lengkapi profil pasien Anda terlebih dahulu.</div>
        </div>
    </div>
</div>
@endif

<!-- Antrian aktif hari ini -->
@if(isset($antrianHariIni) && $antrianHariIni)
<div style="background:linear-gradient(135deg,#2563eb,#1d4ed8); border-radius:1rem; padding:1.5rem; margin-bottom:1.5rem; color:white;">
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div>
            <div style="font-size:0.8125rem; opacity:0.8; margin-bottom:0.25rem;">Antrian Aktif Hari Ini</div>
            <div style="font-size:2.5rem; font-weight:800; font-family:monospace; letter-spacing:0.1em;">{{ $antrianHariIni->no_antrian }}</div>
            <div style="font-size:0.875rem; opacity:0.9; margin-top:0.25rem;">{{ $antrianHariIni->dokter->user->name }} — {{ $antrianHariIni->layanan->nama }}</div>
        </div>
        <div style="text-align:right;">
            @if($antrianHariIni->status === 'dipanggil')
            <div class="badge badge-dipanggil" style="font-size:0.875rem; padding:0.5rem 1rem;">🔔 Anda Dipanggil!</div>
            @else
            <div class="badge" style="background:rgba(255,255,255,0.2); color:white; font-size:0.875rem; padding:0.5rem 1rem;">⏳ Menunggu</div>
            @endif
            <div style="font-size:0.8125rem; opacity:0.7; margin-top:0.5rem;">{{ $antrianHariIni->tanggal->format('d M Y') }}</div>
        </div>
    </div>
</div>
@endif

{{-- Widget Tagihan Belum Dibayar --}}
@if($pembayarans->whereIn('status', ['menunggu', 'ditolak'])->count() > 0)
<div class="card" style="border-left:4px solid #ef4444; margin-bottom:1.5rem; background:#fff5f5;">
    <div class="card-body" style="display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
        <div style="display:flex; align-items:center; gap:1rem;">
            <div style="background:#fee2e2; width:3rem; height:3rem; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg style="width:1.5rem;height:1.5rem;color:#ef4444;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3 1.343 3 3-1.343 3-3 3m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div style="font-weight:700; color:#991b1b;">Tagihan Belum Dibayar</div>
                <div style="font-size:0.875rem; color:#b91c1c;">Anda memiliki {{ $pembayarans->whereIn('status', ['menunggu', 'ditolak'])->count() }} tagihan yang perlu diselesaikan.</div>
            </div>
        </div>
        <a href="{{ route('pasien.pembayaran.index') }}" class="btn btn-primary" style="background:#ef4444; border-color:#ef4444;">Lihat Tagihan</a>
    </div>
</div>
@endif

<!-- Stats -->
<div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:1rem; margin-bottom:1.5rem;">
    @php $stats = [
        ['label'=>'Total Antrian', 'value'=>$pasien ? $pasien->antrians()->count() : 0, 'color'=>'#2563eb', 'bg'=>'#dbeafe', 'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
        ['label'=>'Konsultasi Pending', 'value'=>$konsultasis->count(), 'color'=>'#f59e0b', 'bg'=>'#fef3c7', 'icon'=>'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
        ['label'=>'Tagihan Pending', 'value'=>$pembayarans->count(), 'color'=>'#ef4444', 'bg'=>'#fee2e2', 'icon'=>'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
    ]; @endphp
    @foreach($stats as $s)
    <div class="stat-card">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.75rem;">
            <div style="background:{{ $s['bg'] }}; width:2.5rem; height:2.5rem; border-radius:0.625rem; display:flex; align-items:center; justify-content:center;">
                <svg style="width:1.25rem;height:1.25rem;color:{{ $s['color'] }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}"/></svg>
            </div>
        </div>
        <div style="font-size:1.75rem; font-weight:800; color:#0f172a;">{{ $s['value'] }}</div>
        <div style="font-size:0.8125rem; color:#64748b; margin-top:0.25rem;">{{ $s['label'] }}</div>
    </div>
    @endforeach
</div>

<!-- Riwayat antrian terbaru -->
<div class="card">
    <div class="card-header" style="display:flex; align-items:center; justify-content:space-between;">
        <span>Riwayat Antrian Terbaru</span>
        <a href="{{ route('pasien.antrian.index') }}" style="font-size:0.8125rem; color:#2563eb; text-decoration:none;">Lihat semua →</a>
    </div>
    @if($antrians->isEmpty())
    <div class="empty-state">
        <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        <div style="font-weight:600; color:#94a3b8; margin-bottom:0.5rem;">Belum ada antrian</div>
        <a href="{{ route('pasien.antrian.create') }}" class="btn btn-primary btn-sm">Daftar Antrian Pertama</a>
    </div>
    @else
    <div class="table-container" style="border:none; border-radius:0;">
        <table class="data-table">
            <thead><tr>
                <th>No. Antrian</th><th>Dokter</th><th>Layanan</th><th>Tanggal</th><th>Status</th><th>Aksi</th>
            </tr></thead>
            <tbody>
                @foreach($antrians as $a)
                <tr>
                    <td><span style="font-family:monospace; font-weight:700; color:#2563eb;">{{ $a->no_antrian }}</span></td>
                    <td>{{ $a->dokter->user->name }}</td>
                    <td>{{ $a->layanan->nama }}</td>
                    <td>{{ $a->tanggal->format('d M Y') }}</td>
                    <td>
                        <span class="badge badge-{{ $a->status_badge_color }} {{ $a->status === 'dipanggil' ? 'badge-dipanggil' : '' }}">
                            {{ $a->status_label }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('pasien.antrian.show', $a->id) }}" class="btn btn-secondary btn-sm">Detail</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
