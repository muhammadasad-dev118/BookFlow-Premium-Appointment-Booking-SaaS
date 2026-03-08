<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: true, profileOpen: false, notifOpen: false }" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} — {{ config('app.name', 'BookFlow') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Livewire Styles -->
    @livewireStyles

    <style>
        :root {
            --sidebar-width: 260px;
            --header-height: 64px;
        }
        body { font-family: 'Inter', sans-serif; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            border-right: 1px solid rgba(148, 163, 184, 0.08);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar.collapsed { transform: translateX(-100%); }

        /* Nav item */
        .nav-item {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 16px; border-radius: 10px;
            color: #94a3b8; font-size: 14px; font-weight: 500;
            transition: all 0.2s ease;
        }
        .nav-item:hover { color: #e2e8f0; background: rgba(148, 163, 184, 0.08); }
        .nav-item.active {
            color: #f59e0b; background: rgba(245, 158, 11, 0.1);
            box-shadow: inset 3px 0 0 #f59e0b;
        }
        .nav-item svg { width: 20px; height: 20px; flex-shrink: 0; }

        /* Cards */
        .glass-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(148, 163, 184, 0.1);
            border-radius: 16px;
            transition: all 0.3s ease;
        }
        .glass-card:hover {
            border-color: rgba(148, 163, 184, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        /* Stat Card Gradients */
        .stat-gradient-1 { background: linear-gradient(135deg, rgba(245,158,11,0.15), rgba(245,158,11,0.05)); }
        .stat-gradient-2 { background: linear-gradient(135deg, rgba(34,197,94,0.15), rgba(34,197,94,0.05)); }
        .stat-gradient-3 { background: linear-gradient(135deg, rgba(59,130,246,0.15), rgba(59,130,246,0.05)); }
        .stat-gradient-4 { background: linear-gradient(135deg, rgba(168,85,247,0.15), rgba(168,85,247,0.05)); }

        /* Badge */
        .badge { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-success { background: rgba(34,197,94,0.15); color: #22c55e; }
        .badge-warning { background: rgba(245,158,11,0.15); color: #f59e0b; }
        .badge-danger { background: rgba(239,68,68,0.15); color: #ef4444; }
        .badge-info { background: rgba(59,130,246,0.15); color: #3b82f6; }

        /* Table */
        .dash-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .dash-table th { padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; border-bottom: 1px solid rgba(148,163,184,0.1); }
        .dash-table td { padding: 14px 16px; font-size: 14px; color: #cbd5e1; border-bottom: 1px solid rgba(148,163,184,0.05); }
        .dash-table tr:hover td { background: rgba(148,163,184,0.04); }

        /* Button */
        .btn-primary {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #0f172a; font-weight: 600; padding: 10px 20px;
            border-radius: 10px; font-size: 14px;
            transition: all 0.2s ease; border: none; cursor: pointer;
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(245,158,11,0.4); }

        .btn-secondary {
            background: rgba(148,163,184,0.1); color: #e2e8f0;
            font-weight: 500; padding: 10px 20px;
            border-radius: 10px; font-size: 14px;
            transition: all 0.2s ease; border: 1px solid rgba(148,163,184,0.15); cursor: pointer;
        }
        .btn-secondary:hover { background: rgba(148,163,184,0.15); }

        .btn-danger {
            background: rgba(239,68,68,0.15); color: #ef4444;
            font-weight: 500; padding: 10px 20px;
            border-radius: 10px; font-size: 14px;
            transition: all 0.2s ease; border: 1px solid rgba(239,68,68,0.2); cursor: pointer;
        }
        .btn-danger:hover { background: rgba(239,68,68,0.25); }

        /* Form Inputs */
        .form-input {
            width: 100%; padding: 10px 14px; font-size: 14px;
            background: rgba(15,23,42,0.6); border: 1px solid rgba(148,163,184,0.15);
            border-radius: 10px; color: #e2e8f0; outline: none;
            transition: all 0.2s ease;
        }
        .form-input:focus { border-color: #f59e0b; box-shadow: 0 0 0 3px rgba(245,158,11,0.15); }
        .form-input::placeholder { color: #475569; }

        .form-label { display: block; font-size: 13px; font-weight: 500; color: #94a3b8; margin-bottom: 6px; }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in { animation: fadeInUp 0.4s ease forwards; }
        .delay-1 { animation-delay: 0.05s; }
        .delay-2 { animation-delay: 0.1s; }
        .delay-3 { animation-delay: 0.15s; }
        .delay-4 { animation-delay: 0.2s; }
    </style>

    @stack('styles')
</head>
<body class="bg-[#0f172a] text-slate-200 antialiased min-h-screen">

    <!-- Sidebar -->
    <aside class="sidebar fixed top-0 left-0 h-full z-40 flex flex-col overflow-y-auto"
           :class="{ 'collapsed': !sidebarOpen }">

        <!-- Logo -->
        <div class="flex items-center gap-3 px-6 py-5 border-b border-white/5">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center">
                <svg class="w-5 h-5 text-slate-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <span class="text-lg font-bold text-white tracking-tight">BookFlow</span>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-4 space-y-1">
            @php
                $currentRoute = request()->route()?->getName() ?? '';
                $isAdmin = str_starts_with($currentRoute, 'admin.');
            @endphp

            @if($isAdmin)
                {{-- Admin Navigation --}}
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ $currentRoute === 'admin.dashboard' ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.tenants.index') }}" class="nav-item {{ str_starts_with($currentRoute, 'admin.tenants') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/></svg>
                    Tenants
                </a>
                <a href="{{ route('admin.users.index') }}" class="nav-item {{ str_starts_with($currentRoute, 'admin.users') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                    Users
                </a>
            @else
                {{-- Tenant Navigation --}}
                <a href="{{ route('dashboard') }}" class="nav-item {{ $currentRoute === 'dashboard' ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('appointments.index') }}" class="nav-item {{ str_starts_with($currentRoute, 'appointments') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                    Appointments
                </a>
                <a href="{{ route('services.index') }}" class="nav-item {{ str_starts_with($currentRoute, 'services') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg>
                    Services
                </a>
                <a href="{{ route('staff.index') }}" class="nav-item {{ str_starts_with($currentRoute, 'staff') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    Staff
                </a>
                <a href="{{ route('customers.index') }}" class="nav-item {{ str_starts_with($currentRoute, 'customers') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                    Customers
                </a>
                <a href="{{ route('business-hours.index') }}" class="nav-item {{ str_starts_with($currentRoute, 'business-hours') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Business Hours
                </a>

                <div class="pt-4 mt-4 border-t border-white/5">
                    <a href="{{ route('billing') }}" class="nav-item {{ $currentRoute === 'billing' ? 'active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                        Billing
                    </a>
                </div>
            @endif
        </nav>

        <!-- User Info -->
        <div class="px-4 py-4 border-t border-white/5">
            <div class="flex items-center gap-3 px-3">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-sm font-bold text-slate-900">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="transition-all duration-300" :style="sidebarOpen ? 'margin-left: var(--sidebar-width)' : 'margin-left: 0'">

        <!-- Top Header -->
        <header class="sticky top-0 z-30 bg-[#0f172a]/80 backdrop-blur-lg border-b border-white/5" style="height: var(--header-height);">
            <div class="flex items-center justify-between h-full px-6">
                <!-- Left: Toggle + Title -->
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                    </button>
                    <h1 class="text-lg font-semibold text-white">{{ $title ?? 'Dashboard' }}</h1>
                </div>

                <!-- Right: Actions -->
                <div class="flex items-center gap-3">
                    <!-- Notifications -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="relative p-2 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                            <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-amber-500 rounded-full text-[10px] font-bold text-slate-900 flex items-center justify-center">3</span>
                        </button>
                    </div>

                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-white/5 transition">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-xs font-bold text-slate-900">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" @click.away="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="absolute right-0 mt-2 w-48 glass-card py-2 z-50">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-slate-300 hover:text-white hover:bg-white/5 transition">
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-6">
            @if(session('success'))
                <div class="mb-6 p-4 glass-card border-l-4 border-green-500 text-green-400 text-sm animate-in">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 glass-card border-l-4 border-red-500 text-red-400 text-sm animate-in">
                    {{ session('error') }}
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>

    @livewireScripts

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>

    @stack('scripts')
</body>
</html>
