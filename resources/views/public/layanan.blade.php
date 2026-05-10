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
            @php $iconMap = ['stethoscope'=>'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z','tooth'=>'M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18','baby'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z','pregnant'=>'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z','surgery'=>'M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z','lab'=>'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z']; @endphp
            @foreach($layanans as $layanan)
            <div class="card">
                <div class="card-body">
                    <div style="display:flex; align-items:center; gap:1rem; margin-bottom:1rem;">
                        <div style="background:#dbeafe; width:3rem; height:3rem; border-radius:0.875rem; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <svg style="width:1.5rem;height:1.5rem;color:#2563eb;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconMap[$layanan->ikon] ?? 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z' }}"/></svg>
                        </div>
                        <div>
                            <h2 style="font-size:1.125rem; font-weight:700; color:#1e293b;">Poli {{ $layanan->nama }}</h2>
                        </div>
                    </div>
                    <p style="font-size:0.875rem; color:#64748b; line-height:1.7;">{{ $layanan->deskripsi }}</p>
                    <div style="margin-top:1.25rem;">
                        @auth
                        <a href="{{ route('pasien.antrian.create') }}" class="btn btn-primary btn-sm">Daftar Antrian</a>
                        @else
                        <a href="{{ route('login') }}" class="btn btn-secondary btn-sm">Masuk untuk Daftar</a>
                        @endauth
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
