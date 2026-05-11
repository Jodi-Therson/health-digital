{{-- Admin sidebar --}}
@php $menu = [
    ['route'=>'admin.dashboard','label'=>'Dashboard','icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
    ['route'=>'admin.users.index','label'=>'Pengguna','icon'=>'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
    ['route'=>'admin.layanan.index','label'=>'Layanan','icon'=>'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
    ['route'=>'admin.fasilitas.index','label'=>'Fasilitas','icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
    ['route'=>'admin.pembayaran.index','label'=>'Pembayaran','icon'=>'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
    ['route'=>'admin.laporan.index','label'=>'Laporan','icon'=>'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
]; @endphp
<div style="padding:0 0 1rem;border-bottom:1px solid #f1f5f9;margin:0 0.5rem 0.75rem;">
    <div style="display:flex;align-items:center;gap:0.625rem;padding:0.5rem;">
        <img src="{{ auth()->user()->avatar_url }}" style="width:2.5rem;height:2.5rem;border-radius:50%;object-fit:cover;border:2px solid #bfdbfe;">
        <div><div style="font-size:0.8125rem;font-weight:700;color:#1e293b;">{{ Str::limit(auth()->user()->name, 18) }}</div><div style="font-size:0.75rem;color:#f59e0b;font-weight:500;">● Admin</div></div>
    </div>
</div>
@foreach($menu as $item)
<a href="{{ route($item['route']) }}" class="sidebar-link {{ request()->routeIs($item['route'].'*')?'active':'' }}">
    <svg style="width:1.125rem;height:1.125rem;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/></svg>
    {{ $item['label'] }}
</a>
@endforeach
<div style="border-top:1px solid #f1f5f9;margin:0.75rem 0.5rem 0;padding-top:0.75rem;">
    <button type="button" @click="showLogoutModal = true" class="sidebar-link" style="width:100%;color:#ef4444;background:none;border:none;cursor:pointer;text-align:left;">
        <svg style="width:1.125rem;height:1.125rem;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
        Keluar
    </button>
</div>
