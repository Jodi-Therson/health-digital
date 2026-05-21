@extends('layouts.public')

@section('title', 'Beranda — HealthDigital')
@section('meta_description', 'HealthDigital - Portal layanan kesehatan digital terpadu. Daftar antrian online, konsultasi dokter, rekam medis digital, dan pembayaran mudah.')

@section('content')

<!-- HERO SECTION -->
<section class="hero-section" style="position:relative; background-image: url('{{ asset('images/hero.png') }}'); background-size: cover; background-position: center; padding: 6rem 1.5rem;">
    <div style="max-width:1280px; margin:0 auto; position:relative; z-index:1;">
        <div style="max-width:680px; background:rgba(255, 255, 255, 0.65); backdrop-filter:blur(10px); -webkit-backdrop-filter:blur(24px); border:1px solid rgba(255,255,255,0.35); border-radius:1.5rem; padding:3rem 2.5rem; box-shadow:0 20px 50px rgba(15,23,42,0.12);">
            <div style="display:inline-flex; align-items:center; gap:0.5rem; background:rgba(37, 99, 235, 0.08); border:1px solid rgba(37, 99, 235, 0.15); border-radius:9999px; padding:0.375rem 0.875rem; margin-bottom:1.5rem;">
                <div style="width:0.5rem;height:0.5rem;background:#10b981;border-radius:50%;animation:blink 1.5s ease-in-out infinite;"></div>
                <span style="font-size:0.8125rem; color:#1d4ed8; font-weight:600;">Layanan 24/7 Tersedia</span>
            </div>
            <h1 style="font-size:clamp(2rem, 4vw, 3.25rem); font-weight:800; color:#0f172a; line-height:1.15; margin-bottom:1.25rem; letter-spacing:-0.025em;">
                Layanan Kesehatan<br>
                <span style="color:#2563eb;">Digital Terpadu</span>
            </h1>
            <p style="font-size:1.0625rem; color:#334155; line-height:1.7; margin-bottom:2rem; max-width:540px; font-weight: 500;">
                Akses layanan kesehatan lebih mudah, cepat, dan efisien. Daftar antrian online, konsultasi dokter, dan kelola rekam medis Anda dari mana saja.
            </p>
            <div style="display:flex; gap:1rem; flex-wrap:wrap; margin-bottom:1rem;">
                <a href="{{ route('register') }}" class="btn" style="background:#2563eb; color:white; font-size:1rem; padding:0.75rem 1.75rem; box-shadow:0 4px 16px rgba(37, 99, 235, 0.25);">
                    <svg style="width:1.125rem;height:1.125rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    Daftar Sekarang
                </a>
                <a href="{{ route('layanan') }}" class="btn" style="background:rgba(255,255,255,0.85); color:#334155; border:1.5px solid #cbd5e1; font-size:1rem; padding:0.75rem 1.75rem; backdrop-filter:blur(8px);">
                    Lihat Layanan
                </a>
            </div>

            <!-- Quick Stats — dynamic from DB -->
            <div style="display:flex; gap:2.5rem; margin-top:2.5rem; flex-wrap:wrap; border-top:1px solid rgba(15,23,42,0.08); padding-top:2rem;">
                <div>
                    <div style="font-size:1.75rem; font-weight:800; color:#0f172a;">{{ $totalPasien > 0 ? $totalPasien.'+' : '—' }}</div>
                    <div style="font-size:0.875rem; color:#64748b; font-weight:600;">Pasien Terdaftar</div>
                </div>
                <div>
                    <div style="font-size:1.75rem; font-weight:800; color:#0f172a;">{{ $totalDokter > 0 ? $totalDokter.'+' : '—' }}</div>
                    <div style="font-size:0.875rem; color:#64748b; font-weight:600;">Dokter Spesialis</div>
                </div>
                <div>
                    <div style="font-size:1.75rem; font-weight:800; color:#0f172a;">{{ $totalLayanan }}</div>
                    <div style="font-size:0.875rem; color:#64748b; font-weight:600;">Layanan Medis</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FEATURES STRIP -->
