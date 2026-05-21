@extends('layouts.app')
@section('title', 'Tambah Antrian')
@section('sidebar')@include('perawat._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Tambah Antrian</h1></div>
    <a href="{{ route('perawat.antrian.index') }}" class="btn btn-secondary">← Kembali</a>
</div>

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
            <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(240px, 1fr));gap:1rem;" x-data="{
                selectedLayanan: '{{ old('layanan_id') }}',
                selectedDokter: '{{ old('dokter_id') }}',
                dokters: {{ json_encode($dokters->map(fn($d) => ['id' => $d->id, 'name' => $d->user->name, 'spesialisasi' => $d->spesialisasi, 'avatar' => $d->user->avatar_url, 'tarif' => (float)$d->tarif_konsultasi])) }},
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
                <div class="form-group" x-data="{ dropdownOpen: false }">
                    <label class="form-label">Dokter <span style="color:#ef4444;">*</span></label>
                    
                    <!-- Custom Visual Dropdown Component -->
                    <div style="position:relative;">
                        <!-- Trigger Button -->
                        <button type="button" @click="dropdownOpen = !dropdownOpen" class="form-input" style="display:flex; align-items:center; justify-content:space-between; width:100%; text-align:left; background:white; cursor:pointer; min-height:44px; padding:0.5rem 0.875rem; border:1px solid #cbd5e1; border-radius:0.375rem;">
                            <span x-show="!selectedDokter" style="color:#94a3b8;">-- Pilih Dokter --</span>
                            <template x-if="selectedDokter">
                                <div style="display:flex; align-items:center; gap:0.625rem;">
                                    <img :src="dokters.find(d => d.id == selectedDokter)?.avatar" style="width:1.75rem; height:1.75rem; border-radius:50%; object-fit:cover; border:1px solid #bfdbfe;">
                                    <div>
                                        <span style="font-weight:600; color:#1e293b;" x-text="dokters.find(d => d.id == selectedDokter)?.name"></span>
                                        <span style="font-size:0.75rem; color:#64748b; margin-left:0.25rem;" x-text="'— ' + dokters.find(d => d.id == selectedDokter)?.spesialisasi"></span>
                                    </div>
                                </div>
                            </template>
                            <svg style="width:1rem; height:1rem; color:#64748b;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <!-- Hidden Input for Laravel Submission -->
                        <input type="hidden" name="dokter_id" :value="selectedDokter" required>

                        <!-- Options Dropdown Card -->
                        <div x-show="dropdownOpen" @click.outside="dropdownOpen = false" x-cloak x-transition.opacity style="position:absolute; top:108%; left:0; width:100%; background:white; border:1px solid #cbd5e1; border-radius:0.5rem; box-shadow:0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); z-index:100; max-height:240px; overflow-y:auto; padding:0.25rem;">
                            <template x-for="d in filteredDokters" :key="d.id">
                                <div @click="selectedDokter = d.id; dropdownOpen = false;" style="display:flex; align-items:center; gap:0.75rem; padding:0.625rem 0.875rem; border-radius:0.375rem; cursor:pointer; transition:background 0.2s;" class="dropdown-item" onmouseover="this.style.background='#eff6ff'" onmouseout="this.style.background=''">
                                    <img :src="d.avatar" style="width:2.25rem; height:2.25rem; border-radius:50%; object-fit:cover; border:1px solid #93c5fd;">
                                    <div style="flex:1;">
                                        <div style="font-weight:600; color:#1e293b; font-size:0.875rem;" x-text="d.name"></div>
                                        <div style="font-size:0.75rem; color:#64748b;" x-text="d.spesialisasi"></div>
                                    </div>
                                    <div style="font-size:0.75rem; font-weight:700; color:#10b981;" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(d.tarif)"></div>
                                </div>
                            </template>
                            <template x-if="filteredDokters.length === 0">
                                <div style="padding:1rem; text-align:center; color:#94a3b8; font-size:0.875rem; font-style:italic;">Tidak ada dokter tersedia untuk layanan ini.</div>
                            </template>
                        </div>
                    </div>
                    @error('dokter_id')<div class="form-error">{{ $message }}</div>@enderror

                    {{-- Dokter info card --}}
                    <template x-if="selectedDokter">
                        <div style="margin-top:0.75rem;padding:0.875rem;border:1px solid #bfdbfe;border-radius:0.625rem;background:#eff6ff;display:flex;align-items:center;gap:0.875rem;" class="dokter-info-card">
                            <div style="display:flex;align-items:center;gap:0.875rem;">
                                <img :src="dokters.find(d => d.id == selectedDokter)?.avatar" style="width:3rem;height:3rem;border-radius:50%;object-fit:cover;border:2px solid #93c5fd;" :alt="dokters.find(d => d.id == selectedDokter)?.name">
                                <div>
                                    <div style="font-weight:700;color:#1e3a8a;" x-text="dokters.find(d => d.id == selectedDokter)?.name"></div>
                                    <div style="font-size:0.8125rem;color:#2563eb;" x-text="dokters.find(d => d.id == selectedDokter)?.spesialisasi"></div>
                                    <div style="font-size:0.75rem;color:#64748b;margin-top:0.25rem;" x-text="'Tarif Konsultasi: Rp ' + new Intl.NumberFormat('id-ID').format(dokters.find(d => d.id == selectedDokter)?.tarif || 0)"></div>
                                </div>
                            </div>
                        </div>
                    </template>
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
