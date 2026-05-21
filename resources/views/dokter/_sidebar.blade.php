{{-- Reusable dokter sidebar --}}
@php $menu = [
    ['route'=>'dokter.dashboard', 'label'=>'Dashboard', 'icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
    ['route'=>'dokter.antrian.index', 'label'=>'Antrian Hari Ini', 'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
    ['route'=>'dokter.rekam-medis.index', 'label'=>'Rekam Medis', 'icon'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
    ['route'=>'dokter.konsultasi.index', 'label'=>'Konsultasi', 'icon'=>'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
]; @endphp
<div style="padding:0 0 1rem;border-bottom:1px solid #f1f5f9;margin:0 0.5rem 0.75rem;">
    <div style="display:flex;align-items:center;gap:0.625rem;padding:0.5rem;">
        <img src="{{ auth()->user()->avatar_url }}" style="width:2.5rem;height:2.5rem;border-radius:50%;object-fit:cover;border:2px solid #bfdbfe;">
        <div>
            <div style="font-size:0.8125rem;font-weight:700;color:#1e293b;">{{ Str::limit(auth()->user()->name, 18) }}</div>
            <div style="font-size:0.75rem;color:#2563eb;font-weight:500;">● Dokter</div>
        </div>
    </div>
</div>
@foreach($menu as $item)
<a href="{{ route($item['route']) }}" class="sidebar-link {{ request()->routeIs($item['route'].'*')?'active':'' }}" style="position:relative;">
    <svg style="width:1.125rem;height:1.125rem;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/></svg>
    {{ $item['label'] }}
    @if($item['route'] === 'dokter.konsultasi.index')
    @php $unread = auth()->user()->dokter->konsultasis()->where('dibaca_dokter', false)->count(); @endphp
    @if($unread > 0)
    <span style="margin-left:auto;background:#ef4444;color:white;font-size:0.65rem;font-weight:700;min-width:1.25rem;height:1.25rem;border-radius:9999px;display:flex;align-items:center;justify-content:center;padding:0 0.3rem;">{{ $unread }}</span>
    @endif
    @endif
</a>
@endforeach
<div style="border-top:1px solid #f1f5f9;margin:0.75rem 0.5rem 0;padding-top:0.75rem;">
    <button type="button" @click="showLogoutModal = true" class="sidebar-link" style="width:100%;color:#ef4444;background:none;border:none;cursor:pointer;text-align:left;margin-left: 0;margin-right: 0;">
        <svg style="width:1.125rem;height:1.125rem;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
        Keluar
    </button>
</div>
