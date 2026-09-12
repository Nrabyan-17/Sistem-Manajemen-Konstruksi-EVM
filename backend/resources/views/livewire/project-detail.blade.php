<div x-data="{ 
    tab: 'overview',
    showWeeklyModal: false,
    showRabModal: false,
    showAddendumCostModal: false,
    showAddendumTimeModal: false,
    toastMessage: '',
    showToast: false
}"
@weekly-progress-saved.window="
    showWeeklyModal = false;
    toastMessage = $event.detail.message;
    showToast = true;
    setTimeout(() => showToast = false, 3000);
"
@rab-item-saved.window="
    showRabModal = false;
    toastMessage = $event.detail.message;
    showToast = true;
    setTimeout(() => showToast = false, 3000);
"
@addendum-cost-saved.window="
    showAddendumCostModal = false;
    toastMessage = $event.detail.message;
    showToast = true;
    setTimeout(() => showToast = false, 3000);
"
@addendum-time-saved.window="
    showAddendumTimeModal = false;
    toastMessage = $event.detail.message;
    showToast = true;
    setTimeout(() => showToast = false, 3000);
">

    {{-- TOAST NOTIFICATION --}}
    <div x-show="showToast" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed bottom-5 right-5 z-50 bg-slate-900 text-white px-5 py-3 rounded-xl shadow-lg flex items-center gap-3 border border-slate-800"
         style="display: none;">
        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
        <span x-text="toastMessage" class="text-sm font-semibold"></span>
    </div>

    {{-- BACK LINK --}}
    <a href="{{ route('projects') }}" wire:navigate.hover
       class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-blue-600 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Projects
    </a>

    {{-- PROJECT HEADER --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 lg:p-6 mt-4">
        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4">
            <div class="min-w-0">
                <div class="flex items-center gap-3 flex-wrap">
                    <span class="text-xs font-bold text-blue-600">{{ $project['project_id'] }}</span>
                    <span class="text-[10px] font-extrabold tracking-wider px-2 py-0.5 rounded-full
                        {{ $project['status'] === 'ON TRACK' ? 'bg-emerald-50 text-emerald-700' : '' }}
                        {{ $project['status'] === 'AT RISK' ? 'bg-amber-50 text-amber-700' : '' }}
                        {{ $project['status'] === 'CRITICAL' ? 'bg-rose-50 text-rose-700' : '' }}
                        {{ $project['status'] === 'COMPLETED' ? 'bg-blue-50 text-blue-700' : '' }}">
                        {{ $project['status'] }}
                    </span>
                    {{-- Addendum Risk Badge --}}
                    @if($addendumPercentage > 0)
                        <span class="text-[10px] font-extrabold tracking-wider px-2 py-0.5 rounded-full
                            {{ $riskLevel === 'ON TRACK' ? 'bg-emerald-50 text-emerald-700' : '' }}
                            {{ $riskLevel === 'AT RISK' ? 'bg-amber-50 text-amber-700' : '' }}
                            {{ $riskLevel === 'CRITICAL' ? 'bg-rose-50 text-rose-700' : '' }}"
                            title="Addendum {{ number_format($addendumPercentage, 1) }}% dari RAB">
                            ADD {{ number_format($addendumPercentage, 1) }}%
                        </span>
                    @endif
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 mt-1">{{ $project['project_name'] }}</h1>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-2 text-sm text-slate-500">
                    <span>{{ $project['location'] }}</span>
                    <span>Manager: {{ $project['project_manager'] }}</span>
                    <span>Client: {{ $project['client'] }}</span>
                    <span>SPK: {{ $project['spk_number'] }}</span>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <button @click="showWeeklyModal = true"
                        class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold transition shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Input Lap. Mingguan
                </button>
            </div>
        </div>
    </div>

    {{-- TABS --}}
    <div class="flex items-center gap-1 bg-slate-100 rounded-xl p-1 w-fit flex-wrap mt-6">
        <button @click="tab = 'overview'" :class="tab === 'overview' ? 'bg-blue-600 text-white' : 'text-slate-500'" class="px-4 py-2 rounded-lg text-sm font-semibold transition">Overview</button>
        <button @click="tab = 'evm'" :class="tab === 'evm' ? 'bg-blue-600 text-white' : 'text-slate-500'" class="px-4 py-2 rounded-lg text-sm font-semibold transition">EVM &amp; S-Curve</button>
        <button @click="tab = 'financial'" :class="tab === 'financial' ? 'bg-blue-600 text-white' : 'text-slate-500'" class="px-4 py-2 rounded-lg text-sm font-semibold transition">Financial</button>
        <button @click="tab = 'boq'" :class="tab === 'boq' ? 'bg-blue-600 text-white' : 'text-slate-500'" class="px-4 py-2 rounded-lg text-sm font-semibold transition">BOQ</button>
        <button @click="tab = 'addendum'" :class="tab === 'addendum' ? 'bg-blue-600 text-white' : 'text-slate-500'" class="px-4 py-2 rounded-lg text-sm font-semibold transition">Addendum</button>
        <button @click="tab = 'activity'" :class="tab === 'activity' ? 'bg-blue-600 text-white' : 'text-slate-500'" class="px-4 py-2 rounded-lg text-sm font-semibold transition">Activity</button>
    </div>

    {{-- TAB: OVERVIEW --}}
    <div x-show="tab === 'overview'" class="space-y-4 mt-4">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <h2 class="text-base font-bold text-slate-900 mb-4">Project Information</h2>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 text-sm">
                <div><p class="text-xs text-slate-400">Project ID</p><p class="font-semibold text-slate-800 mt-1">{{ $project['project_id'] }}</p></div>
                <div><p class="text-xs text-slate-400">SPK Number</p><p class="font-semibold text-slate-800 mt-1">{{ $project['spk_number'] }}</p></div>
                <div><p class="text-xs text-slate-400">Client</p><p class="font-semibold text-slate-800 mt-1">{{ $project['client'] }}</p></div>
                <div><p class="text-xs text-slate-400">Project Manager</p><p class="font-semibold text-slate-800 mt-1">{{ $project['project_manager'] }}</p></div>
                <div><p class="text-xs text-slate-400">Location</p><p class="font-semibold text-slate-800 mt-1">{{ $project['location'] }}</p></div>
                <div><p class="text-xs text-slate-400">Contract Value</p><p class="font-semibold text-slate-800 mt-1" title="{{ \App\Support\CurrencyHelper::formatFull($project['contract_value']) }}">{{ \App\Support\CurrencyHelper::format($project['contract_value']) }}</p></div>
                <div><p class="text-xs text-slate-400">Start Date (SPK)</p><p class="font-semibold text-slate-800 mt-1">{{ date('d M Y', strtotime($project['start_date'])) }}</p></div>
                <div>
                    <p class="text-xs text-slate-400">BAST Date</p>
                    <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                        <p class="font-semibold text-slate-800">{{ date('d M Y', strtotime($effectiveBastDate ?: $project['bast_date'])) }}</p>
                        @if($totalDaysAdded > 0)
                            <span class="text-[10px] font-extrabold px-1.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700" title="Total perpanjangan waktu addendum">
                                +{{ $totalDaysAdded }}h
                            </span>
                        @endif
                    </div>
                </div>
                <div><p class="text-xs text-slate-400">Status</p><p class="font-semibold text-slate-800 mt-1">{{ $project['status'] }}</p></div>
            </div>
        </div>

        {{-- EVM Summary Cards in Overview --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4">
                <p class="text-[11px] font-semibold text-blue-600">Progres Rencana (BCWS)</p>
                @php $bcwsPct = round(($bcws / $bac) * 100, 2); @endphp
                <p class="text-lg font-extrabold text-slate-900 mt-1">{{ number_format($bcwsPct, 2) }}%</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4">
                <p class="text-[11px] font-semibold text-emerald-600">Progres Realisasi (BCWP)</p>
                <p class="text-lg font-extrabold text-slate-900 mt-1">{{ number_format($project['progress'], 2) }}%</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4">
                <p class="text-[11px] font-semibold text-orange-600">Actual Cost</p>
                <p class="text-lg font-extrabold text-slate-900 mt-1" title="{{ \App\Support\CurrencyHelper::formatFull($acwp) }}">{{ \App\Support\CurrencyHelper::format($acwp) }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4">
                <p class="text-[11px] font-semibold {{ $deviasi <= 0 ? 'text-emerald-600' : 'text-rose-600' }}">Deviasi (BCWS - BCWP)</p>
                <p class="text-lg font-extrabold mt-1 {{ $deviasi <= 0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $deviasi > 0 ? '+' : '' }}{{ number_format($deviasi, 2) }}%</p>
            </div>
        </div>

        {{-- Last Update --}}
        @if(isset($project['last_updated']))
            <p class="text-xs text-slate-400">Last Update Data: <span class="font-semibold text-slate-600">{{ date('d M Y', strtotime($project['last_updated'])) }}</span></p>
        @endif
    </div>

    {{-- TAB: EVM & S-CURVE --}}
    <div x-show="tab === 'evm'" class="space-y-6 mt-4">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <h2 class="text-base font-bold text-slate-900">EVM S-Curve Analysis</h2>
            <p class="text-xs text-slate-500 mt-0.5">Budgeted Cost vs Earned Value vs Actual Cost</p>

            <div class="grid grid-cols-3 gap-3 mt-4">
                <div class="rounded-xl bg-blue-50 p-3">
                    <p class="text-[11px] font-semibold text-blue-700">BCWS (Planned)</p>
                    <p class="text-lg font-extrabold text-blue-800 mt-0.5" title="{{ \App\Support\CurrencyHelper::formatFull($bcws) }}">{{ \App\Support\CurrencyHelper::format($bcws) }}</p>
                </div>
                <div class="rounded-xl bg-emerald-50 p-3">
                    <p class="text-[11px] font-semibold text-emerald-700">BCWP (Earned)</p>
                    <p class="text-lg font-extrabold text-emerald-800 mt-0.5" title="{{ \App\Support\CurrencyHelper::formatFull($bcwp) }}">{{ \App\Support\CurrencyHelper::format($bcwp) }}</p>
                </div>
                <div class="rounded-xl bg-orange-50 p-3">
                    <p class="text-[11px] font-semibold text-orange-700">ACWP (Actual)</p>
                    <p class="text-lg font-extrabold text-orange-800 mt-0.5" title="{{ \App\Support\CurrencyHelper::formatFull($acwp) }}">{{ \App\Support\CurrencyHelper::format($acwp) }}</p>
                </div>
            </div>

            <div class="relative w-full mt-4" style="height:320px;">
                <canvas id="sCurveChart"></canvas>
            </div>
        </div>

        <div class="grid sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4">
                <p class="text-xs text-slate-500">CV (Cost Variance)</p>
                <p class="text-lg font-extrabold mt-1 {{ $cv >= 0 ? 'text-emerald-600' : 'text-rose-600' }}" title="{{ \App\Support\CurrencyHelper::formatFull($cv, true) }}">{{ \App\Support\CurrencyHelper::formatDiff($cv) }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4">
                <p class="text-xs text-slate-500">SV (Schedule Variance)</p>
                <p class="text-lg font-extrabold mt-1 {{ $sv >= 0 ? 'text-emerald-600' : 'text-rose-600' }}" title="{{ \App\Support\CurrencyHelper::formatFull($sv, true) }}">{{ \App\Support\CurrencyHelper::formatDiff($sv) }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4">
                <p class="text-xs text-slate-500">VAC (Variance at Completion)</p>
                <p class="text-lg font-extrabold mt-1 {{ $vac >= 0 ? 'text-emerald-600' : 'text-rose-600' }}" title="{{ \App\Support\CurrencyHelper::formatFull($vac, true) }}">{{ \App\Support\CurrencyHelper::formatDiff($vac) }}</p>
            </div>
        </div>
    </div>

    {{-- TAB: FINANCIAL (Poin 6 Revisi - Updated) --}}
    <div x-show="tab === 'financial'" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 mt-4">
        <h2 class="text-base font-bold text-slate-900 mb-4">Financial Summary</h2>
        @php $util = min(100, round(($acwp / $bac) * 100)); @endphp
        <div class="mb-4">
            <div class="flex items-center justify-between text-xs text-slate-500 mb-1">
                <span>Budget Utilization (ACWP / Contract Value)</span><span class="font-semibold text-slate-700">{{ $util }}%</span>
            </div>
            <div class="w-full h-2 rounded-full bg-slate-100">
                <div class="h-2 rounded-full bg-blue-600" style="width: {{ $util }}%"></div>
            </div>
        </div>
        <div class="grid sm:grid-cols-3 gap-3">
            {{-- 1. Actual Cost --}}
            <div class="rounded-xl bg-slate-50 p-3">
                <p class="text-[11px] text-slate-500">Actual Cost (ACWP)</p>
                <p class="text-base font-extrabold text-slate-900 mt-0.5" title="{{ \App\Support\CurrencyHelper::formatFull($acwp) }}">{{ \App\Support\CurrencyHelper::format($acwp) }}</p>
            </div>
            {{-- 2. Termin yang Diterima (Uang Masuk) --}}
            <div class="rounded-xl bg-blue-50 p-3">
                <p class="text-[11px] text-blue-600 font-semibold">Termin Diterima (Uang Masuk)</p>
                <p class="text-base font-extrabold text-blue-800 mt-0.5" title="{{ \App\Support\CurrencyHelper::formatFull($terminDiterima) }}">{{ \App\Support\CurrencyHelper::format($terminDiterima) }}</p>
            </div>
            {{-- 3. Laba / Rugi (Termin Diterima - Actual Cost) --}}
            <div class="rounded-xl {{ $labaRugi >= 0 ? 'bg-emerald-50' : 'bg-rose-50' }} p-3">
                <p class="text-[11px] font-semibold {{ $labaRugi >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">Laba / Rugi Realtime</p>
                <p class="text-base font-extrabold mt-0.5 {{ $labaRugi >= 0 ? 'text-emerald-700' : 'text-rose-700' }}" title="{{ \App\Support\CurrencyHelper::formatFull($labaRugi, true) }}">{{ \App\Support\CurrencyHelper::formatDiff($labaRugi) }}</p>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-slate-100">
            <div class="grid sm:grid-cols-2 gap-3">
                <div class="rounded-xl bg-slate-50 p-3">
                    <p class="text-[11px] text-slate-500">Contract Value (BAC)</p>
                    <p class="text-base font-extrabold text-slate-900 mt-0.5" title="{{ \App\Support\CurrencyHelper::formatFull($bac) }}">{{ \App\Support\CurrencyHelper::format($bac) }}</p>
                </div>
                <div class="rounded-xl bg-slate-50 p-3">
                    <p class="text-[11px] text-slate-500">EAC (Estimate at Completion)</p>
                    <p class="text-base font-extrabold text-slate-900 mt-0.5" title="{{ \App\Support\CurrencyHelper::formatFull($project['eac']) }}">{{ \App\Support\CurrencyHelper::format($project['eac']) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- TAB: BOQ (Poin 8 Revisi - Enhanced: Input RAB, Input Addendum Biaya, Input Add Waktu) --}}
    <div x-show="tab === 'boq'" class="space-y-4 mt-4">
        {{-- Toolbar Action Buttons --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-base font-bold text-slate-900">Manajemen BOQ & Addendum Kontrak</h2>
                <p class="text-xs text-slate-500 mt-0.5">Kelola item RAB dasar, penambahan biaya (pekerjaan tambah/kurang), dan perpanjangan waktu proyek</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                {{-- 1. Input RAB --}}
                <button @click="showRabModal = true" 
                        class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-sm transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    + Item RAB
                </button>

                {{-- 2. Input Addendum Biaya --}}
                <button @click="showAddendumCostModal = true" 
                        class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold shadow-sm transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    + Addendum Biaya
                </button>

                {{-- 3. Input Add Waktu --}}
                <button @click="showAddendumTimeModal = true" 
                        class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-sm transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    + Addendum Waktu
                </button>
            </div>
        </div>

        {{-- 3 Summary Cards: RAB Baseline, Addendum Biaya, Addendum Waktu --}}
        <div class="grid sm:grid-cols-3 gap-4">
            {{-- Card 1: RAB Baseline --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold text-slate-500">RAB Awal (Baseline BAC)</p>
                    <span class="p-1.5 rounded-lg bg-blue-50 text-blue-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </span>
                </div>
                <p class="text-lg font-extrabold text-slate-900 mt-1" title="{{ \App\Support\CurrencyHelper::formatFull($bac) }}">{{ \App\Support\CurrencyHelper::format($bac) }}</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Nilai kontrak awal proyek</p>
            </div>

            {{-- Card 2: Addendum Biaya --}}
            @php $totAddVal = array_reduce($addendums, fn($c, $a) => $c + abs($a['value'] ?? 0), 0); @endphp
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold text-slate-500">Addendum Biaya (Item Baru)</p>
                    <span class="p-1.5 rounded-lg bg-amber-50 text-amber-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                </div>
                <div class="flex items-baseline gap-2 mt-1">
                    <p class="text-lg font-extrabold text-slate-900" title="{{ \App\Support\CurrencyHelper::formatFull($totAddVal) }}">{{ \App\Support\CurrencyHelper::format($totAddVal) }}</p>
                    <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full
                        {{ $riskLevel === 'ON TRACK' ? 'bg-emerald-50 text-emerald-700' : '' }}
                        {{ $riskLevel === 'AT RISK' ? 'bg-amber-50 text-amber-700' : '' }}
                        {{ $riskLevel === 'CRITICAL' ? 'bg-rose-50 text-rose-700' : '' }}">
                        {{ number_format($addendumPercentage, 1) }}% — {{ $riskLevel }}
                    </span>
                </div>
                <p class="text-[11px] text-slate-400 mt-0.5">Batas aman addendum &le; 20%</p>
            </div>

            {{-- Card 3: Addendum Waktu --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold text-slate-500">Addendum Waktu (EOT)</p>
                    <span class="p-1.5 rounded-lg bg-indigo-50 text-indigo-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </span>
                </div>
                <div class="flex items-baseline gap-2 mt-1">
                    <p class="text-lg font-extrabold text-indigo-700">+{{ $totalDaysAdded }} Hari</p>
                    @if($totalDaysAdded > 0)
                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700">Diperpanjang</span>
                    @else
                        <span class="text-[10px] font-medium text-slate-400">Jadwal Normal</span>
                    @endif
                </div>
                <p class="text-[11px] text-slate-500 mt-0.5">BAST Baru: <span class="font-semibold text-slate-800">{{ date('d M Y', strtotime($effectiveBastDate ?: $project['bast_date'])) }}</span></p>
            </div>
        </div>

        {{-- RAB & BOQ Table --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Rencana Anggaran Biaya & Rincian Item BOQ</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Daftar item pekerjaan baseline dan item addendum yang telah disetujui</p>
                </div>
            </div>
            @if(count($boqItems) === 0)
                <div class="py-10 text-center">
                    <p class="text-xs text-slate-400">Belum ada data RAB untuk project ini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-400 uppercase font-bold border-b border-slate-100">
                                <th class="p-4 w-12">No</th>
                                <th class="p-4">Item Pekerjaan</th>
                                <th class="p-4 w-20">Satuan</th>
                                <th class="p-4 w-24 text-right">Volume</th>
                                <th class="p-4 text-right">Harga Satuan</th>
                                <th class="p-4 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @php $totalRab = 0; @endphp
                            @foreach($boqItems as $item)
                                @php 
                                    $totalRab += $item['subtotal']; 
                                    $isAdd = !empty($item['is_addendum']) || str_starts_with($item['item'], '[ADDENDUM]');
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition {{ $isAdd ? 'bg-amber-50/40' : '' }}">
                                    <td class="p-4 font-bold text-slate-500">{{ $item['no'] }}</td>
                                    <td class="p-4 font-semibold text-slate-900">
                                        @if($isAdd)
                                            <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 mr-1.5">ADDENDUM</span>
                                            {{ str_replace('[ADDENDUM] ', '', $item['item']) }}
                                        @else
                                            {{ $item['item'] }}
                                        @endif
                                    </td>
                                    <td class="p-4 text-slate-500">{{ $item['satuan'] }}</td>
                                    <td class="p-4 text-right font-semibold text-slate-700">{{ number_format($item['volume'], 0, ',', '.') }}</td>
                                    <td class="p-4 text-right text-slate-600" title="{{ \App\Support\CurrencyHelper::formatFull($item['harga_satuan']) }}">{{ \App\Support\CurrencyHelper::format($item['harga_satuan']) }}</td>
                                    <td class="p-4 text-right font-extrabold {{ $isAdd ? 'text-amber-700' : 'text-slate-900' }}" title="{{ \App\Support\CurrencyHelper::formatFull($item['subtotal']) }}">{{ \App\Support\CurrencyHelper::format($item['subtotal']) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-blue-50 border-t-2 border-blue-200">
                                <td colspan="5" class="p-4 text-right font-extrabold text-blue-800 uppercase text-xs">Total Anggaran (RAB + Addendum)</td>
                                <td class="p-4 text-right font-extrabold text-blue-900 text-sm" title="{{ \App\Support\CurrencyHelper::formatFull($totalRab) }}">{{ \App\Support\CurrencyHelper::format($totalRab) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>

        {{-- Riwayat Addendum Kontrak (Biaya & Waktu) --}}
        @if(count($addendums) > 0)
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-bold text-slate-900">Riwayat Addendum Kontrak (Biaya &amp; Waktu)</h3>
                    <span class="text-xs font-semibold text-slate-500">{{ count($addendums) }} Addendum Tercatat</span>
                </div>
                <div class="space-y-2.5">
                    @foreach($addendums as $a)
                        @php $isTimeOnly = ($a['type'] ?? '') === 'TIME' || (!empty($a['days_added']) && empty($a['value'])); @endphp
                        <div class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50 border border-slate-100 hover:bg-slate-100/60 transition">
                            <div class="flex items-center gap-3">
                                <span class="p-2 rounded-lg {{ $isTimeOnly ? 'bg-indigo-100 text-indigo-700' : 'bg-amber-100 text-amber-700' }}">
                                    @if($isTimeOnly)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @endif
                                </span>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <p class="text-xs font-bold text-slate-800">{{ $a['title'] }}</p>
                                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded {{ $isTimeOnly ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                            {{ $isTimeOnly ? 'ADDENDUM WAKTU' : 'ADDENDUM BIAYA' }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 mt-0.5">{{ $a['addendum_id'] }} &bull; {{ date('d M Y', strtotime($a['date'])) }} &bull; {{ $a['description'] }}</p>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                @if($isTimeOnly)
                                    <p class="text-xs font-extrabold text-indigo-700">+{{ $a['days_added'] ?? 0 }} Hari</p>
                                @else
                                    <p class="text-xs font-extrabold {{ $a['value'] >= 0 ? 'text-rose-600' : 'text-emerald-600' }}" title="{{ \App\Support\CurrencyHelper::formatFull($a['value'], true) }}">
                                        {{ \App\Support\CurrencyHelper::formatDiff($a['value']) }}
                                    </p>
                                @endif
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full mt-0.5 inline-block
                                    {{ $a['status'] === 'APPROVED' ? 'bg-emerald-50 text-emerald-700' : '' }}
                                    {{ $a['status'] === 'PENDING' ? 'bg-amber-50 text-amber-700' : '' }}
                                    {{ $a['status'] === 'REJECTED' ? 'bg-rose-50 text-rose-700' : '' }}">
                                    {{ $a['status'] }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-3.5 pt-3 border-t border-slate-100 flex items-center justify-between">
                    <p class="text-xs font-bold text-slate-600">Akumulasi Risiko Addendum Biaya / RAB Awal</p>
                    <span class="text-sm font-extrabold px-3 py-1 rounded-full
                        {{ $riskLevel === 'ON TRACK' ? 'bg-emerald-50 text-emerald-700' : '' }}
                        {{ $riskLevel === 'AT RISK' ? 'bg-amber-50 text-amber-700' : '' }}
                        {{ $riskLevel === 'CRITICAL' ? 'bg-rose-50 text-rose-700' : '' }}">
                        {{ number_format($addendumPercentage, 1) }}% — {{ $riskLevel }}
                    </span>
                </div>
            </div>
        @endif
    </div>

    {{-- TAB: ADDENDUM --}}
    <div x-show="tab === 'addendum'" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden mt-4">
        <div class="px-5 py-4 border-b border-slate-100">
            <h2 class="text-base font-bold text-slate-900">Contract Addendums</h2>
            <p class="text-xs text-slate-500 mt-0.5">List of contract addendums recorded for this project</p>
        </div>
        @if(count($addendums) === 0)
            <div class="py-10 text-center">
                <p class="text-xs text-slate-400">Belum ada data addendum untuk project ini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 uppercase font-bold border-b border-slate-100">
                            <th class="p-4">Addendum ID</th>
                            <th class="p-4">Title</th>
                            <th class="p-4">Value Variance</th>
                            <th class="p-4">Submission Date</th>
                            <th class="p-4">Description</th>
                            <th class="p-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($addendums as $a)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-4 font-bold text-slate-900">{{ $a['addendum_id'] }}</td>
                                <td class="p-4 text-slate-700 font-semibold">{{ $a['title'] }}</td>
                                <td class="p-4 font-extrabold {{ $a['value'] >= 0 ? 'text-slate-900' : 'text-emerald-600' }}" title="{{ \App\Support\CurrencyHelper::formatFull($a['value'], true) }}">
                                    {{ \App\Support\CurrencyHelper::formatDiff($a['value']) }}
                                </td>
                                <td class="p-4 text-slate-500">{{ date('d M Y', strtotime($a['date'])) }}</td>
                                <td class="p-4 text-slate-500 max-w-xs">{{ $a['description'] }}</td>
                                <td class="p-4">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full
                                        {{ $a['status'] === 'APPROVED' ? 'bg-emerald-50 text-emerald-700' : '' }}
                                        {{ $a['status'] === 'PENDING' ? 'bg-amber-50 text-amber-700' : '' }}
                                        {{ $a['status'] === 'REJECTED' ? 'bg-rose-50 text-rose-700' : '' }}
                                    ">
                                        {{ $a['status'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- TAB: ACTIVITY --}}
    <div x-show="tab === 'activity'" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 mt-4">
        <h2 class="text-base font-bold text-slate-900 mb-4">Project Activity / History</h2>
        @if(count($history) === 0)
            <p class="text-sm text-slate-500">No history recorded yet.</p>
        @else
            <ol class="relative border-l-2 border-slate-100 space-y-6 pl-6">
                @foreach($history as $h)
                    <li class="relative">
                        <span class="absolute -left-[27px] top-1 w-3 h-3 rounded-full bg-blue-600 ring-4 ring-white"></span>
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-sm font-semibold text-slate-900">{{ $h['action'] }}</p>
                            <p class="text-xs text-slate-400 shrink-0">{{ date('d M Y', strtotime($h['date'])) }}</p>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">by {{ $h['user'] }}</p>
                        <div class="mt-2 flex items-center gap-2 text-xs flex-wrap">
                            <span class="px-2 py-1 rounded bg-rose-50 text-rose-600 line-through">{{ $h['old'] }}</span>
                            <span class="px-2 py-1 rounded bg-emerald-50 text-emerald-700 font-semibold">{{ $h['new'] }}</span>
                        </div>
                    </li>
                @endforeach
            </ol>
        @endif
    </div>

    {{-- MODAL: INPUT LAPORAN MINGGUAN (Poin 9 Revisi) --}}
    <div x-show="showWeeklyModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm"
         style="display: none;">
        
        <div @click.away="showWeeklyModal = false"
             class="bg-white w-full max-w-md rounded-2xl border border-slate-200 shadow-2xl overflow-hidden">
            
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Input Laporan Mingguan</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5">Masukkan progres realisasi mingguan</p>
                </div>
                <button @click="showWeeklyModal = false" class="text-slate-400 hover:text-slate-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="submitWeeklyProgress" class="px-6 py-5 space-y-4 text-xs text-slate-700">
                <div>
                    <label class="block font-bold text-slate-800 mb-1.5">Minggu ke-</label>
                    <input type="number" wire:model="inputWeekNumber" min="1" required
                           class="w-full px-3 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm" />
                </div>

                <div>
                    <label class="block font-bold text-slate-800 mb-1.5">Progres Realisasi Mingguan (%)</label>
                    <input type="number" wire:model="inputActualPct" step="0.01" min="0" max="100" required placeholder="Contoh: 2.50"
                           class="w-full px-3 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm" />
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" @click="showWeeklyModal = false" 
                            class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition shadow-md shadow-blue-500/25">
                        Simpan Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL 1: INPUT ITEM RAB --}}
    <div x-show="showRabModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm"
         style="display: none;">
        
        <div @click.away="showRabModal = false"
             class="bg-white w-full max-w-lg rounded-2xl border border-slate-200 shadow-2xl overflow-hidden">
            
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="p-2 rounded-xl bg-blue-50 text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    </span>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Tambah Item RAB</h3>
                        <p class="text-[11px] text-slate-400">Tambahkan rincian item pekerjaan ke baseline BOQ</p>
                    </div>
                </div>
                <button @click="showRabModal = false" class="text-slate-400 hover:text-slate-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="submitRabItem" class="px-6 py-5 space-y-4 text-xs text-slate-700">
                <div>
                    <label class="block font-bold text-slate-800 mb-1.5">Nama Item Pekerjaan</label>
                    <input type="text" wire:model="newRabItem" required placeholder="Contoh: Pekerjaan Dinding Bata Ringan"
                           class="w-full px-3 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm" />
                    @error('newRabItem') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-800 mb-1.5">Satuan</label>
                        <select wire:model="newRabSatuan" 
                                class="w-full px-3 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm bg-white">
                            <option value="m³">m³ (Meter Kubik)</option>
                            <option value="m²">m² (Meter Persegi)</option>
                            <option value="m'">m' (Meter Lari)</option>
                            <option value="kg">kg (Kilogram)</option>
                            <option value="ton">ton (Ton)</option>
                            <option value="Ls">Ls (Lump Sum)</option>
                            <option value="titik">titik</option>
                            <option value="unit">unit</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-800 mb-1.5">Volume</label>
                        <input type="number" wire:model="newRabVolume" step="any" min="0.01" required placeholder="Contoh: 150"
                               class="w-full px-3 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm" />
                        @error('newRabVolume') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-800 mb-1.5">Harga Satuan (Rp)</label>
                    <input type="number" wire:model="newRabHargaSatuan" min="1" step="1" required placeholder="Contoh: 125000"
                           class="w-full px-3 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm" />
                    @error('newRabHargaSatuan') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" @click="showRabModal = false" 
                            class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition shadow-md shadow-blue-500/25">
                        Simpan Item RAB
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL 2: INPUT ADDENDUM BIAYA --}}
    <div x-show="showAddendumCostModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm"
         style="display: none;">
        
        <div @click.away="showAddendumCostModal = false"
             class="bg-white w-full max-w-lg rounded-2xl border border-slate-200 shadow-2xl overflow-hidden">
            
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="p-2 rounded-xl bg-amber-50 text-amber-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Input Addendum Biaya Kontrak</h3>
                        <p class="text-[11px] text-slate-400">Variasi biaya pekerjaan tambah/kurang (Variation Order)</p>
                    </div>
                </div>
                <button @click="showAddendumCostModal = false" class="text-slate-400 hover:text-slate-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="submitAddendumCost" class="px-6 py-5 space-y-4 text-xs text-slate-700">
                <div>
                    <label class="block font-bold text-slate-800 mb-1.5">Judul Addendum Biaya</label>
                    <input type="text" wire:model="addendumTitle" required placeholder="Contoh: Pekerjaan Galian Tambahan Segment B"
                           class="w-full px-3 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition text-sm" />
                    @error('addendumTitle') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-bold text-slate-800 mb-1.5">Nilai Variasi Addendum (Rp)</label>
                    <input type="number" wire:model="addendumValue" required placeholder="Contoh: 150000000 (atau -50000000 jika pengurangan)"
                           class="w-full px-3 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition text-sm font-semibold" />
                    <p class="text-[10px] text-slate-400 mt-1">Gunakan angka positif untuk penambahan biaya, atau negatif (-) jika efisiensi/pengurangan item.</p>
                    @error('addendumValue') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-bold text-slate-800 mb-1.5">Deskripsi / Alasan Addendum</label>
                    <textarea wire:model="addendumDesc" rows="3" placeholder="Jelaskan alasan teknis atau instruksi perubahan..."
                              class="w-full px-3 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition text-sm"></textarea>
                    @error('addendumDesc') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-amber-900 text-[11px] leading-relaxed">
                    <span class="font-bold">Info:</span> Item ini akan otomatis tercatat ke riwayat addendum dan ditambahkan sebagai baris item addendum di tabel BOQ.
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" @click="showAddendumCostModal = false" 
                            class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold transition shadow-md shadow-amber-500/25">
                        Simpan Addendum Biaya
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL 3: INPUT ADD WAKTU (EXTENSION OF TIME) --}}
    <div x-show="showAddendumTimeModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm"
         style="display: none;">
        
        <div @click.away="showAddendumTimeModal = false"
             class="bg-white w-full max-w-lg rounded-2xl border border-slate-200 shadow-2xl overflow-hidden">
            
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="p-2 rounded-xl bg-indigo-50 text-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Input Addendum Waktu (EOT)</h3>
                        <p class="text-[11px] text-slate-400">Perpanjangan waktu pelaksanaan &amp; perubahan jadwal BAST</p>
                    </div>
                </div>
                <button @click="showAddendumTimeModal = false" class="text-slate-400 hover:text-slate-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="submitAddendumTime" class="px-6 py-5 space-y-4 text-xs text-slate-700">
                <div>
                    <label class="block font-bold text-slate-800 mb-1.5">Judul / Referensi Addendum Waktu</label>
                    <input type="text" wire:model="timeTitle" placeholder="Contoh: Addendum Waktu 01 - Dampak Curah Hujan Tinggi"
                           class="w-full px-3 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm" />
                    @error('timeTitle') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-bold text-slate-800 mb-1.5">Tambahan Waktu Pelaksanaan (Hari Kalender)</label>
                    <input type="number" wire:model.live="addDays" min="1" step="1" required placeholder="Contoh: 30"
                           class="w-full px-3 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm font-bold text-indigo-700" />
                    @error('addDays') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Live Preview Box --}}
                <div class="p-3.5 bg-indigo-50/70 rounded-xl border border-indigo-100 space-y-2">
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="text-slate-500">Target BAST Saat Ini:</span>
                        <span class="font-bold text-slate-800">{{ date('d M Y', strtotime($effectiveBastDate ?: $project['bast_date'])) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="text-slate-500">Penambahan:</span>
                        <span class="font-extrabold text-indigo-700">+{{ max(1, (int)$addDays) }} Hari</span>
                    </div>
                    <div class="pt-1.5 border-t border-indigo-200/60 flex items-center justify-between">
                        <span class="text-xs font-bold text-indigo-900">Estimasi BAST Baru:</span>
                        <span class="text-xs font-black text-indigo-700 bg-white px-2.5 py-1 rounded-lg border border-indigo-200 shadow-xs">
                            {{ $previewBastDate }}
                        </span>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-800 mb-1.5">Alasan / Justifikasi Perpanjangan Waktu</label>
                    <textarea wire:model="timeReason" rows="3" required placeholder="Contoh: Keterlambatan pengiriman material impor dan cuaca buruk..."
                              class="w-full px-3 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm"></textarea>
                    @error('timeReason') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" @click="showAddendumTimeModal = false" 
                            class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold transition shadow-md shadow-indigo-500/25">
                        Simpan Addendum Waktu
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<script>
document.addEventListener('livewire:navigated', initSCurveChart);
document.addEventListener('DOMContentLoaded', initSCurveChart);

function initSCurveChart() {
    const canvas = document.getElementById('sCurveChart');
    if (!canvas || canvas.dataset.rendered) return;
    canvas.dataset.rendered = '1';

    new Chart(canvas, {
        type: 'line',
        data: {
            labels: @json($sCurveWeeks),
            datasets: [
                { label: 'BCWS (Planned)', data: @json($sCurveBcws), borderColor: '#2563EB', backgroundColor: 'rgba(37,99,235,0.08)', cubicInterpolationMode: 'monotone', tension: 0.4, fill: true, pointRadius: 3, borderWidth: 2 },
                { label: 'BCWP (Earned)', data: @json($sCurveBcwp), borderColor: '#16A34A', backgroundColor: 'rgba(22,163,74,0.08)', cubicInterpolationMode: 'monotone', tension: 0.4, fill: true, pointRadius: 3, borderWidth: 2, spanGaps: false },
                { label: 'ACWP (Actual)', data: @json($sCurveAcwp), borderColor: '#F97316', backgroundColor: 'rgba(249,115,22,0.08)', cubicInterpolationMode: 'monotone', tension: 0.4, fill: true, pointRadius: 3, borderWidth: 2, borderDash: [5,3], spanGaps: false }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } },
                tooltip: { 
                    backgroundColor: '#0f172a', 
                    padding: 10,
                    callbacks: {
                        label: (ctx) => ctx.dataset.label + ': Rp ' + ctx.parsed.y.toLocaleString('id-ID') + ' Miliar'
                    }
                }
            },
            scales: {
                x: { grid: { display: false } },
                y: { grid: { color: '#f1f5f9' }, ticks: { callback: (v) => 'Rp ' + v.toLocaleString('id-ID') + ' Miliar' } }
            }
        }
    });
}
</script>
@endpush
