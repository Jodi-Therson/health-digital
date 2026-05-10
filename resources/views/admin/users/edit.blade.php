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
    <div class="card" style="max-width:600px;">
        <div class="card-header">Data Akun</div>
        <div class="card-body">
            <div class="form-group"><label class="form-label">Nama Lengkap <span style="color:#ef4444;">*</span></label><input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input" required></div>
            <div class="form-group"><label class="form-label">Email <span style="color:#ef4444;">*</span></label><input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" required></div>
            <div class="form-group"><label class="form-label">No. Telepon</label><input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-input"></div>
            <div class="form-group"><label class="form-label">Role</label><input type="text" class="form-input" value="{{ ucfirst($user->role) }}" disabled><div class="form-hint">Role tidak dapat diubah setelah dibuat.</div></div>
            <hr style="border:none;border-top:1px solid #e2e8f0;margin:1.5rem 0;">
            <div class="form-group"><label class="form-label">Password Baru (Opsional)</label><input type="password" name="password" class="form-input" placeholder="Biarkan kosong jika tidak ingin mengubah password"></div>
            <div class="form-group"><label class="form-label">Konfirmasi Password Baru</label><input type="password" name="password_confirmation" class="form-input"></div>
            <div style="display:flex;justify-content:flex-end;"><button type="submit" class="btn btn-primary" :disabled="loading">Simpan Perubahan</button></div>
        </div>
    </div>
</form>

<!-- Profil spesifik -->
@if($user->role === 'pasien' && $user->pasien)
<form method="POST" action="{{ route('admin.users.update', $user->id) }}" style="margin-top:1.5rem;">
    @csrf @method('PUT')
    <div class="card" style="max-width:600px;">
        <div class="card-header">Profil Pasien</div>
        <div class="card-body">
            <div class="form-group"><label class="form-label">NIK</label><input type="text" name="nik" value="{{ $user->pasien->nik }}" class="form-input"></div>
            <div class="form-group"><label class="form-label">Alamat</label><textarea name="alamat" class="form-input">{{ $user->pasien->alamat }}</textarea></div>
            <button type="submit" class="btn btn-secondary">Simpan Profil Pasien</button>
        </div>
    </div>
</form>
@elseif($user->role === 'dokter' && $user->dokter)
<form method="POST" action="{{ route('admin.users.update', $user->id) }}" style="margin-top:1.5rem;">
    @csrf @method('PUT')
    <div class="card" style="max-width:600px;">
        <div class="card-header">Profil Dokter</div>
        <div class="card-body">
            <div class="form-group"><label class="form-label">Spesialisasi</label><input type="text" name="spesialisasi" value="{{ $user->dokter->spesialisasi }}" class="form-input"></div>
            <div class="form-group"><label class="form-label">No. STR</label><input type="text" name="no_str" value="{{ $user->dokter->no_str }}" class="form-input"></div>
            <div class="form-group"><label class="form-label">Jadwal Praktik</label><textarea name="jadwal_praktik" class="form-input">{{ $user->dokter->jadwal_praktik }}</textarea></div>
            <button type="submit" class="btn btn-secondary">Simpan Profil Dokter</button>
        </div>
    </div>
</form>
@elseif($user->role === 'perawat' && $user->perawat)
<form method="POST" action="{{ route('admin.users.update', $user->id) }}" style="margin-top:1.5rem;">
    @csrf @method('PUT')
    <div class="card" style="max-width:600px;">
        <div class="card-header">Profil Perawat</div>
        <div class="card-body">
            <div class="form-group"><label class="form-label">No. SIP</label><input type="text" name="no_sip" value="{{ $user->perawat->no_sip }}" class="form-input"></div>
            <div class="form-group"><label class="form-label">Ruangan</label><input type="text" name="ruangan" value="{{ $user->perawat->ruangan }}" class="form-input"></div>
            <button type="submit" class="btn btn-secondary">Simpan Profil Perawat</button>
        </div>
    </div>
</form>
@endif
@endsection
