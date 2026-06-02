@extends('layouts.app')
@section('title', 'Antrian Pasien')
@section('sidebar')@include('dokter._sidebar')@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Antrian Hari Ini</h1>
        <p class="page-subtitle" style="font-size:0.875rem; color:#64748b; margin-top:0.25rem; font-weight:500;">
            {{ \Carbon\Carbon::today()->translatedFormat('l, d F Y') }}
        </p>
    </div>
</div>

<style>
    .filter-card {
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .filter-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
        border-color: #cbd5e1 !important;
    }
    .status-cards-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    @media(max-width: 1024px) {
        .status-cards-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    @media(max-width: 640px) {
        .status-cards-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media(max-width: 480px) {
        .status-cards-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="status-cards-grid">
    {{-- Card Semua --}}
    <a href="{{ route('dokter.antrian.index') }}" 
       style="text-decoration: none; color: inherit; display: block;">
        <div style="background: white; padding: 1rem; border-radius: 0.75rem; border: 2px solid {{ !request('status') ? '#2563eb' : '#f1f5f9' }}; box-shadow: {{ !request('status') ? '0 10px 15px -3px rgba(37, 99, 235, 0.08), 0 4px 6px -4px rgba(37, 99, 235, 0.08)' : '0 1px 3px 0 rgba(0,0,0,0.05)' }}; display: flex; align-items: center; justify-content: space-between;"
             class="filter-card">
            <div>
                <span style="font-size: 0.8125rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Semua</span>
                <div style="font-size: 1.5rem; font-weight: 800; color: #1e293b; margin-top: 0.25rem;">{{ $counts['semua'] }}</div>
            </div>
            <div style="background: #eff6ff; color: #2563eb; width: 2.75rem; height: 2.75rem; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            </div>
        </div>
    </a>

    {{-- Card Menunggu --}}
    <a href="{{ route('dokter.antrian.index', ['status' => 'menunggu']) }}" 
       style="text-decoration: none; color: inherit; display: block;">
        <div style="background: white; padding: 1rem; border-radius: 0.75rem; border: 2px solid {{ request('status') === 'menunggu' ? '#f59e0b' : '#f1f5f9' }}; box-shadow: {{ request('status') === 'menunggu' ? '0 10px 15px -3px rgba(245, 158, 11, 0.08), 0 4px 6px -4px rgba(245, 158, 11, 0.08)' : '0 1px 3px 0 rgba(0,0,0,0.05)' }}; display: flex; align-items: center; justify-content: space-between;"
             class="filter-card">
            <div>
                <span style="font-size: 0.8125rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Menunggu</span>
                <div style="font-size: 1.5rem; font-weight: 800; color: #1e293b; margin-top: 0.25rem;">{{ $counts['menunggu'] }}</div>
            </div>
            <div style="background: #fffbeb; color: #f59e0b; width: 2.75rem; height: 2.75rem; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </a>

    {{-- Card Dipanggil --}}
    <a href="{{ route('dokter.antrian.index', ['status' => 'dipanggil']) }}" 
       style="text-decoration: none; color: inherit; display: block;">
        <div style="background: white; padding: 1rem; border-radius: 0.75rem; border: 2px solid {{ request('status') === 'dipanggil' ? '#3b82f6' : '#f1f5f9' }}; box-shadow: {{ request('status') === 'dipanggil' ? '0 10px 15px -3px rgba(59, 130, 246, 0.08), 0 4px 6px -4px rgba(59, 130, 246, 0.08)' : '0 1px 3px 0 rgba(0,0,0,0.05)' }}; display: flex; align-items: center; justify-content: space-between;"
             class="filter-card">
            <div>
                <span style="font-size: 0.8125rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Dipanggil</span>
                <div style="font-size: 1.5rem; font-weight: 800; color: #1e293b; margin-top: 0.25rem;">{{ $counts['dipanggil'] }}</div>
            </div>
            <div style="background: #eff6ff; color: #3b82f6; width: 2.75rem; height: 2.75rem; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
            </div>
        </div>
    </a>

    {{-- Card Selesai --}}
    <a href="{{ route('dokter.antrian.index', ['status' => 'selesai']) }}" 
       style="text-decoration: none; color: inherit; display: block;">
        <div style="background: white; padding: 1rem; border-radius: 0.75rem; border: 2px solid {{ request('status') === 'selesai' ? '#10b981' : '#f1f5f9' }}; box-shadow: {{ request('status') === 'selesai' ? '0 10px 15px -3px rgba(16, 185, 129, 0.08), 0 4px 6px -4px rgba(16, 185, 129, 0.08)' : '0 1px 3px 0 rgba(0,0,0,0.05)' }}; display: flex; align-items: center; justify-content: space-between;"
             class="filter-card">
            <div>
                <span style="font-size: 0.8125rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Selesai</span>
                <div style="font-size: 1.5rem; font-weight: 800; color: #1e293b; margin-top: 0.25rem;">{{ $counts['selesai'] }}</div>
            </div>
            <div style="background: #ecfdf5; color: #10b981; width: 2.75rem; height: 2.75rem; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </a>

    {{-- Card Batal --}}
    <a href="{{ route('dokter.antrian.index', ['status' => 'batal']) }}" 
       style="text-decoration: none; color: inherit; display: block;">
        <div style="background: white; padding: 1rem; border-radius: 0.75rem; border: 2px solid {{ request('status') === 'batal' ? '#ef4444' : '#f1f5f9' }}; box-shadow: {{ request('status') === 'batal' ? '0 10px 15px -3px rgba(239, 68, 68, 0.08), 0 4px 6px -4px rgba(239, 68, 68, 0.08)' : '0 1px 3px 0 rgba(0,0,0,0.05)' }}; display: flex; align-items: center; justify-content: space-between;"
             class="filter-card">
            <div>
                <span style="font-size: 0.8125rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Batal</span>
                <div style="font-size: 1.5rem; font-weight: 800; color: #1e293b; margin-top: 0.25rem;">{{ $counts['batal'] }}</div>
            </div>
            <div style="background: #fef2f2; color: #ef4444; width: 2.75rem; height: 2.75rem; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </a>
</div>
<div class="card">
    @if($antrians->isEmpty())
    <div class="empty-state"><div style="color:#94a3b8;">Tidak ada antrian untuk filter ini</div></div>
    @else
    <div class="table-container" style="border:none;">
        <table class="data-table">
            <thead><tr><th>No</th><th>Pasien</th><th>Layanan</th><th>Keluhan</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($antrians as $a)
                <tr>
                    <td><span style="font-family:monospace;font-weight:700;color:#2563eb;">{{ $a->no_antrian }}</span></td>
                    <td>
                        <div style="font-weight:600;">{{ $a->pasien->user->name }}</div>
                        <div style="font-size:0.75rem;color:#94a3b8;">{{ $a->pasien->nik }}</div>
                    </td>
                    <td>{{ $a->layanan->nama }}</td>
                    <td style="max-width:200px;">{{ Str::limit($a->keluhan, 50) }}</td>
                    <td><span class="badge badge-{{ $a->status_badge_color }} {{ $a->status==='dipanggil'?'badge-dipanggil':'' }}">{{ $a->status_label }}</span></td>
                    <td>
                        <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                            @if($a->status === 'menunggu')
                            <form method="POST" action="{{ route('dokter.antrian.status', $a->id) }}" x-ref="panggilDokter{{ $a->id }}">@csrf @method('PATCH')<input type="hidden" name="status" value="dipanggil">
                                <button type="button"
                                    @click="triggerConfirm(
                                        'Panggil Pasien',
                                        'Panggil {{ $a->pasien->user->name }} — No. {{ $a->no_antrian }} masuk ke ruang periksa?',
                                        () => { $refs.panggilDokter{{ $a->id }}.submit() },
                                        'primary'
                                    )"
                                    class="btn btn-primary btn-sm">Panggil</button>
                            </form>
                            @elseif($a->status === 'dipanggil')
                            <a href="{{ route('dokter.rekam-medis.create', ['antrian_id'=>$a->id]) }}" class="btn btn-success btn-sm">Buat RM</a>
                            <form method="POST" action="{{ route('dokter.antrian.status', $a->id) }}" x-ref="selesaiDokter{{ $a->id }}">@csrf @method('PATCH')<input type="hidden" name="status" value="selesai">
                                <button type="button"
                                    @click="triggerConfirm(
                                        'Tandai Selesai',
                                        'Tandai antrian {{ $a->no_antrian }} sebagai selesai? Pastikan rekam medis sudah dibuat sebelum melanjutkan.',
                                        () => { $refs.selesaiDokter{{ $a->id }}.submit() },
                                        'danger'
                                    )"
                                    class="btn btn-secondary btn-sm">Selesai</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="padding:1rem 1.5rem;">{{ $antrians->links() }}</div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const currentCounts = {
        semua: {{ $counts['semua'] }},
        menunggu: {{ $counts['menunggu'] }},
        dipanggil: {{ $counts['dipanggil'] }},
        selesai: {{ $counts['selesai'] }},
        batal: {{ $counts['batal'] }}
    };
    
    setInterval(() => {
        fetch('{{ route('dokter.antrian.index') }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.counts && (
                data.counts.semua !== currentCounts.semua ||
                data.counts.menunggu !== currentCounts.menunggu ||
                data.counts.dipanggil !== currentCounts.dipanggil ||
                data.counts.selesai !== currentCounts.selesai ||
                data.counts.batal !== currentCounts.batal
            )) {
                const isModalOpen = Array.from(document.querySelectorAll('.modal-backdrop')).some(el => el.offsetWidth > 0 || el.offsetHeight > 0 || el.style.display !== 'none');
                const isTyping = document.activeElement && ['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement.tagName);
                if (!isModalOpen && !isTyping) {
                    window.location.reload();
                }
            }
        })
        .catch(() => {});
    }, 3000);
});
</script>
@endsection
