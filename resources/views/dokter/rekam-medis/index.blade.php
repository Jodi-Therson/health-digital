@extends('layouts.app')
@section('title', 'Rekam Medis Pasien')
@section('sidebar')@include('dokter._sidebar')@endsection
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Rekam Medis Pasien</h1><p class="page-subtitle">Riwayat pemeriksaan seluruh pasien yang pernah Anda tangani</p></div>
    <a href="{{ route('dokter.rekam-medis.create') }}" class="btn btn-primary">
        <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Buat Rekam Medis
    </a>
</div>

{{-- Search bar real-time --}}
<div class="card" style="margin-bottom:1rem;">
    <div class="card-body" style="padding:1rem;">
        <div style="position:relative;max-width:400px;">
            <svg style="position:absolute;left:0.75rem;top:50%;transform:translateY(-50%);width:1rem;height:1rem;color:#94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" id="searchInput" value="{{ request('search') }}"
                   class="form-input" style="padding-left:2.5rem;"
                   placeholder="Cari nama pasien atau NIK..."
                   autocomplete="off">
        </div>
    </div>
</div>

{{-- Grouped by patient --}}
<div id="rm-list">
    @if($pasiens->isEmpty())
    <div class="card"><div class="empty-state"><div style="color:#94a3b8;">Tidak ada rekam medis</div></div></div>
    @else
    @foreach($pasiens as $pasienData)
    <div class="card rm-patient-group" style="margin-bottom:1rem;"
         data-name="{{ strtolower($pasienData['pasien']->user->name) }}"
         data-nik="{{ $pasienData['pasien']->nik }}">
        {{-- Patient header --}}
        <div style="padding:1rem 1.5rem;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #f1f5f9;cursor:pointer;"
             onclick="toggleTimeline(this)">
            <div style="display:flex;align-items:center;gap:0.875rem;">
                <img src="{{ $pasienData['pasien']->user->avatar_url }}" style="width:2.75rem;height:2.75rem;border-radius:50%;object-fit:cover;border:2px solid #bfdbfe;">
                <div>
                    <div style="font-weight:700;color:#1e293b;">{{ $pasienData['pasien']->user->name }}</div>
                    <div style="font-size:0.8125rem;color:#64748b;">NIK: {{ $pasienData['pasien']->nik }} · {{ count($pasienData['rekamMedis']) }} kunjungan</div>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:0.75rem;">
                <span style="font-size:0.8125rem;color:#94a3b8;">Terakhir: {{ $pasienData['rekamMedis']->first()->tanggal_periksa->format('d M Y') }}</span>
                <svg class="toggle-icon" style="width:1.25rem;height:1.25rem;color:#94a3b8;transition:transform 0.2s;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </div>
        </div>
        {{-- Timeline kunjungan --}}
        <div class="rm-timeline" style="display:none;">
            @foreach($pasienData['rekamMedis'] as $i => $rm)
            <div style="display:flex;align-items:flex-start;gap:0;padding:0.875rem 1.5rem 0.875rem 1.5rem;border-bottom:1px solid #f8fafc;{{ $i===0?'background:#f8fafc;':'' }}transition:background 0.15s;"
                 onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='{{ $i===0?'#f8fafc':'white' }}'">
                {{-- Timeline dot --}}
                <div style="display:flex;flex-direction:column;align-items:center;margin-right:1rem;flex-shrink:0;">
                    <div style="width:0.75rem;height:0.75rem;border-radius:50%;background:{{ $i===0?'#2563eb':'#cbd5e1' }};border:2px solid {{ $i===0?'#93c5fd':'#e2e8f0' }};margin-top:0.25rem;"></div>
                    @if(!$loop->last)<div style="width:1px;height:2.5rem;background:#e2e8f0;margin:0.25rem 0;"></div>@endif
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.5rem;">
                        <div>
                            <div style="font-size:0.8125rem;color:#94a3b8;font-weight:500;">{{ $rm->tanggal_periksa->format('d F Y') }}{{ $i===0?' (Terbaru)':'' }}</div>
                            <div style="font-weight:600;color:#1e293b;margin-top:0.125rem;">{{ Str::limit($rm->diagnosa, 60) }}</div>
                            @if($rm->antrian && $rm->antrian->layanan)
                            <div style="font-size:0.75rem;color:#64748b;margin-top:0.125rem;">{{ $rm->antrian->layanan->nama }}</div>
                            @endif
                        </div>
                        <div style="display:flex;gap:0.5rem;flex-shrink:0;">
                            <a href="{{ route('dokter.rekam-medis.show', $rm->id) }}" class="btn btn-secondary btn-sm">Detail</a>
                            <a href="{{ route('dokter.rekam-medis.edit', $rm->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
    @endif
</div>

<script>
function toggleTimeline(header) {
    const card = header.closest('.rm-patient-group');
    const timeline = card.querySelector('.rm-timeline');
    const icon = header.querySelector('.toggle-icon');
    const isOpen = timeline.style.display !== 'none';
    timeline.style.display = isOpen ? 'none' : 'block';
    icon.style.transform = isOpen ? '' : 'rotate(180deg)';
}

// Real-time search filter
document.getElementById('searchInput').addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.rm-patient-group').forEach(group => {
        const name = group.dataset.name || '';
        const nik  = group.dataset.nik  || '';
        const match = name.includes(q) || nik.includes(q);
        group.style.display = match ? '' : 'none';
    });
});

// Auto-open first group
const firstGroup = document.querySelector('.rm-patient-group');
if (firstGroup) {
    const header = firstGroup.querySelector('[onclick]');
    toggleTimeline(header);
}

// If search param exists, expand all matches
const initQ = '{{ request('search') }}'.toLowerCase();
if (initQ) {
    document.getElementById('searchInput').dispatchEvent(new Event('input'));
}
</script>
@endsection
