@extends('layouts.app')
@section('title', 'Detail Konsultasi')
@section('sidebar')@include('dokter._sidebar')@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $konsultasi->judul }}</h1>
        <div style="display:flex;align-items:center;gap:0.75rem;margin-top:0.375rem;flex-wrap:wrap;">
            <span class="badge badge-{{ $konsultasi->status_badge_color }}">{{ $konsultasi->status_label }}</span>
            <span style="font-size:0.8125rem;color:#64748b;">dari {{ $konsultasi->pasien->user->name }}</span>
            @if($konsultasi->pasien->tanggal_lahir)
            <span style="font-size:0.8125rem;color:#64748b;">· {{ $konsultasi->pasien->umur }} tahun</span>
            @endif
            <span style="font-size:0.8125rem;color:#64748b;">· {{ $konsultasi->created_at->format('d F Y, H:i') }}</span>
        </div>
    </div>
    <a href="{{ route('dokter.konsultasi.index') }}" class="btn btn-secondary">← Kembali</a>
</div>

{{-- Pasien Info Strip --}}
<div style="background:white;border:1px solid #e2e8f0;border-radius:0.75rem;padding:1rem 1.25rem;display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap;">
    <img src="{{ $konsultasi->pasien->user->avatar_url }}" style="width:3rem;height:3rem;border-radius:50%;object-fit:cover;border:2px solid #bfdbfe;flex-shrink:0;">
    <div style="flex:1;min-width:0;">
        <div style="font-weight:700;color:#1e293b;">{{ $konsultasi->pasien->user->name }}</div>
        <div style="font-size:0.8125rem;color:#64748b;margin-top:0.125rem;">
            @if($konsultasi->pasien->tanggal_lahir){{ $konsultasi->pasien->umur }} tahun · @endif
            @if($konsultasi->pasien->jenis_kelamin){{ $konsultasi->pasien->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }} · @endif
            NIK: {{ $konsultasi->pasien->nik ?? '-' }}
        </div>
    </div>
</div>

