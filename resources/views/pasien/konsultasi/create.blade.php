@extends('layouts.app')
@section('title', 'Konsultasi Baru')
@section('sidebar')@include('pasien._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Konsultasi Baru</h1></div>
    <a href="{{ route('pasien.konsultasi.index') }}" class="btn btn-secondary">← Kembali</a>
</div>
@if($errors->any())<div class="alert alert-error"><div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div></div>@endif
<div class="card" style="max-width:640px;">
    <div class="card-body">
        <form method="POST" action="{{ route('pasien.konsultasi.store') }}" x-data="{loading:false}" @submit="loading=true">
            @csrf
            <div class="form-group">
                <label class="form-label">Pilih Dokter <span style="color:#ef4444;">*</span></label>
                <select name="dokter_id" class="form-input {{ $errors->has('dokter_id')?'error':'' }}" required>
                    <option value="">-- Pilih Dokter --</option>
                    @foreach($dokters as $d)
                    <option value="{{ $d->id }}" {{ old('dokter_id')==$d->id?'selected':'' }}>{{ $d->user->name }} — {{ $d->spesialisasi }}</option>
                    @endforeach
                </select>
                @error('dokter_id')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Judul Konsultasi <span style="color:#ef4444;">*</span></label>
                <input type="text" name="judul" value="{{ old('judul') }}" class="form-input {{ $errors->has('judul')?'error':'' }}" placeholder="Contoh: Nyeri kepala berulang selama 3 hari" required>
                @error('judul')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Pesan / Keluhan Detail <span style="color:#ef4444;">*</span></label>
                <textarea name="pesan" rows="6" class="form-input {{ $errors->has('pesan')?'error':'' }}" placeholder="Ceritakan keluhan Anda secara lengkap agar dokter bisa memberikan saran terbaik..." required>{{ old('pesan') }}</textarea>
                <div class="form-hint">Minimal 20 karakter. Sertakan gejala, durasi, dan riwayat kesehatan jika ada.</div>
                @error('pesan')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div style="display:flex;gap:1rem;justify-content:flex-end;">
                <a href="{{ route('pasien.konsultasi.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary" :disabled="loading">
                    <span x-show="!loading">Kirim Konsultasi</span>
                    <span x-show="loading" x-cloak style="display:flex;align-items:center;gap:0.5rem;"><div class="spinner" style="border-color:rgba(255,255,255,0.3);border-top-color:white;"></div>Mengirim...</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
