@extends('layouts.app')
@section('title', 'Detail Konsultasi')
@section('sidebar')@include('pasien._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">{{ $konsultasi->judul }}</h1><p class="page-subtitle">{{ $konsultasi->created_at->format('d F Y, H:i') }}</p></div>
    <a href="{{ route('pasien.konsultasi.index') }}" class="btn btn-secondary">← Kembali</a>
</div>

<div style="max-width:700px;">
    <!-- Riwayat Pesan -->
    @if($konsultasi->pesans && $konsultasi->pesans->count() > 0)
        @foreach($konsultasi->pesans as $pesan)
            @if($pesan->pengirim === 'pasien')
            <div style="margin-bottom:1.25rem;">
                <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.75rem;justify-content:flex-end;">
                    <div style="text-align:right;">
                        <div style="font-weight:600;color:#1e293b;font-size:0.875rem;">Anda</div>
                        <div style="font-size:0.75rem;color:#94a3b8;">{{ $pesan->created_at->format('d M Y, H:i') }}</div>
                    </div>
                    <img src="{{ auth()->user()->avatar_url }}" style="width:2.5rem;height:2.5rem;border-radius:50%;object-fit:cover;">
                </div>
                <div style="background:#dbeafe;border-radius:0.75rem 0.75rem 0 0.75rem;padding:1.25rem;margin-left:3.25rem;font-size:0.9375rem;color:#1e293b;line-height:1.7;">
                    {{ $pesan->pesan }}
                </div>
            </div>
            @else
            <div style="margin-bottom:1.25rem;">
                <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.75rem;">
                    <img src="{{ $konsultasi->dokter->user->avatar_url }}" style="width:2.5rem;height:2.5rem;border-radius:50%;object-fit:cover;">
                    <div>
                        <div style="font-weight:600;color:#1e293b;font-size:0.875rem;">{{ $konsultasi->dokter->user->name }}</div>
                        <div style="font-size:0.75rem;color:#94a3b8;">Dokter • {{ $pesan->created_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:0.75rem 0.75rem 0.75rem 0;padding:1.25rem;margin-right:3.25rem;font-size:0.9375rem;color:#1e293b;line-height:1.7;">
                    {{ $pesan->pesan }}
                </div>
            </div>
            @endif
        @endforeach
    @else
        <!-- Fallback untuk data lama -->
        <div style="margin-bottom:1.25rem;">
            <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.75rem;justify-content:flex-end;">
                <div style="text-align:right;">
                    <div style="font-weight:600;color:#1e293b;font-size:0.875rem;">Anda</div>
                    <div style="font-size:0.75rem;color:#94a3b8;">{{ $konsultasi->created_at->format('d M Y, H:i') }}</div>
                </div>
                <img src="{{ auth()->user()->avatar_url }}" style="width:2.5rem;height:2.5rem;border-radius:50%;object-fit:cover;">
            </div>
            <div style="background:#dbeafe;border-radius:0.75rem 0.75rem 0 0.75rem;padding:1.25rem;margin-left:3.25rem;font-size:0.9375rem;color:#1e293b;line-height:1.7;">
                {{ $konsultasi->pesan }}
            </div>
        </div>
        @if($konsultasi->balasan)
        <div style="margin-bottom:1.25rem;">
            <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.75rem;">
                <img src="{{ $konsultasi->dokter->user->avatar_url }}" style="width:2.5rem;height:2.5rem;border-radius:50%;object-fit:cover;">
                <div>
                    <div style="font-weight:600;color:#1e293b;font-size:0.875rem;">{{ $konsultasi->dokter->user->name }}</div>
                    <div style="font-size:0.75rem;color:#94a3b8;">Dokter • {{ $konsultasi->updated_at->format('d M Y, H:i') }}</div>
                </div>
            </div>
            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:0.75rem 0.75rem 0.75rem 0;padding:1.25rem;margin-right:3.25rem;font-size:0.9375rem;color:#1e293b;line-height:1.7;">
                {{ $konsultasi->balasan }}
            </div>
        </div>
        @endif
    @endif

    <!-- Form Balasan Pasien -->
    @if($konsultasi->status === 'menunggu')
        <div style="background:#fef3c7;border:1px solid #fcd34d;border-radius:0.75rem;padding:1.25rem;text-align:center;color:#92400e;font-size:0.875rem;">
            ⏳ Menunggu balasan dari {{ $konsultasi->dokter->user->name }}...
        </div>
    @elseif($konsultasi->status === 'dijawab')
        <div class="card" style="margin-top:1.5rem;">
            <div class="card-header">Tulis Balasan</div>
            <div class="card-body">
                <form method="POST" action="{{ route('pasien.konsultasi.update', $konsultasi->id) }}" x-data="{loading:false}" @submit="loading=true">
                    @csrf @method('PUT')
                    <div class="form-group">
                        <textarea name="pesan" rows="4" class="form-input {{ $errors->has('pesan')?'error':'' }}" placeholder="Tulis tanggapan atau pertanyaan tambahan Anda..." required>{{ old('pesan') }}</textarea>
                        @error('pesan')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div style="display:flex;justify-content:flex-end;">
                        <button type="submit" class="btn btn-primary" :disabled="loading">
                            <span x-show="!loading">Kirim Balasan</span>
                            <span x-show="loading" x-cloak>Mengirim...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @elseif($konsultasi->status === 'ditutup')
        <div class="alert alert-info" style="margin-top:1rem;">Konsultasi ini telah ditutup oleh dokter.</div>
    @endif

    <div style="display:flex;align-items:center;gap:0.75rem;margin-top:1rem;">
        <span class="badge badge-{{ $konsultasi->status_badge_color }}">{{ $konsultasi->status_label }}</span>
        <span style="font-size:0.8125rem;color:#94a3b8;">Kepada: {{ $konsultasi->dokter->user->name }}</span>
    </div>
</div>
@endsection