<section style="background:white; border-bottom:1px solid #e2e8f0;">
    <div style="max-width:1280px; margin:0 auto; padding:1.25rem 1.5rem; display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:1rem;">
        @php $features = [
            ['icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label'=>'Antrian Online', 'desc'=>'Daftar tanpa antre fisik'],
            ['icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'label'=>'Rekam Medis Digital', 'desc'=>'Akses kapan saja'],
            ['icon'=>'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z', 'label'=>'Konsultasi Online', 'desc'=>'Tanya dokter langsung'],
            ['icon'=>'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z', 'label'=>'Pembayaran Digital', 'desc'=>'BPJS, Transfer, QRIS'],
        ]; @endphp

        @foreach($features as $f)
        <div style="display:flex; align-items:center; gap:0.75rem; padding:0.75rem; border-radius:0.625rem;">
            <div style="background:#dbeafe; width:2.5rem; height:2.5rem; border-radius:0.5rem; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg style="width:1.25rem;height:1.25rem;color:#2563eb;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $f['icon'] }}"/></svg>
            </div>
            <div>
                <div style="font-size:0.875rem; font-weight:600; color:#1e293b;">{{ $f['label'] }}</div>
                <div style="font-size:0.8125rem; color:#64748b;">{{ $f['desc'] }}</div>
            </div>
        </div>
        @endforeach
    </div>
</section>

<!-- LAYANAN -->
<section style="padding:4rem 1.5rem; background:#f8fafc;">
    <div style="max-width:1280px; margin:0 auto;">
        <div style="text-align:center; margin-bottom:2.5rem;">
            <div style="display:inline-block; background:#dbeafe; color:#1d4ed8; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.1em; padding:0.375rem 0.875rem; border-radius:9999px; margin-bottom:0.75rem;">Layanan Kami</div>
            <h2 style="font-size:clamp(1.5rem,3vw,2rem); font-weight:800; color:#0f172a; margin-bottom:0.75rem;">Layanan Medis Lengkap</h2>
            <p style="color:#64748b; max-width:480px; margin:0 auto; font-size:0.9375rem;">Tersedia berbagai layanan spesialisasi medis yang ditangani oleh tenaga kesehatan profesional.</p>
        </div>

        @if($layanans->isEmpty())
        <div style="text-align:center; padding:3rem; color:#94a3b8;">
            <svg style="width:3rem;height:3rem;margin:0 auto 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <p>Belum ada layanan yang tersedia saat ini.</p>
        </div>
        @else
        <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); gap:1.25rem;">
            @foreach($layanans as $layanan)
            <div class="card" style="transition:all 0.2s; cursor:pointer; border-left:3px solid #2563eb;" onmouseover="this.style.boxShadow='0 8px 24px rgba(37,99,235,0.12)';this.style.transform='translateY(-3px)'" onmouseout="this.style.boxShadow='';this.style.transform=''">
                <div class="card-body">
                    <!-- Gambar layanan (image upload) atau placeholder -->
                    @if($layanan->gambar_url)
                    <div style="margin-bottom:0.875rem; border-radius:0.75rem; overflow:hidden; height:120px;">
                        <img src="{{ $layanan->gambar_url }}" alt="{{ $layanan->nama }}"
                             style="width:100%; height:100%; object-fit:cover;">
                    </div>
                    @else
                    <div style="background:#dbeafe; width:2.75rem; height:2.75rem; border-radius:0.75rem; display:flex; align-items:center; justify-content:center; margin-bottom:0.875rem;">
                        <svg style="width:1.375rem;height:1.375rem;color:#2563eb;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    @endif
                    <h3 style="font-size:1rem; font-weight:700; color:#1e293b; margin-bottom:0.5rem;">Poli {{ $layanan->nama }}</h3>
                    <p style="font-size:0.875rem; color:#64748b; line-height:1.6;">{{ $layanan->deskripsi }}</p>
                    @auth
                    <a href="{{ route('pasien.antrian.create') }}" class="btn btn-primary btn-sm" style="margin-top:1rem;">Daftar Antrian</a>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-secondary btn-sm" style="margin-top:1rem;">Masuk untuk Daftar</a>
                    @endauth
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <div style="text-align:center; margin-top:2rem;">
            <a href="{{ route('layanan') }}" class="btn btn-secondary">Lihat Semua Layanan →</a>
        </div>
    </div>
