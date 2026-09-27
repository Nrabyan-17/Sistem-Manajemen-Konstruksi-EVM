<div x-data="{ 
    tab: 'overview',
    showWeeklyModal: false,
    showRabModal: false,
    showAddendumCostModal: false,
    showAddendumTimeModal: false,
    showEditProjectModal: @entangle('showEditProjectModal'),
    addendumModalTab: 'detail',
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
"
@addendum-voided.window="
    toastMessage = $event.detail.message;
    showToast = true;
    setTimeout(() => showToast = false, 3000);
"
@addendum-updated.window="
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
    <a href="{{ route('projects') }}"
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
        </div>
    </div>

    {{-- TABS --}}
    <div class="flex items-center justify-between gap-3 mt-6 flex-wrap">
        <div class="flex items-center gap-1 bg-slate-100 rounded-xl p-1 flex-wrap">
            <button @click="tab = 'overview'" :class="tab === 'overview' ? 'bg-blue-600 text-white' : 'text-slate-500'" class="px-4 py-2 rounded-lg text-sm font-semibold transition">Overview</button>
            <button @click="tab = 'evm'" :class="tab === 'evm' ? 'bg-blue-600 text-white' : 'text-slate-500'" class="px-4 py-2 rounded-lg text-sm font-semibold transition">EVM &amp; S-Curve</button>
            <button @click="tab = 'financial'" :class="tab === 'financial' ? 'bg-blue-600 text-white' : 'text-slate-500'" class="px-4 py-2 rounded-lg text-sm font-semibold transition">Financial</button>
            <button @click="tab = 'boq'" :class="tab === 'boq' ? 'bg-blue-600 text-white' : 'text-slate-500'" class="px-4 py-2 rounded-lg text-sm font-semibold transition">BOQ</button>
            <button @click="tab = 'addendum'" :class="tab === 'addendum' ? 'bg-blue-600 text-white' : 'text-slate-500'" class="px-4 py-2 rounded-lg text-sm font-semibold transition">Addendum</button>
            <button @click="tab = 'activity'" :class="tab === 'activity' ? 'bg-blue-600 text-white' : 'text-slate-500'" class="px-4 py-2 rounded-lg text-sm font-semibold transition">Activity</button>
        </div>
        <div class="flex items-center gap-2 ml-auto">
            <button @click="showEditProjectModal = true; $wire.openEditProjectModal()"
                    class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold transition shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M4 20h4l10.768-10.768a2.5 2.5 0 00-3.536-3.536L4.464 16.464A2 2 0 004 17.878V20z" />
                </svg>
                Edit Project
            </button>
        </div>
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

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mt-4">
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
                <div class="rounded-xl {{ $deviasi <= 0 ? 'bg-emerald-50' : 'bg-rose-50' }} p-3">
                    <p class="text-[11px] font-semibold {{ $deviasi <= 0 ? 'text-emerald-700' : 'text-rose-700' }}">Deviasi (BCWS - BCWP)</p>
                    <p class="text-lg font-extrabold mt-0.5 {{ $deviasi <= 0 ? 'text-emerald-800' : 'text-rose-800' }}" title="Deviasi: {{ $deviasi > 0 ? '+' : '' }}{{ number_format($deviasi, 2) }}%">{{ $deviasi > 0 ? '+' : '' }}{{ number_format($deviasi, 2) }}%</p>
                </div>
            </div>

            <div class="relative w-full mt-4" style="height:320px;" wire:ignore
                 x-data="sCurveDetailChart"
                 @weekly-progress-saved.window="$nextTick(() => renderChart())">
                <canvas x-ref="sCurveCanvas" id="sCurveChart"></canvas>
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

        {{-- Tabel Riwayat Laporan Progres Mingguan --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden mt-6">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between flex-wrap gap-3">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Rincian Riwayat Laporan Progres Mingguan</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Data kurva S rencana vs realisasi mingguan dan kumulatif proyek</p>
                </div>
                <div class="flex items-center gap-2">
                    <button wire:click="openWeeklyModal" @click="showWeeklyModal = true"
                            class="px-3.5 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold transition shadow-sm flex items-center gap-1.5">
                        Input Laporan Mingguan
                    </button>
                </div>
            </div>
            @if(count($weeklyProgress) === 0)
                <div class="py-8 text-center text-xs text-slate-400">
                    Belum ada data laporan mingguan yang tercatat untuk project ini.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-400 uppercase font-bold border-b border-slate-100">
                                <th class="p-3.5 text-center w-16">Minggu</th>
                                <th class="p-3.5">Periode Tanggal</th>
                                <th class="p-3.5 text-right">Rencana (%)</th>
                                <th class="p-3.5 text-right">Kum. Rencana (%)</th>
                                <th class="p-3.5 text-right">Realisasi (%)</th>
                                <th class="p-3.5 text-right">Kum. Realisasi (%)</th>
                                <th class="p-3.5 text-right">Deviasi (%)</th>
                                <th class="p-3.5 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach(collect($weeklyProgress)->sortByDesc('week') as $wp)
                                @php
                                    $dev = $wp['deviasi'] ?? round(($wp['plan_pct'] ?? 0) - ($wp['actual_pct'] ?? 0), 2);
                                @endphp
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="p-3.5 text-center font-bold text-slate-900">W{{ $wp['week'] }}</td>
                                    <td class="p-3.5 text-slate-600 font-medium">
                                        {{ date('d M Y', strtotime($wp['start_date'])) }} — {{ date('d M Y', strtotime($wp['end_date'])) }}
                                    </td>
                                    <td class="p-3.5 text-right font-semibold text-slate-600">{{ number_format($wp['plan_pct'], 2) }}%</td>
                                    <td class="p-3.5 text-right font-bold text-blue-700 bg-blue-50/30">{{ number_format($wp['cumulative_plan_pct'] ?? 0, 2) }}%</td>
                                    <td class="p-3.5 text-right font-bold text-slate-900">{{ number_format($wp['actual_pct'], 2) }}%</td>
                                    <td class="p-3.5 text-right font-extrabold text-emerald-700 bg-emerald-50/30">{{ number_format($wp['cumulative_actual_pct'] ?? 0, 2) }}%</td>
                                    <td class="p-3.5 text-right font-extrabold {{ $dev <= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $dev > 0 ? '+' : '' }}{{ number_format($dev, 2) }}%
                                    </td>
                                    <td class="p-3.5 text-center">
                                        <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $dev <= 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                            {{ $dev <= 0 ? 'ON TRACK' : 'DELAY' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
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

    {{-- TAB: BOQ --}}
    <div x-show="tab === 'boq'" class="space-y-4 mt-4">
        {{-- 3 Summary Cards: RAB Baseline, Addendum Biaya, Addendum Waktu --}}
        <div class="grid sm:grid-cols-3 gap-4">
            {{-- Card 1: RAB Baseline --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold text-slate-500">RAB Awal (Baseline BAC)</p>
                </div>
                <p class="text-lg font-extrabold text-slate-900 mt-1" title="{{ \App\Support\CurrencyHelper::formatFull($bac) }}">{{ \App\Support\CurrencyHelper::format($bac) }}</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Nilai kontrak awal proyek</p>
            </div>

            {{-- Card 2: Addendum Biaya --}}
            @php $totAddVal = array_reduce($addendums, fn($c, $a) => $c + abs($a['value'] ?? 0), 0); @endphp
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold text-slate-500">Addendum Biaya (Item Baru)</p>
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
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between flex-wrap gap-3">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Rencana Anggaran Biaya &amp; Rincian Item BOQ</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Daftar rincian item pekerjaan dan anggaran biaya awal proyek (RAB Baseline)</p>
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
                                <th class="p-4 w-12 text-center">No</th>
                                <th class="p-4">Item Pekerjaan</th>
                                <th class="p-4 w-24">Satuan</th>
                                <th class="p-4 w-28 text-right">Volume</th>
                                <th class="p-4 text-right">Harga Satuan</th>
                                <th class="p-4 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @php $totalRab = 0; @endphp
                            @foreach($boqItems as $item)
                                @php $totalRab += $item['subtotal']; @endphp
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="p-4 text-center font-bold text-slate-500">{{ $item['no'] }}</td>
                                    <td class="p-4 font-semibold text-slate-900">{{ $item['item'] }}</td>
                                    <td class="p-4 text-slate-500 font-medium">{{ $item['satuan'] }}</td>
                                    <td class="p-4 text-right font-semibold text-slate-700">{{ number_format($item['volume'], 0, ',', '.') }}</td>
                                    <td class="p-4 text-right text-slate-600" title="{{ \App\Support\CurrencyHelper::formatFull($item['harga_satuan']) }}">{{ \App\Support\CurrencyHelper::format($item['harga_satuan']) }}</td>
                                    <td class="p-4 text-right font-extrabold text-slate-900" title="{{ \App\Support\CurrencyHelper::formatFull($item['subtotal']) }}">{{ \App\Support\CurrencyHelper::format($item['subtotal']) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-blue-50/80 border-t-2 border-blue-200">
                                <td colspan="5" class="p-4 text-left font-extrabold text-blue-900 uppercase text-xs">Total Rencana Anggaran Biaya (RAB Baseline)</td>
                                <td class="p-4 text-right font-extrabold text-blue-950 text-sm" title="{{ \App\Support\CurrencyHelper::formatFull($totalRab) }}">{{ \App\Support\CurrencyHelper::format($totalRab) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- TAB: ADDENDUM --}}
    <div x-show="tab === 'addendum'" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden mt-4">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Dokumen Addendum Kontrak (Biaya &amp; Waktu)</h2>
                <p class="text-xs text-slate-500 mt-0.5">Daftar seluruh amandemen kontrak resmi yang tercatat untuk proyek ini</p>
            </div>
            <div class="flex items-center gap-2">
                <button @click="showAddendumCostModal = true; addendumModalTab = 'detail'; $wire.openAddendumCostModal()" class="px-3 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold transition shadow-sm flex items-center gap-1">
                    + Addendum Biaya
                </button>
                <button @click="showAddendumTimeModal = true" class="px-3 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold transition shadow-sm flex items-center gap-1">
                    + Addendum Waktu
                </button>
            </div>
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
                            <th class="p-4">ID Addendum</th>
                            <th class="p-4">Jenis Addendum</th>
                            <th class="p-4">Judul Addendum</th>
                            <th class="p-4 text-right">Nilai / Perpanjangan</th>
                            <th class="p-4">Tanggal Pengajuan</th>
                            <th class="p-4">Alasan &amp; Deskripsi</th>
                            <th class="p-4 text-center">Status Approval</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($addendums as $a)
                            @php $isTime = ($a['type'] ?? '') === 'TIME' || (!empty($a['days_added']) && empty($a['value'])); @endphp
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-4 font-bold text-slate-900">{{ $a['addendum_id'] }}</td>
                                <td class="p-4">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-extrabold {{ $isTime ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-amber-50 text-amber-800 border border-amber-200' }}">
                                        {{ $isTime ? 'WAKTU (EOT)' : 'BIAYA (COST)' }}
                                    </span>
                                </td>
                                <td class="p-4 text-slate-800 font-bold">{{ $a['title'] }}</td>
                                <td class="p-4 text-right font-extrabold">
                                    @if($isTime)
                                        <span class="text-indigo-700">+{{ $a['days_added'] ?? 0 }} Hari</span>
                                    @else
                                        <span class="{{ $a['value'] >= 0 ? 'text-rose-600' : 'text-emerald-600' }}" title="{{ \App\Support\CurrencyHelper::formatFull($a['value'], true) }}">
                                            {{ \App\Support\CurrencyHelper::formatDiff($a['value']) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 text-slate-500 font-medium">{{ date('d M Y', strtotime($a['date'])) }}</td>
                                <td class="p-4 text-slate-600 max-w-xs leading-relaxed">{{ $a['description'] }}</td>
                                <td class="p-4 text-center">
                                    <span class="text-[10px] font-extrabold px-2.5 py-0.5 rounded-full inline-block
                                        {{ $a['status'] === 'APPROVED' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                                        {{ $a['status'] === 'PENDING' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
                                        {{ $a['status'] === 'REJECTED' ? 'bg-rose-50 text-rose-700 border border-rose-200' : '' }}
                                    ">
                                        {{ $a['status'] }}
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    @if(($a['status'] ?? '') === 'PENDING')
                                        <button type="button" @click="$dispatch('open-edit-addendum-modal'); $wire.openEditAddendumModal({{ $loop->index }})" class="text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline transition">Edit</button>
                                    @else
                                        <span class="text-xs text-slate-400 font-medium">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- Footer: Akumulasi Risiko Addendum Biaya / RAB Awal --}}
        <div class="px-5 py-3.5 bg-slate-50/80 border-t border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-700">Akumulasi Risiko Addendum Biaya / RAB Awal</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Persentase total penambahan biaya dibanding RAB Baseline proyek (Batas toleransi &le; 20%)</p>
            </div>
            <span class="text-xs font-extrabold px-3 py-1.5 rounded-full shadow-xs shrink-0
                {{ $riskLevel === 'ON TRACK' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                {{ $riskLevel === 'AT RISK' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
                {{ $riskLevel === 'CRITICAL' ? 'bg-rose-50 text-rose-700 border border-rose-200' : '' }}">
                {{ number_format($addendumPercentage, 1) }}% — {{ $riskLevel }}
            </span>
        </div>
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

    {{-- MODAL: EDIT PROJECT MASTER DATA --}}
    <div x-show="showEditProjectModal"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-500"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4"
         style="display: none;"
         x-cloak>
        <div @click.outside="showEditProjectModal = false; $wire.closeEditProjectModal()"
             x-show="showEditProjectModal"
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-500"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-2xl bg-white shadow-2xl border border-slate-200">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Edit Project</h3>
                    <p class="text-xs text-slate-500 mt-1">Update project master data without changing project history.</p>
                </div>
                <button type="button" @click="showEditProjectModal = false; $wire.closeEditProjectModal()" class="p-2 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

                <form wire:submit.prevent="updateProject" class="p-6 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold tracking-wider uppercase text-slate-400 mb-1.5">Project Name</label>
                            <input type="text" wire:model="editProjectName" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            @error('editProjectName') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold tracking-wider uppercase text-slate-400 mb-1.5">SPK Number</label>
                            <input type="text" wire:model="editSpkNumber" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            @error('editSpkNumber') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold tracking-wider uppercase text-slate-400 mb-1.5">Client</label>
                            <input type="text" wire:model="editClient" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            @error('editClient') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold tracking-wider uppercase text-slate-400 mb-1.5">Project Manager</label>
                            <input type="text" wire:model="editProjectManager" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            @error('editProjectManager') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold tracking-wider uppercase text-slate-400 mb-1.5">Location</label>
                            <input type="text" wire:model="editLocation" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            @error('editLocation') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold tracking-wider uppercase text-slate-400 mb-1.5">Contract Value (Rp)</label>
                            <input type="number" wire:model="editContractValue" min="1" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            @error('editContractValue') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold tracking-wider uppercase text-slate-400 mb-1.5">Start Date</label>
                            <input type="date" wire:model="editStartDate" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            @error('editStartDate') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold tracking-wider uppercase text-slate-400 mb-1.5">BAST Date</label>
                            <input type="date" wire:model="editBastDate" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            @error('editBastDate') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Import Data (Opsional) --}}
                    <div class="pt-4 border-t border-slate-100">
                        <p class="text-xs font-bold text-slate-800 mb-3">Import Data (Opsional)</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label class="border-2 border-dashed border-slate-200 rounded-xl p-4 text-center hover:border-blue-400 hover:bg-blue-50/30 transition cursor-pointer">
                                <input type="file" wire:model="editRabImport" accept=".xlsx,.xls,.csv" class="sr-only" />
                                <svg viewBox="0 0 24 24" class="w-6 h-6 mx-auto text-slate-400" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path d="M8 3.8V8.5C8 9.05 8.45 9.5 9 9.5H14.5L18 13V18.2C18 18.75 17.55 19.2 17 19.2H7C6.45 19.2 6 18.75 6 18.2V4.8C6 4.25 6.45 3.8 7 3.8H8Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                                    <path d="M14.5 9.5V3.8L18 7.3H14.5V9.5Z" fill="currentColor" opacity="0.2"/>
                                    <path d="M8.5 13.5H15.5M8.5 16.2H13.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                                    <path d="M9.5 9.5V11.5M12 9.5V11.5M14.5 9.5V11.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                                </svg>
                                <p class="text-[10px] font-bold text-slate-600 mt-2">Import Excel RAB</p>
                                <p class="text-[9px] text-slate-400 mt-0.5">No, Item, Sat, Vol, Hrg Sat, Subtotal</p>
                                @if($editRabImport)
                                    <p class="text-[9px] text-blue-600 font-semibold mt-2 truncate">{{ $editRabImport->getClientOriginalName() }}</p>
                                @endif
                                @error('editRabImport') <span class="text-[9px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </label>
                            <label class="border-2 border-dashed border-slate-200 rounded-xl p-4 text-center hover:border-blue-400 hover:bg-blue-50/30 transition cursor-pointer">
                                <input type="file" wire:model="editSCurveImport" accept=".xlsx,.xls,.csv" class="sr-only" />
                                <svg viewBox="0 0 24 24" class="w-6 h-6 mx-auto text-slate-400" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path d="M5 15.5L9.5 11L12.5 14L18.5 8.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M15.8 8.5H18.5V11.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                    <rect x="4" y="4" width="16" height="16" rx="3" stroke="currentColor" stroke-width="1.7"/>
                                </svg>
                                <p class="text-[10px] font-bold text-slate-600 mt-2">Upload/Import Time-Schedule via Excel</p>
                                <p class="text-[9px] text-slate-400 mt-0.5">Mg ke, Tgl Awal, Tgl Akhir, Rencana, Kumulatif</p>
                                @if($editSCurveImport)
                                    <p class="text-[9px] text-blue-600 font-semibold mt-2 truncate">{{ $editSCurveImport->getClientOriginalName() }}</p>
                                @endif
                                @error('editSCurveImport') <span class="text-[9px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="showEditProjectModal = false; $wire.closeEditProjectModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">Cancel</button>
                        <button type="submit" class="px-4 py-2.5 rounded-xl bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 transition shadow-sm shadow-blue-500/25">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

    {{-- MODAL: EDIT PENDING ADDENDUM --}}
    <div x-data="{ show: @entangle('showEditAddendumModal') }"
             @open-edit-addendum-modal.window="show = true"
             x-show="show"
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-500"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm"
             style="display: none;">
             
            <div @click.outside="show = false; $wire.closeEditAddendumModal()"
                 class="w-full max-w-lg rounded-2xl bg-white border border-slate-200 shadow-2xl p-6">
                <h3 class="text-lg font-extrabold text-slate-900">Edit Addendum Pending</h3>
                <p class="mt-1 text-xs text-slate-500">Addendum yang sudah approved tidak dapat diedit.</p>
                <form wire:submit.prevent="updateAddendum" class="mt-5 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Addendum Kontrak</label>
                            <input type="text" wire:model="editAddendumContractNo" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm" />
                            @error('editAddendumContractNo') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Addendum</label>
                            <input type="date" wire:model="editAddendumDate" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm" />
                            @error('editAddendumDate') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Judul Addendum</label>
                        <input type="text" wire:model="editAddendumTitle" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm" />
                        @error('editAddendumTitle') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nilai Addendum (Rp)</label>
                        <input type="number" wire:model="editAddendumValue" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm" />
                        @error('editAddendumValue') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Jumlah Hari Tambahan</label>
                        <input type="number" min="0" wire:model="editAddendumDays" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm" />
                        @error('editAddendumDays') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Alasan / Deskripsi</label>
                        <textarea wire:model="editAddendumDesc" rows="3" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm"></textarea>
                        @error('editAddendumDesc') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="show = false; $wire.closeEditAddendumModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600">Batal</button>
                        <button type="submit" class="px-4 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

    {{-- MODAL: INPUT LAPORAN MINGGUAN & IMPORT EXCEL --}}
    <div x-show="showWeeklyModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm"
         style="display: none;">
        
        <div @click.away="showWeeklyModal = false; $wire.closeWeeklyModal()"
             class="bg-white w-full max-w-lg rounded-2xl border border-slate-200 shadow-2xl overflow-hidden">
            
            {{-- Modal Header --}}
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Input Laporan Mingguan</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Perbarui data progres mingguan secara manual atau melalui file PDF</p>
                </div>
                <button type="button" @click="showWeeklyModal = false; $wire.closeWeeklyModal()" class="text-slate-400 hover:text-slate-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- INPUT MANUAL DAN UPLOAD FILE --}}
                <form wire:submit.prevent="submitWeeklyProgress" class="px-6 py-4 space-y-4 text-xs text-slate-700">
                    
                    {{-- Row 1: Minggu ke- & Tanggal / Periode Laporan --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block font-bold text-slate-800">Minggu ke-</label>
                            </div>
                            <input type="number" wire:model.live="inputWeekNumber" min="1" required
                                   class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm font-bold text-slate-900" />
                            @error('inputWeekNumber') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block font-bold text-slate-800">Tanggal / Periode</label>
                            </div>
                            <div class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 text-xs font-semibold flex items-center justify-between gap-1.5">
                                <div class="flex items-center gap-1.5 min-w-0">
                                    <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="font-bold text-slate-800 text-[11px] truncate">{{ $inputDateRange ?: '-' }}</span>
                                </div>
                                <span class="text-[9px] text-slate-400 font-medium shrink-0">7 Hari</span>
                            </div>
                        </div>
                    </div>

                    {{-- Row 2: Progres Rencana & Progres Realisasi --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block font-bold text-slate-800">Progres Rencana (%)</label>
                            </div>
                            <div>
                                <input type="number" wire:model="inputPlanPct" readonly step="0.01" min="0" max="100"
                                       class="w-full px-3.5 py-2.5 bg-slate-100/80 text-slate-600 font-bold border border-slate-200 rounded-xl cursor-not-allowed select-none focus:outline-none focus:ring-0 transition text-sm" />
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1">Otomatis dari Kurva S Baseline</p>
                            @error('inputPlanPct') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block font-bold text-slate-800">Progres Realisasi (%) <span class="text-rose-500">*</span></label>
                            </div>
                            <div class="relative">
                                <input type="number" wire:model.live="inputActualPct" step="0.01" min="0" max="100" required placeholder="Contoh: 2.50"
                                       class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm font-bold text-slate-900 pr-8" />
                                <div class="absolute inset-y-0 right-0 flex items-center pr-2.5 pointer-events-none font-bold text-slate-400 text-xs">
                                    %
                                </div>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1">Capaian progres minggu ini</p>
                            @error('inputActualPct') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- LABEL DEVIASI LIVE PREVIEW --}}
                    @php
                        $planVal = is_numeric($inputPlanPct) ? (float) $inputPlanPct : null;
                        $actualVal = is_numeric($inputActualPct) ? (float) $inputActualPct : null;
                        $calcDeviasi = ($planVal !== null && $actualVal !== null) ? round($planVal - $actualVal, 2) : null;
                    @endphp

                    <div class="p-3.5 rounded-xl border {{ $calcDeviasi !== null ? ($calcDeviasi <= 0 ? 'bg-emerald-50/80 border-emerald-200 text-emerald-900' : 'bg-rose-50/80 border-rose-200 text-rose-900') : 'bg-slate-50 border-slate-200 text-slate-600' }} transition-all">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full {{ $calcDeviasi !== null ? ($calcDeviasi <= 0 ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500 animate-pulse') : 'bg-slate-400' }}"></span>
                                <span class="font-bold text-xs text-slate-800">Deviasi Mingguan:</span>
                            </div>
                            @if($calcDeviasi !== null)
                                <span class="text-xs font-extrabold px-2.5 py-0.5 rounded-full {{ $calcDeviasi <= 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ $calcDeviasi > 0 ? '+' : '' }}{{ number_format($calcDeviasi, 2) }}%
                                </span>
                            @else
                                <span class="text-xs font-semibold text-slate-400">-- %</span>
                            @endif
                        </div>
                        <div class="mt-1.5 text-[11px] flex items-center justify-between text-slate-500">
                            @if($calcDeviasi !== null)
                                <span>Status: <strong class="{{ $calcDeviasi <= 0 ? 'text-emerald-700' : 'text-rose-700' }}">{{ $calcDeviasi <= 0 ? 'Sesuai Target / Lebih Cepat' : 'Terlambat dari Target' }}</strong></span>
                                <span>{{ $planVal !== null ? 'Target: ' . number_format($planVal, 2) . '% | ' : '' }}Realisasi: {{ number_format($actualVal, 2) }}%</span>
                            @else
                                <span class="text-slate-400">Masukkan nilai progres realisasi untuk menghitung deviasi</span>
                            @endif
                        </div>
                    </div>

                    {{-- Upload PDF dan preview berada dalam form yang sama dengan input manual --}}
                <div class="space-y-4 text-xs text-slate-700">
                    {{-- Upload Dropzone --}}
                    <div>
                        <label class="block font-bold text-slate-800 mb-1.5">Unggah File Laporan (.pdf)</label>
                        <div class="relative border-2 border-dashed border-slate-200 hover:border-blue-400 bg-slate-50/60 rounded-xl p-5 text-center transition cursor-pointer">
                            <input type="file" wire:model="weeklyImportFile" accept=".pdf,application/pdf" class="absolute inset-0 opacity-0 w-full h-full cursor-pointer" />
                            <div class="flex flex-col items-center justify-center pointer-events-none">
                                <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mb-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                </div>
                                <p class="font-bold text-slate-700 text-xs">Klik atau seret file laporan PDF ke sini</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">Format file: .pdf (Maks 10MB)</p>
                            </div>
                        </div>
                        @error('weeklyImportFile') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                        @if($importError)
                            <div class="mt-2 p-2.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 text-[11px] flex items-center gap-1.5">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ $importError }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Upload Loading Indicator --}}
                    <div wire:loading wire:target="weeklyImportFile" class="text-center py-2">
                        <span class="inline-flex items-center gap-2 text-xs font-semibold text-blue-600">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Membaca dan memvalidasi file PDF...
                        </span>
                    </div>

                    {{-- Preview Table If File Selected --}}
                    @if(!empty($importPreviewRows))
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-800 text-xs">Preview Data Siap Diimpor ({{ count($importPreviewRows) }} Minggu):</span>
                                <button type="button" wire:click="resetImportForm" class="text-[10px] font-semibold text-rose-600 hover:underline">Reset File</button>
                            </div>
                            <div class="max-h-48 overflow-y-auto rounded-xl border border-slate-200 shadow-2xs">
                                <table class="w-full text-left text-[11px] border-collapse">
                                    <thead class="bg-slate-100 text-slate-500 font-bold sticky top-0">
                                        <tr>
                                            <th class="p-2 text-center w-12">W#</th>
                                            <th class="p-2">Periode</th>
                                            <th class="p-2 text-right">Rencana</th>
                                            <th class="p-2 text-right">Realisasi</th>
                                            <th class="p-2 text-right">Deviasi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        @foreach($importPreviewRows as $row)
                                            <tr>
                                                <td class="p-2 text-center font-bold text-slate-800">W{{ $row['week'] }}</td>
                                                <td class="p-2 text-slate-500">{{ date('d/m', strtotime($row['start_date'])) }} - {{ date('d/m/y', strtotime($row['end_date'])) }}</td>
                                                <td class="p-2 text-right font-semibold text-slate-600">{{ number_format($row['plan_pct'], 2) }}%</td>
                                                <td class="p-2 text-right font-bold text-slate-900">{{ number_format($row['actual_pct'], 2) }}%</td>
                                                <td class="p-2 text-right font-extrabold {{ $row['deviasi'] <= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                                    {{ $row['deviasi'] > 0 ? '+' : '' }}{{ number_format($row['deviasi'], 2) }}%
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    {{-- Footer --}}
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                        <button type="button" @click="showWeeklyModal = false; $wire.closeWeeklyModal()" 
                                class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                            Batal
                        </button>
                        @if(!empty($importPreviewRows))
                            <button type="button" wire:click="processWeeklyImport"
                                    class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition shadow-md shadow-blue-500/25 flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4 4V4"/></svg>
                                Proses Import Laporan
                            </button>
                        @else
                            <button type="submit"
                                    class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition shadow-md shadow-blue-500/25 flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Simpan Laporan
                            </button>
                        @endif
                    </div>
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
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm overflow-y-auto"
         style="display: none;">
        
        <div @click.away="showAddendumCostModal = false"
             class="bg-white w-full max-w-lg rounded-2xl border border-slate-200 shadow-2xl overflow-hidden my-6 flex flex-col">
            
            {{-- Modal Header --}}
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

            {{-- Modal Tab Navigation (Di bagian atas form) --}}
            <div class="px-6 pt-3 bg-slate-50/80 border-b border-slate-200/80">
                <div class="flex items-center gap-1.5 overflow-x-auto">
                    <button type="button" 
                            @click="addendumModalTab = 'detail'"
                            :class="addendumModalTab === 'detail' ? 'border-amber-500 text-amber-800 bg-white font-bold shadow-2xs' : 'border-transparent text-slate-500 hover:text-slate-800 hover:bg-slate-100 font-medium'"
                            class="px-3 py-2 text-xs rounded-t-xl border-b-2 transition flex items-center gap-1.5 shrink-0">
                        <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        <span>Data Addendum</span>
                    </button>

                    <button type="button" 
                            @click="addendumModalTab = 'rab'"
                            :class="addendumModalTab === 'rab' ? 'border-emerald-500 text-emerald-800 bg-white font-bold shadow-2xs' : 'border-transparent text-slate-500 hover:text-slate-800 hover:bg-slate-100 font-medium'"
                            class="px-3 py-2 text-xs rounded-t-xl border-b-2 transition flex items-center gap-1.5 shrink-0">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/></svg>
                        <span>Upload PDF RAB Addendum</span>
                        @if($addendumRabFile)
                            <span class="w-2 h-2 rounded-full bg-emerald-500 ring-2 ring-white"></span>
                        @endif
                    </button>

                    <button type="button" 
                            @click="addendumModalTab = 'time_schedule'"
                            :class="addendumModalTab === 'time_schedule' ? 'border-blue-500 text-blue-800 bg-white font-bold shadow-2xs' : 'border-transparent text-slate-500 hover:text-slate-800 hover:bg-slate-100 font-medium'"
                            class="px-3 py-2 text-xs rounded-t-xl border-b-2 transition flex items-center gap-1.5 shrink-0">
                        <svg class="w-3.5 h-3.5 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/></svg>
                        <span>Upload PDF Time Schedule Addendum</span>
                        @if($addendumTsFile)
                            <span class="w-2 h-2 rounded-full bg-blue-500 ring-2 ring-white"></span>
                        @endif
                    </button>
                </div>
            </div>

            <form wire:submit.prevent="submitAddendumCost" class="px-6 py-5 space-y-4 text-xs text-slate-700 flex-1">
                {{-- TAB 1: FORM DATA ADDENDUM --}}
                <div x-show="addendumModalTab === 'detail'" class="space-y-4">
                    {{-- CARD INFORMASI PROYEK & KONTRAK --}}
                    <div class="p-3.5 bg-gradient-to-br from-slate-50 to-amber-50/30 rounded-xl border border-slate-200/80 space-y-2.5">
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold tracking-wider text-slate-400 uppercase flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    Nama Pekerjaan
                                </span>
                            </div>
                            <div class="text-xs font-extrabold text-slate-900 mt-1 leading-snug">
                                {{ $project['project_name'] }}
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-2 pt-2 border-t border-slate-200/60 text-center">
                            <div class="bg-white/80 p-2 rounded-lg border border-slate-200/60">
                                <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wide">Tgl Kontrak</span>
                                <span class="text-[11px] font-extrabold text-slate-800 block mt-0.5">
                                    {{ !empty($project['start_date']) ? date('d M Y', strtotime($project['start_date'])) : '-' }}
                                </span>
                            </div>
                            <div class="bg-white/80 p-2 rounded-lg border border-slate-200/60">
                                <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wide">Tgl BAST</span>
                                <span class="text-[11px] font-extrabold text-slate-800 block mt-0.5">
                                    {{ !empty($effectiveBastDate ?: $project['bast_date']) ? date('d M Y', strtotime($effectiveBastDate ?: $project['bast_date'])) : '-' }}
                                </span>
                            </div>
                            <div class="bg-white/80 p-2 rounded-lg border border-slate-200/60">
                                <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wide">Nilai Kontrak</span>
                                <span class="text-[11px] font-extrabold text-amber-700 font-mono block mt-0.5">
                                    Rp {{ number_format($bac, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-800 mb-1">Nomor Addendum Kontrak</label>
                            <input type="text" wire:model="addendumContractNo" placeholder="Contoh: ADD-01/SPK-2026/001"
                                   class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition text-xs font-medium" />
                            @error('addendumContractNo') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block font-bold text-slate-800 mb-1">Tanggal Addendum Kontrak</label>
                            <input type="date" wire:model="addendumDate"
                                   class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition text-xs font-medium" />
                            @error('addendumDate') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- JUDUL ADDENDUM --}}
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block font-bold text-slate-800">Judul Addendum</label>
                        </div>
                        <input type="text" wire:model="addendumTitle" readonly
                               value="Add tambah kurang volume"
                               class="w-full px-3 py-2 bg-slate-50/80 border border-slate-200 rounded-xl text-slate-700 font-bold text-xs cursor-not-allowed select-all" />
                        @error('addendumTitle') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- NILAI ADDENDUM (INPUTAN) --}}
                    <div>
                        <label class="block font-bold text-slate-800 mb-1">Nilai Perubahan Addendum (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" wire:model.live="addendumValue" required placeholder="Contoh: 150000000 (atau -50000000 jika pengurangan)"
                               class="w-full px-3 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition text-sm font-bold text-slate-900" />
                        <p class="text-[10px] text-slate-400 mt-1">Gunakan angka positif (+) untuk penambahan biaya, atau negatif (-) jika efisiensi / pengurangan volume.</p>
                        @error('addendumValue') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Status Lampiran File --}}
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2">
                            <span class="text-slate-500 font-semibold text-[11px]">Lampiran PDF:</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $addendumRabFile ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-500' }}">
                                RAB: {{ $addendumRabFile ? '✓ PDF Terlampir' : 'Kosong' }}
                            </span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $addendumTsFile ? 'bg-blue-100 text-blue-800' : 'bg-slate-200 text-slate-500' }}">
                                TS: {{ $addendumTsFile ? '✓ PDF Terlampir' : 'Kosong' }}
                            </span>
                        </div>
                        <button type="button" @click="addendumModalTab = 'rab'" class="text-[11px] font-bold text-amber-600 hover:text-amber-700 hover:underline">
                            Kelola Lampiran PDF →
                        </button>
                    </div>
                </div>

                {{-- TAB 2: UPLOAD PDF RAB ADDENDUM --}}
                <div x-show="addendumModalTab === 'rab'" class="space-y-4 py-1">
                    <div class="p-3.5 bg-emerald-50/70 rounded-xl border border-emerald-200/80 flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-emerald-950">Upload PDF RAB Addendum</h4>
                            <p class="text-[11px] text-emerald-700 mt-0.5">Unggah rincian item pekerjaan tambah/kurang beserta volume & harga satuan dalam format dokumen PDF (.pdf Maks. 10MB).</p>
                        </div>
                    </div>

                    @if(!$addendumRabFile)
                        <label class="flex flex-col items-center justify-center border-2 border-dashed border-slate-200 hover:border-emerald-400 rounded-2xl p-8 bg-slate-50/50 hover:bg-emerald-50/30 transition cursor-pointer group text-center">
                            <input type="file" wire:model="addendumRabFile" accept=".pdf,application/pdf" class="hidden" />
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center group-hover:scale-110 transition shadow-2xs mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            </div>
                            <p class="text-xs font-bold text-slate-800 group-hover:text-emerald-700 transition">Pilih file PDF RAB Addendum</p>
                            <p class="text-[10px] text-slate-400 mt-1">Klik untuk menjelajah file dari komputer Anda (.pdf)</p>
                        </label>
                    @else
                        <div class="p-4 bg-emerald-50/80 border border-emerald-200 rounded-2xl flex items-center justify-between">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div class="truncate">
                                    <p class="text-xs font-bold text-emerald-950 truncate">{{ $addendumRabFile->getClientOriginalName() }}</p>
                                    <p class="text-[10px] text-emerald-700 mt-0.5">{{ number_format($addendumRabFile->getSize() / 1024, 1) }} KB • Dokumen PDF siap dilampirkan</p>
                                </div>
                            </div>
                            <button type="button" wire:click="removeAddendumRabFile" class="text-slate-400 hover:text-rose-600 p-2 rounded-xl hover:bg-rose-50 transition cursor-pointer" title="Hapus file">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    @endif

                    <div wire:loading wire:target="addendumRabFile" class="text-xs text-emerald-600 font-semibold flex items-center gap-1.5">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Mengunggah file PDF RAB...
                    </div>
                    @error('addendumRabFile') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- TAB 3: UPLOAD PDF TIME SCHEDULE ADDENDUM --}}
                <div x-show="addendumModalTab === 'time_schedule'" class="space-y-4 py-1">
                    <div class="p-3.5 bg-blue-50/70 rounded-xl border border-blue-200/80 flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-blue-950">Upload PDF Time Schedule Addendum</h4>
                            <p class="text-[11px] text-blue-700 mt-0.5">Unggah dokumen penyesuaian jadwal Kurva S / Time Schedule addendum dalam format PDF (.pdf Maks. 10MB).</p>
                        </div>
                    </div>

                    @if(!$addendumTsFile)
                        <label class="flex flex-col items-center justify-center border-2 border-dashed border-slate-200 hover:border-blue-400 rounded-2xl p-8 bg-slate-50/50 hover:bg-blue-50/30 transition cursor-pointer group text-center">
                            <input type="file" wire:model="addendumTsFile" accept=".pdf,application/pdf" class="hidden" />
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 border border-blue-200 flex items-center justify-center group-hover:scale-110 transition shadow-2xs mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            </div>
                            <p class="text-xs font-bold text-slate-800 group-hover:text-blue-700 transition">Pilih file PDF Time Schedule Addendum</p>
                            <p class="text-[10px] text-slate-400 mt-1">Klik untuk menjelajah file dari komputer Anda (.pdf)</p>
                        </label>
                    @else
                        <div class="p-4 bg-blue-50/80 border border-blue-200 rounded-2xl flex items-center justify-between">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div class="truncate">
                                    <p class="text-xs font-bold text-blue-950 truncate">{{ $addendumTsFile->getClientOriginalName() }}</p>
                                    <p class="text-[10px] text-blue-700 mt-0.5">{{ number_format($addendumTsFile->getSize() / 1024, 1) }} KB • Dokumen PDF siap dilampirkan</p>
                                </div>
                            </div>
                            <button type="button" wire:click="removeAddendumTsFile" class="text-slate-400 hover:text-rose-600 p-2 rounded-xl hover:bg-rose-50 transition cursor-pointer" title="Hapus file">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    @endif

                    <div wire:loading wire:target="addendumTsFile" class="text-xs text-blue-600 font-semibold flex items-center gap-1.5">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Mengunggah file PDF Time Schedule...
                    </div>
                    @error('addendumTsFile') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Modal Action Footer --}}
                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" @click="showAddendumCostModal = false" 
                            class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                        Batal
                    </button>
                    <button type="submit" 
                            wire:loading.attr="disabled"
                            class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold transition shadow-md shadow-amber-500/25 flex items-center gap-1.5">
                        <span wire:loading.remove wire:target="submitAddendumCost">Simpan Addendum Biaya</span>
                        <span wire:loading wire:target="submitAddendumCost" class="flex items-center gap-1">
                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Menyimpan...
                        </span>
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

                {{-- Upload Dokumen Justifikasi / Surat Addendum Waktu (PDF) --}}
                <div>
                    <label class="block font-bold text-slate-800 mb-1.5">Unggah Dokumen Pendukung Addendum Waktu (.pdf)</label>
                    
                    @if(!$addendumTimePdfFile)
                        <label class="flex flex-col items-center justify-center border-2 border-dashed border-slate-200 hover:border-indigo-400 rounded-2xl p-4 bg-slate-50/50 hover:bg-indigo-50/30 transition cursor-pointer group text-center">
                            <input type="file" wire:model="addendumTimePdfFile" accept=".pdf,application/pdf" class="hidden" />
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-200 flex items-center justify-center group-hover:scale-105 transition shadow-2xs mb-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            </div>
                            <p class="text-xs font-bold text-slate-800 group-hover:text-indigo-700 transition">Pilih file PDF Surat / Dokumen Pendukung</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">Format file: .pdf (Maks. 10MB) • Opsional</p>
                        </label>
                    @else
                        <div class="p-3.5 bg-indigo-50/80 border border-indigo-200 rounded-2xl flex items-center justify-between">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div class="truncate">
                                    <p class="text-xs font-bold text-indigo-950 truncate">{{ $addendumTimePdfFile->getClientOriginalName() }}</p>
                                    <p class="text-[10px] text-indigo-700 mt-0.5">{{ number_format($addendumTimePdfFile->getSize() / 1024, 1) }} KB • Siap dilampirkan</p>
                                </div>
                            </div>
                            <button type="button" wire:click="removeAddendumTimePdfFile" class="text-slate-400 hover:text-rose-600 p-1.5 rounded-xl hover:bg-rose-50 transition" title="Hapus file">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    @endif

                    <div wire:loading wire:target="addendumTimePdfFile" class="text-xs text-indigo-600 font-semibold flex items-center gap-1.5 mt-1.5">
                        <svg class="animate-spin h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Mengunggah dokumen PDF...
                    </div>
                    @error('addendumTimePdfFile') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" @click="showAddendumTimeModal = false" 
                            class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                        Batal
                    </button>
                    <button type="submit" 
                            wire:loading.attr="disabled"
                            class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold transition shadow-md shadow-indigo-500/25 flex items-center gap-1.5">
                        <span wire:loading.remove wire:target="submitAddendumTime">Simpan Addendum Waktu</span>
                        <span wire:loading wire:target="submitAddendumTime" class="flex items-center gap-1">
                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
@endpush

@script
<script>
    Alpine.data('sCurveDetailChart', () => ({
        chartInstance: null,
        init() {
            this.$nextTick(() => this.renderChart());
            this.$watch('tab', (v) => {
                if (v === 'evm') {
                    this.$nextTick(() => {
                        if (this.chartInstance) {
                            this.chartInstance.resize();
                        } else {
                            this.renderChart();
                        }
                    });
                }
            });
        },
        renderChart() {
            const canvas = this.$refs.sCurveCanvas;
            if (!canvas) return;
            if (typeof Chart === 'undefined') {
                setTimeout(() => this.renderChart(), 80);
                return;
            }
            const existing = Chart.getChart(canvas);
            if (existing) existing.destroy();
            if (this.chartInstance) {
                this.chartInstance.destroy();
                this.chartInstance = null;
            }
            this.chartInstance = new Chart(canvas, {
                type: 'line',
                data: {
                    labels: @json($sCurveWeeks),
                    datasets: [
                        { label: 'BCWS (Planned)', data: @json($sCurveBcws), borderColor: '#2563EB', backgroundColor: 'rgba(37,99,235,0.08)', cubicInterpolationMode: 'monotone', tension: 0.4, fill: true, pointRadius: 3, borderWidth: 2 },
                        { label: 'BCWP (Earned)', data: @json($sCurveBcwp), borderColor: '#16A34A', backgroundColor: 'rgba(22,163,74,0.08)', cubicInterpolationMode: 'monotone', tension: 0.4, fill: true, pointRadius: 3, borderWidth: 2, spanGaps: false },
                        { label: 'ACWP (Actual)', data: @json($sCurveAcwp), borderColor: '#F97316', backgroundColor: 'rgba(249,115,22,0.08)', cubicInterpolationMode: 'monotone', tension: 0.4, fill: true, pointRadius: 3, borderWidth: 2, borderDash: [5,3], spanGaps: false },
                        { label: 'Deviasi', data: @json($sCurveDeviasi), borderColor: '#E11D48', backgroundColor: 'rgba(225,29,72,0.8)', showLine: false, pointRadius: 0, pointHoverRadius: 0, pointHitRadius: 0, borderWidth: 0, fill: false, spanGaps: false }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { 
                            position: 'bottom', 
                            labels: { 
                                usePointStyle: true, 
                                boxWidth: 8, 
                                padding: 18,
                                filter: (item) => item.text !== 'Deviasi'
                            } 
                        },
                        tooltip: { 
                            backgroundColor: '#0f172a', 
                            padding: 10,
                            callbacks: {
                                label: (ctx) => {
                                    if (ctx.parsed.y === null || ctx.parsed.y === undefined) return '';
                                    const val = ctx.parsed.y;
                                    const formatted = Math.abs(val).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                    if (ctx.dataset.label.toLowerCase().includes('deviasi')) {
                                        const sign = val > 0 ? '+' : (val < 0 ? '-' : '');
                                        return ctx.dataset.label + ': ' + (val === 0 ? '' : sign) + 'Rp ' + formatted + ' Miliar';
                                    }
                                    return ctx.dataset.label + ': Rp ' + formatted + ' Miliar';
                                }
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
    }));
</script>
@endscript