<div x-data="{ showTutupModal: false }">

    {{-- Banner ditutup --}}
    @if($konsultasi->status === 'ditutup')
    <div style="background:#f1f5f9;border:1px solid #cbd5e1;border-radius:0.75rem;padding:1rem 1.25rem;display:flex;align-items:center;gap:0.75rem;margin-bottom:1.5rem;">
        <svg style="width:1.25rem;height:1.25rem;color:#64748b;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        <span style="font-size:0.875rem;color:#475569;font-weight:500;">Konsultasi ini telah ditutup.</span>
    </div>
    @endif

    {{-- Bubble Chat --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;margin-bottom:1.5rem;max-height:480px;height:55vh;overflow-y:auto;padding-right:0.75rem;scroll-behavior:smooth;" id="chat-container">
        @if($konsultasi->pesans && $konsultasi->pesans->count() > 0)
            @foreach($konsultasi->pesans as $pesan)
                @if($pesan->pengirim === 'pasien')
                {{-- Pasien: bubble kiri, abu --}}
                <div style="display:flex;align-items:flex-start;gap:0.75rem;">
                    <img src="{{ $konsultasi->pasien->user->avatar_url }}" style="width:2.5rem;height:2.5rem;border-radius:50%;object-fit:cover;flex-shrink:0;">
                    <div style="flex:1;max-width:80%;">
                        <div style="font-weight:600;color:#475569;font-size:0.8125rem;margin-bottom:0.375rem;">{{ $konsultasi->pasien->user->name }}</div>
                        <div style="background:#f1f5f9;border-radius:0 0.75rem 0.75rem 0.75rem;padding:1rem 1.25rem;font-size:0.9375rem;color:#1e293b;line-height:1.7;">{{ $pesan->pesan }}</div>
                        <div style="font-size:0.75rem;color:#94a3b8;margin-top:0.375rem;">{{ $pesan->created_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
                @else
                {{-- Dokter: bubble kanan, biru --}}
                <div style="display:flex;align-items:flex-start;gap:0.75rem;flex-direction:row-reverse;">
                    <img src="{{ auth()->user()->avatar_url }}" style="width:2.5rem;height:2.5rem;border-radius:50%;object-fit:cover;flex-shrink:0;">
                    <div style="flex:1;max-width:80%;">
                        <div style="font-weight:600;color:#2563eb;font-size:0.8125rem;margin-bottom:0.375rem;text-align:right;">Anda</div>
                        <div style="background:#2563eb;color:white;border-radius:0.75rem 0 0.75rem 0.75rem;padding:1rem 1.25rem;font-size:0.9375rem;line-height:1.7;">{{ $pesan->pesan }}</div>
                        <div style="font-size:0.75rem;color:#94a3b8;margin-top:0.375rem;text-align:right;">{{ $pesan->created_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
                @endif
            @endforeach
        @else
            {{-- Fallback --}}
            <div style="display:flex;align-items:flex-start;gap:0.75rem;">
                <img src="{{ $konsultasi->pasien->user->avatar_url }}" style="width:2.5rem;height:2.5rem;border-radius:50%;object-fit:cover;flex-shrink:0;">
                <div style="flex:1;max-width:80%;">
                    <div style="font-weight:600;color:#475569;font-size:0.8125rem;margin-bottom:0.375rem;">{{ $konsultasi->pasien->user->name }}</div>
                    <div style="background:#f1f5f9;border-radius:0 0.75rem 0.75rem 0.75rem;padding:1rem 1.25rem;font-size:0.9375rem;color:#1e293b;line-height:1.7;">{{ $konsultasi->pesan }}</div>
                    <div style="font-size:0.75rem;color:#94a3b8;margin-top:0.375rem;">{{ $konsultasi->created_at->format('d M Y, H:i') }}</div>
                </div>
            </div>
            @if($konsultasi->balasan)
            <div style="display:flex;align-items:flex-start;gap:0.75rem;flex-direction:row-reverse;">
                <img src="{{ auth()->user()->avatar_url }}" style="width:2.5rem;height:2.5rem;border-radius:50%;object-fit:cover;flex-shrink:0;">
                <div style="flex:1;max-width:80%;">
                    <div style="font-weight:600;color:#2563eb;font-size:0.8125rem;margin-bottom:0.375rem;text-align:right;">Anda</div>
                    <div style="background:#2563eb;color:white;border-radius:0.75rem 0 0.75rem 0.75rem;padding:1rem 1.25rem;font-size:0.9375rem;line-height:1.7;">{{ $konsultasi->balasan }}</div>
                    <div style="font-size:0.75rem;color:#94a3b8;margin-top:0.375rem;text-align:right;">{{ $konsultasi->updated_at->format('d M Y, H:i') }}</div>
                </div>
            </div>
            @endif
        @endif
    </div>

    {{-- Form Balas Dokter --}}
    @if(in_array($konsultasi->status, ['menunggu', 'dijawab']))
        @php
            $pembayaran = \App\Models\Pembayaran::where('konsultasi_id', $konsultasi->id)->first();
            $isPaid = $pembayaran && $pembayaran->status === 'dibayar';
        @endphp

        @if($isPaid)
        <div class="card">
            <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
                <span>Tulis Balasan</span>
                <button type="button" class="btn btn-secondary btn-sm" @click="showTutupModal = true"
                        style="display:inline-flex !important; flex-direction:row !important; align-items:center !important; gap:0.375rem !important; color:#64748b !important;">
                    <svg style="width:0.875rem;height:0.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Tutup Konsultasi
                </button>
            </div>
            <div class="card-body">

                <form method="POST" action="{{ route('dokter.konsultasi.update', $konsultasi->id) }}" x-data="{loading:false}" @submit="loading=true" id="formBalas">
                    @csrf @method('PUT')
                    <input type="hidden" name="action" value="dijawab">
                    <div class="form-group">
                        <label class="form-label">Balasan Medis <span style="color:#ef4444;">*</span></label>
                        <textarea name="balasan" rows="5"
                                  class="form-input {{ $errors->has('balasan')?'error':'' }}"
                                  placeholder="Tulis balasan atau saran medis Anda secara detail..."
                                  required>{{ old('balasan') }}</textarea>
                        @error('balasan')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div style="display:flex;gap:1rem;justify-content:flex-end;">
                        <button type="submit" class="btn btn-success" :disabled="loading"
                                style="display:inline-flex !important; flex-direction:row !important; align-items:center !important; gap:0.5rem !important; justify-content:center !important;">
                            <svg x-show="!loading" style="width:1rem;height:1rem;display:inline-block;vertical-align:middle;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            <span x-show="loading" x-cloak class="spinner" style="border-color:rgba(255,255,255,0.3); border-top-color:white; width:1.25rem; height:1.25rem; display:inline-block; vertical-align:middle;"></span>
                            <span x-text="loading ? 'Mengirim...' : 'Kirim Balasan'" style="display:inline-block; vertical-align:middle;"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @else
        <div style="background:#fffbeb; border:1px solid #fcd34d; border-radius:0.75rem; padding:1.5rem; display:flex; align-items:flex-start; gap:1rem; margin-top:0.5rem; margin-bottom:1.5rem;">
            <div style="background:#fef3c7; width:2.5rem; height:2.5rem; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg style="width:1.25rem; height:1.25rem; color:#d97706;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <div style="font-weight:700; color:#92400e; font-size:0.9375rem; margin-bottom:0.375rem;">Menunggu Pembayaran Pasien</div>
                <div style="font-size:0.875rem; color:#b45309; line-height:1.6;">
                    Pasien belum menyelesaikan pembayaran untuk sesi konsultasi ini.
                    Formulir balasan akan otomatis aktif setelah pembayaran lunas diverifikasi.
                </div>
            </div>
        </div>
        @endif
    @endif

    {{-- Modal Tutup Konsultasi --}}
    <div x-show="showTutupModal" class="modal-backdrop" x-cloak>
        <div class="modal" @click.away="showTutupModal = false">
            <div style="padding:1.5rem;">
                <div style="display:flex;align-items:center;gap:0.875rem;margin-bottom:1rem;">
                    <div style="background:#fee2e2;width:2.5rem;height:2.5rem;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg style="width:1.25rem;height:1.25rem;color:#dc2626;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <h3 style="font-size:1.0625rem;font-weight:700;color:#0f172a;">Tutup Konsultasi</h3>
                </div>
                <p style="font-size:0.875rem;color:#475569;margin-bottom:1.5rem;line-height:1.6;">
                    Tutup konsultasi ini? <strong>Pasien tidak dapat mengirim pertanyaan lanjutan</strong> setelah konsultasi ditutup.
                </p>
                <form method="POST" action="{{ route('dokter.konsultasi.update', $konsultasi->id) }}" x-data="{loading:false}" @submit="loading=true">
                    @csrf @method('PUT')
                    <input type="hidden" name="action" value="ditutup">
                    <input type="hidden" name="balasan" value="-">
                    <div style="display:flex;gap:0.75rem;justify-content:flex-end;">
                        <button type="button" class="btn btn-secondary" @click="showTutupModal = false">Batal</button>
                        <button type="submit" class="btn btn-danger" :disabled="loading">
                            <span x-show="!loading">Konfirmasi Tutup</span>
                            <span x-show="loading" x-cloak>Menutup...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('chat-container');
    if (container) container.scrollTop = container.scrollHeight;

    const currentStatus = "{{ $konsultasi->status }}";
    const currentMessagesCount = {{ $konsultasi->pesans ? $konsultasi->pesans->count() : ($konsultasi->balasan ? 2 : 1) }};
    const currentPaymentStatus = "{{ (isset($isPaid) && $isPaid) ? 'dibayar' : 'menunggu' }}";

    if (currentStatus !== 'ditutup') {
        setInterval(() => {
            fetch('{{ route('dokter.konsultasi.show', $konsultasi->id) }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (
                    data.messages_count !== currentMessagesCount || 
                    data.status !== currentStatus ||
                    data.payment_status !== currentPaymentStatus
                ) {
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
