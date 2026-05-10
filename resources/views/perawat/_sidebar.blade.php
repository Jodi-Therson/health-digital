{{-- Perawat sidebar --}}
@php $menu = [
    ['route'=>'perawat.dashboard','label'=>'Dashboard','icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
    ['route'=>'perawat.antrian.index','label'=>'Kelola Antrian','icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
    ['route'=>'perawat.rekam-medis.index','label'=>'Rekam Medis','icon'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
]; @endphp
<div style="padding:0 0 1rem;border-bottom:1px solid #f1f5f9;margin:0 0.5rem 0.75rem;">
    <div style="display:flex;align-items:center;gap:0.625rem;padding:0.5rem;">
        <img src="{{ auth()->user()->avatar_url }}" style="width:2.5rem;height:2.5rem;border-radius:50%;object-fit:cover;border:2px solid #bfdbfe;">
        <div><div style="font-size:0.8125rem;font-weight:700;color:#1e293b;">{{ Str::limit(auth()->user()->name, 18) }}</div><div style="font-size:0.75rem;color:#10b981;font-weight:500;">● Perawat</div></div>
    </div>
</div>
@foreach($menu as $item)
<a href="{{ route($item['route']) }}" class="sidebar-link {{ request()->routeIs($item['route'].'*')?'active':'' }}">
    <svg style="width:1.125rem;height:1.125rem;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/></svg>
    {{ $item['label'] }}
</a>
@endforeach
<div style="border-top:1px solid #f1f5f9;margin:0.75rem 0.5rem 0;padding-top:0.75rem;">
    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="sidebar-link" style="width:100%;color:#ef4444;background:none;border:none;cursor:pointer;text-align:left;"><svg style="width:1.125rem;height:1.125rem;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>Keluar</button></form>
</div>
