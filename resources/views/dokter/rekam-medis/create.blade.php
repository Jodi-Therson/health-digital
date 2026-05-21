@extends('layouts.app')
@section('title', 'Buat Rekam Medis')
@section('sidebar')@include('dokter._sidebar')@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Buat Rekam Medis</h1>
        <div id="draft-indicator" style="font-size:0.75rem;color:#64748b;margin-top:0.25rem;display:flex;align-items:center;gap:0.375rem;">
            <svg style="width:0.875rem;height:0.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Draft kosong</span>
        </div>
    </div>
    <a href="{{ route('dokter.rekam-medis.index') }}" class="btn btn-secondary">← Kembali</a>
</div>


<form id="rmForm" method="POST" action="{{ route('dokter.rekam-medis.store') }}" x-data="{loading:false, showConfirm:false, resep:[{obat:'',dosis:'',aturan:''}]}" @submit.prevent>
    @csrf
    @if($antrian)
    <input type="hidden" name="antrian_id" value="{{ $antrian->id }}">
    <input type="hidden" name="pasien_id" value="{{ $antrian->pasien->id }}">
    @else
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem;" class="form-grid">
        <div class="form-group">
            <label class="form-label">Antrian ID <span style="color:#ef4444;">*</span></label>
            <input type="text" name="antrian_id" value="{{ old('antrian_id') }}" class="form-input" placeholder="ID Antrian" required>
        </div>
        <div class="form-group">
            <label class="form-label">Pasien <span style="color:#ef4444;">*</span></label>
            <select name="pasien_id" class="form-input" required>
                <option value="">-- Pilih Pasien --</option>
                @foreach($pasiens as $p)
                <option value="{{ $p->id }}" {{ old('pasien_id')==$p->id?'selected':'' }}>{{ $p->user->name }} ({{ $p->nik }})</option>
                @endforeach
            </select>
        </div>
    </div>
    @endif

    @if($antrian)
    <div class="card" style="background:#f0fdf4;border:1px solid #bbf7d0;margin-bottom:1.5rem;">
        <div class="card-body" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(140px, 1fr));gap:1rem;">
            <div><div style="font-size:0.75rem;color:#64748b;font-weight:600;">Pasien</div><div style="font-weight:700;color:#1e293b;">{{ $antrian->pasien->user->name }}</div></div>
            <div><div style="font-size:0.75rem;color:#64748b;font-weight:600;">No. Antrian</div><div style="font-weight:700;color:#2563eb;font-family:monospace;">{{ $antrian->no_antrian }}</div></div>
            <div><div style="font-size:0.75rem;color:#64748b;font-weight:600;">Layanan</div><div style="font-weight:700;">{{ $antrian->layanan->nama }}</div></div>
            <div><div style="font-size:0.75rem;color:#64748b;font-weight:600;">Keluhan</div><div style="font-size:0.875rem;color:#475569;">{{ Str::limit($antrian->keluhan, 60) }}</div></div>
        </div>
    </div>
    @endif

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;" class="form-grid">
        <div class="card">
            <div class="card-header">Vital Signs</div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Tanggal Periksa</label>
                    <input type="date" name="tanggal_periksa" value="{{ old('tanggal_periksa', date('Y-m-d')) }}" class="form-input" required>
                </div>
                <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(140px, 1fr));gap:0.75rem;">
                    <div class="form-group">
                        <label class="form-label">Tekanan Darah</label>
                        <input type="text" name="tekanan_darah" value="{{ old('tekanan_darah', $antrian ? $antrian->tekanan_darah : '') }}" class="form-input" placeholder="120/80">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Suhu (°C)</label>
                        <input type="number" name="suhu_tubuh" value="{{ old('suhu_tubuh', $antrian ? $antrian->suhu_tubuh : '') }}" class="form-input" placeholder="36.5" step="0.1" min="30" max="45">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Berat (kg)</label>
                        <input type="number" name="berat_badan" value="{{ old('berat_badan', $antrian ? $antrian->berat_badan : '') }}" class="form-input" placeholder="65" step="0.1">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tinggi (cm)</label>
                        <input type="number" name="tinggi_badan" value="{{ old('tinggi_badan', $antrian ? $antrian->tinggi_badan : '') }}" class="form-input" placeholder="165" step="0.1">
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">Pemeriksaan</div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Anamnesis (Keluhan Detail)</label>
                    <textarea name="anamnesis" rows="3" class="form-input" placeholder="Keluhan yang disampaikan pasien...">{{ old('anamnesis') }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Diagnosa <span style="color:#ef4444;">*</span></label>
                    <textarea name="diagnosa" rows="2" class="form-input {{ $errors->has('diagnosa')?'error':'' }}" placeholder="Diagnosa (ICD-10)..." required>{{ old('diagnosa') }}</textarea>
                    @error('diagnosa')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Tindakan yang Dilakukan</label>
                    <textarea name="tindakan" rows="2" class="form-input" placeholder="Tindakan medis yang diberikan...">{{ old('tindakan') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Resep -->
    <div class="card" style="margin-top:1.5rem;">
        <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
            <span>Resep Obat</span>
            <button type="button" @click="resep.push({obat:'',dosis:'',aturan:''})" class="btn btn-secondary btn-sm">+ Tambah Obat</button>
        </div>
        <div class="card-body">
            <template x-for="(r, i) in resep" :key="i">
                <div class="prescription-grid">
                    <div>
                        <label class="form-label" style="font-size:0.75rem;">Nama Obat</label>
                        <input type="text" :name="'resep_obat['+i+']'" x-model="r.obat" class="form-input" placeholder="Nama obat">
                    </div>
                    <div>
                        <label class="form-label" style="font-size:0.75rem;">Dosis</label>
                        <input type="text" :name="'resep_dosis['+i+']'" x-model="r.dosis" class="form-input" placeholder="500mg">
                    </div>
                    <div>
                        <label class="form-label" style="font-size:0.75rem;">Aturan Pakai</label>
                        <input type="text" :name="'resep_aturan['+i+']'" x-model="r.aturan" class="form-input" placeholder="3x sehari sesudah makan">
                    </div>
                    <div>
                        <button type="button" @click="resep.splice(i,1)" class="btn btn-danger btn-sm" x-show="resep.length > 1">×</button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Catatan -->
    <div class="card" style="margin-top:1rem;">
        <div class="card-body">
            <div class="form-group" style="margin:0;">
                <label class="form-label">Catatan Tambahan</label>
                <textarea name="catatan" rows="2" class="form-input" placeholder="Catatan atau saran untuk pasien...">{{ old('catatan') }}</textarea>
            </div>
        </div>
    </div>

    <div style="display:flex;gap:1rem;justify-content:flex-end;margin-top:1.5rem;">
        <a href="{{ route('dokter.rekam-medis.index') }}" class="btn btn-secondary">Batal</a>
        <button type="button" class="btn btn-primary" @click="showConfirm = true" :disabled="loading">
            <span x-show="!loading">Selesaikan Pemeriksaan</span>
            <span x-show="loading" x-cloak style="display:flex;align-items:center;gap:0.5rem;"><span class="spinner" style="border-color:rgba(255,255,255,0.3);border-top-color:white;"></span>Menyimpan...</span>
        </button>
    </div>

    <!-- Modal Konfirmasi -->
    <div x-show="showConfirm" class="modal-backdrop" x-cloak>
        <div class="modal" @click.away="showConfirm = false">
            <div style="padding:1.5rem;">
                <h3 style="font-size:1.125rem;font-weight:700;margin-bottom:0.5rem;color:#0f172a;">Konfirmasi Selesai</h3>
                <p style="font-size:0.875rem;color:#475569;margin-bottom:1.5rem;">Selesaikan pemeriksaan untuk <strong>{{ $antrian ? $antrian->pasien->user->name : 'Pasien' }}</strong>? Data rekam medis akan dikunci.</p>
                <div style="display:flex;gap:0.75rem;justify-content:flex-end;">
                    <button type="button" class="btn btn-secondary" @click="showConfirm = false">Batal</button>
                    <button type="button" class="btn btn-primary" @click="showConfirm = false; loading = true; $event.target.closest('form').submit();">Konfirmasi & Selesai</button>
                </div>
            </div>
        </div>
    </div>
</form>
<style>@media(max-width:768px){.form-grid{grid-template-columns:1fr !important;}}</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const draftKey = 'rm_draft_{{ $antrian ? $antrian->id : "new" }}';
    const form = document.getElementById('rmForm');
    if(!form) return;

    // Load draft
    const saved = localStorage.getItem(draftKey);
    if(saved) {
        try {
            const draft = JSON.parse(saved);
            Object.keys(draft).forEach(key => {
                const el = form.elements[key];
                if(el && !['antrian_id', 'pasien_id', '_token'].includes(key)) {
                    el.value = draft[key];
                }
            });
            document.getElementById('draft-indicator').querySelector('span').innerText = 'Draft termuat dari kunjungan sebelumnya';
        } catch(e) {}
    }

    // Auto-save every 30 seconds
    setInterval(() => {
        const formData = new FormData(form);
        const draft = {};
        formData.forEach((val, key) => {
            if(!key.startsWith('_')) draft[key] = val;
        });
        localStorage.setItem(draftKey, JSON.stringify(draft));
        
        const now = new Date();
        const time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        document.getElementById('draft-indicator').querySelector('span').innerText = 'Tersimpan otomatis pukul ' + time;
    }, 30000);

    // Clear draft on submit
    form.addEventListener('submit', () => {
        localStorage.removeItem(draftKey);
    });
});
</script>
@endsection
