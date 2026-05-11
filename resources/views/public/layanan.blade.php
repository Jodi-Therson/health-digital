@extends('layouts.public')
@section('title', 'Layanan Kami')
@section('content')
<div style="background:linear-gradient(135deg,#1e40af,#2563eb); padding:3rem 1.5rem; text-align:center;">
    <div style="max-width:1280px; margin:0 auto;">
        <h1 style="font-size:2rem; font-weight:800; color:white; margin-bottom:0.75rem;">Layanan Medis Kami</h1>
        <p style="color:rgba(255,255,255,0.85);">Tersedia berbagai poli spesialisasi dengan tenaga medis profesional</p>
    </div>
</div>
<section style="padding:3rem 1.5rem; background:#f8fafc;">
    <div style="max-width:1280px; margin:0 auto;">
        <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(300px,1fr)); gap:1.5rem;">
            @php
                $iconMap = [
                    'stethoscope' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                    'tooth' => 'M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z',
                    'baby' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                    'heart' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
                    'bolt' => 'M13 10V3L4 14h7v7l9-11h-7z'
                ];
            @endphp
            @foreach($layanans as $layanan)
            <div class="card" style="border-radius:1rem; border:1px solid #e2e8f0; overflow:hidden; transition:transform 0.2s; box-shadow:0 4px 6px -1px rgba(0,0,0,0.05);" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <div class="card-body" style="padding:1.5rem;">
                    <div style="display:flex; align-items:center; gap:1.25rem; margin-bottom:1.25rem;">
                        <div style="background:#eff6ff; width:3.5rem; height:3.5rem; border-radius:1rem; display:flex; align-items:center; justify-content:center; flex-shrink:0; color:#2563eb;">
                            @php
                                $iconPath = $iconMap[$layanan->ikon] ?? $layanan->ikon ?? 'M13 10V3L4 14h7v7l9-11h-7z';
                            @endphp
                            <svg style="width:1.75rem;height:1.75rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"/></svg>
                        </div>
                        <div>
                            <h2 style="font-size:1.25rem; font-weight:800; color:#1e293b; margin:0;">Poli {{ $layanan->nama }}</h2>
                        </div>
                    </div>
                    <p style="font-size:0.9375rem; color:#64748b; line-height:1.6; margin-bottom:1.5rem; min-height:4.5rem;">{{ $layanan->deskripsi }}</p>
                    <div style="display:flex; justify-content:flex-end; border-top:1px solid #f1f5f9; padding-top:1.25rem;">
                        @auth
                        <a href="{{ route('pasien.antrian.create', ['layanan_id' => $layanan->id]) }}" class="btn btn-primary" style="padding:0.625rem 1.25rem; font-weight:600;">Daftar Antrian</a>
                        @else
                        <a href="{{ route('login') }}" class="btn btn-secondary" style="padding:0.625rem 1.25rem; font-weight:600;">Masuk & Daftar</a>
                        @endauth
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
