@extends('layouts.app')
@section('title', 'Detail Antrian')
@section('sidebar')@include('pasien._sidebar')@endsection
@section('breadcrumb')
<a href="{{ route('pasien.dashboard') }}">Dashboard</a><span>/</span>
<a href="{{ route('pasien.antrian.index') }}">Antrian</a><span>/</span>
<span>{{ $antrian->no_antrian }}</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Detail Antrian</h1>
        <p class="page-subtitle">{{ $antrian->no_antrian }}</p>
    </div>
    <div style="display:flex;gap:0.75rem;">
        <a href="{{ route('pasien.antrian.index') }}" class="btn btn-secondary">← Kembali</a>
        @if($antrian->status === 'menunggu')
        <form method="POST" action="{{ route('pasien.antrian.update', $antrian->id) }}" x-ref="batalForm">
            @csrf @method('PUT')
            <button type="button" name="action" value="batal"
                @click="triggerConfirm(
                    'Batalkan Antrian',
                    'Anda yakin ingin membatalkan antrian {{ $antrian->no_antrian }}? Tindakan ini tidak dapat dibatalkan.',
                    () => { $refs.batalForm.submit() },
                    'danger'
                )"
                class="btn" style="background:#fee2e2;color:#ef4444;border:1px solid #fca5a5;">Batalkan Antrian</button>
        </form>
        @endif
    </div>
</div>

<!-- Nomor antrian hero -->
<div style="background:linear-gradient(135deg,{{ $antrian->status==='dipanggil' ? '#2563eb,#1d4ed8' : ($antrian->status==='selesai' ? '#10b981,#059669' : ($antrian->status==='batal' ? '#ef4444,#dc2626' : '#f59e0b,#d97706') ) }}); border-radius:1rem; padding:2rem; margin-bottom:1.5rem; text-align:center; color:white;">
    <div style="font-size:0.875rem; opacity:0.8; margin-bottom:0.5rem;">Nomor Antrian</div>
    <div style="font-size:3rem; font-weight:800; font-family:monospace; letter-spacing:0.1em;">{{ $antrian->no_antrian }}</div>
    <div style="margin-top:0.75rem;">
        <span class="badge" style="background:rgba(255,255,255,0.25); color:white; font-size:0.875rem; padding:0.5rem 1.5rem;">
            {{ $antrian->status_label }}
        </span>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;" class="detail-grid">
    <!-- Info antrian -->
    <div class="card">
        <div class="card-header">Informasi Kunjungan</div>
        <div class="card-body">
            @php $rows = [
                ['label'=>'Layanan', 'val'=>$antrian->layanan->nama],
                ['label'=>'Dokter', 'val'=>$antrian->dokter->user->name],
                ['label'=>'Spesialisasi', 'val'=>$antrian->dokter->spesialisasi],
                ['label'=>'Tanggal', 'val'=>$antrian->tanggal->format('d F Y')],
                ['label'=>'Didaftarkan', 'val'=>$antrian->created_at->format('d M Y, H:i')],
            ]; @endphp
            @foreach($rows as $r)
            <div style="display:flex; gap:1rem; padding:0.625rem 0; border-bottom:1px solid #f1f5f9;">
                <div style="min-width:120px; font-size:0.8125rem; color:#64748b; font-weight:500;">{{ $r['label'] }}</div>
                <div style="font-size:0.875rem; color:#1e293b; font-weight:600;">{{ $r['val'] }}</div>
            </div>
            @endforeach
            <div style="display:flex; gap:1rem; padding:0.625rem 0;">
                <div style="min-width:120px; font-size:0.8125rem; color:#64748b; font-weight:500;">Keluhan</div>
                <div style="font-size:0.875rem; color:#1e293b;">{{ $antrian->keluhan }}</div>
            </div>
        </div>
    </div>

    <div style="display:flex; flex-direction:column; gap:1rem;">
        <!-- Resep Obat Dokter (Only shown if status is selesai) -->
        @if($antrian->status === 'selesai' && optional($antrian->rekamMedis)->resep)
        <div class="card" style="border-left:3px solid #2563eb;">
            <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;background:#eff6ff;">
                <span style="font-weight:700;color:#1e3a8a;display:flex;align-items:center;gap:0.5rem;">
                    <svg style="width:1.25rem;height:1.25rem;color:#2563eb;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Resep Obat Dokter
                </span>
            </div>
            <div class="card-body" style="padding:0;">
                <div class="table-container" style="border:none;border-radius:0;">
                    <table class="data-table" style="width:100%;border-collapse:collapse;margin:0;">
                        <thead>
                            <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.75rem;font-weight:700;color:#64748b;text-transform:uppercase;">Nama Obat</th>
                                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.75rem;font-weight:700;color:#64748b;text-transform:uppercase;">Dosis</th>
                                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.75rem;font-weight:700;color:#64748b;text-transform:uppercase;">Aturan Pakai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($antrian->rekamMedis->resep as $r)
                            <tr style="border-bottom:1px solid #f1f5f9;">
                                <td style="padding:0.875rem 1rem;font-weight:600;color:#1e293b;font-size:0.875rem;">{{ $r['obat'] }}</td>
                                <td style="padding:0.875rem 1rem;color:#334155;font-size:0.875rem;">{{ $r['dosis'] }}</td>
                                <td style="padding:0.875rem 1rem;color:#64748b;font-size:0.875rem;">{{ $r['aturan'] ?? 'Sesuai petunjuk' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Pembayaran -->
        @if($antrian->pembayaran)
        <div class="card">
            <div class="card-header">Tagihan</div>
            <div class="card-body">
                <div style="font-size:1.5rem; font-weight:800; color:#2563eb;">{{ $antrian->pembayaran->jumlah_format }}</div>
                <div style="font-size:0.875rem; color:#64748b; margin:0.5rem 0;">{{ $antrian->pembayaran->kode_invoice }}</div>
                <span class="badge badge-{{ $antrian->pembayaran->status_badge_color }}">{{ $antrian->pembayaran->status_label }}</span>
                @if($antrian->pembayaran->status === 'menunggu')
                <div style="margin-top:1rem;">
                    <a href="{{ route('pasien.pembayaran.show', $antrian->pembayaran->id) }}" class="btn btn-primary btn-sm">Bayar Sekarang</a>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Catatan perawat -->
        @if($antrian->catatan_perawat)
        <div class="card" style="border-left:3px solid #10b981;">
            <div class="card-header">Catatan Perawat</div>
            <div class="card-body" style="font-size:0.875rem; color:#334155;">{{ $antrian->catatan_perawat }}</div>
        </div>
        @endif
    </div>
</div>
<style>@media(max-width:768px){.detail-grid{grid-template-columns:1fr !important;}}</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const currentStatus = "{{ $antrian->status }}";
    
    if (currentStatus !== 'selesai') {
        setInterval(() => {
            fetch('{{ route('pasien.antrian.show', $antrian->id) }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status !== currentStatus) {
                    const isModalOpen = Array.from(document.querySelectorAll('.modal-backdrop')).some(el => el.offsetWidth > 0 || el.offsetHeight > 0 || el.style.display !== 'none');
                    const isTyping = document.activeElement && ['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement.tagName);
                    if (!isModalOpen && !isTyping) {
                        window.location.reload();
                    }
                }
            })
            .catch(() => {});
        }, 3000);
    }
});
</script>
@endsection
