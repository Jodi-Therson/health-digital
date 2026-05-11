@extends('layouts.app')
@section('title', 'Detail Konsultasi')
@section('sidebar')@include('pasien._sidebar')@endsection
@section('breadcrumb')
<a href="{{ route('pasien.dashboard') }}">Dashboard</a><span>/</span><a href="{{ route('pasien.konsultasi.index') }}">Konsultasi</a><span>/</span><span>{{ Str::limit($konsultasi->judul, 30) }}</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $konsultasi->judul }}</h1>
        <div style="display:flex;align-items:center;gap:0.75rem;margin-top:0.375rem;">
            <span class="badge badge-{{ $konsultasi->status_badge_color }}">{{ $konsultasi->status_label }}</span>
            <span style="font-size:0.8125rem;color:#64748b;">kepada {{ $konsultasi->dokter->user->name }} · {{ $konsultasi->created_at->format('d F Y, H:i') }}</span>
        </div>
    </div>
    <a href="{{ route('pasien.konsultasi.index') }}" class="btn btn-secondary">← Kembali</a>
</div>

<div style="max-width:740px;">

    {{-- Banner ditutup --}}
    @if($konsultasi->status === 'ditutup')
    <div style="background:#f1f5f9;border:1px solid #cbd5e1;border-radius:0.75rem;padding:1rem 1.25rem;display:flex;align-items:center;gap:0.75rem;margin-bottom:1.5rem;">
        <svg style="width:1.25rem;height:1.25rem;color:#64748b;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        <span style="font-size:0.875rem;color:#475569;font-weight:500;">Konsultasi ini telah ditutup oleh dokter. Input pesan tidak tersedia.</span>
    </div>
    @endif

    {{-- Bubble Chat --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;margin-bottom:1.5rem;" id="chat-container">
        @if($konsultasi->pesans && $konsultasi->pesans->count() > 0)
            @foreach($konsultasi->pesans as $pesan)
                @if($pesan->pengirim === 'pasien')
                {{-- Pasien: bubble kiri, abu --}}
                <div style="display:flex;align-items:flex-start;gap:0.75rem;">
                    <img src="{{ auth()->user()->avatar_url }}" style="width:2.5rem;height:2.5rem;border-radius:50%;object-fit:cover;flex-shrink:0;">
                    <div style="flex:1;max-width:80%;">
                        <div style="font-weight:600;color:#1e293b;font-size:0.8125rem;margin-bottom:0.375rem;">Anda</div>
                        <div style="background:#f1f5f9;border-radius:0 0.75rem 0.75rem 0.75rem;padding:1rem 1.25rem;font-size:0.9375rem;color:#1e293b;line-height:1.7;">{{ $pesan->pesan }}</div>
                        <div style="font-size:0.75rem;color:#94a3b8;margin-top:0.375rem;">{{ $pesan->created_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
                @else
                {{-- Dokter: bubble kanan, biru --}}
                <div style="display:flex;align-items:flex-start;gap:0.75rem;flex-direction:row-reverse;">
                    <img src="{{ $konsultasi->dokter->user->avatar_url }}" style="width:2.5rem;height:2.5rem;border-radius:50%;object-fit:cover;flex-shrink:0;">
                    <div style="flex:1;max-width:80%;">
                        <div style="font-weight:600;color:#2563eb;font-size:0.8125rem;margin-bottom:0.375rem;text-align:right;">{{ $konsultasi->dokter->user->name }}</div>
                        <div style="background:#2563eb;color:white;border-radius:0.75rem 0 0.75rem 0.75rem;padding:1rem 1.25rem;font-size:0.9375rem;line-height:1.7;">{{ $pesan->pesan }}</div>
                        <div style="font-size:0.75rem;color:#94a3b8;margin-top:0.375rem;text-align:right;">{{ $pesan->created_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
                @endif
            @endforeach
        @else
            {{-- Fallback data lama --}}
            <div style="display:flex;align-items:flex-start;gap:0.75rem;">
                <img src="{{ auth()->user()->avatar_url }}" style="width:2.5rem;height:2.5rem;border-radius:50%;object-fit:cover;flex-shrink:0;">
                <div style="flex:1;max-width:80%;">
                    <div style="font-weight:600;color:#1e293b;font-size:0.8125rem;margin-bottom:0.375rem;">Anda</div>
                    <div style="background:#f1f5f9;border-radius:0 0.75rem 0.75rem 0.75rem;padding:1rem 1.25rem;font-size:0.9375rem;color:#1e293b;line-height:1.7;">{{ $konsultasi->pesan }}</div>
                    <div style="font-size:0.75rem;color:#94a3b8;margin-top:0.375rem;">{{ $konsultasi->created_at->format('d M Y, H:i') }}</div>
                </div>
            </div>
            @if($konsultasi->balasan)
            <div style="display:flex;align-items:flex-start;gap:0.75rem;flex-direction:row-reverse;">
                <img src="{{ $konsultasi->dokter->user->avatar_url }}" style="width:2.5rem;height:2.5rem;border-radius:50%;object-fit:cover;flex-shrink:0;">
                <div style="flex:1;max-width:80%;">
                    <div style="font-weight:600;color:#2563eb;font-size:0.8125rem;margin-bottom:0.375rem;text-align:right;">{{ $konsultasi->dokter->user->name }}</div>
                    <div style="background:#2563eb;color:white;border-radius:0.75rem 0 0.75rem 0.75rem;padding:1rem 1.25rem;font-size:0.9375rem;line-height:1.7;">{{ $konsultasi->balasan }}</div>
                    <div style="font-size:0.75rem;color:#94a3b8;margin-top:0.375rem;text-align:right;">{{ $konsultasi->updated_at->format('d M Y, H:i') }}</div>
                </div>
            </div>
            @endif
        @endif
    </div>

    {{-- Input area --}}
    @if($konsultasi->status === 'menunggu')
    <div style="background:#fffbeb;border:1px solid #fcd34d;border-radius:0.75rem;padding:1.25rem;display:flex;align-items:center;gap:0.875rem;">
        <div style="background:#fef3c7;width:2.25rem;height:2.25rem;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg style="width:1.125rem;height:1.125rem;color:#d97706;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <div style="font-weight:600;color:#92400e;font-size:0.875rem;">Menunggu Balasan</div>
            <div style="font-size:0.8125rem;color:#b45309;">Menunggu balasan dari {{ $konsultasi->dokter->user->name }}...</div>
        </div>
    </div>
    @elseif($konsultasi->status === 'dijawab')
    <div class="card" style="margin-top:0.5rem;">
        <div class="card-header">Tulis Balasan</div>
        <div class="card-body">
            @if($errors->any())<div class="alert alert-error"><div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div></div>@endif
            <form method="POST" action="{{ route('pasien.konsultasi.update', $konsultasi->id) }}" x-data="{loading:false}" @submit="loading=true">
                @csrf @method('PUT')
                <div class="form-group">
                    <textarea name="pesan" rows="4" class="form-input {{ $errors->has('pesan')?'error':'' }}"
                              placeholder="Tulis tanggapan atau pertanyaan lanjutan Anda..." required>{{ old('pesan') }}</textarea>
                    @error('pesan')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div style="display:flex;justify-content:flex-end;">
                    <button type="submit" class="btn btn-primary" :disabled="loading">
                        <span x-show="!loading">Kirim Balasan</span>
                        <span x-show="loading" x-cloak style="display:flex;align-items:center;gap:0.5rem;"><div class="spinner" style="border-color:rgba(255,255,255,0.3);border-top-color:white;"></div>Mengirim...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>

<script>
// Scroll to bottom of chat
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('chat-container');
    if (container) container.scrollTop = container.scrollHeight;
});
</script>
@endsection
