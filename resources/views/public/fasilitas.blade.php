@extends('layouts.public')
@section('title', 'Fasilitas Kami')
@section('content')
<div style="background:linear-gradient(135deg,#064e3b,#065f46); padding:3rem 1.5rem; text-align:center;">
    <div style="max-width:1280px; margin:0 auto;">
        <h1 style="font-size:2rem; font-weight:800; color:white; margin-bottom:0.75rem;">Fasilitas Rumah Sakit</h1>
        <p style="color:rgba(255,255,255,0.85);">Fasilitas medis modern dan lengkap untuk pelayanan terbaik</p>
    </div>
</div>
<section style="padding:3rem 1.5rem; background:#f8fafc;">
    <div style="max-width:1280px; margin:0 auto;">
        <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(300px,1fr)); gap:1.5rem;">
            @foreach($fasilitas as $item)
            <div class="card" style="overflow:hidden;">
                @if($item->foto)
                <img src="{{ asset('storage/'.$item->foto) }}" alt="{{ $item->nama }}" style="width:100%;height:180px;object-fit:cover;">
                @else
                <div style="width:100%;height:180px;background:linear-gradient(135deg,#dbeafe,#bfdbfe);display:flex;align-items:center;justify-content:center;">
                    <svg style="width:3rem;height:3rem;color:#93c5fd;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                @endif
                <div class="card-body">
                    <h2 style="font-size:1rem; font-weight:700; color:#1e293b; margin-bottom:0.5rem;">{{ $item->nama }}</h2>
                    <p style="font-size:0.875rem; color:#64748b; line-height:1.6;">{{ $item->deskripsi }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
