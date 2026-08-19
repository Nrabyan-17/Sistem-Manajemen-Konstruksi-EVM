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
<body class="h-full overflow-hidden text-slate-800 antialiased font-sans" x-data="{ isSidebarOpen: false }">

    <div class="flex h-screen overflow-hidden bg-slate-50">
        <!-- Mobile Sidebar Backdrop -->
        <div x-show="isSidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="isSidebarOpen = false"
             class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm md:hidden" style="display: none;"></div>

        <!-- Sidebar -->
        <aside :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200 flex flex-col transition-transform duration-300 md:translate-x-0 md:static md:shrink-0">
            
            <!-- Brand -->
            <div class="flex items-center justify-between px-6 h-16 border-b border-slate-200">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center shrink-0 shadow-md shadow-blue-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                            <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                            <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                            <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                        </svg>
                    </div>
                    <div class="leading-tight">
                        <p class="text-sm font-bold text-slate-900">PT Bintang Gandari</p>
                        <p class="text-[10px] font-bold tracking-widest text-blue-600">EVM DASHBOARD</p>
                    </div>
                </div>
                <button @click="isSidebarOpen = false" class="md:hidden text-slate-400 hover:text-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-3 py-4 space-y-1.5 overflow-y-auto">
                <a href="/dashboard" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition {{ request()->is('dashboard*') ? 'bg-blue-600 text-white font-semibold shadow-sm shadow-blue-500/30' : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
                
                <a href="/projects" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition {{ request()->is('projects*') ? 'bg-blue-600 text-white font-semibold shadow-sm shadow-blue-500/30' : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2M5 21H3m16 0h-5v-4a1 1 0 00-1-1h-2a1 1 0 00-1 1v4H5m5-17h4m-4 4h4m-4 4h4" />
                    </svg>
                    Projects
                </a>
                <a href="/reports" wire:navigate
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition {{ request()->is('reports*') ? 'bg-blue-600 text-white font-semibold shadow-sm shadow-blue-500/30' : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a4 4 0 014-4h4M9 17H7a2 2 0 01-2-2V5a2 2 0 012-2h6l4 4v3M9 17v3a1 1 0 001 1h9a1 1 0 001-1v-6a1 1 0 00-1-1h-3" />
                    </svg>
                    Reports
                </a>

                <a href="/addendum" wire:navigate
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition {{ request()->is('addendum*') ? 'bg-blue-600 text-white font-semibold shadow-sm shadow-blue-500/30' : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Addendum
                </a>
                
                <a href="#" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-slate-600 hover:bg-slate-100 text-sm font-medium transition">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Approvals
                    </div>
                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-orange-100 text-orange-700">8</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-600 hover:bg-slate-100 text-sm font-medium transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" />
                    </svg>
                    Users
                </a>
            </nav>

            <!-- Bottom Menu -->
            <div class="px-3 py-4 border-t border-slate-200 space-y-1">
                <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-600 hover:bg-slate-100 text-sm font-medium transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Settings
                </a>
                <a href="/login" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-red-600 hover:bg-red-50 text-sm font-semibold transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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
                    <button @click="isSidebarOpen = true" class="md:hidden p-2 rounded-lg text-slate-500 hover:bg-slate-100">
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
                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- Notification -->
                    <button class="relative w-10 h-10 flex items-center justify-center rounded-xl text-slate-500 hover:bg-slate-100 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="absolute top-2 right-2 w-2.5 h-2.5 rounded-full bg-red-500 ring-2 ring-white"></span>
                    </button>

                    <div class="w-px h-7 bg-slate-200"></div>

                    <!-- Profile -->
                    <div class="flex items-center gap-3 pl-1">
                        <div class="text-right leading-tight hidden sm:block">
                            <p class="text-sm font-bold text-slate-900">Alex Thompson</p>
                            <p class="text-[10px] font-semibold tracking-wider text-blue-600 uppercase">Executive Director</p>
                        </div>
                        <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center text-white text-sm font-bold shadow-md shadow-blue-500/25 shrink-0">
                            AT
                        </div>
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
