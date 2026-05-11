{{-- Reusable pasien sidebar --}}
@php $menu = [
    ['route'=>'pasien.dashboard', 'label'=>'Dashboard', 'icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
    ['route'=>'pasien.antrian.index', 'label'=>'Antrian Saya', 'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
    ['route'=>'pasien.rekam-medis.index', 'label'=>'Rekam Medis', 'icon'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
    ['route'=>'pasien.konsultasi.index', 'label'=>'Konsultasi', 'icon'=>'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
    ['route'=>'pasien.pembayaran.index', 'label'=>'Pembayaran', 'icon'=>'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
]; @endphp
<div style="padding:0 0 1rem; border-bottom:1px solid #f1f5f9; margin:0 0.5rem 0.75rem;">
    <div style="display:flex; align-items:center; gap:0.625rem; padding:0.5rem;">
        <img src="{{ auth()->user()->avatar_url }}" style="width:2.5rem;height:2.5rem;border-radius:50%;object-fit:cover;border:2px solid #bfdbfe;">
        <div>
            <div style="font-size:0.8125rem; font-weight:700; color:#1e293b;">{{ Str::limit(auth()->user()->name, 18) }}</div>
            <div style="font-size:0.75rem; color:#10b981; font-weight:500;">● Pasien</div>
        </div>
    </div>
</div>
@foreach($menu as $item)
@php $isCurrent = request()->routeIs($item['route'].'*'); @endphp
<a href="{{ route($item['route']) }}" class="sidebar-link {{ $isCurrent ? 'active' : '' }}" style="position:relative;">
    <svg style="width:1.125rem;height:1.125rem;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/></svg>
    {{ $item['label'] }}
    @if($item['route'] === 'pasien.konsultasi.index')
    @php $unread = auth()->user()->pasien->konsultasis()->where('status','dijawab')->where('dibaca_pasien', false)->count(); @endphp
    @if($unread > 0)
    <span style="margin-left:auto;background:#ef4444;color:white;font-size:0.65rem;font-weight:700;min-width:1.25rem;height:1.25rem;border-radius:9999px;display:flex;align-items:center;justify-content:center;padding:0 0.3rem;">{{ $unread }}</span>
    @endif
    @endif
</a>
@endforeach
<div style="border-top:1px solid #f1f5f9; margin:0.75rem 0.5rem 0; padding-top:0.75rem;">
    <a href="{{ route('pasien.bantuan') }}" class="sidebar-link {{ request()->routeIs('pasien.bantuan') ? 'active' : '' }}">
        <svg style="width:1.25rem;height:1.25rem;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        Bantuan & Panduan
    </a>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="sidebar-link" style="width:100%; color:#ef4444; background:none; border:none; cursor:pointer; text-align:left;">
            <svg style="width:1.125rem;height:1.125rem;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Keluar
        </button>
    </form>
</div>
