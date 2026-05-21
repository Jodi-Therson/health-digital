@extends('layouts.app')
@section('title', 'Daftar Pembayaran')
@section('sidebar')@include('admin._sidebar')@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Daftar Pembayaran</h1>
        <p class="page-subtitle">Daftar transaksi tagihan pasien</p>
    </div>
</div>

{{-- Filter Bar --}}
<div class="card" style="margin-bottom:1rem;"><div class="card-body" style="padding:1rem;">
    <form method="GET" style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:flex-end;">
        <div>
            <label class="form-label" style="margin-bottom:0.25rem;font-size:0.75rem;">Cari Invoice / Pasien</label>
            <input type="text" name="search" value="{{ request('search') }}" class="form-input" placeholder="INV-... atau nama" style="width:200px;">
        </div>
        <div>
            <label class="form-label" style="margin-bottom:0.25rem;font-size:0.75rem;">Status</label>
            <select name="status" class="form-input" style="width:170px;">
                <option value="">Semua Status</option>
                <option value="menunggu" {{ request('status')=='menunggu'?'selected':'' }}>Menunggu Bayar</option>
                <option value="dibayar" {{ request('status')=='dibayar'?'selected':'' }}>Lunas</option>
                <option value="ditolak" {{ request('status')=='ditolak'?'selected':'' }}>Ditolak</option>
            </select>
        </div>

        <div>
            <label class="form-label" style="margin-bottom:0.25rem;font-size:0.75rem;">Tanggal</label>
            <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-input" style="width:160px;">
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-secondary btn-sm">Reset</a>
    </form>
</div></div>

<div class="card">
    @if($pembayarans->isEmpty())
    <div class="empty-state"><div style="color:#94a3b8;">Tidak ada data pembayaran untuk filter ini</div></div>
    @else
    <div class="table-container" style="border:none;">
        <table class="data-table">
            <thead><tr><th>Invoice</th><th>Pasien / Layanan</th><th>Nominal</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($pembayarans as $p)
                <tr>
                    <td>
                        <span style="font-family:monospace;font-weight:700;color:#2563eb;font-size:0.875rem;">{{ $p->kode_invoice }}</span>
                        <div style="font-size:0.75rem;color:#94a3b8;">{{ $p->created_at->format('d/m/Y') }}</div>
                    </td>
                    <td>
                        <div style="font-weight:600;">{{ optional(optional($p->antrian)->pasien)->user->name }}</div>
                        <div style="font-size:0.8125rem;color:#64748b;">{{ optional(optional($p->antrian)->layanan)->nama }}</div>
                    </td>
                    <td style="font-weight:700;">{{ $p->jumlah_format }}</td>

                    <td>
                        <span class="badge badge-{{ $p->status_badge_color }}">{{ $p->status_label }}</span>
                    </td>
                    <td>
                        @if(in_array($p->status, ['menunggu']))
                            <span style="font-size:0.8125rem;color:#94a3b8;">Menunggu Pembayaran Pasien</span>
                        @endif
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
