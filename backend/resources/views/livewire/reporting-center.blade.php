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
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Reporting Center</h1>
            <p class="text-slate-500 text-sm mt-1">Generate, view and export comprehensive project reports</p>
        </div>
        <div class="flex items-center gap-2">
            <button wire:click="exportPdf"
                    class="flex items-center gap-2 px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 text-sm font-semibold transition hover:bg-slate-50 shadow-sm shrink-0 whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 12V3m0 9l3-3m-3 3L9 9" />
                </svg>
                Export PDF
            </button>
            <button wire:click="exportExcel"
                    class="flex items-center gap-2 px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 text-sm font-semibold transition hover:bg-slate-50 shadow-sm shrink-0 whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 12V3m0 9l3-3m-3 3L9 9" />
                </svg>
                Export Excel
            </button>
        </div>
    </div>

    <!-- Recent Generated Reports -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden mt-6">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Recent Generated Reports</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Audit log of all recently generated documents</p>
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
                                <div class="flex items-center justify-end gap-1.5">
                                    {{-- Tombol Download PDF --}}
                                    <button wire:click="downloadReportPdf('{{ $report['id'] }}')"
                                            title="Download PDF"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-100 text-[10px] font-bold transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 12V3m0 9l3-3m-3 3L9 9" />
                                        </svg>
                                        PDF
                                    </button>
                                    {{-- Tombol Download Excel --}}
                                    <button wire:click="downloadReportExcel('{{ $report['id'] }}')"
                                            title="Download Excel"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border border-emerald-100 text-[10px] font-bold transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 12V3m0 9l3-3m-3 3L9 9" />
                                        </svg>
                                        Excel
                                    </button>
                                </div>
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