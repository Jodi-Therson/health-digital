@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')
<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:1.5rem; font-weight:800; color:#0f172a; margin-bottom:0.375rem;">Selamat Datang Kembali</h1>
    <p style="font-size:0.875rem; color:#64748b;">Masuk ke akun HealthDigital Anda</p>
</div>

@if($errors->any())
<div class="alert alert-error" style="margin-bottom:1.25rem;">
    <svg style="width:1.25rem;height:1.25rem;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <div>
        @foreach($errors->all() as $error)
        <div>{{ $error }}</div>
        @endforeach
    </div>
</div>
@endif

<form method="POST" action="{{ route('login') }}" x-data="{ loading: false }" @submit="loading = true">
    @csrf

    <div class="form-group">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}"
               class="form-input {{ $errors->has('email') ? 'error' : '' }}"
               placeholder="nama@email.com" required autofocus autocomplete="email">
        @error('email')
        <div class="form-error">
            <svg style="width:0.875rem;height:0.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="form-group" x-data="{ showPass: false }">
        <label for="password" class="form-label">Password</label>
        <div style="position:relative;">
            <input :type="showPass ? 'text' : 'password'" id="password" name="password"
                   class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                   placeholder="Masukkan password" required autocomplete="current-password"
                   style="padding-right:2.75rem;">
            <button type="button" @click="showPass = !showPass"
                    style="position:absolute; right:0.75rem; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#94a3b8; padding:0;">
                <svg x-show="!showPass" style="width:1.125rem;height:1.125rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <svg x-show="showPass" x-cloak style="width:1.125rem;height:1.125rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
            </button>
        </div>
        @error('password')
        <div class="form-error">{{ $message }}</div>
        @enderror
    </div>

    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem;">
        <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
            <input type="checkbox" name="remember" id="remember" style="width:1rem;height:1rem;accent-color:#2563eb;">
            <span style="font-size:0.875rem; color:#475569;">Ingat saya</span>
        </label>
        <a href="{{ route('password.request') }}" style="font-size:0.875rem; color:#2563eb; text-decoration:none;">Lupa password?</a>
    </div>

    <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;" :disabled="loading">
        <span x-show="!loading">Masuk</span>
        <span x-show="loading" x-cloak style="display:flex; align-items:center; gap:0.5rem;">
            <div class="spinner"></div>
            Memproses...
        </span>
    </button>
</form>

<div style="text-align:center; margin-top:1.5rem; font-size:0.875rem; color:#64748b;">
    Belum punya akun?
    <a href="{{ route('register') }}" style="color:#2563eb; font-weight:600; text-decoration:none;">Daftar sekarang</a>
</div>

@endsection
