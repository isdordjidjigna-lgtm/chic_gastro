<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Administration') — Chic Gastro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@php
    $user = auth()->user();
    $liens = [
        ['admin.dashboard', 'Tableau de bord', 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
        ['admin.plats.index', 'Menu (plats)', 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
        ['admin.categories.index', 'Catégories', 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
        ['admin.tables.index', 'Tables & QR', 'M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z'],
        ['admin.users.index', 'Utilisateurs', 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
        ['admin.commandes.index', 'Commandes', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
        ['admin.paiements.index', 'Paiements', 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
        ['admin.stats', 'Statistiques', 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
    ];
@endphp
<body class="bg-gray-100 font-sans text-gray-900 antialiased" x-data="{ sidebar: false }">
<div class="flex min-h-screen">

    {{-- ===== Sidebar (desktop) / tiroir (mobile) ===== --}}
    <aside class="fixed inset-y-0 left-0 z-40 w-64 flex flex-col bg-[#7c2d12] text-amber-100 transition-transform duration-200 lg:translate-x-0"
           :class="sidebar ? 'translate-x-0' : '-translate-x-full'">
        <div class="p-5">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 bg-white/10 rounded-2xl p-3">
                <x-application-logo class="text-sm" />
            </a>
            <p class="mt-3 px-1 text-[11px] uppercase tracking-widest text-amber-300/70">Pilotage du restaurant</p>
        </div>

        <nav class="flex-1 px-3 space-y-1 overflow-y-auto">
            @foreach($liens as [$route, $label, $icon])
                @php $actif = request()->routeIs($route === 'admin.dashboard' ? 'admin.dashboard' : $route.'.*'); @endphp
                <a href="{{ route($route) }}"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition {{ $actif ? 'bg-white text-gray-900 shadow' : 'text-amber-100/80 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $icon }}"/></svg>
                    {{ $label }}
                </a>
            @endforeach
        </nav>

        <div class="p-4 border-t border-white/10">
            <div class="flex items-center gap-3 px-2">
                <div class="w-9 h-9 rounded-full bg-amber-400 text-amber-950 font-bold flex items-center justify-center text-sm">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-white truncate">{{ $user->name }}</p>
                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide bg-amber-400 text-amber-950">{{ $user->roleLabel() }}</span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-amber-100 bg-white/10 hover:bg-white/20 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Déconnexion
                </button>
            </form>
        </div>
    </aside>

    {{-- Overlay mobile --}}
    <div x-show="sidebar" x-cloak @click="sidebar = false" class="fixed inset-0 bg-black/50 z-30 lg:hidden"></div>

    {{-- ===== Zone de contenu ===== --}}
    <div class="flex-1 lg:pl-64 min-w-0">
        {{-- Barre mobile --}}
        <header class="lg:hidden bg-white border-b border-gray-200 px-4 py-3 flex items-center gap-3 sticky top-0 z-20">
            <button @click="sidebar = true" class="p-2 -ml-2 text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <span class="font-bold text-gray-900">Chic <span class="text-amber-600">Gastro</span></span>
        </header>

        <main class="px-4 sm:px-6 lg:px-10 py-8 max-w-7xl mx-auto">
            <p class="text-sm text-gray-500 mb-1">@yield('eyebrow', 'Administration')</p>
            <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                <h1 class="text-3xl font-bold text-gray-900">@yield('title', 'Tableau de bord')</h1>
                <div class="flex gap-2">@yield('actions')</div>
            </div>

            @yield('content')

            <p class="text-xs text-gray-400 mt-10">Mis à jour le {{ now()->format('d M Y à H:i') }}</p>
        </main>
    </div>
</div>
</body>
</html>
