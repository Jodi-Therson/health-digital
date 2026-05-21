@extends('layouts.public')
@section('title', 'Layanan Kami — HealthDigital')
@section('meta_description', 'Lihat semua layanan medis yang tersedia di HealthDigital. Poli spesialisasi dengan tenaga medis profesional.')

@section('content')
<div style="background:linear-gradient(135deg,#1e40af,#2563eb); padding:3rem 1.5rem; text-align:center;">
    <div style="max-width:1280px; margin:0 auto;">
        <h1 style="font-size:2rem; font-weight:800; color:white; margin-bottom:0.75rem;">Layanan Medis Kami</h1>
        <p style="color:rgba(255,255,255,0.85);">Tersedia berbagai poli spesialisasi dengan tenaga medis profesional</p>
    </div>
</div>

<section style="padding:3rem 1.5rem; background:#f8fafc;">
    <div style="max-width:1280px; margin:0 auto;">
        @if($layanans->isEmpty())
        <div style="text-align:center; padding:4rem 2rem; background:white; border-radius:1rem; border:1px solid #e2e8f0;">
            <svg style="width:4rem;height:4rem;color:#cbd5e1;margin:0 auto 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <h3 style="font-size:1.125rem;font-weight:600;color:#64748b;margin-bottom:0.5rem;">Belum Ada Layanan</h3>
            <p style="color:#94a3b8;font-size:0.875rem;">Layanan medis sedang dalam persiapan. Silakan cek kembali nanti.</p>
        </div>
        @else
        <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(300px,1fr)); gap:1.5rem;">
            @foreach($layanans as $layanan)
            <div class="card" style="border-radius:1rem; border:1px solid #e2e8f0; overflow:hidden; transition:transform 0.2s, box-shadow 0.2s; box-shadow:0 4px 6px -1px rgba(0,0,0,0.05);" onmouseover="this.style.transform='translateY(-5px)';this.style.boxShadow='0 12px 24px rgba(37,99,235,0.12)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 6px -1px rgba(0,0,0,0.05)'">
                {{-- Gambar layanan --}}
                @if($layanan->gambar_url)
                <div style="height:160px; overflow:hidden;">
                    <img src="{{ $layanan->gambar_url }}" alt="{{ $layanan->nama }}"
                         style="width:100%; height:100%; object-fit:cover; transition:transform 0.3s;"
                         onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                </div>
                @else
                <div style="height:100px; background:linear-gradient(135deg,#dbeafe,#eff6ff); display:flex; align-items:center; justify-content:center;">
                    <svg style="width:2.5rem;height:2.5rem;color:#93c5fd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                @endif

                <div class="card-body" style="padding:1.5rem;">
                    <div style="margin-bottom:1.25rem;">
                        <h2 style="font-size:1.125rem; font-weight:800; color:#1e293b; margin:0 0 0.5rem;">Poli {{ $layanan->nama }}</h2>
                        <p style="font-size:0.9375rem; color:#64748b; line-height:1.6; min-height:3.5rem;">{{ $layanan->deskripsi }}</p>
                    </div>
                    <div style="display:flex; justify-content:flex-end; border-top:1px solid #f1f5f9; padding-top:1.25rem;">
                        @auth
                        <a href="{{ route('pasien.antrian.create', ['layanan_id' => $layanan->id]) }}" class="btn btn-primary" style="padding:0.625rem 1.25rem; font-weight:600;">Daftar Antrian</a>
                        @else
                        <a href="{{ route('login') }}" class="btn btn-secondary" style="padding:0.625rem 1.25rem; font-weight:600;">Masuk &amp; Daftar</a>
                        @endauth
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endsection
