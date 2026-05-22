<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Autentikasi') — HealthDigital</title>
    <meta name="description" content="Platform Layanan Kesehatan Digital Terpadu">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full" style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 50%, #e0f2fe 100%); min-height:100vh;">

<div style="display:flex; min-height:100vh; align-items:center; justify-content:center; padding:1.5rem;">
    <div style="width:100%; max-width:26rem;">
        <!-- Logo -->
        <div style="text-align:center; margin-bottom:2rem;">
            <a href="{{ route('home') }}" style="display:inline-flex; align-items:center; justify-content:center; gap:0.75rem; text-decoration:none;">
                <div style="background:linear-gradient(135deg,#2563eb,#0891b2); width:3rem; height:3rem; border-radius:0.875rem; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 12px rgba(37,99,235,0.3);">
                    <img src="{{ asset('images/image.png') }}" alt="" style="width:1.75rem; height:1.75rem; object-fit:contain;">
                </div>
                <div style="text-align:left;">
                    <div style="font-weight:800;font-size:1.5rem;color:#1e3a8a;letter-spacing:-0.025em;line-height:1;">Health<span style="color:#2563eb;">Digital</span></div>
                    <div style="font-size:0.75rem;color:#64748b;font-weight:500;">Platform Kesehatan Digital</div>
                </div>
            </a>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
        <div class="alert alert-success" style="margin-bottom:1rem;">
            <svg style="width:1.25rem;height:1.25rem;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-error" style="margin-bottom:1rem;">
            <svg style="width:1.25rem;height:1.25rem;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('error') }}</span>
        </div>
        @endif
        @if(session('info'))
        <div class="alert alert-info" style="margin-bottom:1rem;">
            <svg style="width:1.25rem;height:1.25rem;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('info') }}</span>
        </div>
        @endif
        @if($errors->any())
        <div class="alert alert-error" style="margin-bottom:1rem; flex-direction:column; align-items:flex-start; gap:0.25rem;">
            @foreach($errors->all() as $error)
                <div style="font-size:0.875rem;">• {{ $error }}</div>
            @endforeach
        </div>
        @endif

        <!-- Card -->
        <div style="background:white; border-radius:1rem; box-shadow:0 8px 32px rgba(30,58,138,0.1); padding:2rem; border:1px solid rgba(219,234,254,0.8);">
            @yield('content')
        </div>

        <!-- Back to home -->
        <div style="text-align:center; margin-top:1.5rem;">
            <a href="{{ route('home') }}" style="font-size:0.875rem; color:#64748b; text-decoration:none;">
                ← Kembali ke halaman utama
            </a>
        </div>
    </div>
</div>

</body>
</html>
