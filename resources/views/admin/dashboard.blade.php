@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('sidebar')@include('admin._sidebar')@endsection
@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard Admin</h1>
        <p class="page-subtitle">Ringkasan sistem HealthDigital — {{ now()->format('d F Y') }}</p>
    </div>
    <a href="{{ route('admin.laporan.index') }}" class="btn btn-secondary">
        <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Laporan Bulan Ini
    </a>
</div>

{{-- ── KEY METRICS ── --}}
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1rem;margin-bottom:1.5rem;">
    @php $stats = [
        ['label'=>'Total Pasien','val'=>$totalPasien,'icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z','color'=>'#2563eb','bg'=>'#dbeafe','href'=>route('admin.users.index')],
        ['label'=>'Total Dokter','val'=>$totalDokter,'icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z','color'=>'#10b981','bg'=>'#d1fae5','href'=>route('admin.users.index')],
        ['label'=>'Total Perawat','val'=>$totalPerawat,'icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z','color'=>'#8b5cf6','bg'=>'#ede9fe','href'=>route('admin.users.index')],
        ['label'=>'Antrian Hari Ini','val'=>$antrianHariIni,'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2','color'=>'#f59e0b','bg'=>'#fef3c7','href'=>'#'],
        ['label'=>'Pembayaran Pending','val'=>$pembayaranPending,'icon'=>'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z','color'=>'#ef4444','bg'=>'#fee2e2','href'=>route('admin.pembayaran.index')],
        ['label'=>'Konsultasi Pending','val'=>$konsultasiPending,'icon'=>'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z','color'=>'#06b6d4','bg'=>'#cffafe','href'=>'#'],
    ]; @endphp
    @foreach($stats as $s)
    <a href="{{ $s['href'] }}" style="text-decoration:none;">
        <div class="stat-card" style="transition:transform 0.15s,box-shadow 0.15s;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 20px rgba(0,0,0,0.08)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
            <div style="background:{{ $s['bg'] }};width:2.5rem;height:2.5rem;border-radius:0.625rem;display:flex;align-items:center;justify-content:center;margin-bottom:0.75rem;">
                <svg style="width:1.25rem;height:1.25rem;color:{{ $s['color'] }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}"/></svg>
            </div>
            <div style="font-size:1.75rem;font-weight:800;color:#0f172a;">{{ $s['val'] }}</div>
            <div style="font-size:0.8125rem;color:#64748b;margin-top:0.25rem;">{{ $s['label'] }}</div>
        </div>
    </a>
    @endforeach
</div>

{{-- ── PENDAPATAN CARD ── --}}
<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin-bottom:1.5rem;" class="revenue-row">
    <div class="card" style="background:linear-gradient(135deg,#047857,#10b981);color:white;grid-column:span 1;">
        <div class="card-body">
            <div style="font-size:0.8125rem;opacity:0.85;margin-bottom:0.25rem;">Pendapatan Bulan Ini</div>
            <div style="font-size:1.75rem;font-weight:800;margin-bottom:0.75rem;">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</div>
            <a href="{{ route('admin.pembayaran.index') }}" style="font-size:0.8125rem;color:rgba(255,255,255,0.85);text-decoration:none;border-bottom:1px solid rgba(255,255,255,0.4);">Lihat Pembayaran →</a>
        </div>
    </div>
    <div class="card" style="background:linear-gradient(135deg,#1e3a8a,#2563eb);color:white;">
        <div class="card-body">
            <div style="font-size:0.8125rem;opacity:0.85;margin-bottom:0.25rem;">Total Pendapatan Semua</div>
            <div style="font-size:1.75rem;font-weight:800;margin-bottom:0.75rem;">Rp {{ number_format($pendapatanTotalAll, 0, ',', '.') }}</div>
            <div style="font-size:0.8125rem;opacity:0.7;">Semua waktu</div>
        </div>
    </div>
    <div class="card" style="background:linear-gradient(135deg,#92400e,#d97706);color:white;">
        <div class="card-body">
            <div style="font-size:0.8125rem;opacity:0.85;margin-bottom:0.25rem;">Antrian Aktif Hari Ini</div>
            <div style="font-size:1.75rem;font-weight:800;margin-bottom:0.75rem;">{{ $antriansAktif }}</div>
            <div style="font-size:0.8125rem;opacity:0.7;">Menunggu &amp; Dipanggil</div>
        </div>
    </div>
</div>

