<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'EVM Dashboard - PT Bintang Gandari' }}</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full overflow-hidden text-slate-800 antialiased font-sans" 
      x-data="{ isSidebarOpen: true }">

    <div class="flex h-screen overflow-hidden bg-slate-50">

        <!-- Sidebar -->
        <aside :style="isSidebarOpen ? 'width: 256px; min-width: 256px;' : 'width: 0; min-width: 0;'"
               class="flex-none bg-white border-r border-slate-200 flex flex-col transition-all duration-300 overflow-hidden">
            
            <!-- Brand -->
            <div class="h-16 border-b border-slate-200 flex items-center px-4 shrink-0">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center shrink-0 shadow-md shadow-blue-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                            <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                            <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                            <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                        </svg>
                    </div>
                    <div class="leading-tight truncate">
                        <p class="text-sm font-bold text-slate-900 truncate">PT Bintang Gandari</p>
                        <p class="text-[10px] font-bold tracking-widest text-blue-600">EVM DASHBOARD</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-3 py-4 space-y-1.5 overflow-y-auto">
                <a href="/dashboard" wire:navigate.hover
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition {{ request()->is('dashboard*') ? 'bg-blue-600 text-white font-semibold shadow-sm shadow-blue-500/30' : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
                
                <a href="/projects" wire:navigate.hover
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition {{ request()->is('projects*') ? 'bg-blue-600 text-white font-semibold shadow-sm shadow-blue-500/30' : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2M5 21H3m16 0h-5v-4a1 1 0 00-1-1h-2a1 1 0 00-1 1v4H5m5-17h4m-4 4h4m-4 4h4" />
                    </svg>
                    Projects
                </a>
                <a href="/evm" wire:navigate.hover
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition {{ request()->is('evm*') ? 'bg-blue-600 text-white font-semibold shadow-sm shadow-blue-500/30' : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M7 8h10v10" />
                    </svg>
                    EVM Analysis
                </a>
                <a href="/reports" wire:navigate.hover
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition {{ request()->is('reports*') ? 'bg-blue-600 text-white font-semibold shadow-sm shadow-blue-500/30' : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a4 4 0 014-4h4M9 17H7a2 2 0 01-2-2V5a2 2 0 012-2h6l4 4v3M9 17v3a1 1 0 001 1h9a1 1 0 001-1v-6a1 1 0 00-1-1h-3" />
                    </svg>
                    Reports
                </a>
                
                <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-600 hover:bg-slate-100 text-sm font-medium transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    Approvals
                </a>

                <a href="/users" wire:navigate.hover
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition {{ request()->is('users*') ? 'bg-blue-600 text-white font-semibold shadow-sm shadow-blue-500/30' : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" />
                    </svg>
                    Users
                </a>
            </nav>

            <!-- Bottom Menu -->
            <div class="px-3 py-4 border-t border-slate-200 space-y-1">
                <a href="/login" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-red-600 hover:bg-red-50 text-sm font-semibold transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Sign Out
                </a>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- Top Header -->
            <header class="h-16 shrink-0 flex items-center justify-between gap-4 px-4 sm:px-6 bg-white border-b border-slate-200">
                <div class="flex items-center gap-3 flex-1 max-w-lg">
                    <button @click="isSidebarOpen = !isSidebarOpen" 
                            class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition shrink-0"
                            title="Toggle Sidebar"
                            aria-label="Toggle Sidebar">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Search -->
                    <div class="relative w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10.5A6.5 6.5 0 114 10.5a6.5 6.5 0 0113 0z" />
                        </svg>
                        <input type="text" placeholder="Search projects, EVM reports, milestones..." class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
                    </div>
                </div>

                <!-- Right Header Actions -->
                <div class="flex items-center gap-3">
                    <div class="text-right leading-tight hidden sm:block">
                        <p class="text-sm font-bold text-slate-900">Admin</p>
                        <p class="text-[10px] font-semibold tracking-wider text-blue-600 uppercase">Executive Director</p>
                    </div>
                    <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center text-white text-sm font-bold shadow-md shadow-blue-500/25 shrink-0">
                        AD
                    </div>
                </div>
            </header>

            <!-- Scrollable Content -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 space-y-6">
                {{ $slot }}
            </main>

        </div>
    </div>

    @stack('scripts')
</body>
</html>
