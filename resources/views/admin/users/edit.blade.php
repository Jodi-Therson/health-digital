@extends('layouts.app')
@section('title', 'Edit Pengguna')
@section('sidebar')@include('admin._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Edit Pengguna</h1><p class="page-subtitle">{{ $user->name }}</p></div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">← Kembali</a>
</div>


<form method="POST" action="{{ route('admin.users.update', $user->id) }}" x-data="{ loading: false }" x-ref="editForm" @submit.prevent>
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
                    <div class="form-group"><label class="form-label">Tanggal Lahir</label><input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $user->pasien->tanggal_lahir?->format('Y-m-d')) }}" class="form-input"></div>
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
                <div class="form-group" x-data="{ 
                    days: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                    selectedDays: {{ json_encode(array_keys($user->dokter->jadwal ?? [])) }},
                    schedule: {{ json_encode($user->dokter->jadwal ?? []) }}
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
                                    <input type="time" :name="'jadwal['+day+'][start]'" class="form-input" style="padding:0.25rem 0.5rem; width:auto;" :value="schedule[day]?.start || '08:00'" :disabled="!selectedDays.includes(day)">
                                    <span style="color:#64748b;">s/d</span>
                                    <input type="time" :name="'jadwal['+day+'][end]'" class="form-input" style="padding:0.25rem 0.5rem; width:auto;" :value="schedule[day]?.end || '14:00'" :disabled="!selectedDays.includes(day)">
                                </div>
                                <div x-show="!selectedDays.includes(day)" style="flex:1; color:#cbd5e1; font-size:0.75rem; font-style:italic;">Libur</div>
                            </div>
                        </template>
                    </div>
                </div>
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
        <button type="button" 
            @click="triggerConfirm(
                'Simpan Perubahan',
                'Apakah Anda yakin ingin menyimpan perubahan data untuk user ini?',
                () => { loading = true; $refs.editForm.submit(); },
                'primary'
            )"
            class="btn btn-primary" :disabled="loading">
            <span x-show="!loading">Simpan Semua Perubahan</span>
            <span x-show="loading">Menyimpan...</span>
        </button>
    </div>
</form>
@endsection
