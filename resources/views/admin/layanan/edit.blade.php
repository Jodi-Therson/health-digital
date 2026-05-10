@extends('layouts.app')
@section('title', 'Edit Layanan')
@section('sidebar')@include('admin._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Edit Layanan</h1></div>
    <a href="{{ route('admin.layanan.index') }}" class="btn btn-secondary">← Kembali</a>
</div>
<div class="card" style="max-width:600px;">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.layanan.update', $layanan->id) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="form-group"><label class="form-label">Nama Layanan <span style="color:#ef4444;">*</span></label><input type="text" name="nama" value="{{ old('nama', $layanan->nama) }}" class="form-input" required></div>
            <div class="form-group"><label class="form-label">Deskripsi <span style="color:#ef4444;">*</span></label><textarea name="deskripsi" class="form-input" rows="3" required>{{ old('deskripsi', $layanan->deskripsi) }}</textarea></div>
            <div class="form-group"><label class="form-label">Harga Dasar (Rp) <span style="color:#ef4444;">*</span></label><input type="number" name="harga_dasar" value="{{ old('harga_dasar', $layanan->harga_dasar) }}" class="form-input" required></div>
            <div class="form-group">
                <label class="form-label">Icon / Gambar</label>
                @if($layanan->icon)<div style="margin-bottom:0.5rem;"><img src="{{ asset('storage/'.$layanan->icon) }}" style="width:64px;height:64px;object-fit:cover;border-radius:0.5rem;"></div>@endif
                <input type="file" name="icon" class="form-input" accept="image/*">
                <div class="form-hint">Biarkan kosong jika tidak ingin mengubah icon</div>
            </div>
            <div class="form-group"><label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;"><input type="checkbox" name="is_active" value="1" {{ $layanan->is_active ? 'checked' : '' }}> <span>Aktifkan layanan ini</span></label></div>
            <div style="display:flex;justify-content:flex-end;"><button type="submit" class="btn btn-primary">Simpan Perubahan</button></div>
        </form>
    </div>
</div>
@endsection
