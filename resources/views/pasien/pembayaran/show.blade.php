@extends('layouts.app')
@section('title', 'Detail Pembayaran')
@section('sidebar')@include('pasien._sidebar')@endsection
@section('breadcrumb')
<a href="{{ route('pasien.dashboard') }}">Dashboard</a><span>/</span>
<a href="{{ route('pasien.pembayaran.index') }}">Pembayaran</a><span>/</span><span>{{ $pembayaran->kode_invoice }}</span>
@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Detail Pembayaran</h1><p class="page-subtitle">{{ $pembayaran->kode_invoice }}</p></div>
    <a href="{{ route('pasien.pembayaran.index') }}" class="btn btn-secondary">← Kembali</a>
</div>


<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;" class="pay-grid">
    <div style="display:flex;flex-direction:column;gap:1.25rem;">
        {{-- Invoice header card --}}
        <div class="card" style="background:linear-gradient(135deg,#1e3a8a,#2563eb);color:white;">
            <div class="card-body">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:1rem;">
                    <div>
                        <div style="font-size:0.75rem;opacity:0.8;margin-bottom:0.25rem;">INVOICE</div>
                        <div style="font-size:1.25rem;font-weight:800;font-family:monospace;">{{ $pembayaran->kode_invoice }}</div>
                    </div>
                    <span class="badge" style="background:rgba(255,255,255,0.2);color:white;font-size:0.875rem;padding:0.5rem 1rem;">{{ $pembayaran->status_label }}</span>
                </div>
                <div style="margin-top:1.25rem;">
                    <div style="font-size:0.75rem;opacity:0.7;">Total Tagihan</div>
                    <div style="font-size:2rem;font-weight:800;">{{ $pembayaran->jumlah_format }}</div>
                </div>
            </div>
        </div>

        {{-- Rincian biaya --}}
        <div class="card">
            <div class="card-header">Rincian Tagihan</div>
            <div class="card-body">
                <div style="font-size:0.75rem; color:#64748b; font-weight:700; text-transform:uppercase; margin-bottom:1rem; letter-spacing:0.05em;">Layanan & Tindakan</div>
                
                <div style="display:flex; justify-content:space-between; padding:0.5rem 0; border-bottom:1px solid #f1f5f9;">
                    <div style="font-size:0.875rem; color:#1e293b;">
                        <div style="font-weight:600;">Jasa Konsultasi Dokter</div>
                        <div style="font-size:0.75rem; color:#64748b;">{{ optional($pembayaran->antrian->dokter)->user->name }} — {{ optional($pembayaran->antrian->layanan)->nama }}</div>
                    </div>
                    <div style="font-weight:700; color:#1e293b;">{{ $pembayaran->jumlah_format }}</div>
                </div>

                @if($pembayaran->antrian->rekamMedis && $pembayaran->antrian->rekamMedis->tindakan)
                <div style="display:flex; justify-content:space-between; padding:0.5rem 0; border-bottom:1px solid #f1f5f9;">
                    <div style="font-size:0.875rem; color:#1e293b;">
                        <div style="font-weight:600;">Tindakan Medis</div>
                        <div style="font-size:0.75rem; color:#64748b;">{{ $pembayaran->antrian->rekamMedis->tindakan }}</div>
                    </div>
                    <div style="font-weight:700; color:#10b981;">Gratis</div>
                </div>
                @endif

                @if($pembayaran->antrian->rekamMedis && $pembayaran->antrian->rekamMedis->resep)
                <div style="margin-top:1rem;">
                    <div style="font-size:0.75rem; color:#64748b; font-weight:700; text-transform:uppercase; margin-bottom:0.5rem; letter-spacing:0.05em;">Resep Obat</div>
                    @foreach($pembayaran->antrian->rekamMedis->resep as $r)
                    <div style="display:flex; justify-content:space-between; padding:0.375rem 0; font-size:0.875rem; color:#1e293b;">
                        <div>• {{ $r['obat'] }} ({{ $r['dosis'] }})</div>
                        <div style="color:#10b981; font-weight:600;">Termasuk</div>
                    </div>
                    @endforeach
                </div>
                @endif

                <div style="margin-top:1.5rem; padding-top:1rem; border-top:2px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center;">
                    <div style="font-weight:700; color:#1e293b;">TOTAL PEMBAYARAN</div>
                    <div style="font-size:1.25rem; font-weight:800; color:#2563eb;">{{ $pembayaran->jumlah_format }}</div>
                </div>
            </div>
        </div>

        {{-- Informasi Umum --}}
        <div class="card">
            <div class="card-header">Informasi Umum</div>
            <div class="card-body">
                @php $rows = [
                    ['l' => 'No. Antrian',      'v' => $pembayaran->antrian->no_antrian],
                    ['l' => 'Tanggal Antrian',  'v' => $pembayaran->antrian->tanggal->format('d F Y')],
                    ['l' => 'Metode Pembayaran','v' => $pembayaran->metode_label],
                    ['l' => 'Dibuat',           'v' => $pembayaran->created_at->format('d M Y, H:i')],
                ]; @endphp
                @foreach($rows as $r)
                <div style="display:flex;gap:1rem;padding:0.625rem 0;border-bottom:1px solid #f1f5f9;">
                    <div style="min-width:150px;font-size:0.8125rem;color:#64748b;font-weight:500;">{{ $r['l'] }}</div>
                    <div style="font-size:0.875rem;font-weight:600;color:#1e293b;">{{ $r['v'] }}</div>
                </div>
                @endforeach
                @if($pembayaran->dibayar_pada)
                <div style="display:flex;gap:1rem;padding:0.625rem 0;">
                    <div style="min-width:150px;font-size:0.8125rem;color:#64748b;font-weight:500;">Dibayar Pada</div>
                    <div style="font-size:0.875rem;font-weight:600;color:#10b981;">{{ $pembayaran->dibayar_pada->format('d M Y, H:i') }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- QRIS Payment Simulation --}}
        @if($pembayaran->status === 'menunggu' && $pembayaran->metode === 'qris')
        <div class="card" style="border:2px dashed #93c5fd; text-align:center;">
            <div class="card-header" style="justify-content:center; background:#eff6ff;">
                <span style="font-weight:700; color:#1e3a8a;">Scan QR Code untuk Membayar</span>
            </div>
            <div class="card-body" id="qris-card-body">
                @php
                    URL::forceRootUrl('http://192.168.100.9:8000');

                    $qrUrl = URL::temporarySignedRoute(
                        'qris.scan',
                        now()->addMinutes(10),
                        ['reference' => $pembayaran->reference]
                    );
                @endphp
                <div style="margin-bottom: 1.5rem; display:flex; justify-content:center;">
                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)->generate($qrUrl) !!}
                </div>
                
                <p style="font-size:0.875rem;color:#475569;margin-bottom:1.5rem;">Total yang harus dibayar: <strong style="font-size:1.125rem; color:#2563eb;">{{ $pembayaran->jumlah_format }}</strong></p>


            </div>
        </div>
        @endif
    </div>

    <div>
        <div class="card" style="position:sticky;top:5rem;">
            <div class="card-header">Cara Pembayaran</div>
            <div class="card-body" style="font-size:0.875rem;color:#475569;line-height:1.8;">
                <div style="font-weight:600;color:#1e293b;margin-bottom:0.5rem;">QRIS</div>
                <div style="font-size:0.8125rem;">Scan QR Code di layar menggunakan aplikasi dompet digital (GoPay, OVO, DANA, dll.) atau Mobile Banking.</div>
                <div style="margin-top:1rem;padding:0.75rem;background:#fef3c7;border-radius:0.5rem;font-size:0.8125rem;color:#92400e;">
                    <strong>Nominal:</strong> {{ $pembayaran->jumlah_format }}<br>
                    Pembayaran akan diverifikasi secara otomatis setelah Anda scan dan menyelesaikan pembayaran.
                </div>
            </div>
        </div>
    </div>
