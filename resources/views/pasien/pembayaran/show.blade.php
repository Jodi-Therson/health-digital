@extends('layouts.app')
@section('title', 'Detail Pembayaran')
@section('sidebar')@include('pasien._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Detail Pembayaran</h1><p class="page-subtitle">{{ $pembayaran->kode_invoice }}</p></div>
    <a href="{{ route('pasien.pembayaran.index') }}" class="btn btn-secondary">← Kembali</a>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;" class="pay-grid">
    <div style="display:flex;flex-direction:column;gap:1.25rem;">
        <!-- Invoice header -->
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

        <!-- Detail -->
        <div class="card">
            <div class="card-header">Informasi Tagihan</div>
            <div class="card-body">
                @php $rows = [
                    ['l'=>'Dokter', 'v'=>optional($pembayaran->antrian->dokter)->user->name],
                    ['l'=>'Layanan', 'v'=>optional($pembayaran->antrian->layanan)->nama],
                    ['l'=>'No. Antrian', 'v'=>$pembayaran->antrian->no_antrian],
                    ['l'=>'Tanggal Antrian', 'v'=>$pembayaran->antrian->tanggal->format('d F Y')],
                    ['l'=>'Metode Pembayaran', 'v'=>$pembayaran->metode_label],
                    ['l'=>'Dibuat', 'v'=>$pembayaran->created_at->format('d M Y, H:i')],
                ]; @endphp
                @foreach($rows as $r)
                <div style="display:flex;gap:1rem;padding:0.625rem 0;border-bottom:1px solid #f1f5f9;">
                    <div style="min-width:140px;font-size:0.8125rem;color:#64748b;font-weight:500;">{{ $r['l'] }}</div>
                    <div style="font-size:0.875rem;font-weight:600;color:#1e293b;">{{ $r['v'] }}</div>
                </div>
                @endforeach
                @if($pembayaran->dibayar_pada)
                <div style="display:flex;gap:1rem;padding:0.625rem 0;">
                    <div style="min-width:140px;font-size:0.8125rem;color:#64748b;font-weight:500;">Dibayar Pada</div>
                    <div style="font-size:0.875rem;font-weight:600;color:#10b981;">{{ $pembayaran->dibayar_pada->format('d M Y, H:i') }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Upload bukti -->
        @if($pembayaran->status === 'menunggu' && in_array($pembayaran->metode, ['transfer','qris']))
        <div class="card" style="border:2px dashed #93c5fd;">
            <div class="card-header">Upload Bukti Pembayaran</div>
            <div class="card-body">
                @if($pembayaran->bukti_bayar)
                <div class="alert alert-info" style="margin-bottom:1rem;">
                    Bukti pembayaran sudah diupload. Menunggu verifikasi admin.
                </div>
                <img src="{{ asset('storage/'.$pembayaran->bukti_bayar) }}" style="max-width:300px;border-radius:0.625rem;border:1px solid #e2e8f0;">
                @else
                <form method="POST" action="{{ route('pasien.pembayaran.upload', $pembayaran->id) }}" enctype="multipart/form-data" x-data="{loading:false}" @submit="loading=true">
                    @csrf
                    @if($errors->has('bukti_bayar'))<div class="alert alert-error">{{ $errors->first('bukti_bayar') }}</div>@endif
                    <div class="form-group">
                        <label class="form-label">File Bukti (JPG/PNG, maks 2MB)</label>
                        <input type="file" name="bukti_bayar" class="form-input" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-primary" :disabled="loading">
                        <span x-show="!loading">Upload Bukti</span>
                        <span x-show="loading" x-cloak style="display:flex;align-items:center;gap:0.5rem;"><div class="spinner" style="border-color:rgba(255,255,255,0.3);border-top-color:white;"></div>Mengupload...</span>
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Info metode pembayaran -->
    <div>
        <div class="card" style="position:sticky;top:5rem;">
            <div class="card-header">Cara Pembayaran</div>
            <div class="card-body" style="font-size:0.875rem;color:#475569;line-height:1.8;">
                @if($pembayaran->metode === 'bpjs')
                    ✅ Pembayaran BPJS otomatis diproses. Tidak diperlukan tindakan lebih lanjut.
                @elseif($pembayaran->metode === 'transfer')
                    <strong>Transfer Bank:</strong><br>
                    Bank BCA: <strong>1234567890</strong><br>
                    A/N: HealthDigital RS<br><br>
                    Nominal: <strong>{{ $pembayaran->jumlah_format }}</strong><br><br>
                    Upload bukti transfer setelah pembayaran berhasil.
                @elseif($pembayaran->metode === 'qris')
                    <strong>QRIS:</strong><br>
                    Scan QR Code di bawah menggunakan aplikasi dompet digital Anda.<br><br>
                    Upload screenshot/foto bukti pembayaran setelah berhasil.
                @elseif($pembayaran->metode === 'tunai')
                    💵 Pembayaran tunai dilakukan di kasir rumah sakit.<br><br>
                    Tunjukkan nomor invoice ini: <strong style="font-family:monospace;">{{ $pembayaran->kode_invoice }}</strong>
                @endif
            </div>
        </div>
    </div>
</div>
<style>@media(max-width:768px){.pay-grid{grid-template-columns:1fr !important;}}</style>
@endsection
