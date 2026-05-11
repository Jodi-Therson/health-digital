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
        <form method="POST" action="{{ route('pasien.konsultasi.store') }}"
              x-data="{
                loading: false,
                dokterId: '{{ old('dokter_id') }}',
                charCount: {{ strlen(old('pesan','')) }},
                dupAlert: false,
                dupNama: '',
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
              @submit="loading=true">
            @csrf

            {{-- Pilih Dokter --}}
            <div class="form-group">
                <label class="form-label">Pilih Dokter <span style="color:#ef4444;">*</span></label>
                <select name="dokter_id" id="dokter_id" class="form-input {{ $errors->has('dokter_id')?'error':'' }}"
                        x-model="dokterId" @change="checkDup()" required>
                    <option value="">-- Pilih Dokter --</option>
                    @foreach($dokters as $d)
                    <option value="{{ $d->id }}" {{ old('dokter_id')==$d->id?'selected':'' }}>
                        {{ $d->user->name }} — {{ $d->spesialisasi }}
                    </option>
                    @endforeach
                </select>
                @error('dokter_id')<div class="form-error">{{ $message }}</div>@enderror

                {{-- Dokter info card --}}
                @foreach($dokters as $d)
                <div id="info-dokter-{{ $d->id }}" style="display:none;margin-top:0.75rem;padding:0.875rem;border:1px solid #bfdbfe;border-radius:0.625rem;background:#eff6ff;gap:0.875rem;" class="dokter-info-card">
                    <div style="display:flex;align-items:center;gap:0.875rem;">
                        <img src="{{ $d->user->avatar_url }}" style="width:3rem;height:3rem;border-radius:50%;object-fit:cover;border:2px solid #93c5fd;" alt="{{ $d->user->name }}">
                        <div>
                            <div style="font-weight:700;color:#1e3a8a;">{{ $d->user->name }}</div>
                            <div style="font-size:0.8125rem;color:#2563eb;">{{ $d->spesialisasi }}</div>
                            @if($d->tarif_konsultasi)
                            <div style="font-size:0.75rem;color:#64748b;margin-top:0.25rem;">Tarif konsultasi: Rp {{ number_format($d->tarif_konsultasi,0,',','.') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach

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
                <a href="{{ route('pasien.konsultasi.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary" :disabled="loading || dupAlert || charCount < 20">
                    <span x-show="!loading" style="display:flex;align-items:center;gap:0.5rem;">
                        <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Kirim Pertanyaan
                    </span>
                    <span x-show="loading" x-cloak style="display:flex;align-items:center;gap:0.5rem;"><div class="spinner" style="border-color:rgba(255,255,255,0.3);border-top-color:white;"></div>Mengirim...</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('dokter_id');
    function showDokterInfo(val) {
        document.querySelectorAll('.dokter-info-card').forEach(el => el.style.display = 'none');
        if (val) {
            const card = document.getElementById('info-dokter-' + val);
            if (card) card.style.display = 'flex';
        }
    }
    if (select) {
        select.addEventListener('change', () => showDokterInfo(select.value));
        showDokterInfo(select.value); // init on load (old value)
    }
    // init char count
    const textarea = document.querySelector('textarea[name=pesan]');
    if (textarea) {
        // Alpine will handle reactivity; just sync on load
    }
});
</script>
@endsection