</div>
<style>@media(max-width:768px){.pay-grid{grid-template-columns:1fr !important;}}</style>

<script>
@if($pembayaran->status === 'menunggu' && $pembayaran->metode === 'qris')
let isPaid = false;
setInterval(() => {
    if (isPaid) return;
    
    fetch('{{ route('pasien.pembayaran.show', $pembayaran->id) }}', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'dibayar') {
            isPaid = true;
            const qrCard = document.getElementById('qris-card-body');
            if(qrCard) {
                qrCard.innerHTML = `
                    <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; padding: 2rem 0;">
                        <div style="width: 4rem; height: 4rem; background: #d1fae5; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; animation: scaleIn 0.5s ease-out;">
                            <svg style="width: 2rem; height: 2rem; color: #10b981;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h3 style="font-size: 1.25rem; font-weight: 700; color: #0f172a; margin: 0 0 0.5rem 0;">Pembayaran Berhasil!</h3>
                        <p style="font-size: 0.875rem; color: #64748b; margin: 0;">Mengalihkan halaman dalam beberapa detik...</p>
                    </div>
                    <style>
                        @keyframes scaleIn {
                            0% { transform: scale(0); opacity: 0; }
                            100% { transform: scale(1); opacity: 1; }
                        }
                    </style>
                `;
            }
            setTimeout(() => {
                window.location.reload();
            }, 2500);
        }
    })
    .catch(() => {});
}, 3000);
@endif
</script>
@endsection
