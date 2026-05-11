@extends('layouts.app')
@section('title', 'Dashboard Dokter')
@section('sidebar')@include('dokter._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Dashboard Dokter</h1><p class="page-subtitle">Selamat datang, {{ auth()->user()->name }}!</p></div>
    <a href="{{ route('dokter.rekam-medis.create') }}" class="btn btn-primary">
        <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Buat Rekam Medis
    </a>
</div>

<!-- Stats -->
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1rem;margin-bottom:1.5rem;">
    @php $stats = [
        ['label'=>'Pasien Hari Ini','value'=>$totalHariIni,'color'=>'#2563eb','bg'=>'#dbeafe','icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
        ['label'=>'Total Pasien','value'=>$totalPasien,'color'=>'#10b981','bg'=>'#d1fae5','icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
        ['label'=>'Selesai Ditangani','value'=>$totalSelesai,'color'=>'#8b5cf6','bg'=>'#ede9fe','icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label'=>'Konsultasi Pending','value'=>$konsultasiPending->count(),'color'=>'#f59e0b','bg'=>'#fef3c7','icon'=>'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
    ]; @endphp
    @foreach($stats as $s)
    <div class="stat-card">
        <div style="background:{{ $s['bg'] }};width:2.5rem;height:2.5rem;border-radius:0.625rem;display:flex;align-items:center;justify-content:center;margin-bottom:0.75rem;">
            <svg style="width:1.25rem;height:1.25rem;color:{{ $s['color'] }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}"/></svg>
        </div>
        <div style="font-size:1.75rem;font-weight:800;color:#0f172a;">{{ $s['value'] }}</div>
        <div style="font-size:0.8125rem;color:#64748b;margin-top:0.25rem;">{{ $s['label'] }}</div>
    </div>
    @endforeach
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;" class="dash-grid">
    <!-- Antrian hari ini -->
    <div class="card">
        <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
            <span>Antrian Hari Ini — {{ now()->format('d M Y') }}</span>
            <a href="{{ route('dokter.antrian.index') }}" style="font-size:0.8125rem;color:#2563eb;text-decoration:none;">Lihat semua →</a>
        </div>
        @if($antrianHariIni->isEmpty())
        <div class="empty-state" style="padding:2rem;">
            <div style="color:#94a3b8;">Tidak ada antrian hari ini</div>
        </div>
        @else
        <div style="display:flex;flex-direction:column;gap:0;">
            @foreach($antrianHariIni as $a)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:0.875rem 1.5rem;border-bottom:1px solid #f1f5f9;">
                <div style="display:flex;align-items:center;gap:0.875rem;">
                    <div style="background:#dbeafe;color:#2563eb;font-weight:800;font-family:monospace;width:3rem;height:3rem;border-radius:0.5rem;display:flex;align-items:center;justify-content:center;font-size:0.875rem;">
                        {{ substr($a->no_antrian, -3) }}
                    </div>
                    <div>
                        <div style="font-weight:600;color:#1e293b;">{{ $a->pasien->user->name }}</div>
                        <div style="font-size:0.8125rem;color:#64748b;margin-bottom:0.25rem;">{{ $a->layanan->nama }} • {{ Str::limit($a->keluhan, 40) }}</div>
                        @if($a->status === 'dipanggil' && $a->tekanan_darah)
                        <div style="font-size:0.75rem;gap:0.75rem;color:#0f172a;background:#f8fafc;padding:0.25rem 0.5rem;border-radius:0.375rem;border:1px solid #e2e8f0;display:inline-flex;align-items:center;">
                            <span><strong style="color:#64748b;font-weight:500;">TD:</strong> {{ $a->tekanan_darah }}</span>
                            <span><strong style="color:#64748b;font-weight:500;">Suhu:</strong> {{ $a->suhu_tubuh }}°C</span>
                            <span><strong style="color:#64748b;font-weight:500;">BB/TB:</strong> {{ (float)$a->berat_badan }}kg/{{ (float)$a->tinggi_badan }}cm</span>
                        </div>
                        @endif
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:0.75rem;">
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

    <!-- Konsultasi pending -->
    <div class="card">
        <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
            <span>Konsultasi Pending</span>
            <a href="{{ route('dokter.konsultasi.index') }}" style="font-size:0.8125rem;color:#2563eb;text-decoration:none;">Semua →</a>
        </div>
        @if($konsultasiPending->isEmpty())
        <div class="empty-state" style="padding:2rem;">
            <div style="color:#94a3b8;font-size:0.875rem;">Tidak ada konsultasi pending</div>
        </div>
        @else
        @foreach($konsultasiPending as $k)
        <a href="{{ route('dokter.konsultasi.show', $k->id) }}" style="display:block;padding:0.875rem 1.5rem;border-bottom:1px solid #f1f5f9;text-decoration:none;transition:background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
            <div style="font-weight:600;color:#1e293b;font-size:0.875rem;">{{ $k->pasien->user->name }}</div>
            <div style="font-size:0.8125rem;color:#64748b;margin:0.125rem 0;">{{ Str::limit($k->judul, 45) }}</div>
            <div style="font-size:0.75rem;color:#94a3b8;">{{ $k->created_at->diffForHumans() }}</div>
        </a>
        @endforeach
        @endif
    </div>
</div>
<style>@media(max-width:1024px){.dash-grid{grid-template-columns:1fr !important;}}</style>
@endsection
