@extends('layouts.app')
@section('title', 'Detail Konsultasi')
@section('sidebar')@include('dokter._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">{{ $konsultasi->judul }}</h1><p class="page-subtitle">{{ $konsultasi->pasien->user->name }} • {{ $konsultasi->created_at->format('d M Y, H:i') }}</p></div>
    <a href="{{ route('dokter.konsultasi.index') }}" class="btn btn-secondary">← Kembali</a>
</div>

<div style="max-width:700px;">
    <!-- Riwayat Pesan -->
    @if($konsultasi->pesans && $konsultasi->pesans->count() > 0)
        @foreach($konsultasi->pesans as $pesan)
            @if($pesan->pengirim === 'pasien')
            <div style="margin-bottom:1.25rem;">
                <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.75rem;">
                    <img src="{{ $konsultasi->pasien->user->avatar_url }}" style="width:2.5rem;height:2.5rem;border-radius:50%;object-fit:cover;">
                    <div>
                        <div style="font-weight:600;color:#1e293b;font-size:0.875rem;">{{ $konsultasi->pasien->user->name }}</div>
                        <div style="font-size:0.75rem;color:#94a3b8;">Pasien • {{ $pesan->created_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
                <div style="background:#f1f5f9;border-radius:0.75rem 0.75rem 0.75rem 0;padding:1.25rem;margin-right:3.25rem;font-size:0.9375rem;color:#1e293b;line-height:1.7;">
                    {{ $pesan->pesan }}
                </div>
            </div>
            @else
            <div style="margin-bottom:1.25rem;">
                <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.75rem;justify-content:flex-end;">
                    <div style="text-align:right;">
                        <div style="font-weight:600;color:#1e293b;font-size:0.875rem;">Anda</div>
                        <div style="font-size:0.75rem;color:#2563eb;">{{ $pesan->created_at->format('d M Y, H:i') }}</div>
                    </div>
                    <img src="{{ auth()->user()->avatar_url }}" style="width:2.5rem;height:2.5rem;border-radius:50%;object-fit:cover;">
                </div>
                <div style="background:#dbeafe;border-radius:0.75rem 0.75rem 0 0.75rem;padding:1.25rem;margin-left:3.25rem;font-size:0.9375rem;color:#1e293b;line-height:1.7;">
                    {{ $pesan->pesan }}
                </div>
            </div>
            @endif
        @endforeach
    @else
        <!-- Fallback untuk data lama -->
        <div style="margin-bottom:1.25rem;">
            <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.75rem;">
                <img src="{{ $konsultasi->pasien->user->avatar_url }}" style="width:2.5rem;height:2.5rem;border-radius:50%;object-fit:cover;">
                <div>
                    <div style="font-weight:600;color:#1e293b;font-size:0.875rem;">{{ $konsultasi->pasien->user->name }}</div>
                    <div style="font-size:0.75rem;color:#94a3b8;">Pasien • {{ $konsultasi->created_at->format('d M Y, H:i') }}</div>
                </div>
            </div>
            <div style="background:#f1f5f9;border-radius:0.75rem 0.75rem 0.75rem 0;padding:1.25rem;margin-right:3.25rem;font-size:0.9375rem;color:#1e293b;line-height:1.7;">
                {{ $konsultasi->pesan }}
            </div>
        </div>
        @if($konsultasi->balasan)
        <div style="margin-bottom:1.25rem;">
            <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.75rem;justify-content:flex-end;">
                <div style="text-align:right;">
                    <div style="font-weight:600;color:#1e293b;font-size:0.875rem;">Anda</div>
                    <div style="font-size:0.75rem;color:#2563eb;">{{ $konsultasi->updated_at->format('d M Y, H:i') }}</div>
                </div>
                <img src="{{ auth()->user()->avatar_url }}" style="width:2.5rem;height:2.5rem;border-radius:50%;object-fit:cover;">
            </div>
            <div style="background:#dbeafe;border-radius:0.75rem 0.75rem 0 0.75rem;padding:1.25rem;margin-left:3.25rem;font-size:0.9375rem;color:#1e293b;line-height:1.7;">
                {{ $konsultasi->balasan }}
            </div>
        </div>
        @endif
    @endif

    @if(in_array($konsultasi->status, ['menunggu', 'dijawab']))
    <div class="card" style="margin-top:1.5rem;">
        <div class="card-header">Tulis Balasan</div>
        <div class="card-body">
            @if($errors->any())<div class="alert alert-error"><div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div></div>@endif
            <form method="POST" action="{{ route('dokter.konsultasi.update', $konsultasi->id) }}" x-data="{loading:false, action:'dijawab'}" @submit="loading=true">
                @csrf @method('PUT')
                <input type="hidden" name="action" x-model="action">
                <div class="form-group">
                    <label class="form-label">Balasan <span style="color:#ef4444;">*</span></label>
                    <textarea name="balasan" rows="4" class="form-input {{ $errors->has('balasan')?'error':'' }}" placeholder="Tulis balasan atau saran medis Anda..." required></textarea>
                    @error('balasan')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div style="display:flex;gap:1rem;">
                    <button type="submit" class="btn btn-success" :disabled="loading" @click="action = 'dijawab'">
                        <span x-show="!loading">✓ Kirim Balasan</span>
                        <span x-show="loading" x-cloak>Mengirim...</span>
                    </button>
                    <button type="submit" class="btn btn-secondary" :disabled="loading" @click="action = 'ditutup'">Jawab & Tutup</button>
                </div>
            </form>
        </div>
    </div>
    @elseif($konsultasi->status === 'ditutup')
    <div class="alert alert-info" style="margin-top:1.5rem;">Konsultasi ini telah ditutup.</div>
    @endif
</div>
@endsection
