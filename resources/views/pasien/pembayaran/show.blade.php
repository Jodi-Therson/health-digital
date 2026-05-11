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

{{-- Alert: ditolak --}}
@if($pembayaran->status === 'ditolak')
<div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:0.75rem;padding:1.125rem 1.25rem;display:flex;gap:0.875rem;margin-bottom:1.5rem;">
    <svg style="width:1.25rem;height:1.25rem;color:#dc2626;flex-shrink:0;margin-top:0.125rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <div>
        <div style="font-weight:700;color:#991b1b;margin-bottom:0.25rem;">Bukti Bayar Ditolak</div>
        <div style="font-size:0.875rem;color:#b91c1c;">{{ $pembayaran->alasan_tolak }}</div>
        <div style="font-size:0.8125rem;color:#dc2626;margin-top:0.5rem;">Silakan upload ulang bukti pembayaran yang valid.</div>
    </div>
</div>
@endif

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

        {{-- Upload bukti --}}
        @if(in_array($pembayaran->status, ['menunggu', 'ditolak']) && in_array($pembayaran->metode, ['transfer', 'qris']))
        <div class="card" style="border:2px dashed #93c5fd;" x-data="{
            fileName: '',
            fileSize: '',
            previewUrl: '',
            uploading: false,
            progress: 0,
            handleFile(e) {
                const f = e.target.files[0];
                if (!f) return;
                this.fileName = f.name;
                this.fileSize = (f.size / 1024 / 1024).toFixed(2) + ' MB';
                if (f.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = ev => { this.previewUrl = ev.target.result; };
                    reader.readAsDataURL(f);
                } else {
                    this.previewUrl = '';
                }
            }
        }">
            <div class="card-header" style="display:flex;align-items:center;gap:0.625rem;">
                <svg style="width:1rem;height:1rem;color:#2563eb;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Upload Bukti Pembayaran
            </div>
            <div class="card-body">


                <form method="POST" action="{{ route('pasien.pembayaran.upload', $pembayaran->id) }}"
                      enctype="multipart/form-data"
                      @submit="uploading = true">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">File Bukti Transfer <span style="color:#ef4444;">*</span></label>

                        {{-- Custom file input --}}
                        <label style="display:flex;flex-direction:column;align-items:center;justify-content:center;border:2px dashed #bfdbfe;border-radius:0.75rem;padding:2rem;cursor:pointer;background:#eff6ff;transition:all 0.15s;"
                               onmouseover="this.style.borderColor='#2563eb'" onmouseout="this.style.borderColor='#bfdbfe'">
                            <svg style="width:2.5rem;height:2.5rem;color:#93c5fd;margin-bottom:0.75rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            <div style="font-weight:600;color:#1e3a8a;font-size:0.9375rem;">Pilih atau seret file</div>
                            <div style="font-size:0.8125rem;color:#64748b;margin-top:0.375rem;">JPG, PNG, atau PDF — maks 2 MB</div>
                            <div x-show="fileName" x-cloak style="margin-top:0.75rem;padding:0.5rem 1rem;background:white;border-radius:0.5rem;border:1px solid #bfdbfe;font-size:0.8125rem;color:#2563eb;font-weight:600;">
                                📎 <span x-text="fileName"></span> (<span x-text="fileSize"></span>)
                            </div>
                            <input type="file" name="bukti_bayar" accept=".jpg,.jpeg,.png,.pdf" required
                                   @change="handleFile($event)" style="display:none;">
                        </label>

                        {{-- Preview gambar --}}
                        <div x-show="previewUrl" x-cloak style="margin-top:0.75rem;">
                            <img :src="previewUrl" style="max-width:100%;max-height:200px;object-fit:contain;border-radius:0.5rem;border:1px solid #e2e8f0;">
                        </div>
                    </div>

                    {{-- Progress indicator --}}
                    <div x-show="uploading" x-cloak style="margin-bottom:1rem;">
                        <div style="height:4px;background:#e2e8f0;border-radius:9999px;overflow:hidden;margin-bottom:0.5rem;">
                            <div style="height:100%;background:#2563eb;border-radius:9999px;width:0%;" id="upload-bar"></div>
                        </div>
                        <div style="font-size:0.8125rem;color:#64748b;">Mengunggah...</div>
                    </div>

                    <button type="submit" class="btn btn-primary" :disabled="uploading || !fileName"
                            style="width:100%;justify-content:center;">
                        <span x-show="!uploading" style="display:flex;align-items:center;gap:0.5rem;">
                            <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            Kirim Bukti Pembayaran
                        </span>
                        <span x-show="uploading" x-cloak style="display:flex;align-items:center;gap:0.5rem;">
                            <div class="spinner" style="border-color:rgba(255,255,255,0.3);border-top-color:white;"></div>
                            Mengunggah...
                        </span>
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- Bukti sudah diupload --}}
        @if($pembayaran->bukti_bayar && $pembayaran->status === 'menunggu_verifikasi')
        <div class="card" style="border:1px solid #86efac;">
            <div class="card-header" style="background:#f0fdf4;color:#166534;display:flex;align-items:center;gap:0.625rem;">
                <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Bukti Pembayaran Terkirim
            </div>
            <div class="card-body">
                <p style="font-size:0.875rem;color:#475569;margin-bottom:1rem;">Bukti pembayaran sudah diterima. Admin akan memverifikasi dalam <strong>1×24 jam</strong>.</p>
                @php $ext = pathinfo($pembayaran->bukti_bayar, PATHINFO_EXTENSION); @endphp
                @if(in_array(strtolower($ext), ['jpg','jpeg','png']))
                <img src="{{ asset('storage/'.$pembayaran->bukti_bayar) }}"
                     style="max-width:100%;max-height:250px;object-fit:contain;border-radius:0.5rem;border:1px solid #bbf7d0;">
                @else
                <a href="{{ asset('storage/'.$pembayaran->bukti_bayar) }}" target="_blank" class="btn btn-secondary btn-sm">
                    Lihat Bukti PDF
                </a>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- Info metode & cara bayar --}}
    <div>
        <div class="card" style="position:sticky;top:5rem;">
            <div class="card-header">Cara Pembayaran</div>
            <div class="card-body" style="font-size:0.875rem;color:#475569;line-height:1.8;">
                @if($pembayaran->metode === 'bpjs')
                <div style="display:flex;align-items:flex-start;gap:0.75rem;padding:0.75rem;background:#f0fdf4;border-radius:0.5rem;border:1px solid #86efac;">
                    <svg style="width:1.25rem;height:1.25rem;color:#16a34a;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span style="color:#166534;font-weight:500;">Pembayaran BPJS diproses otomatis. Tidak diperlukan tindakan lebih lanjut.</span>
                </div>
                @elseif($pembayaran->metode === 'transfer')
                <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:0.625rem;padding:1rem;margin-bottom:1rem;">
                    <div style="font-size:0.75rem;color:#2563eb;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem;">Rekening Tujuan</div>
                    <div style="font-weight:700;color:#1e3a8a;font-size:1rem;">Bank BCA</div>
                    <div style="font-size:1.125rem;font-weight:800;color:#1e293b;letter-spacing:0.1em;margin:0.25rem 0;">1234 5678 90</div>
                    <div style="font-size:0.8125rem;color:#64748b;">a.n. HealthDigital RS</div>
                </div>
                <div style="background:#fef3c7;border:1px solid #fcd34d;border-radius:0.625rem;padding:1rem;">
                    <div style="font-size:0.75rem;color:#92400e;font-weight:600;margin-bottom:0.25rem;">Nominal Transfer</div>
                    <div style="font-size:1.25rem;font-weight:800;color:#92400e;">{{ $pembayaran->jumlah_format }}</div>
                    <div style="font-size:0.75rem;color:#b45309;margin-top:0.375rem;">Transfer tepat sesuai nominal di atas</div>
                </div>
                <div style="margin-top:1rem;font-size:0.8125rem;color:#64748b;">
                    Setelah transfer, upload bukti transfer di form sebelah kiri.
                </div>
                @elseif($pembayaran->metode === 'qris')
                <div style="font-weight:600;color:#1e293b;margin-bottom:0.5rem;">QRIS</div>
                <div style="font-size:0.8125rem;">Scan QR Code di kasir menggunakan dompet digital (GoPay, OVO, DANA, dll.)</div>
                <div style="margin-top:1rem;padding:0.75rem;background:#fef3c7;border-radius:0.5rem;font-size:0.8125rem;color:#92400e;">
                    <strong>Nominal:</strong> {{ $pembayaran->jumlah_format }}<br>
                    Upload screenshot bukti setelah berhasil.
                </div>
                @elseif($pembayaran->metode === 'tunai')
                <div style="display:flex;align-items:flex-start;gap:0.75rem;padding:0.75rem;background:#f8fafc;border-radius:0.5rem;border:1px solid #e2e8f0;">
                    <span style="font-size:1.5rem;">💵</span>
                    <div>
                        <div style="font-weight:600;color:#1e293b;">Pembayaran Tunai</div>
                        <div style="font-size:0.8125rem;color:#64748b;margin-top:0.25rem;">Lakukan pembayaran di kasir rumah sakit.</div>
                        <div style="margin-top:0.75rem;">
                            <div style="font-size:0.75rem;color:#64748b;font-weight:600;">Tunjukkan kode invoice:</div>
                            <div style="font-family:monospace;font-weight:800;color:#2563eb;font-size:1rem;margin-top:0.25rem;">{{ $pembayaran->kode_invoice }}</div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<style>@media(max-width:768px){.pay-grid{grid-template-columns:1fr !important;}}</style>

<script>
// Simple upload progress animation
document.querySelector('form[action*="upload"]')?.addEventListener('submit', function() {
    const bar = document.getElementById('upload-bar');
    if (!bar) return;
    let w = 0;
    const interval = setInterval(() => {
        w = Math.min(w + Math.random() * 15, 90);
        bar.style.width = w + '%';
        if (w >= 90) clearInterval(interval);
    }, 200);
});
</script>
@endsection
