@extends('layouts.app')
@section('title', 'Konsultasi')
@section('sidebar')@include('pasien._sidebar')@endsection
@section('breadcrumb')
<a href="{{ route('pasien.dashboard') }}">Dashboard</a><span>/</span><span>Konsultasi Online</span>
@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Konsultasi Online</h1><p class="page-subtitle">Tanyakan keluhan Anda kepada dokter</p></div>
    <a href="{{ route('pasien.konsultasi.create') }}" class="btn btn-primary">
        <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Konsultasi Baru
    </a>
</div>
<div class="card">
    @if($konsultasis->isEmpty())
    <div class="empty-state">
        <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        <div style="font-weight:600;color:#94a3b8;margin-bottom:0.75rem;">Belum ada konsultasi</div>
        <a href="{{ route('pasien.konsultasi.create') }}" class="btn btn-primary btn-sm">Mulai Konsultasi</a>
    </div>
    @else
    <div style="display:flex;flex-direction:column;gap:0;">
        @foreach($konsultasis as $k)
        <a href="{{ route('pasien.konsultasi.show', $k->id) }}" style="text-decoration:none;display:block;padding:1rem 1.5rem;border-bottom:1px solid #f1f5f9;transition:background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;">
                <div style="flex:1;">
                    <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.375rem;">
                        <div style="font-weight:600;color:#1e293b;font-size:0.9375rem;">{{ $k->judul }}</div>
                        @if(!$k->dibaca_pasien && $k->status === 'dijawab')
                        <span style="background:#2563eb;color:white;font-size:0.625rem;font-weight:700;padding:0.125rem 0.375rem;border-radius:9999px;">BARU</span>
                        @endif
                    </div>
                    <div style="font-size:0.8125rem;color:#64748b;">Kepada: {{ $k->dokter->user->name }} • {{ $k->created_at->diffForHumans() }}</div>
                    <div style="font-size:0.8125rem;color:#94a3b8;margin-top:0.25rem;">{{ Str::limit($k->pesan, 80) }}</div>
                </div>
                <span class="badge badge-{{ $k->status_badge_color }}">{{ $k->status_label }}</span>
            </div>
        </a>
        @endforeach
    </div>
    <div style="padding:1rem 1.5rem;">{{ $konsultasis->links() }}</div>
    @endif
</div>
@endsection
