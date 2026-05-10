@extends('layouts.app')
@section('title', 'Pembayaran')
@section('sidebar')@include('pasien._sidebar')@endsection
@section('content')
<div class="page-header"><div><h1 class="page-title">Tagihan Saya</h1><p class="page-subtitle">Daftar tagihan dan status pembayaran</p></div></div>
<div class="card">
    @if($pembayarans->isEmpty())
    <div class="empty-state">
        <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        <div style="font-weight:600;color:#94a3b8;">Tidak ada tagihan</div>
    </div>
    @else
    <div class="table-container" style="border:none;">
        <table class="data-table">
            <thead><tr><th>Invoice</th><th>Layanan</th><th>Jumlah</th><th>Metode</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($pembayarans as $p)
                <tr>
                    <td><span style="font-family:monospace;font-size:0.8125rem;color:#2563eb;">{{ $p->kode_invoice }}</span></td>
                    <td>{{ optional($p->antrian->dokter)->user->name }}</td>
                    <td style="font-weight:700;">{{ $p->jumlah_format }}</td>
                    <td>{{ $p->metode_label }}</td>
                    <td><span class="badge badge-{{ $p->status_badge_color }}">{{ $p->status_label }}</span></td>
                    <td><a href="{{ route('pasien.pembayaran.show', $p->id) }}" class="btn btn-secondary btn-sm">Detail</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
