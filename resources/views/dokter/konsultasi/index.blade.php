@extends('layouts.app')
@section('title', 'Konsultasi')
@section('sidebar')@include('dokter._sidebar')@endsection
@section('content')
<div class="page-header"><div><h1 class="page-title">Konsultasi Pasien</h1></div></div>
<div class="card">
    @if($konsultasis->isEmpty())
    <div class="empty-state"><div style="color:#94a3b8;">Tidak ada konsultasi</div></div>
    @else
    @foreach($konsultasis as $k)
    <a href="{{ route('dokter.konsultasi.show', $k->id) }}" style="display:block;padding:1rem 1.5rem;border-bottom:1px solid #f1f5f9;text-decoration:none;transition:background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;">
            <div style="display:flex;align-items:center;gap:0.875rem;flex:1;">
                <img src="{{ $k->pasien->user->avatar_url }}" style="width:2.5rem;height:2.5rem;border-radius:50%;">
                <div>
                    <div style="display:flex;align-items:center;gap:0.625rem;">
                        <span style="font-weight:600;color:#1e293b;">{{ $k->pasien->user->name }}</span>
                        @if(!$k->dibaca_dokter)<span style="background:#2563eb;color:white;font-size:0.625rem;font-weight:700;padding:0.125rem 0.375rem;border-radius:9999px;">BARU</span>@endif
                    </div>
                    <div style="font-size:0.875rem;color:#475569;margin:0.125rem 0;">{{ $k->judul }}</div>
                    <div style="font-size:0.8125rem;color:#94a3b8;">{{ $k->created_at->diffForHumans() }}</div>
                </div>
            </div>
            <span class="badge badge-{{ $k->status_badge_color }}">{{ $k->status_label }}</span>
        </div>
    </a>
    @endforeach
    <div style="padding:1rem 1.5rem;">{{ $konsultasis->links() }}</div>
    @endif
</div>
@endsection
