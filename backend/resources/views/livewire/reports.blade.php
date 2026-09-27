<div x-data="{ showToast: @entangle('showToast'), toastMessage: @entangle('toastMessage'), openModalId: null }"
     x-effect="if (showToast) { setTimeout(() => showToast = false, 3000) }">
    
    <!-- Toast Notification -->
    <div x-show="showToast" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-2"
         x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed bottom-5 right-5 z-50 bg-slate-900 text-white px-5 py-3 rounded-xl shadow-lg flex items-center gap-3 border border-slate-800"
         style="display: none;">
        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
        <span x-text="toastMessage" class="text-sm font-semibold"></span>
    </div>

    <!-- Title & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 print:hidden">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">EVM Performance Reports</h1>
            <p class="text-slate-500 text-sm mt-1">Generate and export portfolio performance reports based on Earned Value Management parameters</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" 
                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 text-sm font-semibold transition hover:bg-slate-50 shadow-sm shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Report
            </button>
            <button wire:click="triggerExport('csv')" 
                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition shadow-md shadow-blue-500/25 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 12V3m0 9l3-3m-3 3L9 9" />
                </svg>
                Export CSV
            </button>
        </div>
    </div>



    <!-- Print Header (Hidden on screen) -->
    <div class="hidden print:block text-slate-900 border-b-2 border-slate-900 pb-5 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-extrabold">EVM Portfolio Performance Report</h1>
                <p class="text-xs text-slate-500 mt-1">Generated dynamically on {{ date('d F Y H:i') }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm font-extrabold uppercase text-blue-600 tracking-wider">PT BINTANG GANDARI</p>
                <p class="text-[10px] text-slate-400 font-bold">PROJECT MANAGEMENT OFFICE</p>
            </div>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 mt-6 print:hidden">
        <div class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[180px]">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Select Project</label>
                <input type="text" wire:model.live="selectedProject" placeholder="Search projects..."
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Status</label>
                <select wire:model.live="statusFilter" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <option value="all">All Status</option>
                    <option value="ON TRACK">On Track</option>
                    <option value="AT RISK">At Risk</option>
                    <option value="CRITICAL">Critical</option>
                    <option value="COMPLETED">Completed</option>
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Report Type</label>
                <select wire:model.live="reportType" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <option value="evm">EVM Analysis</option>
                    <option value="financial">Financial Performance</option>
                    <option value="variance">Schedule &amp; Cost Variance</option>
                </select>
            </div>
            <button wire:click="handleClearFilters"
                    class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-600 hover:bg-slate-50 transition shadow-sm shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Clear Filters
            </button>
        </div>
    </div>

    <!-- Reports Table Container -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden mt-6">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between print:hidden">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Performance Breakdown</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Showing report data for current active filters</p>
            </div>
            <span class="text-[11px] font-bold px-2 py-0.5 bg-slate-100 text-slate-600 rounded-full capitalize">
                Mode: {{ $reportType }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 uppercase font-bold border-b border-slate-100">
                        <th class="p-4">Project</th>
                        <th class="p-4">SPK</th>
                        <th class="p-4">Manager</th>

                        @if($reportType === 'evm')
                            <th class="p-4 text-right">BAC</th>
                            <th class="p-4 text-right">BCWS</th>
                            <th class="p-4 text-right">BCWP</th>
                            <th class="p-4 text-right">ACWP</th>
                            <th class="p-4 text-center">CPI</th>
                            <th class="p-4 text-center">SPI</th>
                        @elseif($reportType === 'financial')
                            <th class="p-4 text-right">BAC (Contract)</th>
                            <th class="p-4 text-right">ACWP (Actual)</th>
                            <th class="p-4 text-right">EAC (Estimated)</th>
                            <th class="p-4 text-right">VAC (Variance)</th>
                            <th class="p-4 text-center">Profitability</th>
                        @elseif($reportType === 'variance')
                            <th class="p-4 text-right">CV (Cost Var)</th>
                            <th class="p-4 text-right">SV (Sched Var)</th>
                            <th class="p-4 text-center">Cost Status</th>
                            <th class="p-4 text-center">Schedule Status</th>
                        @endif

                        <th class="p-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($projects as $p)
                        <tr @click="openModalId = '{{ $p['project_id'] }}'" 
                            class="hover:bg-blue-50/60 cursor-pointer transition group"
                            title="Klik untuk melihat pop form & analisis EVM proyek {{ $p['project_name'] }}">
                            <td class="p-4">
                                <p class="font-bold text-slate-900 group-hover:text-blue-600 transition">{{ $p['project_name'] }}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">ID: {{ $p['project_id'] }} | {{ $p['location'] }}</p>
                            </td>
                            <td class="p-4 text-slate-500 font-semibold">{{ $p['spk_number'] }}</td>
                            <td class="p-4 text-slate-600 font-semibold">{{ $p['project_manager'] }}</td>

                            @if($reportType === 'evm')
                                <td class="p-4 text-right font-extrabold text-slate-900" title="{{ \App\Support\CurrencyHelper::formatFull($p['bac']) }}">{{ \App\Support\CurrencyHelper::format($p['bac']) }}</td>
                                <td class="p-4 text-right text-blue-600 font-semibold" title="{{ \App\Support\CurrencyHelper::formatFull($p['bcws']) }}">{{ \App\Support\CurrencyHelper::format($p['bcws']) }}</td>
                                <td class="p-4 text-right text-emerald-600 font-semibold" title="{{ \App\Support\CurrencyHelper::formatFull($p['bcwp']) }}">{{ \App\Support\CurrencyHelper::format($p['bcwp']) }}</td>
                                <td class="p-4 text-right text-orange-600 font-semibold" title="{{ \App\Support\CurrencyHelper::formatFull($p['acwp']) }}">{{ \App\Support\CurrencyHelper::format($p['acwp']) }}</td>
                                <td class="p-4 text-center">
                                    <span class="font-extrabold {{ $p['cpi'] >= 1.0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ number_format($p['cpi'], 2) }}
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="font-extrabold {{ $p['spi'] >= 1.0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ number_format($p['spi'], 2) }}
                                    </span>
                                </td>
                            @elseif($reportType === 'financial')
                                <td class="p-4 text-right font-extrabold text-slate-900" title="{{ \App\Support\CurrencyHelper::formatFull($p['bac']) }}">{{ \App\Support\CurrencyHelper::format($p['bac']) }}</td>
                                <td class="p-4 text-right text-slate-600 font-semibold" title="{{ \App\Support\CurrencyHelper::formatFull($p['acwp']) }}">{{ \App\Support\CurrencyHelper::format($p['acwp']) }}</td>
                                <td class="p-4 text-right text-slate-900 font-semibold" title="{{ \App\Support\CurrencyHelper::formatFull($p['eac']) }}">{{ \App\Support\CurrencyHelper::format($p['eac']) }}</td>
                                <td class="p-4 text-right font-extrabold {{ $p['vac'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}" title="{{ \App\Support\CurrencyHelper::formatFull($p['vac'], true) }}">
                                    {{ \App\Support\CurrencyHelper::formatDiff($p['vac']) }}
                                </td>
                                <td class="p-4 text-center">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $p['vac'] >= 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                        {{ $p['vac'] >= 0 ? 'Profitable' : 'Loss Risk' }}
                                    </span>
                                </td>
                            @elseif($reportType === 'variance')
                                <td class="p-4 text-right font-extrabold {{ $p['cv'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}" title="{{ \App\Support\CurrencyHelper::formatFull($p['cv'], true) }}">
                                    {{ \App\Support\CurrencyHelper::formatDiff($p['cv']) }}
                                </td>
                                <td class="p-4 text-right font-extrabold {{ $p['sv'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}" title="{{ \App\Support\CurrencyHelper::formatFull($p['sv'], true) }}">
                                    {{ \App\Support\CurrencyHelper::formatDiff($p['sv']) }}
                                </td>
                                <td class="p-4 text-center">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $p['cv'] >= 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                        {{ $p['cv'] >= 0 ? 'Savings' : 'Overrun' }}
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $p['sv'] >= 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                        {{ $p['sv'] >= 0 ? 'Ahead' : 'Delay' }}
                                    </span>
                                </td>
                            @endif

                            <td class="p-4 text-center">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full
                                    {{ $p['status'] === 'ON TRACK' ? 'bg-emerald-50 text-emerald-700' : '' }}
                                    {{ $p['status'] === 'AT RISK' ? 'bg-amber-50 text-amber-700' : '' }}
                                    {{ $p['status'] === 'CRITICAL' ? 'bg-rose-50 text-rose-700' : '' }}
                                    {{ $p['status'] === 'COMPLETED' ? 'bg-blue-50 text-blue-700' : '' }}
                                ">
                                    {{ $p['status'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-20 text-center">
                                <div class="w-full flex items-center justify-center text-center">
                                    <p class="text-sm sm:text-base font-medium text-slate-400 text-center">
                                        No projects found matching the selected filters.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Summary Totals Row at the bottom of the table -->
        @if(count($projects) > 0)
            <div class="bg-slate-50 border-t border-slate-100 px-5 py-4 font-bold text-slate-900 flex justify-between items-center text-xs">
                <span>Summary Totals (Filtered Portfolio)</span>
                <div class="flex items-center gap-6">
                    <div>
                        <span class="text-[10px] text-slate-400 font-bold block uppercase text-right">Total BAC</span>
                        <span class="text-sm font-extrabold text-right block" title="{{ \App\Support\CurrencyHelper::formatFull($totalBac) }}">
                            {{ \App\Support\CurrencyHelper::format($totalBac) }}
                        </span>
                    </div>
                    @if($reportType === 'evm')
                        <div>
                            <span class="text-[10px] text-slate-400 font-bold block uppercase text-right">Avg CPI / SPI</span>
                            <span class="text-sm font-extrabold text-right block text-blue-600">
                                {{ number_format($aggCpi, 2) }} / {{ number_format($aggSpi, 2) }}
                            </span>
                        </div>
                    @elseif($reportType === 'financial')
                        <div>
                            <span class="text-[10px] text-slate-400 font-bold block uppercase text-right">Total EAC</span>
                            <span class="text-sm font-extrabold text-right block" title="{{ \App\Support\CurrencyHelper::formatFull($totalEac) }}">
                                {{ \App\Support\CurrencyHelper::format($totalEac) }}
                            </span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 font-bold block uppercase text-right">Total VAC</span>
                            <span class="text-sm font-extrabold text-right block {{ $aggVac >= 0 ? 'text-emerald-600' : 'text-rose-600' }}" title="{{ \App\Support\CurrencyHelper::formatFull($aggVac, true) }}">
                                {{ \App\Support\CurrencyHelper::formatDiff($aggVac) }}
                            </span>
                        </div>
                    @elseif($reportType === 'variance')
                        <div>
                            <span class="text-[10px] text-slate-400 font-bold block uppercase text-right">Total CV</span>
                            <span class="text-sm font-extrabold text-right block {{ $aggCv >= 0 ? 'text-emerald-600' : 'text-rose-600' }}" title="{{ \App\Support\CurrencyHelper::formatFull($aggCv, true) }}">
                                {{ \App\Support\CurrencyHelper::formatDiff($aggCv) }}
                            </span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 font-bold block uppercase text-right">Total SV</span>
                            <span class="text-sm font-extrabold text-right block {{ $aggSv >= 0 ? 'text-emerald-600' : 'text-rose-600' }}" title="{{ \App\Support\CurrencyHelper::formatFull($aggSv, true) }}">
                                {{ \App\Support\CurrencyHelper::formatDiff($aggSv) }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- MODAL / POP FORM DETAIL PROYEK EVM --}}
    @foreach($projects as $selectedProjectData)
        <div x-show="openModalId === '{{ $selectedProjectData['project_id'] }}'"
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-500"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm print:hidden"
             style="display: none;"
             x-cloak>
            <div @click.outside="openModalId = null"
                 x-show="openModalId === '{{ $selectedProjectData['project_id'] }}'"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-500"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="bg-white w-full max-w-2xl max-h-[90vh] flex flex-col rounded-2xl border border-slate-200 shadow-2xl overflow-hidden">
                
                {{-- Modal Header --}}
                <div class="relative px-6 py-5 border-b border-slate-100 bg-slate-50/60 rounded-t-2xl">
                    <button type="button" @click="openModalId = null" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition cursor-pointer z-10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <div class="flex items-start gap-4 pr-10 relative">
                        <div class="w-12 h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold shrink-0 shadow-md shadow-blue-500/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div class="min-w-0 pt-0.5">
                            <div class="flex items-center gap-2 mb-2">
                                <h3 class="text-lg font-extrabold text-slate-900 truncate">{{ $selectedProjectData['project_name'] }}</h3>
                                <span class="text-[10px] font-extrabold px-2.5 py-0.5 rounded-full shrink-0 tracking-wide
                                    {{ $selectedProjectData['status'] === 'ON TRACK' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                                    {{ $selectedProjectData['status'] === 'AT RISK' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
                                    {{ $selectedProjectData['status'] === 'CRITICAL' ? 'bg-rose-50 text-rose-700 border border-rose-200' : '' }}
                                    {{ $selectedProjectData['status'] === 'COMPLETED' ? 'bg-blue-50 text-blue-700 border border-blue-200' : '' }}
                                ">
                                    {{ $selectedProjectData['status'] }}
                                </span>
                            </div>
                            <div class="flex flex-wrap items-center gap-2">
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-white border border-slate-200 text-[11px] font-semibold text-slate-600 shadow-sm">
                                    <span class="text-slate-400 font-medium">ID:</span> {{ $selectedProjectData['project_id'] }}
                                </div>
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-white border border-slate-200 text-[11px] font-semibold text-slate-600 shadow-sm">
                                    <span class="text-slate-400 font-medium">SPK:</span> {{ $selectedProjectData['spk_number'] }}
                                </div>
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-white border border-slate-200 text-[11px] font-semibold text-slate-600 shadow-sm">
                                    <span class="text-slate-400 font-medium">Lokasi:</span> {{ $selectedProjectData['location'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Body Scrollable --}}
                <div class="p-6 overflow-y-auto max-h-[calc(90vh-140px)] text-xs text-slate-700">
                    
                    {{-- 1. General Project Information Box --}}
                    <div class="grid grid-cols-2 gap-y-5 gap-x-6 bg-slate-50/50 p-5 rounded-2xl shadow-2xs border border-slate-200">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Client / Pemberi Tugas</span>
                            <span class="font-bold text-slate-800 text-xs mt-0.5 block truncate">{{ $selectedProjectData['client'] }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Project Manager</span>
                            <span class="font-bold text-slate-800 text-xs mt-0.5 block truncate">{{ $selectedProjectData['project_manager'] }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Target BAST</span>
                            <span class="font-bold text-slate-800 text-xs mt-0.5 block">{{ date('d M Y', strtotime($selectedProjectData['bast_date'])) }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Progres Fisik</span>
                            <div class="flex items-center gap-3 mt-1">
                                <div class="flex-1 h-2 bg-slate-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-600 rounded-full transition-all duration-300" style="width: {{ $selectedProjectData['progress'] }}%"></div>
                                </div>
                                <span class="font-extrabold text-blue-700 text-xs shrink-0">{{ $selectedProjectData['progress'] }}%</span>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Parameter Utama EVM (2x2 Grid of 4 Cards) --}}
                    <div class="mt-12">
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3.5 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                            Parameter Utama EVM (Earned Value Management)
                        </h4>
                        <div class="grid grid-cols-2 gap-4">
                            {{-- BAC --}}
                            <div class="p-3.5 bg-white rounded-xl border border-slate-200 shadow-2xs">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">BAC (Kontrak)</span>
                                <span class="text-sm sm:text-base font-extrabold text-slate-900 mt-1 block" title="{{ \App\Support\CurrencyHelper::formatFull($selectedProjectData['bac']) }}">
                                    {{ \App\Support\CurrencyHelper::format($selectedProjectData['bac']) }}
                                </span>
                            </div>

                            {{-- BCWS --}}
                            <div class="p-3.5 bg-blue-50/30 rounded-xl border border-blue-100 shadow-2xs">
                                <span class="text-[10px] font-bold text-blue-600 uppercase tracking-wider block">BCWS (Rencana)</span>
                                <span class="text-sm sm:text-base font-extrabold text-blue-700 mt-1 block" title="{{ \App\Support\CurrencyHelper::formatFull($selectedProjectData['bcws']) }}">
                                    {{ \App\Support\CurrencyHelper::format($selectedProjectData['bcws']) }}
                                </span>
                            </div>

                            {{-- BCWP --}}
                            <div class="p-3.5 bg-emerald-50/30 rounded-xl border border-emerald-100 shadow-2xs">
                                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider block">BCWP (Realisasi)</span>
                                <span class="text-sm sm:text-base font-extrabold text-emerald-700 mt-1 block" title="{{ \App\Support\CurrencyHelper::formatFull($selectedProjectData['bcwp']) }}">
                                    {{ \App\Support\CurrencyHelper::format($selectedProjectData['bcwp']) }}
                                </span>
                            </div>

                            {{-- ACWP --}}
                            <div class="p-3.5 bg-orange-50/30 rounded-xl shadow-2xs border border-slate-200">
                                <span class="text-[10px] font-bold text-orange-600 uppercase tracking-wider block">ACWP (Real Cost)</span>
                                <span class="text-sm sm:text-base font-extrabold text-orange-700 mt-1 block" title="{{ \App\Support\CurrencyHelper::formatFull($selectedProjectData['acwp']) }}">
                                    {{ \App\Support\CurrencyHelper::format($selectedProjectData['acwp']) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Indeks Kinerja & Analisis Varians (3x2 Grid of 6 Cards) --}}
                    <div class="mt-12">
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3.5 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                            Indeks Kinerja &amp; Analisis Varians
                        </h4>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            {{-- CPI --}}
                            <div class="p-3.5 bg-white rounded-xl border border-slate-200 shadow-2xs">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">CPI (Efisiensi Biaya)</span>
                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded {{ $selectedProjectData['cpi'] >= 1.0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                        {{ $selectedProjectData['cpi'] >= 1.0 ? 'Under Budget' : 'Over Budget' }}
                                    </span>
                                </div>
                                <span class="text-base sm:text-lg font-extrabold mt-1.5 block {{ $selectedProjectData['cpi'] >= 1.0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ number_format($selectedProjectData['cpi'], 2) }}
                                </span>
                            </div>

                            {{-- SPI --}}
                            <div class="p-3.5 bg-white rounded-xl border border-slate-200 shadow-2xs">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">SPI (Efisiensi Waktu)</span>
                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded {{ $selectedProjectData['spi'] >= 1.0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                        {{ $selectedProjectData['spi'] >= 1.0 ? 'On Schedule' : 'Behind' }}
                                    </span>
                                </div>
                                <span class="text-base sm:text-lg font-extrabold mt-1.5 block {{ $selectedProjectData['spi'] >= 1.0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ number_format($selectedProjectData['spi'], 2) }}
                                </span>
                            </div>

                            {{-- CV --}}
                            <div class="p-3.5 bg-white rounded-xl border border-slate-200 shadow-2xs">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">CV (Cost Variance)</span>
                                <span class="text-sm sm:text-base font-extrabold mt-1.5 block {{ $selectedProjectData['cv'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}" title="{{ \App\Support\CurrencyHelper::formatFull($selectedProjectData['cv'], true) }}">
                                    {{ \App\Support\CurrencyHelper::formatDiff($selectedProjectData['cv']) }}
                                </span>
                            </div>

                            {{-- SV --}}
                            <div class="p-3.5 bg-white rounded-xl border border-slate-200 shadow-2xs">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">SV (Schedule Variance)</span>
                                <span class="text-sm sm:text-base font-extrabold mt-1.5 block {{ $selectedProjectData['sv'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}" title="{{ \App\Support\CurrencyHelper::formatFull($selectedProjectData['sv'], true) }}">
                                    {{ \App\Support\CurrencyHelper::formatDiff($selectedProjectData['sv']) }}
                                </span>
                            </div>

                            {{-- EAC --}}
                            <div class="p-3.5 bg-white rounded-xl border border-slate-200 shadow-2xs">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">EAC (Estimasi Akhir)</span>
                                <span class="text-sm sm:text-base font-extrabold text-slate-900 mt-1.5 block" title="{{ \App\Support\CurrencyHelper::formatFull($selectedProjectData['eac']) }}">
                                    {{ \App\Support\CurrencyHelper::format($selectedProjectData['eac']) }}
                                </span>
                            </div>

                            {{-- VAC --}}
                            <div class="p-3.5 bg-white rounded-xl border border-slate-200 shadow-2xs">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">VAC (Variance at Completion)</span>
                                <span class="text-sm sm:text-base font-extrabold mt-1.5 block {{ $selectedProjectData['vac'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}" title="{{ \App\Support\CurrencyHelper::formatFull($selectedProjectData['vac'], true) }}">
                                    {{ \App\Support\CurrencyHelper::formatDiff($selectedProjectData['vac']) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end bg-slate-50/50 rounded-b-2xl">
                    <button type="button" @click="openModalId = null" 
                            class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-100 transition cursor-pointer shadow-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endforeach

</div>

<!-- Styles specific to printing layout -->
<style>
@media print {
    /* Hide layout sidebars, headers, and footer */
    aside, header, .print\:hidden, nav, button {
        display: none !important;
    }
    
    /* Ensure content spans full width when printing */
    main, .flex-1, body {
        padding: 0 !important;
        margin: 0 !important;
        background-color: white !important;
        overflow: visible !important;
        height: auto !important;
        width: 100% !important;
    }
    
    .bg-white {
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
    }
    
    table {
        page-break-inside: auto;
    }
    
    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }
}
</style>
