@extends('layouts.app')
@section('title', 'Tambah Layanan')
@section('sidebar')@include('admin._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Tambah Layanan</h1><p class="page-subtitle">Isi detail layanan medis baru</p></div>
    <a href="{{ route('admin.layanan.index') }}" class="btn btn-secondary">← Kembali</a>
</div>
<div class="card" style="max-width:640px;">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.layanan.store') }}" enctype="multipart/form-data"
              x-data="{
                previewUrl: null,
                handleFile(e) {
                    const file = e.target.files[0];
                    if (!file) { this.previewUrl = null; return; }
                    const reader = new FileReader();
                    reader.onload = (ev) => { this.previewUrl = ev.target.result; };
                    reader.readAsDataURL(file);
                }
              }">
            @csrf

            <div class="form-group">
                <label class="form-label">Nama Layanan <span style="color:#ef4444;">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}" class="form-input {{ $errors->has('nama') ? 'error' : '' }}" placeholder="Contoh: Poli Umum" required>
                @error('nama')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi <span style="color:#ef4444;">*</span></label>
                <textarea name="deskripsi" class="form-input {{ $errors->has('deskripsi') ? 'error' : '' }}" rows="3" placeholder="Jelaskan deskripsi layanan ini..." required>{{ old('deskripsi') }}</textarea>
                @error('deskripsi')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
                <div class="form-group">
                    <label class="form-label">Urutan Tampil <span style="color:#ef4444;">*</span></label>
                    <input type="number" name="urutan" value="{{ old('urutan', 0) }}" class="form-input" min="0" required>
                    <div class="form-hint">Urutan muncul di halaman publik (0 = pertama)</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="is_active" class="form-input">
                        <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            {{-- Upload Gambar --}}
            <div class="form-group">
                <label class="form-label">Gambar Layanan</label>
                <div style="border:2px dashed #cbd5e1; border-radius:0.75rem; padding:1.5rem; text-align:center; transition:border-color 0.2s; cursor:pointer;"
                     :style="previewUrl ? 'border-color:#2563eb;' : ''"
                     @dragover.prevent="$el.style.borderColor='#2563eb'"
                     @dragleave="$el.style.borderColor='#cbd5e1'"
                     @drop.prevent="handleFile({target:{files:$event.dataTransfer.files}})">

                    {{-- Preview gambar --}}
                    <div x-show="previewUrl" style="margin-bottom:1rem;">
                        <img :src="previewUrl" alt="Preview" style="max-height:180px; max-width:100%; border-radius:0.5rem; object-fit:cover; margin:0 auto;">
                    </div>

                    {{-- Placeholder --}}
                    <div x-show="!previewUrl">
                        <svg style="width:2.5rem;height:2.5rem;color:#cbd5e1;margin:0 auto 0.75rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p style="font-size:0.875rem;color:#64748b;margin-bottom:0.5rem;">Klik atau seret gambar ke sini</p>
                        <p style="font-size:0.75rem;color:#94a3b8;">PNG, JPG, WEBP — Maks. 2MB</p>
                    </div>

                    <input type="file" id="gambar-input" name="gambar" accept="image/jpeg,image/png,image/jpg,image/webp"
                           class="form-input {{ $errors->has('gambar') ? 'error' : '' }}"
                           style="margin-top:0.75rem; cursor:pointer;"
                           @change="handleFile($event)">
                </div>
                @error('gambar')<div class="form-error" style="margin-top:0.5rem;">{{ $message }}</div>@enderror
                <div class="form-hint">Gambar akan ditampilkan di halaman publik dan di daftar admin.</div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:0.75rem;margin-top:2rem;">
                <a href="{{ route('admin.layanan.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary" style="padding:0.75rem 2rem;">Simpan Layanan</button>
            </div>
        </form>
    </div>
</div>
@endsection
