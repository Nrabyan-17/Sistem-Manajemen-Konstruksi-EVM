{{-- Temporary preview shell, adapted from layouts/app. Replace with the shared
     application layout once role integration is approved; not a second app shell. --}}
<div class="flex h-screen h-dvh overflow-hidden bg-slate-50 font-sans text-slate-800"
     x-data="{
        sidebarOpen: true, mobileOpen: false, desktop: window.innerWidth >= 1024,
        toggleMenu() { if (this.desktop) { this.sidebarOpen = !this.sidebarOpen; } else { this.mobileOpen = !this.mobileOpen; } },
        closeMenu() { this.mobileOpen = false; this.$refs.menuToggle.focus(); },
        selectScreen() { this.mobileOpen = false; this.$nextTick(() => { this.$refs.content.scrollTo({ top: 0 }); this.$refs.content.focus({ preventScroll: true }); }); }
     }"
     @resize.window="desktop = window.innerWidth >= 1024; if (desktop) mobileOpen = false"
     @keydown.escape.window="if (mobileOpen) closeMenu()">
    <a href="#pelaksana-content" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-xl focus:bg-white focus:p-3 focus:text-blue-700">Lewati navigasi</a>
    <div x-cloak x-show="mobileOpen" x-transition.opacity @click="closeMenu()" class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-xs lg:hidden" aria-hidden="true"></div>

    <aside id="pelaksana-sidebar" aria-label="Sidebar Pelaksana" :inert="!desktop && !mobileOpen"
           :role="!desktop && mobileOpen ? 'dialog' : null" :aria-modal="!desktop && mobileOpen ? 'true' : null"
           :style="desktop ? (sidebarOpen ? 'width:256px;min-width:256px' : 'width:72px;min-width:72px') : ''"
           :class="mobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           @keydown.tab="if (!desktop && mobileOpen && $event.target === $refs.lastMenu && !$event.shiftKey) { $event.preventDefault(); $refs.closeDrawer.focus(); } else if (!desktop && mobileOpen && $event.target === $refs.closeDrawer && $event.shiftKey) { $event.preventDefault(); $refs.lastMenu.focus(); }"
           class="fixed inset-y-0 left-0 z-50 flex w-72 max-w-[85vw] shrink-0 flex-col overflow-hidden border-r border-slate-200 bg-white transition-all duration-300 ease-in-out lg:static lg:max-w-none lg:shadow-none">
        <div class="flex h-16 shrink-0 items-center justify-between gap-2 border-b border-slate-200 px-5">
            <div class="flex min-w-0 items-center gap-3">
                <img src="/images/logo-bintang-gandari.jpg" alt="Logo PT Bintang Gandari" class="h-9 w-9 shrink-0 rounded-md object-contain">
                <div x-show="!desktop || sidebarOpen" class="min-w-0 overflow-hidden leading-tight">
                    <p class="truncate text-xs font-bold text-slate-900">Sistem Manajemen Konstruksi</p>
                    <p class="truncate text-[10px] font-bold uppercase tracking-wider text-blue-600">PT Bintang Gandari</p>
                </div>
            </div>
            <button x-ref="closeDrawer" type="button" @click="closeMenu()" aria-label="Tutup menu" class="shrink-0 rounded-xl p-2 text-slate-500 hover:bg-slate-100 lg:hidden">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" d="m6 6 12 12M18 6 6 18" /></svg>
            </button>
        </div>
        <nav aria-label="Navigasi pratinjau Pelaksana" class="flex-1 space-y-1.5 overflow-y-auto px-3.5 py-4">
            @foreach(['dashboard' => 'Dashboard', 'projects' => 'Proyek Saya', 'form' => 'Input Progres', 'reports' => 'Riwayat Laporan'] as $key => $label)
                <button type="button" wire:click="$set('screen', '{{ $key }}')"
                        @click="selectScreen()" @if($loop->last) x-ref="lastMenu" @endif
                        aria-label="{{ $label }}" title="{{ $label }}" @if($screen === $key) aria-current="page" @endif
                        class="flex min-h-11 w-full items-center gap-3.5 rounded-xl px-3.5 py-2.5 text-left text-sm font-semibold transition {{ $screen === $key ? 'bg-blue-600 text-white shadow-sm shadow-blue-500/30' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">
                    <svg viewBox="0 0 24 24" class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        @if($key === 'dashboard')
                            <path d="M4 10.5 12 4l8 6.5v8a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-8Z" /><path d="M9.2 19.5v-7h5.6v7" />
                        @elseif($key === 'projects')
                            <path d="M4.5 18.5v-12a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v12h-15Z" /><path d="M8.5 8.5h7M8.5 12h7M8.5 15.5h5" />
                        @elseif($key === 'form')
                            <path d="M14 4.5H7a1 1 0 0 0-1 1v13a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V12M14 4.5l4 4M10 14l1-4 6-6 3 3-6 6-4 1Z" />
                        @else
                            <path d="M7 4.5h6.5l5 5v9a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1v-13a1 1 0 0 1 1-1Z" /><path d="M13.5 4.5v5h5M8.5 13.5h7M8.5 16.5h5" />
                        @endif
                    </svg>
                    <span x-show="!desktop || sidebarOpen" class="whitespace-nowrap">{{ $label }}</span>
                </button>
            @endforeach
        </nav>
        <div class="flex items-center gap-3 px-3.5 py-3">
            <div aria-hidden="true" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-sm font-bold text-white shadow-md shadow-blue-500/25">PL</div>
            <div x-show="!desktop || sidebarOpen" class="min-w-0 leading-tight">
                <p class="text-sm font-bold text-slate-900">Pelaksana Lapangan</p>
                <p class="mt-1 text-[10px] font-semibold uppercase tracking-wider text-blue-600">{{ $demo ? 'DEMO — bukan akun login' : 'Pratinjau — belum terintegrasi' }}</p>
            </div>
        </div>
        <div class="border-t border-slate-200 px-3.5 py-4">
            <button type="button" disabled aria-describedby="preview-signout-note" title="Tidak ada sesi login pada preview" class="flex min-h-11 w-full items-center gap-3.5 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-red-600 disabled:opacity-60">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m17 16 4-4-4-4M21 12H7m6 4v1a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1" /></svg>
                <span x-show="!desktop || sidebarOpen">Sign Out</span>
            </button>
            <p id="preview-signout-note" x-show="!desktop || sidebarOpen" class="mt-1 px-3.5 text-[10px] text-slate-500">Nonaktif pada preview tanpa login.</p>
        </div>
    </aside>

    <div class="flex min-w-0 flex-1 flex-col overflow-hidden" :inert="!desktop && mobileOpen">
        <header class="flex h-16 shrink-0 items-center justify-between gap-3 border-b border-slate-200 bg-white px-3.5 sm:px-6">
            <div class="flex min-w-0 items-center gap-2 sm:gap-3">
                <button x-ref="menuToggle" type="button" @click="toggleMenu(); if (mobileOpen) $nextTick(() => $refs.closeDrawer.focus())" aria-label="Buka atau ciutkan menu" aria-controls="pelaksana-sidebar" :aria-expanded="desktop ? sidebarOpen : mobileOpen" class="shrink-0 rounded-xl p-2 text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <div class="min-w-0 pl-0.5 leading-tight">
                    <p class="truncate text-sm font-extrabold tracking-tight text-slate-900 sm:text-base md:text-lg">Selamat datang, Pelaksana Lapangan!</p>
                    <p class="mt-0.5 truncate text-[11px] font-medium text-slate-500 sm:text-xs">Sistem Manajemen Konstruksi &amp; EVM PT Bintang Gandari</p>
                </div>
            </div>
            <div class="hidden shrink-0 items-center gap-2.5 rounded-full border border-slate-200/80 bg-slate-50 px-3.5 py-1.5 text-sm text-slate-700 shadow-2xs xl:flex"
                 x-data="{
                    dateStr: '', timeStr: '', timer: null,
                    tick() { const now = new Date(); this.dateStr = new Intl.DateTimeFormat('id-ID', { timeZone: 'Asia/Jakarta', weekday: 'short', day: '2-digit', month: 'short', year: 'numeric' }).format(now); this.timeStr = new Intl.DateTimeFormat('en-GB', { timeZone: 'Asia/Jakarta', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }).format(now) + ' WIB'; },
                    init() { this.tick(); this.timer = setInterval(() => this.tick(), 1000); },
                    destroy() { clearInterval(this.timer); }
                 }">
                <span x-text="dateStr" class="font-bold"></span><span aria-hidden="true" class="text-slate-300">•</span><span x-text="timeStr" class="font-mono font-extrabold text-blue-600"></span>
            </div>
        </header>
        <main id="pelaksana-content" x-ref="content" tabindex="-1" class="min-h-0 flex-1 overflow-y-auto overscroll-contain">
            <div class="space-y-4 p-3.5 sm:space-y-6 sm:p-5 lg:p-7">
                <div role="note" class="flex flex-wrap items-center gap-x-3 gap-y-1 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-xs leading-relaxed text-blue-800">
                    <strong class="shrink-0">{{ $demo ? 'DEMO · Pratinjau UI' : 'UI · Belum terintegrasi' }}</strong>
                    <span>{{ $demo ? 'Data DEMO untuk peninjauan tampilan. Tidak mewakili proyek atau akun operasional.' : 'Belum ada sumber data terhubung. Tidak ada data contoh yang dimuat otomatis.' }} Isian tidak disimpan dan akan hilang ketika berpindah halaman atau memuat ulang.</span>
                </div>
                @if($screen === 'projects')
                    <livewire:pelaksana-projects :projects="$projects" />
                @elseif($screen === 'reports')
                    <livewire:pelaksana-reports :reports="$reports" />
                @elseif($screen === 'form')
                    @if(count($projects))
                        <div><label for="preview-project" class="mb-2 block text-sm font-bold">Proyek untuk pratinjau</label>
                        <select id="preview-project" wire:model.live="projectId" class="min-h-11 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 sm:max-w-lg">
                            @foreach($projects as $project)<option value="{{ $project['id'] }}">{{ $project['name'] }}</option>@endforeach
                        </select></div>
                    @endif
                    <livewire:pelaksana-weekly-form :context="$context" :key="'weekly-'.($context['id'] ?? 'empty')" />
                @else
                    <livewire:pelaksana-dashboard :projects="$projects" :reports="$reports" />
                @endif
            </div>
        </main>
    </div>
</div>
