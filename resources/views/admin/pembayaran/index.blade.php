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
<div class="card" style="margin-bottom: 1.5rem; background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0;">
    <div class="card-body" style="padding: 1.25rem;">
        <form method="GET" action="{{ route('admin.pembayaran.index') }}" class="filter-grid">
            
            <div class="form-group" style="display: flex; flex-direction: column; gap: 0.5rem;">
                <label class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #475569;">Cari Invoice / Pasien</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-input" placeholder="INV-... atau nama" style="height: 40px; padding: 0.5rem; border: 1px solid #cbd5e1; border-radius: 0.375rem; background: white; box-sizing: border-box; width: 100%;">
            </div>
            
            <div class="form-group" style="display: flex; flex-direction: column; gap: 0.5rem;">
                <label class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #475569;">Status</label>
                <select name="status" class="form-input" style="height: 40px; padding: 0.5rem; border: 1px solid #cbd5e1; border-radius: 0.375rem; background: white; box-sizing: border-box; width: 100%;">
                    <option value="">Semua Status</option>
                    <option value="menunggu" {{ request('status')=='menunggu'?'selected':'' }}>Menunggu Bayar</option>
                    <option value="dibayar" {{ request('status')=='dibayar'?'selected':'' }}>Lunas</option>
                    <option value="ditolak" {{ request('status')=='ditolak'?'selected':'' }}>Ditolak</option>
                </select>
            </div>

            <div class="form-group" style="display: flex; flex-direction: column; gap: 0.5rem;">
                <label class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #475569;">Tanggal</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-input" style="height: 40px; padding: 0.5rem; border: 1px solid #cbd5e1; border-radius: 0.375rem; background: white; box-sizing: border-box; width: 100%;">
            </div>
            
            {{-- Tombol Tampilkan & Reset --}}
            <div class="form-group" style="display: flex; flex-direction: column; gap: 0.5rem;">
                {{-- Label transparan agar tombol sejajar dengan input di sebelahnya --}}
                <label class="form-label" style="font-size: 0.875rem; visibility: hidden;">Action</label>
                <div style="display: flex; gap: 0.5rem; width: 100%;">
                    {{-- Tombol Tampilkan (Lebih lebar) --}}
                    <button type="submit" class="btn btn-primary" style="height: 40px; flex: 2; display: flex; justify-content: center; align-items: center; border-radius: 0.375rem; background-color: #2563eb; color: white; border: none; font-weight: 600; cursor: pointer; transition: background-color 0.2s; box-sizing: border-box;">
                        Tampilkan
                    </button>
                    {{-- Tombol Reset --}}
                    <a href="{{ route('admin.pembayaran.index') }}" style="height: 40px; flex: 1; display: flex; justify-content: center; align-items: center; border-radius: 0.375rem; background-color: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; font-weight: 600; text-decoration: none; cursor: pointer; transition: background-color 0.2s; box-sizing: border-box;" title="Reset Filter">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

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
                        <div style="font-weight:600;">{{ $p->pasien?->user?->name ?? 'Pasien Terhapus' }}</div>
                        <div style="font-size:0.8125rem;color:#64748b;">
                            @if($p->antrian)
                                <span style="color:#2563eb;font-weight:600;">Poli:</span> {{ $p->antrian->layanan?->nama ?? '—' }}
                            @elseif($p->konsultasi)
                                <span style="color:#0891b2;font-weight:600;">Konsultasi:</span> {{ Str::limit($p->konsultasi->judul, 35) }}
                            @else
                                <span style="color:#64748b;">Lainnya</span>
                            @endif
                        </div>
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
<style>
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }
    
    @media(max-width: 1024px) {
        .filter-grid { 
            grid-template-columns: 1fr 1fr; 
        }
    }
    
    @media(max-width: 640px) {
        .filter-grid { 
            grid-template-columns: 1fr; 
        }
    }
</style>
@endsection
