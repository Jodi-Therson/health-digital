@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('content')
<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:1.5rem; font-weight:800; color:#0f172a; margin-bottom:0.375rem;">Reset Password</h1>
    <p style="font-size:0.875rem; color:#64748b;">Masukkan email Anda dan kami akan mengirimkan link reset password.</p>
</div>

@if(session('info'))
<div class="alert alert-info" style="margin-bottom:1.25rem;">
    <svg style="width:1.25rem;height:1.25rem;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <span>{{ session('info') }}</span>
</div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf
    <div class="form-group">
        <label for="email" class="form-label">Alamat Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}"
               class="form-input {{ $errors->has('email') ? 'error' : '' }}"
               placeholder="email@domain.com" required autofocus>
        @error('email')<div class="form-error">{{ $message }}</div>@enderror
    </div>
    <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">Kirim Link Reset</button>
</form>

<div style="text-align:center; margin-top:1.5rem; font-size:0.875rem; color:#64748b;">
    Ingat password?
    <a href="{{ route('login') }}" style="color:#2563eb; font-weight:600; text-decoration:none;">Kembali ke login</a>
</div>
@endsection
