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
        <form method="POST" action="{{ route('admin.fasilitas.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group"><label class="form-label">Nama Fasilitas <span style="color:#ef4444;">*</span></label><input type="text" name="nama" value="{{ old('nama') }}" class="form-input" required></div>
            <div class="form-group"><label class="form-label">Kategori <span style="color:#ef4444;">*</span></label>
                <select name="kategori" class="form-input" required>
                    <option value="ruangan">Ruangan</option>
                    <option value="peralatan">Peralatan</option>
                    <option value="umum">Umum</option>
                </select>
            </div>
            <div class="form-group"><label class="form-label">Deskripsi</label><textarea name="deskripsi" class="form-input" rows="3">{{ old('deskripsi') }}</textarea></div>
            <div class="form-group"><label class="form-label">Foto Fasilitas (Opsional)</label><input type="file" name="foto" class="form-input" accept="image/*"></div>
            <div class="form-group"><label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;"><input type="checkbox" name="is_active" value="1" checked> <span>Aktifkan fasilitas ini</span></label></div>
            <div style="display:flex;justify-content:flex-end;"><button type="submit" class="btn btn-primary">Simpan Fasilitas</button></div>
        </form>
    </div>
</div>
@endsection
