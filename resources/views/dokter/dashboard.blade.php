@extends('layouts.app')
@section('title', 'Dashboard Dokter')
@section('sidebar')@include('dokter._sidebar')@endsection
@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard Dokter</h1>
        <p class="page-subtitle">Selamat datang, {{ auth()->user()->name }}! — {{ now()->format('l, d F Y') }}</p>
    </div>
    <a href="{{ route('dokter.rekam-medis.create') }}" class="btn btn-primary">
        <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Buat Rekam Medis
    </a>
</div>

{{-- ── KEY METRICS ── --}}
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1rem;margin-bottom:1.5rem;">
    @php $stats = [
        ['label'=>'Pasien Hari Ini','value'=>$totalHariIni,'color'=>'#2563eb','bg'=>'#dbeafe','icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z','desc'=>'Antrian hari ini'],
        ['label'=>'Total Pasien','value'=>$totalPasien,'color'=>'#10b981','bg'=>'#d1fae5','icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z','desc'=>'Semua waktu'],
        ['label'=>'Selesai Ditangani','value'=>$totalSelesai,'color'=>'#8b5cf6','bg'=>'#ede9fe','icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z','desc'=>'Total antrian selesai'],
        ['label'=>'Konsultasi Pending','value'=>$konsultasiPending->count(),'color'=>'#f59e0b','bg'=>'#fef3c7','icon'=>'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z','desc'=>'Belum dijawab'],
    ]; @endphp
    @foreach($stats as $s)
    <div class="stat-card" style="transition:transform 0.15s,box-shadow 0.15s;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 20px rgba(0,0,0,0.08)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
        <div style="background:{{ $s['bg'] }};width:2.5rem;height:2.5rem;border-radius:0.625rem;display:flex;align-items:center;justify-content:center;margin-bottom:0.75rem;">
            <svg style="width:1.25rem;height:1.25rem;color:{{ $s['color'] }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}"/></svg>
        </div>
        <div style="font-size:1.75rem;font-weight:800;color:#0f172a;">{{ $s['value'] }}</div>
        <div style="font-size:0.8125rem;color:#64748b;margin-top:0.25rem;">{{ $s['label'] }}</div>
        <div style="font-size:0.75rem;color:#94a3b8;margin-top:0.125rem;">{{ $s['desc'] }}</div>
    </div>
    @endforeach
</div>

{{-- ── CHARTS ROW ── --}}
<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;margin-bottom:1.5rem;" class="dash-charts">
    {{-- Line Chart: Antrian per hari --}}
    <div class="card">
        <div class="card-header">
            <span style="font-weight:700;">Antrian 7 Hari Terakhir</span>
            <span class="badge badge-primary">Mingguan</span>
        </div>
        <div class="card-body" style="padding: 1.25rem;">
            <div style="height: 300px; position: relative;">
                <canvas id="chartAntrianDokter"></canvas>
            </div>
        </div>
    </div>

    {{-- Donut Chart: Status distribusi --}}
    <div class="card">
        <div class="card-header"><span style="font-weight:700;">Status Antrian</span><span style="font-size:0.75rem;color:#94a3b8;">Bulan ini</span></div>
        <div class="card-body" style="display:flex;flex-direction:column;align-items:center;padding:1.25rem;">
            <div style="height: 300px; width: 100%; position: relative; display: flex; justify-content: center; align-items: center; margin-bottom: 1rem;">
                <canvas id="chartStatusDokter" style="max-width:260px;"></canvas>
            </div>
            <div style="display:flex;flex-direction:column;gap:0.5rem;width:100%;font-size:0.8125rem;">
                @php
                    $dColors = ['Menunggu'=>'#fbbf24','Selesai'=>'#10b981','Batal'=>'#ef4444'];
                @endphp
                @foreach($distribusiStatus as $key => $val)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:0.375rem 0.5rem;background:#f8fafc;border-radius:0.375rem;">
                    <div style="display:flex;align-items:center;gap:0.5rem;">
                        <div style="width:0.625rem;height:0.625rem;border-radius:50%;background:{{ $dColors[$key] ?? '#94a3b8' }};"></div>
                        <span style="color:#475569;">{{ $key }}</span>
                    </div>
                    <strong style="color:#1e293b;">{{ $val }}</strong>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- ── ANTRIAN HARI INI + KONSULTASI ── --}}
