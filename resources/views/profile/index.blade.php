@extends('layouts.app')
@section('title', 'Profil Saya')

@section('sidebar')
    @if(auth()->user()->role === 'pasien')
        @include('pasien._sidebar')
    @elseif(auth()->user()->role === 'dokter')
        @include('dokter._sidebar')
    @elseif(auth()->user()->role === 'perawat')
        @include('perawat._sidebar')
    @elseif(auth()->user()->role === 'admin')
        @include('admin._sidebar')
    @endif
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Profil Saya</h1>
        <p class="page-subtitle">Kelola informasi akun Anda</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem; align-items: start;">
    <!-- LEFT CARD: Editable -->
    <div class="card">
        <div class="card-header">Pengaturan Kontak & Keamanan</div>
        <div class="card-body">
            <form method="POST" action="{{ route('profile.update') }}" x-data="{ loading: false }" @submit="loading = true">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label class="form-label">Email <span style="color:#ef4444;">*</span></label>
                    <input type="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required>
                    @error('email')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">No. Telepon</label>
                    <input type="text" name="phone" class="form-input" value="{{ old('phone', $user->phone) }}">
                    @error('phone')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div style="margin-top: 2rem; margin-bottom: 1rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem; font-weight: 600; color: #1e293b;">
                    Ubah Password (Opsional)
                </div>

                <div class="form-group">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-input" placeholder="Kosongkan jika tidak ingin mengubah">
                    @error('password')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi password baru">
                </div>

                <div style="margin-top: 1.5rem; text-align: right;">
                    <button type="submit" class="btn btn-primary" :disabled="loading">
                        <span x-show="!loading">Simpan Perubahan</span>
                        <span x-show="loading" x-cloak style="display:flex;align-items:center;gap:0.5rem;">
                            <span class="spinner" style="border-color:rgba(255,255,255,0.3);border-top-color:white;"></span>Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- RIGHT CARD: Read-Only Data -->
    <div class="card">
        <div class="card-header">Informasi Personal Pokok</div>
        <div class="card-body">
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <div class="form-input" style="background: #f8fafc; color: #475569; border-color: #e2e8f0; cursor: not-allowed;">{{ $user->name }}</div>
            </div>
            
            @if($user->role === 'pasien' && $user->pasien)
            <div class="form-group">
                <label class="form-label">NIK (Nomor Induk Kependudukan)</label>
                <div class="form-input" style="background: #f8fafc; color: #475569; border-color: #e2e8f0; cursor: not-allowed;">{{ $user->pasien->nik }}</div>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Tanggal Lahir</label>
                    <div class="form-input" style="background: #f8fafc; color: #475569; border-color: #e2e8f0; cursor: not-allowed;">{{ \Carbon\Carbon::parse($user->pasien->tanggal_lahir)->format('d M Y') }}</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Jenis Kelamin</label>
                    <div class="form-input" style="background: #f8fafc; color: #475569; border-color: #e2e8f0; cursor: not-allowed;">{{ $user->pasien->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Alamat</label>
                <div class="form-input" style="background: #f8fafc; color: #475569; border-color: #e2e8f0; cursor: not-allowed; min-height: 4.5rem; display: flex; align-items: flex-start;">{{ $user->pasien->alamat ?: '-' }}</div>
            </div>
            @elseif($user->role === 'dokter' && $user->dokter)
            <div class="form-group">
                <label class="form-label">No. STR</label>
                <div class="form-input" style="background: #f8fafc; color: #475569; border-color: #e2e8f0; cursor: not-allowed;">{{ $user->dokter->no_str }}</div>
            </div>
            <div class="form-group">
                <label class="form-label">Spesialisasi</label>
                <div class="form-input" style="background: #f8fafc; color: #475569; border-color: #e2e8f0; cursor: not-allowed;">{{ $user->dokter->spesialisasi }}</div>
            </div>
            @elseif($user->role === 'perawat' && $user->perawat)
            <div class="form-group">
                <label class="form-label">No. STR</label>
                <div class="form-input" style="background: #f8fafc; color: #475569; border-color: #e2e8f0; cursor: not-allowed;">{{ $user->perawat->no_str }}</div>
            </div>
            <div class="form-group">
                <label class="form-label">Bagian</label>
                <div class="form-input" style="background: #f8fafc; color: #475569; border-color: #e2e8f0; cursor: not-allowed;">{{ $user->perawat->bagian }}</div>
            </div>
            @endif

            <div style="margin-top: 1.5rem; font-size: 0.8125rem; color: #64748b; display: flex; align-items: flex-start; gap: 0.625rem; background: #f8fafc; padding: 0.875rem; border-radius: 0.5rem; border: 1px solid #e2e8f0;">
                <svg style="width: 1.25rem; height: 1.25rem; flex-shrink: 0; color: #94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Data pokok di atas bersifat permanen karena terikat dengan riwayat rekam medis dan hukum administrasi kesehatan. Jika terdapat kesalahan pencatatan, silakan ajukan perbaikan langsung ke loket pendaftaran di fasilitas kesehatan.</span>
            </div>
        </div>
    </div>
</div>
@endsection
