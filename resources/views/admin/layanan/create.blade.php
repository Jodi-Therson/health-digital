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
        <form method="POST" action="{{ route('admin.layanan.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group"><label class="form-label">Nama Layanan <span style="color:#ef4444;">*</span></label><input type="text" name="nama" value="{{ old('nama') }}" class="form-input" required></div>
            <div class="form-group"><label class="form-label">Deskripsi <span style="color:#ef4444;">*</span></label><textarea name="deskripsi" class="form-input" rows="3" required>{{ old('deskripsi') }}</textarea></div>
            <div class="form-group"><label class="form-label">Harga Dasar (Rp) <span style="color:#ef4444;">*</span></label><input type="number" name="harga_dasar" value="{{ old('harga_dasar') }}" class="form-input" required></div>
            <div class="form-group"><label class="form-label">Icon / Gambar (Opsional)</label><input type="file" name="icon" class="form-input" accept="image/*"></div>
            <div class="form-group"><label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;"><input type="checkbox" name="is_active" value="1" checked> <span>Aktifkan layanan ini</span></label></div>
            <div style="display:flex;justify-content:flex-end;"><button type="submit" class="btn btn-primary">Simpan Layanan</button></div>
        </form>
    </div>
</div>
@endsection
