@extends('layouts.app')
@section('title', 'Verifikasi Pembayaran')
@section('sidebar')@include('admin._sidebar')@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Verifikasi Pembayaran</h1>
        <p class="page-subtitle">Kelola dan verifikasi tagihan pasien</p>
    </div>
    @if($pendingCount > 0)
    <div style="background:#fef3c7;border:1px solid #fcd34d;border-radius:0.625rem;padding:0.625rem 1rem;display:flex;align-items:center;gap:0.625rem;">
        <svg style="width:1rem;height:1rem;color:#d97706;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <span style="font-size:0.875rem;font-weight:600;color:#92400e;">{{ $pendingCount }} pembayaran menunggu verifikasi</span>
    </div>
    @endif
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
                <option value="menunggu_verifikasi" {{ request('status')=='menunggu_verifikasi'?'selected':'' }}>Menunggu Verifikasi</option>
                <option value="dibayar" {{ request('status')=='dibayar'?'selected':'' }}>Lunas</option>
                <option value="ditolak" {{ request('status')=='ditolak'?'selected':'' }}>Ditolak</option>
            </select>
        </div>
        <div>
            <label class="form-label" style="margin-bottom:0.25rem;font-size:0.75rem;">Metode</label>
            <select name="metode" class="form-input" style="width:140px;">
                <option value="">Semua Metode</option>
                <option value="transfer" {{ request('metode')=='transfer'?'selected':'' }}>Transfer Bank</option>
                <option value="qris" {{ request('metode')=='qris'?'selected':'' }}>QRIS</option>
                <option value="tunai" {{ request('metode')=='tunai'?'selected':'' }}>Tunai</option>
                <option value="bpjs" {{ request('metode')=='bpjs'?'selected':'' }}>BPJS</option>
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
            <thead><tr><th>Invoice</th><th>Pasien / Layanan</th><th>Nominal</th><th>Metode</th><th>Bukti Bayar</th><th>Status</th><th>Aksi</th></tr></thead>
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
                    <td><span class="badge badge-neutral">{{ $p->metode_label }}</span></td>
                    <td>
                        @if($p->bukti_bayar)
                        <a href="{{ asset('storage/'.$p->bukti_bayar) }}" target="_blank" class="btn btn-secondary btn-sm">Lihat Bukti</a>
                        @else
                        <span style="font-size:0.8125rem;color:#94a3b8;">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-{{ $p->status_badge_color }}">{{ $p->status_label }}</span>
                        @if($p->alasan_tolak)
                        <div style="font-size:0.75rem;color:#ef4444;margin-top:0.25rem;" title="{{ $p->alasan_tolak }}">{{ Str::limit($p->alasan_tolak, 30) }}</div>
                        @endif
                    </td>
                    <td>
                        {{-- Action modal only for menunggu/menunggu_verifikasi --}}
                        @if(in_array($p->status, ['menunggu', 'menunggu_verifikasi']))
                        <div x-data="{ showModal: false, showTolak: false }" style="display:inline-block;">
                            <button @click="showModal = true" class="btn btn-primary btn-sm">Verifikasi</button>

                            {{-- Verification Modal --}}
                            <div x-show="showModal" class="modal-backdrop" x-cloak>
                                <div class="modal" style="max-width:520px;" @click.away="showModal = false">
                                    <div style="padding:1.5rem;">
                                        <h3 style="font-size:1.0625rem;font-weight:700;color:#0f172a;margin-bottom:1rem;">
                                            Verifikasi Pembayaran
                                        </h3>

                                        {{-- Pasien info --}}
                                        <div style="background:#f8fafc;border-radius:0.5rem;padding:0.875rem;margin-bottom:1rem;">
                                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.5rem;font-size:0.8125rem;">
                                                <div><span style="color:#64748b;">Invoice:</span> <strong style="font-family:monospace;">{{ $p->kode_invoice }}</strong></div>
                                                <div><span style="color:#64748b;">Nominal:</span> <strong>{{ $p->jumlah_format }}</strong></div>
                                                <div><span style="color:#64748b;">Pasien:</span> <strong>{{ optional(optional($p->antrian)->pasien)->user->name }}</strong></div>
                                                <div><span style="color:#64748b;">Metode:</span> <strong>{{ $p->metode_label }}</strong></div>
                                            </div>
                                        </div>

                                        {{-- Bukti preview --}}
                                        @if($p->bukti_bayar)
                                        <div style="margin-bottom:1rem;">
                                            <div style="font-size:0.8125rem;font-weight:600;color:#64748b;margin-bottom:0.5rem;">Bukti Pembayaran:</div>
                                            @php $ext = pathinfo($p->bukti_bayar, PATHINFO_EXTENSION); @endphp
                                            @if(in_array(strtolower($ext), ['jpg','jpeg','png']))
                                            <img src="{{ asset('storage/'.$p->bukti_bayar) }}"
                                                 style="width:100%;max-height:200px;object-fit:contain;border-radius:0.5rem;border:1px solid #e2e8f0;background:#f1f5f9;"
                                                 alt="Bukti pembayaran">
                                            @else
                                            <a href="{{ asset('storage/'.$p->bukti_bayar) }}" target="_blank"
                                               class="btn btn-secondary btn-sm" style="display:inline-flex;align-items:center;gap:0.375rem;">
                                                <svg style="width:0.875rem;height:0.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                Buka PDF
                                            </a>
                                            @endif
                                        </div>
                                        @else
                                        <div style="background:#fef3c7;border:1px solid #fcd34d;border-radius:0.5rem;padding:0.75rem;margin-bottom:1rem;font-size:0.8125rem;color:#92400e;">
                                            ⚠️ Belum ada bukti pembayaran yang diupload.
                                        </div>
                                        @endif

                                        {{-- Tolak form (toggleable) --}}
                                        <div x-show="showTolak" x-cloak style="margin-bottom:1rem;">
                                            <form method="POST" action="{{ route('admin.pembayaran.verifikasi', $p->id) }}">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="action" value="tolak">
                                                <div class="form-group">
                                                    <label class="form-label">Alasan Penolakan <span style="color:#ef4444;">*</span></label>
                                                    <textarea name="alasan_tolak" rows="3" class="form-input"
                                                              placeholder="Jelaskan alasan penolakan secara spesifik..." required></textarea>
                                                </div>
                                                <div style="display:flex;gap:0.75rem;justify-content:flex-end;border-top:1px solid #f1f5f9;padding-top:1rem;">
                                                    <button type="button" class="btn btn-secondary btn-sm" @click="showTolak = false">Batal</button>
                                                    <button type="submit" class="btn btn-danger btn-sm">Kirim Penolakan</button>
                                                </div>
                                            </form>
                                        </div>

                                        {{-- Actions --}}
                                        <div x-show="!showTolak" style="display:flex;gap:0.75rem;justify-content:flex-end;flex-wrap:wrap;border-top:1px solid #f1f5f9;padding-top:1rem;">
                                            <button @click="showModal = false" class="btn btn-secondary btn-sm">Tutup</button>
                                            <button @click="showTolak = true" class="btn btn-danger btn-sm">Tolak Bukti</button>
                                            {{-- Confirm LUNAS modal --}}
                                            <div x-data="{ showConfirm: false }">
                                                <button @click="showConfirm = true" class="btn btn-success btn-sm">✓ Konfirmasi Dibayar</button>
                                                <div x-show="showConfirm" class="modal-backdrop" x-cloak>
                                                    <div class="modal" @click.away="showConfirm = false">
                                                        <div style="padding:1.5rem;">
                                                            <h3 style="font-size:1rem;font-weight:700;margin-bottom:0.75rem;">Tandai sebagai LUNAS?</h3>
                                                            <p style="font-size:0.875rem;color:#475569;margin-bottom:1.5rem;">
                                                                Tandai pembayaran <strong>{{ $p->kode_invoice }}</strong> sebagai <strong style="color:#10b981;">LUNAS</strong>?
                                                                Tindakan ini tidak dapat dibatalkan.
                                                            </p>
                                                            <form method="POST" action="{{ route('admin.pembayaran.verifikasi', $p->id) }}">
                                                                @csrf @method('PATCH')
                                                                <input type="hidden" name="action" value="lunas">
                                                                <div style="display:flex;gap:0.75rem;justify-content:flex-end;">
                                                                    <button type="button" class="btn btn-secondary" @click="showConfirm = false">Batal</button>
                                                                    <button type="submit" class="btn btn-success">Ya, Konfirmasi LUNAS</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
