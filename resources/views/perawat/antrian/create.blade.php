@extends('layouts.app')
@section('title', 'Tambah Antrian')
@section('sidebar')@include('perawat._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Tambah Antrian</h1></div>
    <a href="{{ route('perawat.antrian.index') }}" class="btn btn-secondary">← Kembali</a>
</div>
@if($errors->any())<div class="alert alert-error"><div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div></div>@endif
<div class="card" style="max-width:640px;">
    <div class="card-body">
        <form method="POST" action="{{ route('perawat.antrian.store') }}" x-data="{loading:false}" @submit="loading=true">
            @csrf
            <div class="form-group">
                <label class="form-label">Pasien <span style="color:#ef4444;">*</span></label>
                <select name="pasien_id" class="form-input {{ $errors->has('pasien_id')?'error':'' }}" required>
                    <option value="">-- Pilih Pasien --</option>
                    @foreach($pasiens as $p)
                    <option value="{{ $p->id }}" {{ old('pasien_id')==$p->id?'selected':'' }}>{{ $p->user->name }} ({{ $p->nik }})</option>
                    @endforeach
                </select>
                @error('pasien_id')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;" x-data="{
                selectedLayanan: '{{ old('layanan_id') }}',
                selectedDokter: '{{ old('dokter_id') }}',
                dokters: {{ json_encode($dokters->map(fn($d) => ['id' => $d->id, 'name' => $d->user->name, 'spesialisasi' => $d->spesialisasi])) }},
                layanans: {{ json_encode($layanans->map(fn($l) => ['id' => $l->id, 'nama' => strtolower($l->nama)])) }},
                get filteredDokters() {
                    if(!this.selectedLayanan) return [];
                    const lay = this.layanans.find(l => l.id == this.selectedLayanan);
                    if(!lay) return [];
                    return this.dokters.filter(d => d.spesialisasi.toLowerCase().includes(lay.nama));
                }
            }" x-init="$watch('selectedLayanan', value => { selectedDokter = '' })">
                <div class="form-group">
                    <label class="form-label">Layanan <span style="color:#ef4444;">*</span></label>
                    <select name="layanan_id" class="form-input {{ $errors->has('layanan_id')?'error':'' }}" required x-model="selectedLayanan">
                        <option value="">-- Pilih Layanan --</option>
                        @foreach($layanans as $l)
                        <option value="{{ $l->id }}">{{ $l->nama }}</option>
                        @endforeach
                    </select>
                    @error('layanan_id')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Dokter <span style="color:#ef4444;">*</span></label>
                    <select name="dokter_id" class="form-input {{ $errors->has('dokter_id')?'error':'' }}" required x-model="selectedDokter">
                        <option value="">-- Pilih Dokter --</option>
                        <template x-for="d in filteredDokters" :key="d.id">
                            <option :value="d.id" x-text="d.name + ' — ' + d.spesialisasi" :selected="d.id == selectedDokter"></option>
                        </template>
                    </select>
                    @error('dokter_id')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Tanggal <span style="color:#ef4444;">*</span></label>
                <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="form-input" min="{{ date('Y-m-d') }}" required>
                @error('tanggal')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Keluhan <span style="color:#ef4444;">*</span></label>
                <textarea name="keluhan" rows="3" class="form-input {{ $errors->has('keluhan')?'error':'' }}" placeholder="Keluhan pasien..." required>{{ old('keluhan') }}</textarea>
                @error('keluhan')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div style="display:flex;gap:1rem;justify-content:flex-end;">
                <a href="{{ route('perawat.antrian.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary" :disabled="loading">
                    <span x-show="!loading">Tambah Antrian</span>
                    <span x-show="loading" x-cloak>Memproses...</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