{{-- ── CHARTS ROW ── --}}
<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;margin-bottom:1.5rem;" class="charts-row">

    {{-- Chart: Antrian 7 hari --}}
    <div class="card">
        <div class="card-header">
            <span style="font-weight:700;">Antrian 7 Hari Terakhir</span>
            <span class="badge badge-primary">Harian</span>
        </div>
        <div class="card-body" style="padding: 1.25rem;">
            <div style="height: 300px; position: relative;">
                <canvas id="chartAntrian"></canvas>
            </div>
        </div>
    </div>

    {{-- Chart: Status Distribution Donut --}}
    <div class="card">
        <div class="card-header"><span style="font-weight:700;">Status Antrian Bulan Ini</span></div>
        <div class="card-body" style="display:flex;flex-direction:column;align-items:center;padding:1.25rem;">
            <div style="height: 300px; width: 100%; position: relative; display: flex; justify-content: center; align-items: center; margin-bottom: 1rem;">
                <canvas id="chartStatus" style="max-width:260px;"></canvas>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.5rem;width:100%;font-size:0.8125rem;">
                @php
                    $statusColors = ['menunggu'=>'#f59e0b','dipanggil'=>'#2563eb','selesai'=>'#10b981','batal'=>'#ef4444'];
                    $statusLabels = ['menunggu'=>'Menunggu','dipanggil'=>'Dipanggil','selesai'=>'Selesai','batal'=>'Batal'];
                @endphp
                @foreach($statusDistribusi as $key => $val)
                <div style="display:flex;align-items:center;gap:0.375rem;">
                    <div style="width:0.75rem;height:0.75rem;border-radius:50%;background:{{ $statusColors[$key] }};flex-shrink:0;"></div>
                    <span style="color:#64748b;">{{ $statusLabels[$key] }}: <strong style="color:#1e293b;">{{ $val }}</strong></span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- ── BOTTOM ROW ── --}}
<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;" class="bottom-row">

    {{-- Chart: Pendapatan Harian --}}
    <div class="card">
        <div class="card-header"><span style="font-weight:700;">Pendapatan 7 Hari Terakhir</span></div>
        <div class="card-body" style="padding: 1.25rem;">
            <div style="height: 300px; position: relative;">
                <canvas id="chartPendapatan"></canvas>
            </div>
        </div>
    </div>

    {{-- Top Layanan --}}
    <div class="card">
        <div class="card-header"><span style="font-weight:700;">Layanan Terpopuler</span><span style="font-size:0.75rem;color:#94a3b8;">Bulan ini</span></div>
        <div class="card-body" style="padding:0;">
            @if($layananDistribusi->isEmpty())
            <div style="padding:2rem;text-align:center;color:#94a3b8;font-size:0.875rem;">Belum ada data</div>
            @else
            @php $maxCount = $layananDistribusi->max('antrians_count') ?: 1; @endphp
            @foreach($layananDistribusi as $l)
            <div style="padding:0.75rem 1.25rem;border-bottom:1px solid #f1f5f9;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.375rem;">
                    <span style="font-size:0.875rem;font-weight:600;color:#1e293b;">{{ $l->nama }}</span>
                    <span style="font-size:0.8125rem;font-weight:700;color:#2563eb;">{{ $l->antrians_count }}</span>
                </div>
                <div style="background:#f1f5f9;border-radius:9999px;height:4px;overflow:hidden;">
                    <div style="background:#2563eb;height:100%;border-radius:9999px;width:{{ ($l->antrians_count/$maxCount)*100 }}%;transition:width 0.8s ease;"></div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</div>

<style>
@media(max-width:1024px){
    .charts-row,.bottom-row,.revenue-row{grid-template-columns:1fr !important;}
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart 1: Antrian Bar Chart
    const antrianLabels = @json($chartData->pluck('date'));
    const antrianData   = @json($chartData->pluck('count'));
    new Chart(document.getElementById('chartAntrian'), {
        type: 'bar',
        data: {
            labels: antrianLabels,
            datasets: [{
                label: 'Jumlah Antrian',
                data: antrianData,
                backgroundColor: 'rgba(37, 99, 235, 0.15)',
                borderColor: '#2563eb',
                borderWidth: 2,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ` ${ctx.raw} antrian` } } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0,0,0,0.05)' } },
                x: { grid: { display: false } }
            }
        }
    });

    // Chart 2: Status Donut
    const statusLabels = @json(array_keys($statusDistribusi));
    const statusData   = @json(array_values($statusDistribusi));
    const statusDisplayLabels = { menunggu: 'Menunggu', dipanggil: 'Dipanggil', selesai: 'Selesai', batal: 'Batal' };
    new Chart(document.getElementById('chartStatus'), {
        type: 'doughnut',
        data: {
            labels: statusLabels.map(k => statusDisplayLabels[k] || k),
            datasets: [{
                data: statusData,
                backgroundColor: ['#fbbf24','#3b82f6','#10b981','#ef4444'],
                borderWidth: 2,
                borderColor: '#ffffff',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.raw}` } } }
        }
    });

    // Chart 3: Pendapatan Area Chart
    const pendLabels = @json($pendapatanHarian->pluck('date'));
    const pendData   = @json($pendapatanHarian->pluck('jumlah'));
    new Chart(document.getElementById('chartPendapatan'), {
        type: 'line',
        data: {
            labels: pendLabels,
            datasets: [{
                label: 'Pendapatan',
                data: pendData,
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#10b981',
                pointRadius: 4,
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ` Rp ${Number(ctx.raw).toLocaleString('id-ID')}`
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: v => 'Rp ' + Number(v).toLocaleString('id-ID') },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: { grid: { display: false } }
            }
        }
    });
});
</script>

@endsection
