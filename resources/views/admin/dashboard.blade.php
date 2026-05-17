@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('sidebar')@include('admin._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Dashboard Admin</h1><p class="page-subtitle">Ringkasan sistem HealthDigital</p></div>
    <a href="{{ route('admin.laporan.index') }}" class="btn btn-secondary">
        <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Laporan Bulan Ini
    </a>
</div>

<!-- Key Metrics -->
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;margin-bottom:1.5rem;">
    @php $stats = [
        ['label'=>'Total Pasien','val'=>$totalPasien,'icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z','color'=>'#2563eb','bg'=>'#dbeafe'],
        ['label'=>'Total Dokter','val'=>$totalDokter,'icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z','color'=>'#10b981','bg'=>'#d1fae5'],
        ['label'=>'Total Perawat','val'=>$totalPerawat,'icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z','color'=>'#8b5cf6','bg'=>'#ede9fe'],
        ['label'=>'Antrian Hari Ini','val'=>$antrianHariIni,'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2','color'=>'#f59e0b','bg'=>'#fef3c7'],
    ]; @endphp
    @foreach($stats as $s)
    <div class="stat-card">
        <div style="background:{{ $s['bg'] }};width:2.5rem;height:2.5rem;border-radius:0.625rem;display:flex;align-items:center;justify-content:center;margin-bottom:0.75rem;">
            <svg style="width:1.25rem;height:1.25rem;color:{{ $s['color'] }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}"/></svg>
        </div>
        <div style="font-size:1.75rem;font-weight:800;color:#0f172a;">{{ $s['val'] }}</div>
        <div style="font-size:0.8125rem;color:#64748b;margin-top:0.25rem;">{{ $s['label'] }}</div>
    </div>
    @endforeach
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;" class="admin-grid">
    <div style="display:flex;flex-direction:column;gap:1.5rem;">
        <!-- Antrian Terbaru -->
        <div class="card">
            <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
                <span>Antrian Terbaru</span>
                <span class="badge badge-warning">{{ $antriansAktif }} Aktif</span>
            </div>
            @if($antriansRecent->isEmpty())
            <div class="empty-state"><div style="color:#94a3b8;">Belum ada antrian</div></div>
            @else
            <div class="table-container" style="border:none;">
                <table class="data-table">
                    <thead><tr><th>No</th><th>Pasien</th><th>Layanan</th><th>Status</th></tr></thead>
                    <tbody>
                        @foreach($antriansRecent as $a)
                        <tr>
                            <td><span style="font-family:monospace;font-weight:700;color:#2563eb;">{{ $a->no_antrian }}</span></td>
                            <td>{{ $a->pasien->user->name }}</td>
                            <td>{{ $a->layanan->nama }}</td>
                            <td><span class="badge badge-{{ $a->status_badge_color }} {{ $a->status==='dipanggil'?'badge-dipanggil':'' }}">{{ $a->status_label }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        <!-- Chart (Simple Bar) -->
        <div class="card">
            <div class="card-header">Antrian 7 Hari Terakhir</div>
            <div class="card-body">
                <div style="display:flex;align-items:flex-end;gap:0.5rem;height:150px;padding-top:1rem;">
                    @php $maxCount = $chartData->max('count') ?: 1; @endphp
                    @foreach($chartData->reverse() as $d)
                    <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:0.5rem;">
                        <div style="font-size:0.75rem;font-weight:600;color:#64748b;">{{ $d['count'] }}</div>
                        <div style="width:100%;background:#dbeafe;border-radius:0.25rem 0.25rem 0 0;height:{{ ($d['count']/$maxCount)*100 }}%;min-height:2px;transition:height 0.5s;"></div>
                        <div style="font-size:0.625rem;color:#94a3b8;">{{ $d['date'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div>
        <!-- Keuangan -->
        <div class="card" style="margin-bottom:1.5rem;background:linear-gradient(135deg,#047857,#10b981);color:white;">
            <div class="card-body">
                <div style="font-size:0.8125rem;opacity:0.8;">Pendapatan Bulan Ini</div>
                <div style="font-size:2rem;font-weight:800;margin:0.25rem 0 1rem;">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</div>
                <div style="display:flex;align-items:center;justify-content:space-between;border-top:1px solid rgba(255,255,255,0.2);padding-top:0.75rem;">
                    <div style="font-size:0.8125rem;opacity:0.9;">Total Transaksi Berhasil</div>
                    <div style="font-weight:700;">{{ \App\Models\Pembayaran::where('status', 'dibayar')->count() }}</div>
                </div>
            </div>
        </div>

        <!-- Action Items -->
        <div class="card">
            <div class="card-header">Perlu Tindakan</div>
            <div class="card-body" style="padding:0;">
                <a href="{{ route('admin.pembayaran.index') }}" style="display:flex;align-items:center;justify-content:space-between;padding:1rem;border-bottom:1px solid #f1f5f9;text-decoration:none;color:#1e293b;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
                    <div style="display:flex;align-items:center;gap:0.75rem;">
                        <div style="background:#dbeafe;color:#2563eb;width:2rem;height:2rem;border-radius:0.5rem;display:flex;align-items:center;justify-content:center;">
                            <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div style="font-weight:600;font-size:0.875rem;">Lihat Semua Pembayaran</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<style>@media(max-width:1024px){.admin-grid{grid-template-columns:1fr !important;}}</style>
@endsection
