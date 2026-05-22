@extends('layouts.app')
@section('title', 'Edit Layanan')
@section('sidebar')@include('admin._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Edit Layanan</h1><p class="page-subtitle">Perbarui informasi layanan medis</p></div>
    <a href="{{ route('admin.layanan.index') }}" class="btn btn-secondary">← Kembali</a>
</div>
<div class="card" style="max-width:640px;">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.layanan.update', $layanan->id) }}" enctype="multipart/form-data"
              x-data="{
                previewUrl: null,
                hasExisting: {{ $layanan->gambar_url ? 'true' : 'false' }},
                handleFile(e) {
                    const file = e.target.files[0];
                    if (!file) { this.previewUrl = null; return; }
                    const reader = new FileReader();
                    reader.onload = (ev) => { this.previewUrl = ev.target.result; };
                    reader.readAsDataURL(file);
                }
              }">
            @csrf @method('PUT')

            <div class="form-group">
                <label class="form-label">Nama Layanan <span style="color:#ef4444;">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $layanan->nama) }}" class="form-input {{ $errors->has('nama') ? 'error' : '' }}" required>
                @error('nama')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi <span style="color:#ef4444;">*</span></label>
                <textarea name="deskripsi" class="form-input {{ $errors->has('deskripsi') ? 'error' : '' }}" rows="3" required>{{ old('deskripsi', $layanan->deskripsi) }}</textarea>
                @error('deskripsi')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
                <div class="form-group">
                    <label class="form-label">Urutan Tampil</label>
                    <input type="number" name="urutan" value="{{ old('urutan', $layanan->urutan) }}" class="form-input" min="0">
                    <div class="form-hint">0 = tampil pertama</div>
                </div>
                <div class="form-group">
                    <label style="display:flex; align-items:center; gap:0.625rem; cursor:pointer; margin-top:1.75rem;">
                        <input type="checkbox" name="is_active" value="1" {{ $layanan->is_active ? 'checked' : '' }} style="width:1rem;height:1rem;">
                        <span style="font-size:0.875rem; font-weight:500; color:#1e293b;">Aktifkan layanan</span>
                    </label>
                </div>
            </div>

            {{-- Upload Gambar --}}
            <div class="form-group">
                <label class="form-label">Gambar Layanan</label>

                {{-- Preview gambar existing --}}
                <div x-show="hasExisting && !previewUrl" style="margin-bottom:1rem;">
                    <div style="font-size:0.8125rem; color:#64748b; margin-bottom:0.5rem; font-weight:500;">Gambar Saat Ini:</div>
                    @if($layanan->gambar_url)
                    <div style="position:relative; display:inline-block;">
                        <img src="{{ $layanan->gambar_url }}" alt="{{ $layanan->nama }}"
                             style="height:120px; border-radius:0.625rem; object-fit:cover; border:2px solid #e2e8f0;">
                        <div style="position:absolute;inset:0;background:rgba(0,0,0,0.4);border-radius:0.625rem;display:flex;align-items:center;justify-content:center;opacity:0;transition:opacity 0.2s;"
                             onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">
                            <span style="color:white;font-size:0.75rem;font-weight:600;">Upload baru untuk mengganti</span>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Preview gambar baru --}}
                <div x-show="previewUrl" style="margin-bottom:1rem;">
                    <div style="font-size:0.8125rem; color:#2563eb; margin-bottom:0.5rem; font-weight:500;">Preview Gambar Baru:</div>
                    <img :src="previewUrl" alt="Preview baru" style="height:120px; border-radius:0.625rem; object-fit:cover; border:2px solid #2563eb;">
                </div>

                <div style="border:2px dashed #cbd5e1; border-radius:0.75rem; padding:1.25rem; transition:border-color 0.2s;">
                    <p style="font-size:0.875rem;color:#64748b;margin-bottom:0.5rem;">
                        {{ $layanan->gambar_url ? 'Upload gambar baru untuk mengganti yang ada' : 'Upload gambar layanan' }}
                    </p>
                    <input type="file" name="gambar" accept="image/jpeg,image/png,image/jpg,image/webp"
                           class="form-input {{ $errors->has('gambar') ? 'error' : '' }}"
                           style="cursor:pointer;"
                           @change="handleFile($event)">
                    @error('gambar')<div class="form-error" style="margin-top:0.5rem;">{{ $message }}</div>@enderror
                    <div class="form-hint">PNG, JPG, WEBP — Maks. 2MB. Biarkan kosong jika tidak ingin mengubah gambar.</div>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:0.75rem;margin-top:2rem;">
                <button type="submit" class="btn btn-primary" style="padding:0.75rem 2rem;">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
