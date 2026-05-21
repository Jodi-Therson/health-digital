@extends('layouts.app')
@section('title', 'Edit Rekam Medis')
@section('sidebar')@include('dokter._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Edit Rekam Medis</h1></div>
    <a href="{{ route('dokter.rekam-medis.show', $rm->id) }}" class="btn btn-secondary">← Kembali</a>
</div>
<form method="POST" action="{{ route('dokter.rekam-medis.update', $rm->id) }}" x-data="{ loading:false, resep: {{ json_encode($rm->resep ?: [['obat'=>'','dosis'=>'','aturan'=>'']]) }} }" @submit="loading=true">
    @csrf @method('PUT')
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;" class="form-grid">
        <div class="card">
            <div class="card-header">Vital Signs</div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
                    <div class="form-group"><label class="form-label">Tekanan Darah</label><input type="text" name="tekanan_darah" value="{{ old('tekanan_darah', $rm->tekanan_darah) }}" class="form-input" placeholder="120/80"></div>
                    <div class="form-group"><label class="form-label">Suhu (°C)</label><input type="number" name="suhu_tubuh" value="{{ old('suhu_tubuh', $rm->suhu_tubuh) }}" class="form-input" step="0.1"></div>
                    <div class="form-group"><label class="form-label">Berat (kg)</label><input type="number" name="berat_badan" value="{{ old('berat_badan', $rm->berat_badan) }}" class="form-input" step="0.1"></div>
                    <div class="form-group"><label class="form-label">Tinggi (cm)</label><input type="number" name="tinggi_badan" value="{{ old('tinggi_badan', $rm->tinggi_badan) }}" class="form-input" step="0.1"></div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">Pemeriksaan</div>
            <div class="card-body">
                <div class="form-group"><label class="form-label">Anamnesis</label><textarea name="anamnesis" rows="2" class="form-input">{{ old('anamnesis', $rm->anamnesis) }}</textarea></div>
                <div class="form-group"><label class="form-label">Diagnosa <span style="color:#ef4444;">*</span></label><textarea name="diagnosa" rows="2" class="form-input {{ $errors->has('diagnosa')?'error':'' }}" required>{{ old('diagnosa', $rm->diagnosa) }}</textarea>@error('diagnosa')<div class="form-error">{{ $message }}</div>@enderror</div>
                <div class="form-group"><label class="form-label">Tindakan</label><textarea name="tindakan" rows="2" class="form-input">{{ old('tindakan', $rm->tindakan) }}</textarea></div>
            </div>
        </div>
    </div>
    <div class="card" style="margin-top:1rem;">
        <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
            <span>Resep Obat</span>
            <button type="button" @click="resep.push({obat:'',dosis:'',aturan:''})" class="btn btn-secondary btn-sm">+ Tambah</button>
        </div>
        <div class="card-body">
            <template x-for="(r,i) in resep" :key="i">
                <div style="display:grid;grid-template-columns:2fr 1fr 2fr auto;gap:0.75rem;margin-bottom:0.75rem;align-items:end;">
                    <div><label class="form-label" style="font-size:0.75rem;">Obat</label><input type="text" :name="'resep_obat['+i+']'" x-model="r.obat" class="form-input"></div>
                    <div><label class="form-label" style="font-size:0.75rem;">Dosis</label><input type="text" :name="'resep_dosis['+i+']'" x-model="r.dosis" class="form-input"></div>
                    <div><label class="form-label" style="font-size:0.75rem;">Aturan</label><input type="text" :name="'resep_aturan['+i+']'" x-model="r.aturan" class="form-input"></div>
                    <div><button type="button" @click="resep.splice(i,1)" class="btn btn-danger btn-sm" x-show="resep.length>1">×</button></div>
                </div>
            </template>
        </div>
    </div>
    <div class="card" style="margin-top:1rem;">
        <div class="card-body"><div class="form-group" style="margin:0;"><label class="form-label">Catatan</label><textarea name="catatan" rows="2" class="form-input">{{ old('catatan', $rm->catatan) }}</textarea></div></div>
    </div>
    <div style="display:flex;gap:1rem;justify-content:flex-end;margin-top:1.5rem;">
        <a href="{{ route('dokter.rekam-medis.show', $rm->id) }}" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary" :disabled="loading">
            <span x-show="!loading">Perbarui</span>
            <span x-show="loading" x-cloak style="display:flex;align-items:center;gap:0.5rem;"><span class="spinner" style="border-color:rgba(255,255,255,0.3);border-top-color:white;"></span>Menyimpan...</span>
        </button>
    </div>
</form>
<style>@media(max-width:768px){.form-grid{grid-template-columns:1fr !important;}}</style>
@endsection
