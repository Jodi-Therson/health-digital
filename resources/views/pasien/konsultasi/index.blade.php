@extends('layouts.app')
@section('title', 'Konsultasi Online')
@section('sidebar')@include('pasien._sidebar')@endsection
@section('breadcrumb')
<a href="{{ route('pasien.dashboard') }}">Dashboard</a><span>/</span><span>Konsultasi Online</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Konsultasi Online</h1>
        <p class="page-subtitle">Tanyakan keluhan Anda langsung kepada dokter</p>
    </div>
    <a href="{{ route('pasien.konsultasi.create') }}" class="btn btn-primary">
        <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Mulai Konsultasi
    </a>
</div>

{{-- Tabs --}}
@php
    $aktif   = $konsultasis->filter(fn($k) => in_array($k->status, ['menunggu','dijawab']));
    $riwayat = $konsultasis->filter(fn($k) => $k->status === 'ditutup');
    $tab = request('tab', 'aktif');
@endphp
<div style="display:flex;gap:0;border-bottom:2px solid #e2e8f0;margin-bottom:1.5rem;">
    <a href="?tab=aktif" style="padding:0.75rem 1.5rem;font-weight:600;font-size:0.9375rem;text-decoration:none;border-bottom:2px solid {{ $tab==='aktif'?'#2563eb':'transparent' }};color:{{ $tab==='aktif'?'#2563eb':'#64748b' }};margin-bottom:-2px;transition:color 0.15s;">
        Aktif
        @php $jmlAktif = $aktif->count(); @endphp
        @if($jmlAktif > 0)
        <span style="background:#2563eb;color:white;font-size:0.7rem;font-weight:700;padding:0.1rem 0.5rem;border-radius:9999px;margin-left:0.375rem;">{{ $jmlAktif }}</span>
        @endif
    </a>
    <a href="?tab=riwayat" style="padding:0.75rem 1.5rem;font-weight:600;font-size:0.9375rem;text-decoration:none;border-bottom:2px solid {{ $tab==='riwayat'?'#2563eb':'transparent' }};color:{{ $tab==='riwayat'?'#2563eb':'#64748b' }};margin-bottom:-2px;transition:color 0.15s;">
        Riwayat
        @if($riwayat->count() > 0)
        <span style="background:#94a3b8;color:white;font-size:0.7rem;font-weight:700;padding:0.1rem 0.5rem;border-radius:9999px;margin-left:0.375rem;">{{ $riwayat->count() }}</span>
        @endif
    </a>
</div>

<div class="card">
    @php $list = $tab === 'riwayat' ? $riwayat : $aktif; @endphp
    @if($konsultasis->isEmpty())
    {{-- Empty state --}}
    <div class="empty-state" style="padding:4rem 2rem;">
        <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:5rem;height:5rem;margin:0 auto 1.25rem;opacity:0.3;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        <div style="font-weight:700;font-size:1.0625rem;color:#64748b;margin-bottom:0.5rem;">Belum ada konsultasi</div>
        <div style="font-size:0.875rem;color:#94a3b8;margin-bottom:1.25rem;">Ajukan pertanyaan kesehatan Anda kepada dokter kapan saja</div>
        <a href="{{ route('pasien.konsultasi.create') }}" class="btn btn-primary">Mulai Konsultasi Pertama</a>
    </div>
    @elseif($list->isEmpty())
    <div class="empty-state" style="padding:3rem 2rem;">
        <div style="font-size:0.875rem;color:#94a3b8;">Tidak ada konsultasi di tab ini</div>
    </div>
    @else
    <div style="display:flex;flex-direction:column;gap:0;">
        @foreach($list->sortByDesc('created_at') as $k)
        <a href="{{ route('pasien.konsultasi.show', $k->id) }}"
           style="text-decoration:none;display:block;padding:1.125rem 1.5rem;border-bottom:1px solid #f1f5f9;transition:background 0.15s;"
           onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;">
                <div style="display:flex;align-items:center;gap:0.875rem;flex:1;">
                    <img src="{{ $k->dokter->user->avatar_url }}" style="width:2.75rem;height:2.75rem;border-radius:50%;object-fit:cover;flex-shrink:0;border:2px solid #e2e8f0;">
                    <div style="flex:1;min-width:0;">
                        <div style="display:flex;align-items:center;gap:0.625rem;margin-bottom:0.25rem;flex-wrap:wrap;">
                            <div style="font-weight:700;color:#1e293b;font-size:0.9375rem;">{{ $k->judul }}</div>
                            @if(!$k->dibaca_pasien && $k->status === 'dijawab')
                            <span style="background:#2563eb;color:white;font-size:0.625rem;font-weight:700;padding:0.125rem 0.5rem;border-radius:9999px;animation:blink 1.2s ease-in-out infinite;">BARU</span>
                            @endif
                        </div>
                        <div style="font-size:0.8125rem;color:#64748b;">kepada {{ $k->dokter->user->name }} • {{ $k->dokter->spesialisasi }}</div>
                        <div style="font-size:0.8125rem;color:#94a3b8;margin-top:0.25rem;">{{ $k->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                <span class="badge badge-{{ $k->status_badge_color }}">{{ $k->status_label }}</span>
            </div>
        </a>
        @endforeach
    </div>
    @if(method_exists($konsultasis, 'links'))
    <div style="padding:1rem 1.5rem;">{{ $konsultasis->links() }}</div>
    @endif
    @endif
</div>
@endsection
