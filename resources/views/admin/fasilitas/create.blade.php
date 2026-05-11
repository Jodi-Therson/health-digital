@extends('layouts.app')
@section('title', 'Tambah Fasilitas')
@section('sidebar')@include('admin._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Tambah Fasilitas</h1></div>
    <a href="{{ route('admin.fasilitas.index') }}" class="btn btn-secondary">← Kembali</a>
</div>
<div class="card" style="max-width:600px;">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.fasilitas.store') }}" enctype="multipart/form-data" x-data="{ photoPreview: null }">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Fasilitas <span style="color:#ef4444;">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}" class="form-input" placeholder="Contoh: Lab Radiologi" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-input" rows="3" placeholder="Jelaskan kegunaan fasilitas ini...">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Foto Fasilitas (Opsional)</label>
                <input type="file" name="foto" class="form-input" accept="image/*" 
                       @change="const file = $el.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL(file); }">
                
                {{-- Image Preview Container --}}
                <div x-show="photoPreview" style="margin-top:1rem; position:relative; width:100%; max-height:250px; border-radius:0.75rem; overflow:hidden; border:1px solid #e2e8f0;">
                    <img :src="photoPreview" style="width:100%; height:100%; object-fit:cover;">
                    <button type="button" @click="photoPreview = null; $refs.photoInput.value = ''" 
                            style="position:absolute; top:0.5rem; right:0.5rem; background:rgba(0,0,0,0.5); color:white; border:none; border-radius:50%; width:2rem; height:2rem; cursor:pointer;">✕</button>
                </div>
            </div>

            <div class="form-group">
                <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;">
                    <input type="checkbox" name="is_active" value="1" checked> 
                    <span style="font-weight:600; color:#475569;">Aktifkan fasilitas ini</span>
                </label>
            </div>

            <div style="display:flex;justify-content:flex-end;margin-top:1.5rem;">
                <button type="submit" class="btn btn-primary" style="padding:0.75rem 2rem;">Simpan Fasilitas</button>
            </div>
        </form>
    </div>
</div>
@endsection
