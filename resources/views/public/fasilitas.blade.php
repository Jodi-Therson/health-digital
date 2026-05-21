@extends('layouts.public')
@section('title', 'Fasilitas Rumah Sakit — HealthDigital')
@section('meta_description', 'Fasilitas medis modern dan lengkap di HealthDigital untuk pelayanan kesehatan terbaik.')

@section('content')
<div style="background:linear-gradient(135deg,#064e3b,#065f46); padding:3rem 1.5rem; text-align:center;">
    <div style="max-width:1280px; margin:0 auto;">
        <h1 style="font-size:2rem; font-weight:800; color:white; margin-bottom:0.75rem;">Fasilitas Rumah Sakit</h1>
        <p style="color:rgba(255,255,255,0.85);">Fasilitas medis modern dan lengkap untuk pelayanan terbaik</p>
    </div>
</div>

<section style="padding:3rem 1.5rem; background:#f8fafc;">
    <div style="max-width:1280px; margin:0 auto;">
        @if($fasilitas->isEmpty())
        <div style="text-align:center; padding:4rem 2rem; background:white; border-radius:1rem; border:1px solid #e2e8f0;">
            <svg style="width:4rem;height:4rem;color:#cbd5e1;margin:0 auto 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <h3 style="font-size:1.125rem;font-weight:600;color:#64748b;margin-bottom:0.5rem;">Belum Ada Fasilitas</h3>
            <p style="color:#94a3b8;font-size:0.875rem;">Data fasilitas sedang dalam persiapan. Silakan cek kembali nanti.</p>
        </div>
        @else
        <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(300px,1fr)); gap:1.5rem;">
            @foreach($fasilitas as $item)
            <div class="card" style="overflow:hidden; border-radius:1rem; transition:transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 28px rgba(6,78,59,0.12)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
                @if($item->foto)
                <div style="height:200px; overflow:hidden;">
                    <img src="{{ asset('storage/'.$item->foto) }}" alt="{{ $item->nama }}"
                         style="width:100%;height:100%;object-fit:cover;transition:transform 0.4s;"
                         onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                </div>
                @else
                <div style="width:100%;height:200px;background:linear-gradient(135deg,#d1fae5,#a7f3d0);display:flex;align-items:center;justify-content:center;">
                    <svg style="width:3.5rem;height:3.5rem;color:#34d399;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                @endif
                <div class="card-body" style="padding:1.25rem;">
                    <h2 style="font-size:1rem; font-weight:700; color:#1e293b; margin-bottom:0.5rem;">{{ $item->nama }}</h2>
                    <p style="font-size:0.875rem; color:#64748b; line-height:1.6;">{{ $item->deskripsi }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endsection
