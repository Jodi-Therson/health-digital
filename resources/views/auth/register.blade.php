@extends('layouts.auth')

@section('title', 'Daftar Akun')

@section('content')
<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:1.5rem; font-weight:800; color:#0f172a; margin-bottom:0.375rem;">Buat Akun Baru</h1>
    <p style="font-size:0.875rem; color:#64748b;">Daftar sebagai pasien di HealthDigital</p>
</div>


<form method="POST" action="{{ route('register') }}" x-data="{ loading: false }" @submit="loading = true">
    @csrf

    <div class="form-group">
        <label for="name" class="form-label">Nama Lengkap <span style="color:#ef4444;">*</span></label>
        <input type="text" id="name" name="name" value="{{ old('name') }}"
               class="form-input {{ $errors->has('name') ? 'error' : '' }}"
               placeholder="Nama sesuai KTP" required>
        @error('name')<div class="form-error">{{ $message }}</div>@enderror
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
        <div class="form-group">
            <label for="email" class="form-label">Email <span style="color:#ef4444;">*</span></label>
            <input type="email" id="email" name="email" value="{{ old('email') }}"
                   class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                   placeholder="email@domain.com" required>
            @error('email')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label for="phone" class="form-label">No. HP</label>
            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                   class="form-input" placeholder="08xxxxxxxxxx">
        </div>
    </div>

    <div class="form-group">
        <label for="nik" class="form-label">NIK (16 Digit) <span style="color:#ef4444;">*</span></label>
        <input type="text" id="nik" name="nik" value="{{ old('nik') }}"
               class="form-input {{ $errors->has('nik') ? 'error' : '' }}"
               placeholder="3201xxxxxxxxxxxx" maxlength="16" pattern="[0-9]{16}" required>
        <div class="form-hint">Nomor Induk Kependudukan sesuai KTP</div>
        @error('nik')<div class="form-error">{{ $message }}</div>@enderror
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
        <div class="form-group">
            <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span style="color:#ef4444;">*</span></label>
            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                   class="form-input {{ $errors->has('tanggal_lahir') ? 'error' : '' }}"
                   max="{{ date('Y-m-d') }}" required>
            @error('tanggal_lahir')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span style="color:#ef4444;">*</span></label>
            <select id="jenis_kelamin" name="jenis_kelamin" class="form-input {{ $errors->has('jenis_kelamin') ? 'error' : '' }}" required>
                <option value="">Pilih...</option>
                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
            @error('jenis_kelamin')<div class="form-error">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="form-group">
        <label for="alamat" class="form-label">Alamat</label>
        <textarea id="alamat" name="alamat" class="form-input" placeholder="Alamat lengkap" rows="2">{{ old('alamat') }}</textarea>
    </div>

    <div class="form-group" x-data="{ showPass: false }">
        <label for="password" class="form-label">Password <span style="color:#ef4444;">*</span></label>
        <div style="position:relative;">
            <input :type="showPass ? 'text' : 'password'" id="password" name="password"
                   class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                   placeholder="Min. 8 karakter, huruf besar & angka" required style="padding-right:2.75rem;">
            <button type="button" @click="showPass = !showPass"
                    style="position:absolute;right:0.75rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;">
                <svg x-show="!showPass" style="width:1.125rem;height:1.125rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <svg x-show="showPass" x-cloak style="width:1.125rem;height:1.125rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
            </button>
        </div>
        @error('password')<div class="form-error">{{ $message }}</div>@enderror
    </div>

    <div class="form-group">
        <label for="password_confirmation" class="form-label">Konfirmasi Password <span style="color:#ef4444;">*</span></label>
        <input type="password" id="password_confirmation" name="password_confirmation"
               class="form-input" placeholder="Ulangi password" required>
    </div>

    <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;" :disabled="loading">
        <span x-show="!loading">Buat Akun</span>
        <span x-show="loading" x-cloak style="display:flex;align-items:center;gap:0.5rem;">
            <div class="spinner"></div>Memproses...
        </span>
    </button>
</form>

<div style="text-align:center; margin-top:1.5rem; font-size:0.875rem; color:#64748b;">
    Sudah punya akun?
    <a href="{{ route('login') }}" style="color:#2563eb; font-weight:600; text-decoration:none;">Masuk di sini</a>
</div>
@endsection