<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;margin-bottom:1.5rem;" class="dash-grid">
    {{-- Antrian hari ini --}}
    <div class="card">
        <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
            <span style="font-weight:700;">Antrian Hari Ini — {{ now()->format('d M Y') }}</span>
            <a href="{{ route('dokter.antrian.index') }}" style="font-size:0.8125rem;color:#2563eb;text-decoration:none;">Lihat semua →</a>
        </div>
        @if($antrianHariIni->isEmpty())
        <div class="empty-state" style="padding:2rem;">
            <svg style="width:2.5rem;height:2.5rem;color:#cbd5e1;margin:0 auto 0.75rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <div style="color:#94a3b8;">Tidak ada antrian hari ini 🎉</div>
        </div>
        @else
        <div style="display:flex;flex-direction:column;gap:0;">
            @foreach($antrianHariIni as $a)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:0.875rem 1.5rem;border-bottom:1px solid #f1f5f9;gap:1rem;">
                <div style="display:flex;align-items:center;gap:0.875rem;">
                    <div style="background:#dbeafe;color:#2563eb;font-weight:800;font-family:monospace;width:3rem;height:3rem;border-radius:0.5rem;display:flex;align-items:center;justify-content:center;font-size:0.875rem;flex-shrink:0;">
                        {{ substr($a->no_antrian, -3) }}
                    </div>
                    <div>
                        <div style="font-weight:600;color:#1e293b;">{{ $a->pasien->user->name }}</div>
                        <div style="font-size:0.8125rem;color:#64748b;margin-bottom:0.25rem;">{{ $a->layanan->nama }} • {{ Str::limit($a->keluhan, 35) }}</div>
                        @if($a->status === 'dipanggil' && $a->tekanan_darah)
                        <div style="font-size:0.75rem;color:#0f172a;background:#f8fafc;padding:0.2rem 0.5rem;border-radius:0.375rem;border:1px solid #e2e8f0;display:inline-flex;gap:0.75rem;">
                            <span><strong style="color:#64748b;">TD:</strong> {{ $a->tekanan_darah }}</span>
                            @if($a->suhu_tubuh)<span><strong style="color:#64748b;">Suhu:</strong> {{ $a->suhu_tubuh }}°C</span>@endif
                        </div>
                        @endif
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:0.5rem;flex-shrink:0;">
                    <span class="badge badge-{{ $a->status_badge_color }} {{ $a->status==='dipanggil'?'badge-dipanggil':'' }}">{{ $a->status_label }}</span>
                    @if($a->status === 'menunggu')
                    <form method="POST" action="{{ route('dokter.antrian.status', $a->id) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="dipanggil">
                        <button type="submit" class="btn btn-primary btn-sm">Panggil</button>
                    </form>
                    @elseif($a->status === 'dipanggil')
                    <div style="display:flex;gap:0.5rem;">
                        <a href="{{ route('dokter.rekam-medis.create', ['antrian_id'=>$a->id]) }}" class="btn btn-success btn-sm">Buat RM</a>
                        <form method="POST" action="{{ route('dokter.antrian.status', $a->id) }}">@csrf @method('PATCH')<input type="hidden" name="status" value="selesai"><button type="submit" class="btn btn-secondary btn-sm">Selesai</button></form>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Konsultasi pending --}}
    <div class="card">
        <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:0.625rem;">
                <span style="font-weight:700;">Konsultasi Pending</span>
                @php $unreadKonsul = $konsultasiPending->filter(fn($k) => !$k->dibaca_dokter)->count(); @endphp
                @if($unreadKonsul > 0)
                <span style="background:#ef4444;color:white;font-size:0.7rem;font-weight:700;padding:0.15rem 0.5rem;border-radius:9999px;">{{ $unreadKonsul }} baru</span>
                @endif
            </div>
            <a href="{{ route('dokter.konsultasi.index') }}" style="font-size:0.8125rem;color:#2563eb;text-decoration:none;">Semua →</a>
        </div>
        @if($konsultasiPending->isEmpty())
        <div class="empty-state" style="padding:2rem;">
            <div style="color:#94a3b8;font-size:0.875rem;">Tidak ada konsultasi pending ✅</div>
        </div>
        @else
        @foreach($konsultasiPending as $k)
        <a href="{{ route('dokter.konsultasi.show', $k->id) }}" style="display:block;padding:0.875rem 1.5rem;border-bottom:1px solid #f1f5f9;text-decoration:none;transition:background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
            <div style="font-weight:600;color:#1e293b;font-size:0.875rem;">{{ $k->pasien->user->name }}</div>
            <div style="font-size:0.8125rem;color:#64748b;margin:0.125rem 0;">{{ Str::limit($k->judul, 40) }}</div>
            <div style="font-size:0.75rem;color:#94a3b8;">{{ $k->created_at->diffForHumans() }}</div>
        </a>
        @endforeach
        @endif
    </div>
</div>

{{-- ── PASIEN TERBARU ── --}}
@if($pasienTerbaru->isNotEmpty())
<div class="card">
    <div class="card-header"><span style="font-weight:700;">Pasien Terbaru Ditangani</span></div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:0;padding:0.5rem 1rem 1rem;">
        @foreach($pasienTerbaru as $a)
        <div style="padding:0.75rem;display:flex;align-items:center;gap:0.75rem;">
            <img src="{{ $a->pasien->user->avatar_url }}" alt="{{ $a->pasien->user->name }}"
                 style="width:2.5rem;height:2.5rem;border-radius:50%;object-fit:cover;border:2px solid #dbeafe;flex-shrink:0;">
            <div>
                <div style="font-weight:600;font-size:0.875rem;color:#1e293b;">{{ $a->pasien->user->name }}</div>
                <div style="font-size:0.75rem;color:#94a3b8;">{{ $a->tanggal->format('d M Y') }}</div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<style>
@media(max-width:1024px){
    .dash-grid,.dash-charts{grid-template-columns:1fr !important;}
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart 1: Antrian per hari — Line Chart
    const labels = @json($antrianPerHari->pluck('date'));
    const counts = @json($antrianPerHari->pluck('count'));
    new Chart(document.getElementById('chartAntrianDokter'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Antrian',
                data: counts,
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.08)',
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#2563eb',
                pointRadius: 5,
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => ` ${ctx.raw} pasien` } }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0,0,0,0.05)' } },
                x: { grid: { display: false } }
            }
        }
    });

    // Chart 2: Status Donut
    const statusKeys   = @json(array_keys($distribusiStatus));
    const statusValues = @json(array_values($distribusiStatus));
    new Chart(document.getElementById('chartStatusDokter'), {
        type: 'doughnut',
        data: {
            labels: statusKeys,
            datasets: [{
                data: statusValues,
                backgroundColor: ['#fbbf24', '#10b981', '#ef4444'],
                borderWidth: 3,
                borderColor: '#ffffff',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.raw}` } }
            }
        }
    });
});
</script>

@endsection
