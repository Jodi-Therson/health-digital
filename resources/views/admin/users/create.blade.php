@extends('layouts.app')
@section('title', 'Tambah Pengguna')
@section('sidebar')@include('admin._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Tambah Pengguna</h1></div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">← Kembali</a>
</div>


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
                    <div class="form-group">
                        <label class="form-label">No. STR <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="no_str" value="{{ old('no_str') }}" class="form-input" placeholder="Nomor Surat Tanda Registrasi">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Spesialisasi <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="spesialisasi" value="{{ old('spesialisasi') }}" class="form-input" placeholder="Contoh: Spesialis Anak">
                    </div>
                    <div class="form-group" x-data="{ 
                        days: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                        selectedDays: []
                    }">
                        <label class="form-label">Jadwal Praktik <span style="color:#ef4444;">*</span></label>
                        <div style="display:flex; flex-direction:column; gap:0.75rem; background:#f8fafc; padding:1rem; border-radius:0.75rem; border:1px solid #e2e8f0;">
                            <template x-for="day in days" :key="day">
                                <div style="display:flex; align-items:center; justify-content:space-between; gap:1rem;">
                                    <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; width:100px;">
                                        <input type="checkbox" :name="'jadwal['+day+'][active]'" value="1" x-model="selectedDays" :value="day">
                                        <span x-text="day" style="font-size:0.875rem; font-weight:600;"></span>
                                    </label>
                                    <div x-show="selectedDays.includes(day)" style="display:flex; align-items:center; gap:0.5rem; flex:1;" x-transition>
                                        <input type="time" :name="'jadwal['+day+'][start]'" class="form-input" style="padding:0.25rem 0.5rem; width:auto;" value="08:00" :disabled="!selectedDays.includes(day)">
                                        <span style="color:#64748b;">s/d</span>
                                        <input type="time" :name="'jadwal['+day+'][end]'" class="form-input" style="padding:0.25rem 0.5rem; width:auto;" value="14:00" :disabled="!selectedDays.includes(day)">
                                    </div>
                                    <div x-show="!selectedDays.includes(day)" style="flex:1; color:#cbd5e1; font-size:0.75rem; font-style:italic;">Libur</div>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tarif Konsultasi (Rp)</label>
                        <input type="number" name="tarif_konsultasi" value="{{ old('tarif_konsultasi', 150000) }}" class="form-input">
                    </div>
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
