@extends('layouts.app')
@section('title', 'Konsultasi Pasien')
@section('sidebar')@include('dokter._sidebar')@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Konsultasi Pasien</h1>
        <p class="page-subtitle">Diurutkan berdasarkan yang paling lama menunggu</p>
    </div>
</div>
<div class="card">
    @if($konsultasis->isEmpty())
    <div class="empty-state" style="padding:3rem 2rem;">
        <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        <div style="color:#94a3b8;font-weight:600;margin-top:0.75rem;">Tidak ada konsultasi masuk</div>
    </div>
    @else
    @foreach($konsultasis as $k)
    <a href="{{ route('dokter.konsultasi.show', $k->id) }}"
       style="display:block;padding:1.125rem 1.5rem;border-bottom:1px solid #f1f5f9;text-decoration:none;transition:background 0.15s;"
       onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;">
            <div style="display:flex;align-items:center;gap:0.875rem;flex:1;">
                <div style="position:relative;flex-shrink:0;">
                    <img src="{{ $k->pasien->user->avatar_url }}" style="width:2.75rem;height:2.75rem;border-radius:50%;object-fit:cover;">
                    @if(!$k->dibaca_dokter)
                    <span style="position:absolute;top:-2px;right:-2px;width:0.625rem;height:0.625rem;background:#ef4444;border-radius:50%;border:1.5px solid white;"></span>
                    @endif
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="display:flex;align-items:center;gap:0.625rem;margin-bottom:0.25rem;flex-wrap:wrap;">
                        <span style="font-weight:700;color:#1e293b;">{{ $k->pasien->user->name }}</span>
                        @if(!$k->dibaca_dokter)
                        <span style="background:#ef4444;color:white;font-size:0.625rem;font-weight:700;padding:0.125rem 0.5rem;border-radius:9999px;">BARU</span>
                        @endif
                        @if($k->status === 'menunggu')
                            @php
                                $pembayaran = \App\Models\Pembayaran::where('konsultasi_id', $k->id)->first();
                                $isPaid = $pembayaran && $pembayaran->status === 'dibayar';
                            @endphp
                            @if($isPaid)
                                <span style="background:#fef3c7;color:#92400e;font-size:0.7rem;font-weight:600;padding:0.1rem 0.5rem;border-radius:9999px;">Menunggu Balasan Anda</span>
                            @else
                                <span style="background:#fee2e2;color:#ef4444;font-size:0.7rem;font-weight:600;padding:0.1rem 0.5rem;border-radius:9999px;">Belum Bayar (Menunggu Pembayaran)</span>
                            @endif
                        @endif
                    </div>
                    <div style="font-size:0.875rem;color:#475569;margin-bottom:0.125rem;">{{ $k->judul }}</div>
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
