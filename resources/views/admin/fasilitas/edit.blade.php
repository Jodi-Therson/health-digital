@extends('layouts.app')
@section('title', 'Edit Fasilitas')
@section('sidebar')@include('admin._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Edit Fasilitas</h1></div>
    <a href="{{ route('admin.fasilitas.index') }}" class="btn btn-secondary">← Kembali</a>
</div>
<div class="card" style="max-width:600px;">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.fasilitas.update', $fasilitas->id) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="form-group"><label class="form-label">Nama Fasilitas <span style="color:#ef4444;">*</span></label><input type="text" name="nama" value="{{ old('nama', $fasilitas->nama) }}" class="form-input" required></div>
            <div class="form-group"><label class="form-label">Kategori <span style="color:#ef4444;">*</span></label>
                <select name="kategori" class="form-input" required>
                    <option value="ruangan" {{ $fasilitas->kategori == 'ruangan' ? 'selected' : '' }}>Ruangan</option>
                    <option value="peralatan" {{ $fasilitas->kategori == 'peralatan' ? 'selected' : '' }}>Peralatan</option>
                    <option value="umum" {{ $fasilitas->kategori == 'umum' ? 'selected' : '' }}>Umum</option>
                </select>
            </div>
            <div class="form-group"><label class="form-label">Deskripsi</label><textarea name="deskripsi" class="form-input" rows="3">{{ old('deskripsi', $fasilitas->deskripsi) }}</textarea></div>
            <div class="form-group">
                <label class="form-label">Foto Fasilitas</label>
                @if($fasilitas->foto)<div style="margin-bottom:0.5rem;"><img src="{{ asset('storage/'.$fasilitas->foto) }}" style="width:100px;height:70px;object-fit:cover;border-radius:0.5rem;"></div>@endif
                <input type="file" name="foto" class="form-input" accept="image/*">
                <div class="form-hint">Biarkan kosong jika tidak ingin mengubah foto</div>
            </div>
            <div class="form-group"><label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;"><input type="checkbox" name="is_active" value="1" {{ $fasilitas->is_active ? 'checked' : '' }}> <span>Aktifkan fasilitas ini</span></label></div>
            <div style="display:flex;justify-content:flex-end;"><button type="submit" class="btn btn-primary">Simpan Perubahan</button></div>
        </form>
    </div>
</div>
@endsection
