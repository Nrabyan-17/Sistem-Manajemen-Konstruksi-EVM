@php
    $routeName = request()->route() ? request()->route()->getName() : 'dashboard';
    $isProjectDetail = ($routeName === 'projects.show');
    $initialTab = $isProjectDetail ? 'project-detail' : ($routeName === 'reports.evm' ? 'evm' : ($routeName === 'login' ? 'dashboard' : $routeName));
@endphp
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

    <link rel="prefetch" href="{{ route('dashboard') }}">
    <link rel="prefetch" href="{{ route('projects') }}">
    <link rel="prefetch" href="{{ route('evm') }}">
    <link rel="prefetch" href="{{ route('reports') }}">
    <link rel="prefetch" href="{{ route('users') }}">
</head>
<body class="h-full overflow-hidden text-slate-800 antialiased font-sans no-scrollbar" 
      x-data="{ 
          isSidebarOpen: true, 
          showLogoutModal: false,
          activeTab: '{{ $initialTab }}',
          switchTab(tabName, targetUrl) {
              this.activeTab = tabName;
              if (targetUrl) {
                  window.history.pushState({ tab: tabName }, '', targetUrl);
              }
              this.$nextTick(() => {
                  if (typeof window.resetPageScroll === 'function') {
                      window.resetPageScroll();
                  } else {
                      const mainEl = document.querySelector('main');
                      if (mainEl) mainEl.scrollTop = 0;
                      window.scrollTo(0, 0);
                  }
              });
              if (tabName === 'dashboard') {
                  this.$nextTick(() => window.dispatchEvent(new CustomEvent('dashboard-activated')));
              }
          },
          init() {
              const activateDashboardIfNeeded = () => {
                  if (this.activeTab === 'dashboard') {
                      this.$nextTick(() => {
                          if (typeof window.resetPageScroll === 'function') {
                              window.resetPageScroll();
                          }
                          window.dispatchEvent(new CustomEvent('dashboard-activated'));
                      });
                  }
              };

              activateDashboardIfNeeded();

              window.addEventListener('popstate', (e) => {
                  const path = window.location.pathname.replace('/', '') || 'dashboard';
                  if (['dashboard', 'projects', 'evm', 'reports', 'approvals', 'users'].includes(path)) {
                      this.activeTab = path;
                      if (typeof window.resetPageScroll === 'function') {
                          this.$nextTick(() => window.resetPageScroll());
                      }
                      if (path === 'dashboard') {
                          this.$nextTick(() => window.dispatchEvent(new CustomEvent('dashboard-activated')));
                      }
                  }
              });
          }
      }">

    <div class="flex h-screen overflow-hidden bg-slate-50">

        <!-- Sidebar -->
        <aside :style="isSidebarOpen ? 'width: 256px; min-width: 256px;' : 'width: 84px; min-width: 84px;'"
               class="flex-none bg-white border-r border-slate-200 flex flex-col transition-all duration-300 overflow-hidden">
            
            <!-- Brand -->
            <div class="h-16 border-b border-slate-200 flex items-center px-4 shrink-0">
                <div class="flex items-center gap-3 min-w-0" :class="{ 'justify-center w-full': !isSidebarOpen }">
                    <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center shrink-0 shadow-md shadow-blue-500/20">
                        <svg viewBox="0 0 24 24" class="w-5 h-5 text-white" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <rect x="3.4" y="3.4" width="6.8" height="6.8" rx="1.8" stroke="currentColor" stroke-width="1.8"/>
                            <rect x="13.8" y="3.4" width="6.8" height="6.8" rx="1.8" stroke="currentColor" stroke-width="1.8"/>
                            <rect x="3.4" y="13.8" width="6.8" height="6.8" rx="1.8" stroke="currentColor" stroke-width="1.8"/>
                            <rect x="13.8" y="13.8" width="6.8" height="6.8" rx="1.8" stroke="currentColor" stroke-width="1.8"/>
                        </svg>
                    </div>
                    <div class="leading-tight truncate" x-show="isSidebarOpen" x-transition>
                        <p class="text-xs font-bold text-slate-900 truncate">Sistem Manajemen Konstruksi</p>
                        <p class="text-[10px] font-bold tracking-wider text-blue-600 uppercase">PT Bintang Gandari</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-3 py-4 space-y-1.5 overflow-y-auto no-scrollbar">
                <a href="/dashboard"
                   @click.prevent="switchTab('dashboard', '/dashboard')"
                   :class="activeTab === 'dashboard' ? 'bg-blue-600 text-white font-semibold shadow-sm shadow-blue-500/30' : 'text-slate-600 hover:bg-slate-100'"
                   :class="{ 'justify-center px-2': !isSidebarOpen }"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition cursor-pointer">
                    <svg viewBox="0 0 24 24" class="w-5 h-5 shrink-0" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M4 10.5L12 4L20 10.5V18.5C20 19.05 19.55 19.5 19 19.5H5C4.45 19.5 4 19.05 4 18.5V10.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                        <path d="M9.2 19.5V12.5H14.8V19.5" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                    </svg>
                    <span x-show="isSidebarOpen" x-transition>Dashboard</span>
                </a>
                
                <a href="/projects"
                   @click.prevent="switchTab('projects', '/projects')"
                   :class="activeTab === 'projects' ? 'bg-blue-600 text-white font-semibold shadow-sm shadow-blue-500/30' : 'text-slate-600 hover:bg-slate-100'"
                   :class="{ 'justify-center px-2': !isSidebarOpen }"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition cursor-pointer">
                    <svg viewBox="0 0 24 24" class="w-5 h-5 shrink-0" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M4.5 18.5V6.5C4.5 5.4 5.4 4.5 6.5 4.5H17.5C18.6 4.5 19.5 5.4 19.5 6.5V18.5H4.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                        <path d="M8.5 8.5H15.5M8.5 12H15.5M8.5 15.5H13.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                    <span x-show="isSidebarOpen" x-transition>Projects</span>
                </a>

                <a href="/evm"
                   @click.prevent="switchTab('evm', '/evm')"
                   :class="activeTab === 'evm' ? 'bg-blue-600 text-white font-semibold shadow-sm shadow-blue-500/30' : 'text-slate-600 hover:bg-slate-100'"
                   :class="{ 'justify-center px-2': !isSidebarOpen }"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition cursor-pointer">
                    <svg viewBox="0 0 24 24" class="w-5 h-5 shrink-0" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M5 18.5V9.5M12 18.5V5.5M19 18.5V12.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M3.5 18.5H20.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M7 8L10 5L13 8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M14 11L17 8L20 11" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span x-show="isSidebarOpen" x-transition>EVM Analysis</span>
                </a>

                <a href="/reports"
                   @click.prevent="switchTab('reports', '/reports')"
                   :class="activeTab === 'reports' ? 'bg-blue-600 text-white font-semibold shadow-sm shadow-blue-500/30' : 'text-slate-600 hover:bg-slate-100'"
                   :class="{ 'justify-center px-2': !isSidebarOpen }"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition cursor-pointer">
                    <svg viewBox="0 0 24 24" class="w-5 h-5 shrink-0" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M7 4.5H13.5L18.5 9.5V18.5C18.5 19.05 18.05 19.5 17.5 19.5H7C6.45 19.5 6 19.05 6 18.5V5.5C6 4.95 6.45 4.5 7 4.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                        <path d="M13.5 4.5V9.5H18.5" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                        <path d="M8.5 13.5H15.5M8.5 16.5H13.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                    <span x-show="isSidebarOpen" x-transition>Reports</span>
                </a>
                
                <a href="/approvals"
                   @click.prevent="switchTab('approvals', '/approvals')"
                   :class="activeTab === 'approvals' ? 'bg-blue-600 text-white font-semibold shadow-sm shadow-blue-500/30' : 'text-slate-600 hover:bg-slate-100'"
                   :class="{ 'justify-center px-2': !isSidebarOpen }"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition cursor-pointer">
                    <svg viewBox="0 0 24 24" class="w-5 h-5 shrink-0" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M12 4.2C7.5 4.2 3.8 7.5 3 11.8C3.8 16.1 7.5 19.4 12 19.4C16.5 19.4 20.2 16.1 21 11.8C20.2 7.5 16.5 4.2 12 4.2Z" stroke="currentColor" stroke-width="1.8"/>
                        <circle cx="12" cy="11.8" r="3.1" stroke="currentColor" stroke-width="1.8"/>
                    </svg>
                    <span x-show="isSidebarOpen" x-transition>Approvals</span>
                </a>

                <a href="/users"
                   @click.prevent="switchTab('users', '/users')"
                   :class="activeTab === 'users' ? 'bg-blue-600 text-white font-semibold shadow-sm shadow-blue-500/30' : 'text-slate-600 hover:bg-slate-100'"
                   :class="{ 'justify-center px-2': !isSidebarOpen }"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition cursor-pointer">
                    <svg viewBox="0 0 24 24" class="w-5 h-5 shrink-0" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <circle cx="9" cy="8" r="3.2" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M4.5 18.2C5.3 15.5 7.3 14.2 9.5 14.2C11.7 14.2 13.7 15.5 14.5 18.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <circle cx="17.2" cy="9" r="2.7" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M15.2 17.1C15.8 15.8 16.9 15 18.4 15C19.1 15 19.7 15.2 20.3 15.6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                    <span x-show="isSidebarOpen" x-transition>Users</span>
                </a>
            </nav>

            <!-- Bottom Menu -->
            <div class="px-3 py-4 border-t border-slate-200 space-y-1">
                <button type="button" @click="showLogoutModal = true" :class="{ 'justify-center px-2': !isSidebarOpen }" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-red-600 hover:bg-red-50 text-sm font-semibold transition cursor-pointer">
                    <svg viewBox="0 0 24 24" class="w-5 h-5 shrink-0" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M8.5 7.5V6.8C8.5 5.2 9.8 4 11.4 4H12.6C14.2 4 15.5 5.2 15.5 6.8V7.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M6 9.5H18C18.6 9.5 19 9.9 19 10.5V17.2C19 18.5 17.9 19.5 16.6 19.5H7.4C6.1 19.5 5 18.5 5 17.2V10.5C5 9.9 5.4 9.5 6 9.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                        <path d="M12 12.2V16.2M9.8 14.2H14.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                    <span x-show="isSidebarOpen" x-transition>Sign Out</span>
                </button>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- Top Header -->
            <header class="h-16 shrink-0 flex items-center justify-between gap-4 px-4 sm:px-6 bg-white border-b border-slate-200">
                <div class="flex items-center gap-3">
                    <button @click="isSidebarOpen = !isSidebarOpen" 
                            class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition shrink-0 cursor-pointer"
                            title="Toggle Sidebar"
                            aria-label="Toggle Sidebar">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div class="hidden sm:block leading-tight pl-1">
                        <h2 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight">Selamat datang kembali, Admin!</h2>
                    </div>
                </div>

                <!-- Right Header Actions -->
                <div class="flex items-center gap-3 sm:gap-4">
                    {{-- Real-Time Clock & Date Widget --}}
                    <div x-data="{
                            dateStr: '',
                            timeStr: '',
                            updateClock() {
                                const now = new Date();
                                const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                const dayName = days[now.getDay()];
                                const day = String(now.getDate()).padStart(2, '0');
                                const monthName = months[now.getMonth()];
                                const year = now.getFullYear();
                                const hours = String(now.getHours()).padStart(2, '0');
                                const minutes = String(now.getMinutes()).padStart(2, '0');
                                const seconds = String(now.getSeconds()).padStart(2, '0');
                                
                                this.dateStr = `${dayName}, ${day} ${monthName} ${year}`;
                                this.timeStr = `${hours}:${minutes}:${seconds} WIB`;
                            }
                         }" 
                         x-init="updateClock(); setInterval(() => updateClock(), 1000)" 
                         class="hidden md:flex items-center gap-2.5 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200/80 text-slate-700 text-xs shrink-0 shadow-2xs">
                        <div class="p-1 rounded-lg bg-blue-50 text-blue-600 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex items-center gap-2">
                            <span x-text="dateStr" class="font-bold text-slate-700"></span>
                            <span class="text-slate-300">•</span>
                            <span x-text="timeStr" class="font-mono font-extrabold text-blue-600"></span>
                        </div>
                    </div>

                    <div class="h-6 w-px bg-slate-200 hidden md:block"></div>

                    <!-- Account Profile -->
                    <div class="flex items-center gap-3">
                        <div class="text-right leading-tight hidden sm:block">
                            <p class="text-sm font-bold text-slate-900">Admin</p>
                            <p class="text-[10px] font-semibold tracking-wider text-blue-600 uppercase">Executive Director</p>
                        </div>
                        <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center text-white text-sm font-bold shadow-md shadow-blue-500/25 shrink-0">
                            AD
                        </div>
                    </div>
                </div>
            </header>

            <!-- Scrollable Content -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 space-y-6 no-scrollbar">
                @if($isProjectDetail)
                    {{ $slot }}
                @else
                    <div x-show="activeTab === 'dashboard'">
                        @livewire('dashboard')
                    </div>
                    <div x-show="activeTab === 'projects'" style="display: none;">
                        @livewire('projects')
                    </div>
                    <div x-show="activeTab === 'evm'" style="display: none;">
                        @livewire('reports')
                    </div>
                    <div x-show="activeTab === 'reports'" style="display: none;">
                        @livewire('reporting-center')
                    </div>
                    <div x-show="activeTab === 'approvals'" style="display: none;">
                        @livewire('approval-center')
                    </div>
                    <div x-show="activeTab === 'users'" style="display: none;">
                        @livewire('users')
                    </div>
                @endif
            </main>

        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div x-show="showLogoutModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
         style="display: none;">
        
        <div @click.away="showLogoutModal = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-white rounded-2xl shadow-xl border border-slate-200 max-w-sm w-full p-6 text-center space-y-4">
            
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto shrink-0 border border-rose-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </div>

            <div>
                <h3 class="text-lg font-bold text-slate-900">Konfirmasi Sign Out</h3>
                <p class="text-xs text-slate-500 mt-1">Apakah Anda yakin ingin keluar dari sistem dashboard PT Bintang Gandari?</p>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="button" @click="showLogoutModal = false"
                        class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold transition cursor-pointer">
                    Batal
                </button>
                <a href="/login"
                   class="flex-1 px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold transition shadow-sm flex items-center justify-center cursor-pointer">
                    Ya, Sign Out
                </a>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
