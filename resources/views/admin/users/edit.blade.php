@extends('layouts.app')
@section('title', 'Edit Pengguna')
@section('sidebar')@include('admin._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Edit Pengguna</h1><p class="page-subtitle">{{ $user->name }}</p></div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">← Kembali</a>
</div>
@if($errors->any())<div class="alert alert-error"><div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div></div>@endif

<form method="POST" action="{{ route('admin.users.update', $user->id) }}" x-data="{ loading: false }" @submit="loading=true">
    @csrf @method('PUT')
    <input type="hidden" name="role" value="{{ $user->role }}">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem; align-items: start;">
        <div class="card">
            <div class="card-header">Data Akun & Keamanan</div>
            <div class="card-body">
                <div class="form-group"><label class="form-label">Nama Lengkap <span style="color:#ef4444;">*</span></label><input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input" required></div>
                <div class="form-group"><label class="form-label">Email <span style="color:#ef4444;">*</span></label><input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" required></div>
                <div class="form-group"><label class="form-label">No. Telepon</label><input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-input"></div>
                <div class="form-group"><label class="form-label">Role</label><input type="text" class="form-input" value="{{ ucfirst($user->role) }}" disabled><div class="form-hint">Role terikat secara sistemik dan tidak dapat diubah.</div></div>
                <hr style="border:none;border-top:1px solid #e2e8f0;margin:1.5rem 0;">
                <div class="form-group"><label class="form-label">Password Baru (Opsional)</label><input type="password" name="password" class="form-input" placeholder="Kosongkan jika tidak ingin mengubah password"></div>
                <div class="form-group"><label class="form-label">Konfirmasi Password</label><input type="password" name="password_confirmation" class="form-input"></div>
            </div>
        </div>

        @if($user->role === 'pasien' && $user->pasien)
        <div class="card">
            <div class="card-header">Data Personal Pasien</div>
            <div class="card-body">
                <div class="form-group"><label class="form-label">NIK (16 Digit)</label><input type="text" name="nik" value="{{ old('nik', $user->pasien->nik) }}" class="form-input" maxlength="16"></div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                    <div class="form-group"><label class="form-label">Tanggal Lahir</label><input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $user->pasien->tanggal_lahir) }}" class="form-input"></div>
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-input">
                            <option value="L" {{ old('jenis_kelamin', $user->pasien->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $user->pasien->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                </div>
                <div class="form-group"><label class="form-label">Alamat Lengkap</label><textarea name="alamat" class="form-input" rows="3">{{ old('alamat', $user->pasien->alamat) }}</textarea></div>
            </div>
        </div>
        @elseif($user->role === 'dokter' && $user->dokter)
        <div class="card">
            <div class="card-header">Profil Medis Dokter</div>
            <div class="card-body">
                <div class="form-group"><label class="form-label">No. STR</label><input type="text" name="no_str" value="{{ old('no_str', $user->dokter->no_str) }}" class="form-input"></div>
                <div class="form-group"><label class="form-label">Spesialisasi</label><input type="text" name="spesialisasi" value="{{ old('spesialisasi', $user->dokter->spesialisasi) }}" class="form-input"></div>
                <div class="form-group"><label class="form-label">Tarif Konsultasi (Rp)</label><input type="number" name="tarif_konsultasi" value="{{ old('tarif_konsultasi', $user->dokter->tarif_konsultasi) }}" class="form-input"></div>
                <div class="form-group"><label class="form-label">Bio Singkat</label><textarea name="bio" class="form-input" rows="3">{{ old('bio', $user->dokter->bio) }}</textarea></div>
            </div>
        </div>
        @elseif($user->role === 'perawat' && $user->perawat)
        <div class="card">
            <div class="card-header">Profil Medis Perawat</div>
            <div class="card-body">
                <div class="form-group"><label class="form-label">No. STR Perawat</label><input type="text" name="no_str" value="{{ old('no_str', $user->perawat->no_str) }}" class="form-input"></div>
                <div class="form-group"><label class="form-label">Bagian / Poli</label><input type="text" name="bagian" value="{{ old('bagian', $user->perawat->bagian) }}" class="form-input"></div>
            </div>
        </div>
        @endif
    </div>
    
    <div style="margin-top: 1.5rem; display:flex; justify-content:flex-end;">
        <button type="submit" class="btn btn-primary" :disabled="loading">Simpan Semua Perubahan</button>
    </div>
</form>
@endsection
