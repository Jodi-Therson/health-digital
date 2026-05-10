@extends('layouts.app')
@section('title', 'Tambah Pengguna')
@section('sidebar')@include('admin._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Tambah Pengguna</h1></div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">← Kembali</a>
</div>
@if($errors->any())<div class="alert alert-error"><div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div></div>@endif

<form method="POST" action="{{ route('admin.users.store') }}" x-data="{ role: '{{ old('role', 'pasien') }}', loading: false }" @submit="loading=true">
    @csrf
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;" class="form-grid">
        <div class="card">
            <div class="card-header">Data Akun</div>
            <div class="card-body">
                <div class="form-group"><label class="form-label">Nama Lengkap <span style="color:#ef4444;">*</span></label><input type="text" name="name" value="{{ old('name') }}" class="form-input" required></div>
                <div class="form-group"><label class="form-label">Email <span style="color:#ef4444;">*</span></label><input type="email" name="email" value="{{ old('email') }}" class="form-input" required></div>
                <div class="form-group"><label class="form-label">No. Telepon</label><input type="text" name="phone" value="{{ old('phone') }}" class="form-input"></div>
                <div class="form-group"><label class="form-label">Password <span style="color:#ef4444;">*</span></label><input type="password" name="password" class="form-input" required></div>
                <div class="form-group"><label class="form-label">Konfirmasi Password <span style="color:#ef4444;">*</span></label><input type="password" name="password_confirmation" class="form-input" required></div>
                <div class="form-group">
                    <label class="form-label">Role <span style="color:#ef4444;">*</span></label>
                    <select name="role" x-model="role" class="form-input" required>
                        <option value="pasien">Pasien</option>
                        <option value="dokter">Dokter</option>
                        <option value="perawat">Perawat</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Data Profil Spesifik</div>
            <div class="card-body">
                <!-- Pasien -->
                <div x-show="role === 'pasien'">
                    <div class="form-group"><label class="form-label">NIK</label><input type="text" name="nik" value="{{ old('nik') }}" class="form-input" maxlength="16"></div>
                    <div class="form-group"><label class="form-label">Tanggal Lahir</label><input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="form-input"></div>
                    <div class="form-group"><label class="form-label">Jenis Kelamin</label><select name="jenis_kelamin" class="form-input"><option value="L">Laki-laki</option><option value="P">Perempuan</option></select></div>
                    <div class="form-group"><label class="form-label">Alamat</label><textarea name="alamat" class="form-input" rows="2">{{ old('alamat') }}</textarea></div>
                    <div class="form-group"><label class="form-label">Golongan Darah</label><select name="golongan_darah" class="form-input"><option value="">--Pilih--</option><option value="A">A</option><option value="B">B</option><option value="AB">AB</option><option value="O">O</option></select></div>
                </div>
                <!-- Dokter -->
                <div x-show="role === 'dokter'" style="display:none;">
                    <div class="form-group"><label class="form-label">Spesialisasi <span style="color:#ef4444;">*</span></label><input type="text" name="spesialisasi" value="{{ old('spesialisasi') }}" class="form-input"></div>
                    <div class="form-group"><label class="form-label">No. STR <span style="color:#ef4444;">*</span></label><input type="text" name="no_str" value="{{ old('no_str') }}" class="form-input"></div>
                    <div class="form-group"><label class="form-label">Jadwal Praktik</label><textarea name="jadwal_praktik" class="form-input" rows="2" placeholder="Senin-Jumat 08:00-12:00">{{ old('jadwal_praktik') }}</textarea></div>
                </div>
                <!-- Perawat -->
                <div x-show="role === 'perawat'" style="display:none;">
                    <div class="form-group"><label class="form-label">No. SIP <span style="color:#ef4444;">*</span></label><input type="text" name="no_sip" value="{{ old('no_sip') }}" class="form-input"></div>
                    <div class="form-group"><label class="form-label">Ruangan</label><input type="text" name="ruangan" value="{{ old('ruangan') }}" class="form-input"></div>
                </div>
                <!-- Admin -->
                <div x-show="role === 'admin'" style="display:none;">
                    <div class="alert alert-info">Admin tidak memiliki profil spesifik tambahan.</div>
                </div>
            </div>
        </div>
    </div>
    <div style="display:flex;gap:1rem;justify-content:flex-end;margin-top:1.5rem;">
        <button type="submit" class="btn btn-primary" :disabled="loading">Simpan Pengguna</button>
    </div>
</form>
<style>@media(max-width:768px){.form-grid{grid-template-columns:1fr !important;}}</style>
@endsection
