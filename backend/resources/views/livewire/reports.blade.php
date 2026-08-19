<div x-data="{ showToast: @entangle('showToast'), toastMessage: @entangle('toastMessage') }"
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
            <h1 class="text-2xl font-extrabold text-slate-900">EVM Performance Reports</h1>
            <p class="text-slate-500 text-sm mt-1">Generate and export portfolio performance reports based on Earned Value Management parameters</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" 
                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 text-sm font-semibold transition hover:bg-slate-50 shadow-sm shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Report
            </button>
            <button wire:click="triggerExport('csv')" 
                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition shadow-md shadow-blue-500/25 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 12V3m0 9l3-3m-3 3L9 9" />
                </svg>
                Export CSV
            </button>
        </div>
    </div>

    <!-- Filtering Section -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 mt-6 print:hidden">
        <div class="flex flex-col lg:flex-row gap-3">
            <div class="flex-1">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Select Project</label>
                <select wire:model.live="selectedProject" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <option value="all">All Projects</option>
                    @foreach($projectList as $p)
                        <option value="{{ $p['project_id'] }}">{{ $p['project_id'] }} - {{ $p['project_name'] }}</option>
                    @endforeach
                </select>
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
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Project Manager</label>
                <select wire:model.live="pmFilter" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <option value="all">All Managers</option>
                    <option value="Andi Pratama">Andi Pratama</option>
                    <option value="Budi Santoso">Budi Santoso</option>
                    <option value="Dimas Wijaya">Dimas Wijaya</option>
                    <option value="Rizky Ramadhan">Rizky Ramadhan</option>
                    <option value="Fajar Nugroho">Fajar Nugroho</option>
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

            <div class="flex items-end">
                <button wire:click="handleClearFilters"
                        class="flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition w-full lg:w-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Clear Filters
                </button>
            </div>
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

    <!-- Aggregate Portfolio Metrics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mt-6">
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase">Filtered Projects</p>
            <p class="text-2xl font-extrabold text-slate-950 mt-1.5">{{ $projectCount }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase">Total Contract (BAC)</p>
            <p class="text-xl font-extrabold text-slate-950 mt-1.5">Rp {{ number_format($totalBac / 1e9, 2) }}B</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase">Portfolio CPI</p>
            <div class="flex items-center gap-2 mt-1.5">
                <span class="text-xl font-extrabold {{ $aggCpi >= 1.0 ? 'text-emerald-600' : 'text-rose-600' }}">
                    {{ number_format($aggCpi, 2) }}
                </span>
                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded {{ $aggCpi >= 1.0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                    {{ $aggCpi >= 1.0 ? 'Under Budget' : 'Over Budget' }}
                </span>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase">Portfolio SPI</p>
            <div class="flex items-center gap-2 mt-1.5">
                <span class="text-xl font-extrabold {{ $aggSpi >= 1.0 ? 'text-emerald-600' : 'text-rose-600' }}">
                    {{ number_format($aggSpi, 2) }}
                </span>
                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded {{ $aggSpi >= 1.0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                    {{ $aggSpi >= 1.0 ? 'On Schedule' : 'Behind Schedule' }}
                </span>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm col-span-2 lg:col-span-1">
            <p class="text-[10px] font-bold text-slate-400 uppercase">EAC vs Contract Variance</p>
            <p class="text-xl font-extrabold mt-1.5 {{ $aggVac >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                {{ $aggVac >= 0 ? '+' : '' }}Rp {{ number_format($aggVac / 1e9, 2) }}B
            </p>
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
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4">
                                <p class="font-bold text-slate-900">{{ $p['project_name'] }}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">ID: {{ $p['project_id'] }} | {{ $p['location'] }}</p>
                            </td>
                            <td class="p-4 text-slate-500 font-semibold">{{ $p['spk_number'] }}</td>
                            <td class="p-4 text-slate-600 font-semibold">{{ $p['project_manager'] }}</td>

                            @if($reportType === 'evm')
                                <td class="p-4 text-right font-extrabold text-slate-900">Rp {{ number_format($p['bac'] / 1e9, 2) }}B</td>
                                <td class="p-4 text-right text-blue-600">Rp {{ number_format($p['bcws'] / 1e9, 2) }}B</td>
                                <td class="p-4 text-right text-emerald-600">Rp {{ number_format($p['bcwp'] / 1e9, 2) }}B</td>
                                <td class="p-4 text-right text-orange-600">Rp {{ number_format($p['acwp'] / 1e9, 2) }}B</td>
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
                                <td class="p-4 text-right font-extrabold text-slate-900">Rp {{ number_format($p['bac'] / 1e9, 2) }}B</td>
                                <td class="p-4 text-right text-slate-600">Rp {{ number_format($p['acwp'] / 1e9, 2) }}B</td>
                                <td class="p-4 text-right text-slate-900">Rp {{ number_format($p['eac'] / 1e9, 2) }}B</td>
                                <td class="p-4 text-right font-extrabold {{ $p['vac'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $p['vac'] >= 0 ? '+' : '' }}Rp {{ number_format($p['vac'] / 1e9, 2) }}B
                                </td>
                                <td class="p-4 text-center">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $p['vac'] >= 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                        {{ $p['vac'] >= 0 ? 'Profitable' : 'Loss Risk' }}
                                    </span>
                                </td>
                            @elseif($reportType === 'variance')
                                <td class="p-4 text-right font-extrabold {{ $p['cv'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $p['cv'] >= 0 ? '+' : '' }}Rp {{ number_format($p['cv'] / 1e9, 2) }}B
                                </td>
                                <td class="p-4 text-right font-extrabold {{ $p['sv'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $p['sv'] >= 0 ? '+' : '' }}Rp {{ number_format($p['sv'] / 1e9, 2) }}B
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
                            <td colspan="10" class="text-center p-8 text-slate-400 font-medium">
                                No projects found matching the selected filters.
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
                        <span class="text-sm font-extrabold text-right block">Rp {{ number_format($totalBac / 1e9, 2) }}B</span>
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
                            <span class="text-sm font-extrabold text-right block">Rp {{ number_format($totalEac / 1e9, 2) }}B</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 font-bold block uppercase text-right">Total VAC</span>
                            <span class="text-sm font-extrabold text-right block {{ $aggVac >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                Rp {{ number_format($aggVac / 1e9, 2) }}B
                            </span>
                        </div>
                    @elseif($reportType === 'variance')
                        <div>
                            <span class="text-[10px] text-slate-400 font-bold block uppercase text-right">Total CV</span>
                            <span class="text-sm font-extrabold text-right block {{ $aggCv >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                Rp {{ number_format($aggCv / 1e9, 2) }}B
                            </span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 font-bold block uppercase text-right">Total SV</span>
                            <span class="text-sm font-extrabold text-right block {{ $aggSv >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                Rp {{ number_format($aggSv / 1e9, 2) }}B
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

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
