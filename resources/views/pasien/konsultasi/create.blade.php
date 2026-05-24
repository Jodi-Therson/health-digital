@extends('layouts.app')
@section('title', 'Konsultasi Baru')
@section('sidebar')@include('pasien._sidebar')@endsection
@section('breadcrumb')
<a href="{{ route('pasien.dashboard') }}">Dashboard</a><span>/</span><a href="{{ route('pasien.konsultasi.index') }}">Konsultasi</a><span>/</span><span>Baru</span>
@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Konsultasi Baru</h1><p class="page-subtitle">Ajukan pertanyaan kepada dokter pilihan Anda</p></div>
    <a href="{{ route('pasien.konsultasi.index') }}" class="btn btn-secondary">← Kembali</a>
</div>


<div class="card" style="max-width:680px;">
    <div class="card-header" style="display:flex;align-items:center;gap:0.75rem;">
        <div style="background:#dbeafe;width:2rem;height:2rem;border-radius:0.5rem;display:flex;align-items:center;justify-content:center;">
            <svg style="width:1rem;height:1rem;color:#2563eb;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        </div>
        <span>Form Konsultasi</span>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('pasien.konsultasi.store') }}" x-ref="konsultasiForm"
              x-data="{
                loading: false,
                selectedLayanan: '{{ old('layanan_id') }}',
                dokterId: '{{ old('dokter_id') }}',
                charCount: {{ strlen(old('pesan','')) }},
                dupAlert: false,
                dupNama: '',
                dokters: {{ json_encode($dokters->map(fn($d) => ['id' => $d->id, 'name' => $d->user->name, 'spesialisasi' => $d->spesialisasi, 'avatar' => $d->user->avatar_url, 'tarif' => (float)$d->tarif_konsultasi])) }},
                layanans: {{ json_encode($layanans->map(fn($l) => ['id' => $l->id, 'nama' => strtolower($l->nama)])) }},
                get filteredDokters() {
                    if(!this.selectedLayanan) return [];
                    const lay = this.layanans.find(l => l.id == this.selectedLayanan);
                    if(!lay) return [];
                    return this.dokters.filter(d => d.spesialisasi.toLowerCase().includes(lay.nama));
                },
                checkDup() {
                    if (!this.dokterId) { this.dupAlert = false; return; }
                    fetch('/pasien/konsultasi/cek-duplikat?dokter_id=' + this.dokterId)
                        .then(r => r.json())
                        .then(data => {
                            this.dupAlert = data.ada;
                            this.dupNama = data.nama_dokter;
                        });
                }
              }"
              x-init="$watch('selectedLayanan', value => { dokterId = ''; dupAlert = false }); if(dokterId) { checkDup(); }"
              @submit="loading=true">
            @csrf

            {{-- Pilih Layanan --}}
            <div class="form-group">
                <label class="form-label">Pilih Layanan <span style="color:#ef4444;">*</span></label>
                <select name="layanan_id" class="form-input {{ $errors->has('layanan_id')?'error':'' }}" x-model="selectedLayanan" required>
                    <option value="">-- Pilih Layanan --</option>
                    @foreach($layanans as $l)
                    <option value="{{ $l->id }}">Poli {{ $l->nama }}</option>
                    @endforeach
                </select>
                @error('layanan_id')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            {{-- Pilih Dokter --}}
            <div class="form-group" x-data="{ dropdownOpen: false }">
                <label class="form-label">Pilih Dokter <span style="color:#ef4444;">*</span></label>
                
                <!-- Custom Visual Dropdown Component -->
                <div style="position:relative;">
                    <!-- Trigger Button -->
                    <button type="button" @click="dropdownOpen = !dropdownOpen" class="form-input" style="display:flex; align-items:center; justify-content:space-between; width:100%; text-align:left; background:white; cursor:pointer; min-height:44px; padding:0.5rem 0.875rem; border:1px solid #cbd5e1; border-radius:0.375rem;">
                        <span x-show="!dokterId" style="color:#94a3b8;">-- Pilih Dokter --</span>
                        <template x-if="dokterId">
                            <div style="display:flex; align-items:center; gap:0.625rem;">
                                <img :src="dokters.find(d => d.id == dokterId)?.avatar" style="width:1.75rem; height:1.75rem; border-radius:50%; object-fit:cover; border:1px solid #bfdbfe;">
                                <div>
                                    <span style="font-weight:600; color:#1e293b;" x-text="dokters.find(d => d.id == dokterId)?.name"></span>
                                    <span style="font-size:0.75rem; color:#64748b; margin-left:0.25rem;" x-text="'— ' + dokters.find(d => d.id == dokterId)?.spesialisasi"></span>
                                </div>
                            </div>
                        </template>
                        <svg style="width:1rem; height:1rem; color:#64748b;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <!-- Hidden Input for Laravel Submission -->
                    <input type="hidden" name="dokter_id" :value="dokterId" required>

                    <!-- Options Dropdown Card -->
                    <div x-show="dropdownOpen" @click.outside="dropdownOpen = false" x-cloak x-transition.opacity style="position:absolute; top:108%; left:0; width:100%; background:white; border:1px solid #cbd5e1; border-radius:0.5rem; box-shadow:0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); z-index:100; max-height:240px; overflow-y:auto; padding:0.25rem;">
                        <template x-for="d in filteredDokters" :key="d.id">
                            <div @click="dokterId = d.id; dropdownOpen = false; checkDup();" style="display:flex; align-items:center; gap:0.75rem; padding:0.625rem 0.875rem; border-radius:0.375rem; cursor:pointer; transition:background 0.2s;" class="dropdown-item" onmouseover="this.style.background='#eff6ff'" onmouseout="this.style.background=''">
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
                <template x-if="dokterId">
                    <div style="margin-top:0.75rem;padding:0.875rem;border:1px solid #bfdbfe;border-radius:0.625rem;background:#eff6ff;display:flex;align-items:center;gap:0.875rem;" class="dokter-info-card">
                        <div style="display:flex;align-items:center;gap:0.875rem;">
                            <img :src="dokters.find(d => d.id == dokterId)?.avatar" style="width:3rem;height:3rem;border-radius:50%;object-fit:cover;border:2px solid #93c5fd;" :alt="dokters.find(d => d.id == dokterId)?.name">
                            <div>
                                <div style="font-weight:700;color:#1e3a8a;" x-text="dokters.find(d => d.id == dokterId)?.name"></div>
                                <div style="font-size:0.8125rem;color:#2563eb;" x-text="dokters.find(d => d.id == dokterId)?.spesialisasi"></div>
                                <div style="font-size:0.75rem;color:#64748b;margin-top:0.25rem;" x-text="'Tarif Konsultasi: Rp ' + new Intl.NumberFormat('id-ID').format(dokters.find(d => d.id == dokterId)?.tarif || 0)"></div>
                            </div>
                        </div>
                    </div>
                </template>

                {{-- Duplicate alert --}}
                <div x-show="dupAlert" x-cloak style="margin-top:0.75rem;padding:0.875rem 1rem;background:#fffbeb;border:1px solid #fcd34d;border-radius:0.625rem;display:flex;align-items:flex-start;gap:0.75rem;">
                    <svg style="width:1.25rem;height:1.25rem;color:#d97706;flex-shrink:0;margin-top:0.125rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <div style="font-size:0.875rem;color:#92400e;">
                        <strong>Konsultasi aktif ditemukan.</strong><br>
                        <span x-text="'Anda masih memiliki konsultasi aktif dengan ' + dupNama + '. Tunggu balasan terlebih dahulu.'"></span>
                    </div>
                </div>
            </div>

            {{-- Judul --}}
            <div class="form-group">
                <label class="form-label">Judul Pertanyaan <span style="color:#ef4444;">*</span></label>
                <input type="text" name="judul" value="{{ old('judul') }}"
                       class="form-input {{ $errors->has('judul')?'error':'' }}"
                       placeholder="Contoh: Nyeri dada saat beraktivitas" required>
                @error('judul')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            {{-- Pesan --}}
            <div class="form-group">
                <label class="form-label">Detail Keluhan <span style="color:#ef4444;">*</span></label>
                <textarea name="pesan" rows="7"
                          class="form-input {{ $errors->has('pesan')?'error':'' }}"
                          placeholder="Ceritakan keluhan Anda secara lengkap: gejala, durasi, riwayat kesehatan..."
                          @input="charCount = $event.target.value.length"
                          required>{{ old('pesan') }}</textarea>
                <div style="display:flex;justify-content:space-between;margin-top:0.375rem;">
                    <div class="form-hint">Minimal 20 karakter. Sertakan gejala, durasi, dan riwayat kesehatan.</div>
                    <div style="font-size:0.8125rem;" :style="charCount < 20 ? 'color:#ef4444;' : 'color:#10b981;'">
                        <span x-text="charCount"></span> karakter
                    </div>
                </div>
                @error('pesan')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div style="display:flex;gap:1rem;justify-content:flex-end;">
                <button type="button" class="btn btn-primary" :disabled="loading || dupAlert || charCount < 20"
                        style="display:inline-flex !important; flex-direction:row !important; align-items:center !important; gap:0.5rem !important; justify-content:center !important;"
                        @click="triggerConfirm(
                            'Konfirmasi Kirim Pertanyaan',
                            'Kirim pertanyaan konsultasi ini? Setelah dikirim, Anda perlu menunggu balasan dokter sebelum dapat mengirim pesan tambahan.',
                            () => { loading = true; $refs.konsultasiForm.submit() },
                            'primary'
                        )">
                    <svg x-show="!loading" style="width:1rem;height:1rem;display:inline-block;vertical-align:middle;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    <span x-show="loading" x-cloak class="spinner" style="border-color:rgba(255,255,255,0.3); border-top-color:white; width:1.25rem; height:1.25rem; display:inline-block; vertical-align:middle;"></span>
                    <span x-text="loading ? 'Mengirim...' : 'Kirim Pertanyaan'" style="display:inline-block; vertical-align:middle;"></span>
                </button>
            </div>
        </form>
    </div>
</div>


@endsection
