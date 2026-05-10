@extends('layouts.app')
@section('title', 'Verifikasi Pembayaran')
@section('sidebar')@include('admin._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Verifikasi Pembayaran</h1><p class="page-subtitle">Kelola dan verifikasi tagihan pasien</p></div>
</div>

<div class="card" style="margin-bottom:1rem;"><div class="card-body" style="padding:1rem;">
    <form method="GET" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
        <div>
            <label class="form-label" style="margin-bottom:0.25rem;">Status</label>
            <select name="status" class="form-input" style="width:150px;">
                <option value="">Semua Status</option>
                <option value="menunggu" {{ request('status')=='menunggu'?'selected':'' }}>Menunggu</option>
                <option value="lunas" {{ request('status')=='lunas'?'selected':'' }}>Lunas</option>
                <option value="batal" {{ request('status')=='batal'?'selected':'' }}>Batal</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
    </form>
</div></div>

<div class="card">
    @if($pembayarans->isEmpty())
    <div class="empty-state"><div style="color:#94a3b8;">Tidak ada data pembayaran</div></div>
    @else
    <div class="table-container" style="border:none;">
        <table class="data-table">
            <thead><tr><th>Invoice</th><th>Pasien / Layanan</th><th>Nominal</th><th>Metode</th><th>Bukti Transfer</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($pembayarans as $p)
                <tr>
                    <td><span style="font-family:monospace;font-weight:700;color:#2563eb;font-size:0.875rem;">{{ $p->kode_invoice }}</span><div style="font-size:0.75rem;color:#94a3b8;">{{ $p->created_at->format('d/m/Y') }}</div></td>
                    <td>
                        <div style="font-weight:600;">{{ optional(optional($p->antrian)->pasien)->user->name }}</div>
                        <div style="font-size:0.8125rem;color:#64748b;">{{ optional(optional($p->antrian)->layanan)->nama }}</div>
                    </td>
                    <td style="font-weight:700;">{{ $p->jumlah_format }}</td>
                    <td><span class="badge badge-neutral">{{ $p->metode_label }}</span></td>
                    <td>
                        @if($p->bukti_bayar)
                        <a href="{{ asset('storage/'.$p->bukti_bayar) }}" target="_blank" class="btn btn-secondary btn-sm">Lihat Bukti</a>
                        @else<span style="font-size:0.8125rem;color:#94a3b8;">-</span>@endif
                    </td>
                    <td><span class="badge badge-{{ $p->status_badge_color }}">{{ $p->status_label }}</span></td>
                    <td>
                        @if($p->status === 'menunggu')
                        <div style="display:flex;gap:0.5rem;">
                            <form method="POST" action="{{ route('admin.pembayaran.verifikasi', $p->id) }}">@csrf @method('PATCH')<button type="submit" class="btn btn-success btn-sm">Verifikasi (Lunas)</button></form>
                        </div>
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
