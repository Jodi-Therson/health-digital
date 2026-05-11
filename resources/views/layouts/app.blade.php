<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — HealthDigital</title>
    <meta name="description" content="Platform Layanan Kesehatan Digital Terpadu">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full" x-data="{ sidebarOpen: false }">

<!-- NAVBAR -->
<nav class="navbar flex items-center justify-between px-4 lg:px-6">
    <div class="flex items-center gap-3">
        <!-- Mobile hamburger -->
        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-md hover:bg-neutral-100">
            <svg class="w-5 h-5 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <div style="background: linear-gradient(135deg, #2563eb, #0891b2); width:2rem; height:2rem; border-radius:0.5rem; display:flex; align-items:center; justify-content:center;">
                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5C3.9 3 3 3.9 3 5v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 3c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm7 13H5v-.23c0-.62.28-1.2.76-1.58C7.47 15.82 9.64 15 12 15s4.53.82 6.24 2.19c.48.38.76.97.76 1.58V19z"/></svg>
            </div>
            <span style="font-weight: 800; font-size: 1.125rem; color: #1e3a8a; letter-spacing: -0.025em;">Health<span style="color:#2563eb;">Digital</span></span>
        </a>
    </div>

    <div class="flex items-center gap-3">
        <!-- Notifications -->
        <button class="relative p-2 rounded-full hover:bg-neutral-100 text-neutral-500 hover:text-neutral-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        </button>

        <!-- User dropdown -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-neutral-100 transition-colors">
                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover">
                <div class="hidden md:block text-left">
                    <div style="font-size:0.8125rem; font-weight:600; color:#1e293b; line-height:1.2;">{{ Str::limit(auth()->user()->name, 20) }}</div>
                    <div style="font-size:0.75rem; color:#64748b; text-transform:capitalize;">{{ auth()->user()->role }}</div>
                </div>
                <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>

            <div x-show="open" @click.away="open = false" x-transition
                 style="position:absolute; right:0; top:calc(100% + 0.5rem); width:13rem; background:white; border-radius:0.75rem; box-shadow:0 10px 30px rgba(0,0,0,0.12); border:1px solid #e2e8f0; z-index:60; overflow:hidden;">
                <div style="padding:0.75rem 1rem; border-bottom:1px solid #f1f5f9;">
                    <div style="font-weight:600; font-size:0.875rem; color:#0f172a;">{{ auth()->user()->name }}</div>
                    <div style="font-size:0.75rem; color:#64748b;">{{ auth()->user()->email }}</div>
                </div>
                <div style="padding:0.5rem;">
                    <a href="{{ route('profile.index') }}" style="display:flex; align-items:center; gap:0.5rem; padding:0.5rem 0.75rem; border-radius:0.5rem; font-size:0.875rem; color:#475569; text-decoration:none; transition:background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Profil Saya
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" style="width:100%; display:flex; align-items:center; gap:0.5rem; padding:0.5rem 0.75rem; border-radius:0.5rem; font-size:0.875rem; color:#ef4444; background:none; border:none; cursor:pointer; text-align:left; transition:background 0.15s;" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='transparent'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- LAYOUT BODY -->
<div class="flex" style="min-height: calc(100vh - 4rem);">

    <!-- SIDEBAR MOBILE OVERLAY -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/40 z-40 lg:hidden" x-cloak></div>

    <!-- SIDEBAR -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="sidebar fixed lg:sticky lg:translate-x-0 z-50 transition-transform duration-200 lg:transition-none" x-cloak>
        <div style="padding:1rem 0.5rem;">
            @yield('sidebar')
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 overflow-hidden" style="padding:1.5rem;">
        <!-- Breadcrumb -->
        @hasSection('breadcrumb')
        <nav class="breadcrumb">
            @yield('breadcrumb')
        </nav>
        @endif

        <!-- Flash Messages -->
        @if(session('success'))
        <div class="alert alert-success fade-in" data-auto-hide="5000">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-error fade-in" data-auto-hide="7000">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('error') }}</span>
        </div>
        @endif
        @if(session('info'))
        <div class="alert alert-info fade-in" data-auto-hide="6000">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('info') }}</span>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-error fade-in" style="flex-direction:column; align-items:flex-start; gap:0.5rem;">
            <div style="display:flex; align-items:center; gap:0.5rem;">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span style="font-weight:700;">Terdapat kesalahan input:</span>
            </div>
            <ul style="margin:0; padding-left:1.5rem; font-size:0.875rem; list-style-type:disc;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @yield('content')
    </main>
</div>

<!-- FOOTER -->
<footer style="background:white; border-top:1px solid #e2e8f0; padding:1rem 1.5rem; text-align:center; font-size:0.8125rem; color:#94a3b8;">
    © {{ date('Y') }} <strong style="color:#2563eb;">HealthDigital</strong> — Platform Layanan Kesehatan Digital. All rights reserved.
</footer>

</body>
</html>
