@extends('layouts.app')
@section('title', 'Daftar Antrian')
@section('sidebar')@include('pasien._sidebar')@endsection
@section('breadcrumb')
<a href="{{ route('pasien.dashboard') }}">Dashboard</a><span>/</span>
<a href="{{ route('pasien.antrian.index') }}">Antrian</a><span>/</span>
<span>Daftar Baru</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Daftar Antrian</h1>
        <p class="page-subtitle">Isi form di bawah untuk mendaftar antrian</p>
    </div>
    <a href="{{ route('pasien.antrian.index') }}" class="btn btn-secondary">← Kembali</a>
</div>

@if($errors->any())
<div class="alert alert-error"><svg style="width:1.25rem;height:1.25rem;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
<div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div></div>
@endif

<form id="antrianForm" method="POST" action="{{ route('pasien.antrian.store') }}" x-data="{ loading: false, showModal: false }" @submit.prevent="if($el.checkValidity()) showModal = true">
    @csrf
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;" class="form-grid">
        <div class="card">
            <div class="card-header">Pilih Layanan & Dokter</div>
            <div class="card-body">
                <div x-data="{
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
                        <label for="layanan_id" class="form-label">Layanan <span style="color:#ef4444;">*</span></label>
                        <select id="layanan_id" name="layanan_id" class="form-input {{ $errors->has('layanan_id') ? 'error' : '' }}" required x-model="selectedLayanan">
                            <option value="">-- Pilih Layanan --</option>
                            @foreach($layanans as $l)
                            <option value="{{ $l->id }}">Poli {{ $l->nama }}</option>
                            @endforeach
                        </select>
                        @error('layanan_id')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="dokter_id" class="form-label">Dokter <span style="color:#ef4444;">*</span></label>
                        <select id="dokter_id" name="dokter_id" class="form-input {{ $errors->has('dokter_id') ? 'error' : '' }}" required x-model="selectedDokter">
                            <option value="">-- Pilih Dokter --</option>
                            <template x-for="d in filteredDokters" :key="d.id">
                                <option :value="d.id" x-text="d.name + ' — ' + d.spesialisasi" :selected="d.id == selectedDokter"></option>
                            </template>
                        </select>
                        @error('dokter_id')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="tanggal" class="form-label">Tanggal Kunjungan <span style="color:#ef4444;">*</span></label>
                    <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}"
                           class="form-input {{ $errors->has('tanggal') ? 'error' : '' }}"
                           min="{{ date('Y-m-d') }}" required>
                    <div class="form-hint">Pilih tanggal mulai hari ini</div>
                    @error('tanggal')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Informasi Keluhan</div>
            <div class="card-body">
                <div class="form-group">
                    <label for="keluhan" class="form-label">Keluhan Utama <span style="color:#ef4444;">*</span></label>
                    <textarea id="keluhan" name="keluhan" rows="5"
                              class="form-input {{ $errors->has('keluhan') ? 'error' : '' }}"
                              placeholder="Ceritakan keluhan yang Anda rasakan secara detail, minimal 10 karakter..."
                              required>{{ old('keluhan') }}</textarea>
                    <div class="form-hint">Minimal 10 karakter, maksimal 500 karakter. Semakin detail semakin baik.</div>
                    @error('keluhan')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <!-- Tips -->
                <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:0.625rem; padding:1rem; font-size:0.8125rem;">
                    <div style="font-weight:600; color:#15803d; margin-bottom:0.5rem;">💡 Tips pengisian keluhan:</div>
                    <ul style="color:#166534; padding-left:1.25rem; margin:0; line-height:1.8;">
                        <li>Sebutkan gejala yang dirasakan</li>
                        <li>Durasi keluhan sudah berapa lama</li>
                        <li>Apakah ada riwayat penyakit serupa</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div style="display:flex; gap:1rem; justify-content:flex-end; margin-top:1.5rem;">
        <a href="{{ route('pasien.antrian.index') }}" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary" :disabled="loading">
            Daftar Antrian
        </button>
    </div>

    <!-- Modal Konfirmasi -->
    <div x-show="showModal" style="display: none;" class="modal-backdrop" x-transition.opacity>
        <div class="modal-content" @click.away="showModal = false" x-transition>
            <div class="modal-header">
                Konfirmasi Pendaftaran
            </div>
            <div class="modal-body">
                <p style="color: #334155; line-height: 1.6;">Apakah Anda yakin keluhan dan jadwal yang Anda pilih sudah benar?</p>
                <div style="margin-top: 1rem; padding: 0.75rem; background: #fffbeb; border-left: 4px solid #f59e0b; font-size: 0.875rem; color: #92400e;">
                    <strong>Perhatian:</strong> Antrian yang sudah dibuat tidak dapat dibatalkan atau diubah jadwalnya secara mandiri.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="showModal = false" :disabled="loading">Cek Kembali</button>
                <button type="button" class="btn btn-primary" :disabled="loading" @click="loading = true; document.getElementById('antrianForm').submit()">
                    <span x-show="!loading">Ya, Daftar Sekarang</span>
                    <span x-show="loading" x-cloak style="display:flex;align-items:center;gap:0.5rem;"><div class="spinner" style="border-color:rgba(255,255,255,0.3);border-top-color:white;"></div>Memproses...</span>
                </button>
            </div>
        </div>
    </div>
</form>

<style>
@media(max-width:768px){.form-grid{grid-template-columns:1fr !important;}}
.modal-backdrop {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(4px);
    display: flex; align-items: center; justify-content: center; z-index: 1000;
}
.modal-content {
    background: white; border-radius: 0.75rem; width: 90%; max-width: 28rem;
    box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
}
.modal-header {
    padding: 1.25rem 1.5rem; font-size: 1.125rem; font-weight: 700; color: #0f172a;
    border-bottom: 1px solid #e2e8f0;
}
.modal-body {
    padding: 1.5rem;
}
.modal-footer {
    padding: 1rem 1.5rem; background: #f8fafc; border-top: 1px solid #e2e8f0;
    display: flex; justify-content: flex-end; gap: 0.75rem; border-bottom-left-radius: 0.75rem; border-bottom-right-radius: 0.75rem;
}
</style>
@endsection