</section>

<!-- DOKTER -->
@if($dokters->count() > 0)
<section style="padding:4rem 1.5rem; background:white;">
    <div style="max-width:1280px; margin:0 auto;">
        <div style="text-align:center; margin-bottom:2.5rem;">
            <div style="display:inline-block; background:#d1fae5; color:#065f46; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.1em; padding:0.375rem 0.875rem; border-radius:9999px; margin-bottom:0.75rem;">Tim Dokter</div>
            <h2 style="font-size:clamp(1.5rem,3vw,2rem); font-weight:800; color:#0f172a; margin-bottom:0.75rem;">Dokter Berpengalaman</h2>
            <p style="color:#64748b; max-width:480px; margin:0 auto; font-size:0.9375rem;">Ditangani oleh dokter-dokter spesialis yang berpengalaman dan berlisensi.</p>
        </div>

        <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:1.25rem;">
            @foreach($dokters as $dokter)
            <div class="card" style="text-align:center; transition:all 0.2s;" onmouseover="this.style.boxShadow='0 8px 24px rgba(0,0,0,0.1)';this.style.transform='translateY(-3px)'" onmouseout="this.style.boxShadow='';this.style.transform=''">
                <div class="card-body">
                    <img src="{{ $dokter->user->avatar_url }}" alt="{{ $dokter->user->name }}"
                         style="width:4.5rem;height:4.5rem;border-radius:50%;object-fit:cover;margin:0 auto 0.875rem;border:3px solid #dbeafe;">
                    <h3 style="font-size:0.9375rem; font-weight:700; color:#1e293b; margin-bottom:0.25rem;">{{ $dokter->user->name }}</h3>
                    <div class="badge badge-primary" style="margin-bottom:0.75rem;">{{ $dokter->spesialisasi }}</div>
                    @if($dokter->bio)
                    <p style="font-size:0.8125rem; color:#64748b; line-height:1.5;">{{ Str::limit($dokter->bio, 80) }}</p>
                    @endif
                    <div style="font-size:0.8125rem; font-weight:600; color:#10b981; margin-top:0.75rem;">
                        Rp {{ number_format($dokter->tarif_konsultasi, 0, ',', '.') }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA SECTION -->
<section style="background:linear-gradient(135deg,#1e3a8a,#2563eb); padding:4rem 1.5rem; text-align:center;">
    <div style="max-width:600px; margin:0 auto;">
        <h2 style="font-size:clamp(1.5rem,3vw,2.25rem); font-weight:800; color:white; margin-bottom:1rem;">Mulai Perjalanan Kesehatan Anda</h2>
        <p style="color:rgba(255,255,255,0.8); font-size:1rem; margin-bottom:2rem; line-height:1.6;">Daftar sekarang dan nikmati kemudahan layanan kesehatan digital. Gratis tanpa biaya pendaftaran.</p>
        @guest
        <a href="{{ route('register') }}" class="btn" style="background:white; color:#2563eb; font-size:1rem; padding:0.875rem 2rem; box-shadow:0 4px 16px rgba(0,0,0,0.2);">
            Daftar Gratis Sekarang
        </a>
        @else
        <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="btn" style="background:white; color:#2563eb; font-size:1rem; padding:0.875rem 2rem;">
            Ke Dashboard
        </a>
        @endguest
    </div>
</section>

@endsection
