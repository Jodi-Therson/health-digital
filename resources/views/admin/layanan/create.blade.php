@extends('layouts.app')
@section('title', 'Tambah Layanan')
@section('sidebar')@include('admin._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Tambah Layanan</h1></div>
    <a href="{{ route('admin.layanan.index') }}" class="btn btn-secondary">← Kembali</a>
</div>
<div class="card" style="max-width:600px;">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.layanan.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Layanan <span style="color:#ef4444;">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}" class="form-input" placeholder="Contoh: Poli Umum" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Deskripsi <span style="color:#ef4444;">*</span></label>
                <textarea name="deskripsi" class="form-input" rows="3" placeholder="Jelaskan deskripsi layanan ini..." required>{{ old('deskripsi') }}</textarea>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
                <div class="form-group">
                    <label class="form-label">Urutan Tampil <span style="color:#ef4444;">*</span></label>
                    <input type="number" name="urutan" value="{{ old('urutan', 0) }}" class="form-input" min="0" required>
                    <div class="form-hint">Urutan muncul di halaman publik</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="is_active" class="form-input">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="form-group" x-data="{ selectedIcon: '{{ old('ikon') }}' }">
                <label class="form-label">Pilih Ikon <span style="color:#ef4444;">*</span></label>
                <div style="display:grid; grid-template-columns:repeat(5, 1fr); gap:0.75rem; margin-top:0.5rem;">
                    @php
                        $icons = [
                            'Stetoskop' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                            'Gigi' => 'M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z',
                            'Anak' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                            'Jantung' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
                            'Umum' => 'M13 10V3L4 14h7v7l9-11h-7z'
                        ];
                    @endphp
                    @foreach($icons as $name => $path)
                    <label style="cursor:pointer; text-align:center;">
                        <input type="radio" name="ikon" value="{{ $path }}" class="hidden-radio" style="display:none;" 
                               x-model="selectedIcon" {{ $loop->first ? 'checked' : '' }}>
                        <div :style="selectedIcon === '{{ $path }}' ? 'border-color:#2563eb; background:#eff6ff; color:#2563eb;' : 'border-color:#e2e8f0; background:white; color:#64748b;'" 
                             style="border:2px solid; border-radius:0.75rem; padding:1rem; transition:all 0.2s; display:flex; flex-direction:column; align-items:center; gap:0.5rem;">
                            <svg style="width:1.5rem;height:1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $path }}"/></svg>
                            <span style="font-size:0.65rem; font-weight:700; text-transform:uppercase;">{{ $name }}</span>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;margin-top:2rem;">
                <button type="submit" class="btn btn-primary" style="padding:0.75rem 2rem;">Simpan Layanan</button>
            </div>
        </form>
    </div>
</div>
@endsection
