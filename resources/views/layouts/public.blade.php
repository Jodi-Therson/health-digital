<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HealthDigital') — Platform Kesehatan Digital</title>
    <meta name="description" content="@yield('meta_description', 'HealthDigital - Portal digital terintegrasi untuk layanan kesehatan terpadu')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ mobileMenuOpen: false }">

<!-- PUBLIC NAVBAR -->
<nav style="background:white; border-bottom:1px solid #e2e8f0; box-shadow:0 1px 3px rgba(0,0,0,0.05); position:sticky; top:0; z-index:50;">
    <div style="max-width:1280px; margin:0 auto; padding:0 1.5rem; height:4rem; display:flex; align-items:center; justify-content:space-between;">
        <!-- Logo -->
        <a href="{{ route('home') }}" style="display:flex; align-items:center; gap:0.625rem; text-decoration:none;">
            <div style="background:linear-gradient(135deg,#2563eb,#0891b2); width:2.25rem; height:2.25rem; border-radius:0.625rem; display:flex; align-items:center; justify-content:center;">
                <svg style="width:1.25rem;height:1.25rem;color:white;" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5C3.9 3 3 3.9 3 5v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 3c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm7 13H5v-.23c0-.62.28-1.2.76-1.58C7.47 15.82 9.64 15 12 15s4.53.82 6.24 2.19c.48.38.76.97.76 1.58V19z"/></svg>
            </div>
            <span style="font-weight:800; font-size:1.125rem; color:#1e3a8a; letter-spacing:-0.025em;">Health<span style="color:#2563eb;">Digital</span></span>
        </a>

        <!-- Desktop nav -->
        <div style="display:flex; align-items:center; gap:0.25rem;" class="hidden md:flex">
            <a href="{{ route('home') }}" class="public-nav-link {{ request()->routeIs('home') ? 'text-blue-600 bg-blue-50' : '' }}">Beranda</a>
            <a href="{{ route('layanan') }}" class="public-nav-link {{ request()->routeIs('layanan') ? 'text-blue-600 bg-blue-50' : '' }}">Layanan</a>
            <a href="{{ route('fasilitas') }}" class="public-nav-link {{ request()->routeIs('fasilitas') ? 'text-blue-600 bg-blue-50' : '' }}">Fasilitas</a>
            <a href="{{ route('kontak') }}" class="public-nav-link {{ request()->routeIs('kontak') ? 'text-blue-600 bg-blue-50' : '' }}">Kontak</a>
        </div>

        <div style="display:flex; align-items:center; gap:0.75rem;">
            @auth
            <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="btn btn-primary btn-sm">
                Dashboard
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            @else
            <a href="{{ route('login') }}" class="btn btn-secondary btn-sm">Masuk</a>
            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar</a>
            @endauth
        </div>
    </div>
</nav>

<!-- CONTENT -->
@yield('content')

<!-- FOOTER -->
<footer style="background:#0f172a; color:#94a3b8; padding:3rem 1.5rem;">
    <div style="max-width:1280px; margin:0 auto;">
        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:2rem; margin-bottom:2rem;">
            <div>
                <div style="display:flex; align-items:center; gap:0.625rem; margin-bottom:1rem;">
                    <div style="background:linear-gradient(135deg,#2563eb,#0891b2); width:2rem; height:2rem; border-radius:0.5rem; display:flex; align-items:center; justify-content:center;">
                        <svg style="width:1rem;height:1rem;color:white;" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5C3.9 3 3 3.9 3 5v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 3c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm7 13H5v-.23c0-.62.28-1.2.76-1.58C7.47 15.82 9.64 15 12 15s4.53.82 6.24 2.19c.48.38.76.97.76 1.58V19z"/></svg>
                    </div>
                    <span style="font-weight:800;font-size:1.125rem;color:white;">Health<span style="color:#60a5fa;">Digital</span></span>
                </div>
                <p style="font-size:0.875rem;line-height:1.6;">Portal digital terintegrasi untuk layanan kesehatan yang lebih mudah, cepat, dan terjangkau.</p>
            </div>
            <div>
                <h4 style="color:white; font-weight:600; margin-bottom:0.75rem;">Layanan</h4>
                <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:0.5rem;">
                    <li><a href="{{ route('layanan') }}" style="font-size:0.875rem; color:#94a3b8; text-decoration:none;">Poli Umum</a></li>
                    <li><a href="{{ route('layanan') }}" style="font-size:0.875rem; color:#94a3b8; text-decoration:none;">Poli Gigi</a></li>
                    <li><a href="{{ route('layanan') }}" style="font-size:0.875rem; color:#94a3b8; text-decoration:none;">Poli Anak</a></li>
                    <li><a href="{{ route('layanan') }}" style="font-size:0.875rem; color:#94a3b8; text-decoration:none;">Laboratorium</a></li>
                </ul>
            </div>
            <div>
                <h4 style="color:white; font-weight:600; margin-bottom:0.75rem;">Kontak</h4>
                <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:0.5rem; font-size:0.875rem;">
                    <li>📍 Jl. Kesehatan No. 1, Jakarta</li>
                    <li>📞 (021) 123-4567</li>
                    <li>✉️ info@healthdigital.id</li>
                    <li>🕒 Senin–Jumat, 07.00–17.00</li>
                </ul>
            </div>
        </div>
        <div style="border-top:1px solid #1e293b; padding-top:1.5rem; text-align:center; font-size:0.8125rem;">
            © {{ date('Y') }} HealthDigital. All rights reserved. Platform Kesehatan Digital Indonesia.
        </div>
    </div>
</footer>

</body>
</html>
