@extends('layouts.app')
@section('title', 'Tagihan Saya')
@section('sidebar')@include('pasien._sidebar')@endsection
@section('breadcrumb')
<a href="{{ route('pasien.dashboard') }}">Dashboard</a><span>/</span>
<span>Tagihan</span>
@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Tagihan Saya</h1><p class="page-subtitle">Daftar tagihan dan status pembayaran</p></div>
</div>
<div class="card">
    @if($pembayarans->isEmpty())
    <div class="empty-state">
        <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        <div style="font-weight:600;color:#94a3b8;margin-bottom:0.5rem;">Belum ada tagihan</div>
        <p style="font-size:0.875rem;color:#cbd5e1;">Tagihan akan muncul setelah Anda membuat antrian atau konsultasi.</p>
    </div>
    @else
    <div class="table-container" style="border:none;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Jenis</th>
                    <th>Keterangan</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pembayarans as $p)
                <tr>
                    <td><span style="font-family:monospace;font-size:0.8125rem;color:#2563eb;font-weight:700;">{{ $p->kode_invoice }}</span></td>
                    <td>
                        @if($p->antrian)
                        <span class="badge badge-primary" style="font-size:0.7rem;">Antrian</span>
                        @elseif($p->konsultasi)
                        <span class="badge badge-info" style="font-size:0.7rem;">Konsultasi</span>
                        @else
                        <span class="badge" style="font-size:0.7rem;">Lainnya</span>
                        @endif
                    </td>
                    <td>
                        @if($p->antrian)
                        <div style="font-size:0.875rem;color:#1e293b;">
                            {{ optional($p->antrian->dokter?->user)->name ?? '—' }}
                        </div>
                        <div style="font-size:0.75rem;color:#94a3b8;">
                            {{ optional($p->antrian->layanan)->nama ?? '' }}
                            · {{ $p->antrian->tanggal->format('d M Y') }}
                        </div>
                        @elseif($p->konsultasi)
                        <div style="font-size:0.875rem;color:#1e293b;">
                            {{ optional($p->konsultasi->dokter?->user)->name ?? '—' }}
                        </div>
                        <div style="font-size:0.75rem;color:#94a3b8;">
                            Konsultasi: {{ Str::limit($p->konsultasi->judul, 35) }}
                        </div>
                        @else
                        <span style="color:#94a3b8;font-size:0.875rem;">—</span>
                        @endif
                    </td>
                    <td style="font-weight:700;">{{ $p->jumlah_format }}</td>
                    <td>
                        <span class="badge badge-{{ $p->status_badge_color }}">{{ $p->status_label }}</span>
                        @if($p->status === 'menunggu')
                        <div style="font-size:0.7rem;color:#f59e0b;margin-top:0.25rem;">⏳ Perlu dibayar</div>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('pasien.pembayaran.show', $p->id) }}" class="btn btn-secondary btn-sm">
                            {{ $p->status === 'menunggu' ? 'Bayar' : 'Detail' }}
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="padding:1rem 1.5rem;">{{ $pembayarans->links() }}</div>
    @endif
</div>
@endsection
