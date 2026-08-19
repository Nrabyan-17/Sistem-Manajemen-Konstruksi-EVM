<div x-data="{ showToast: @entangle('showToast'), toastMessage: @entangle('toastMessage'), showGenerateModal: @entangle('showGenerateModal') }"
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
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Reporting Center</h1>
            <p class="text-slate-500 text-sm mt-1">Generate, view and export comprehensive project reports</p>
        </div>
        <div class="flex items-center gap-2">
            <button wire:click="exportPdf"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 text-sm font-semibold transition hover:bg-slate-50 shadow-sm shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export PDF
            </button>
            <button wire:click="exportExcel"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 text-sm font-semibold transition hover:bg-slate-50 shadow-sm shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export Excel
            </button>
            <button onclick="window.print()"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition shadow-md shadow-blue-500/25 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Report
            </button>
        </div>
    </div>

    <!-- Available Report Types -->
    <div class="flex items-center justify-between mt-8">
        <h2 class="text-base font-bold text-slate-900">Available Report Types</h2>
        <a href="#" class="flex items-center gap-1 text-sm font-semibold text-blue-600 hover:text-blue-700 transition">
            Custom Report Builder
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
        @foreach($reportTypeCards as $card)
            @php
                $colorMap = [
                    'blue'    => ['bg' => 'bg-blue-50',    'text' => 'text-blue-600'],
                    'emerald' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600'],
                    'violet'  => ['bg' => 'bg-violet-50',  'text' => 'text-violet-600'],
                    'orange'  => ['bg' => 'bg-orange-50',  'text' => 'text-orange-600'],
                ];
                $colors = $colorMap[$card['icon_color']];
            @endphp
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 flex flex-col">
                <div class="w-11 h-11 rounded-xl {{ $colors['bg'] }} {{ $colors['text'] }} flex items-center justify-center">
                    @if($card['key'] === 'weekly_progress')
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    @elseif($card['key'] === 'financial')
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    @elseif($card['key'] === 'profit_loss')
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    @endif
                </div>

                <h3 class="text-sm font-bold text-slate-900 mt-4">{{ $card['label'] }}</h3>
                <p class="text-xs text-slate-500 mt-1.5 leading-relaxed flex-1">{{ $card['description'] }}</p>

                @if($card['key'] === 'evm_performance')
                    {{-- EVM Performance already has a full working report page — link straight to it --}}
                    <a href="{{ route('reports.evm') }}" wire:navigate
                       class="mt-4 flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-bold transition border border-slate-100">
                        Generate Report
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                @else
                    <button wire:click="openGenerateModal('{{ $card['label'] }}')"
                            class="mt-4 flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-bold transition border border-slate-100">
                        Generate Report
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </button>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Recent Generated Reports -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden mt-6">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Recent Generated Reports</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Audit log of all recently generated documents</p>
            </div>
            <div class="flex items-center gap-2">
                <button class="w-9 h-9 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </button>
                <button class="w-9 h-9 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 12V3m0 9l3-3m-3 3L9 9" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 uppercase font-bold border-b border-slate-100">
                        <th class="p-4">Report Name</th>
                        <th class="p-4">Project Reference</th>
                        <th class="p-4">Generated Date</th>
                        <th class="p-4">Created By</th>
                        <th class="p-4 text-right">Download</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($recentReports as $report)
                        @php
                            $iconColorMap = [
                                'rose'    => 'bg-rose-50 text-rose-600',
                                'emerald' => 'bg-emerald-50 text-emerald-600',
                                'blue'    => 'bg-blue-50 text-blue-600',
                            ];
                            $iconClasses = $iconColorMap[$report['icon_color']] ?? 'bg-slate-100 text-slate-500';
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg {{ $iconClasses }} flex items-center justify-center shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $report['name'] }}</p>
                                        <p class="text-[10px] text-slate-400 mt-0.5">ID: {{ $report['id'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 text-slate-600 font-semibold">{{ $report['project'] }}</td>
                            <td class="p-4 text-slate-500">{{ $report['date'] }}</td>
                            <td class="p-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 text-[10px] font-bold flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($report['by'], 0, 1)) }}
                                    </div>
                                    <span class="font-semibold text-slate-700">{{ $report['by'] }}</span>
                                </div>
                            </td>
                            <td class="p-4 text-right">
                                <button wire:click="downloadReport('{{ $report['id'] }}')"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-blue-600 hover:bg-blue-50 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 12V3m0 9l3-3m-3 3L9 9" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center p-8 text-slate-400 font-medium">
                                No reports have been generated yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4 border-t border-slate-100 text-center">
            <button class="text-xs font-bold text-slate-400 hover:text-slate-600 transition">
                Show More History
            </button>
        </div>
    </div>

    <!-- Generate Report Modal -->
    <div x-show="showGenerateModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm"
         style="display: none;">
        <div @click.outside="$wire.closeGenerateModal()"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">

            <h3 class="text-lg font-extrabold text-slate-900">Generate Report</h3>
            <p class="text-xs text-slate-500 mt-1">Configure the parameters for this report before generating.</p>

            <div class="mt-5 space-y-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">Report Type</label>
                    <input type="text" wire:model="modalReportType" disabled
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-500 font-semibold" />
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">Project</label>
                    <select wire:model="modalProject"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        <option value="all">All Projects</option>
                        @foreach($projectList as $p)
                            <option value="{{ $p['project_name'] }}">{{ $p['project_id'] }} - {{ $p['project_name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">Period</label>
                    <input type="text" wire:model="modalPeriod" placeholder="e.g. June 2026"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 mt-6">
                <button wire:click="closeGenerateModal"
                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                    Cancel
                </button>
                <button wire:click="generateReport"
                        class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition shadow-md shadow-blue-500/25">
                    Generate Report
                </button>
            </div>
        </div>
    </div>

</div>