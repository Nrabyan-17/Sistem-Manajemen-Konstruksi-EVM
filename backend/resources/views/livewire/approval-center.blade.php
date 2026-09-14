<div x-data="{
    isDetailModalOpen: false,
    isApproveModalOpen: false,
    isRejectModalOpen: false,
    toastMessage: '',
    showToast: false
}"
@open-detail-modal.window="isDetailModalOpen = true"
@open-approve-modal.window="isApproveModalOpen = true"
@open-reject-modal.window="isRejectModalOpen = true"
@submission-approved.window="
    isApproveModalOpen = false;
    isDetailModalOpen = false;
    toastMessage = $event.detail.message;
    showToast = true;
    setTimeout(() => showToast = false, 3500);
"
@submission-rejected.window="
    isRejectModalOpen = false;
    isDetailModalOpen = false;
    toastMessage = $event.detail.message;
    showToast = true;
    setTimeout(() => showToast = false, 3500);
" class="space-y-6">

    {{-- Toast Notification --}}
    <div x-show="showToast"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed bottom-5 right-5 z-50 bg-slate-900 text-white px-4 py-2.5 rounded-xl shadow-xl flex items-center gap-2.5 border border-slate-800"
         style="display: none;">
        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
        <span x-text="toastMessage" class="text-xs font-semibold"></span>
    </div>

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <p class="text-[11px] font-bold tracking-wider text-blue-600 uppercase">Validasi Data</p>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Approval Center</h1>
            <p class="text-slate-500 text-xs mt-0.5">Review and validate submitted data before it affects EVM calculations</p>
        </div>
        <div class="flex items-center gap-2.5 flex-wrap">
            {{-- Search Bar --}}
            <div class="relative min-w-[200px] sm:min-w-[220px]">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                </svg>
                <input wire:model.live.debounce.300ms="search" type="text" id="approval-search" placeholder="Search queue..."
                       class="w-full pl-8 pr-3 py-2 rounded-xl border border-slate-200/90 bg-white text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition shadow-xs"/>
            </div>

            {{-- Status Filter --}}
            <div class="relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                <select wire:model.live="statusFilter" id="approval-status-filter"
                        class="appearance-none pl-8 pr-8 py-2 rounded-xl border border-slate-200/90 bg-white text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition shadow-xs cursor-pointer">
                    <option value="all">Semua Status</option>
                    <option value="PENDING">Menunggu Approval</option>
                    <option value="APPROVED">Approved</option>
                    <option value="REJECTED">Rejected</option>
                </select>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>

            {{-- Category Filter --}}
            <div class="relative">
                <select wire:model.live="categoryFilter" id="approval-category-filter"
                        class="appearance-none pl-3.5 pr-8 py-2 rounded-xl border border-slate-200/90 bg-white text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition shadow-xs cursor-pointer">
                    <option value="all">Semua Kategori</option>
                    <option value="Progress Payment">Progress Payment</option>
                    <option value="Financial Addendum">Financial Addendum</option>
                    <option value="Scope Change">Scope Change</option>
                    <option value="Weekly Progress">Weekly Progress</option>
                </select>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>

            {{-- Reset Filter Button in Header --}}
            @if($search !== '' || $statusFilter !== 'all' || $categoryFilter !== 'all')
                <button wire:click="handleClearFilters" title="Reset Semua Filter"
                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition shadow-xs cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Reset Filter
                </button>
            @endif
        </div>
    </div>

    {{-- KPI Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Progress Approval --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500">Progress Approval</p>
                <div class="flex items-baseline gap-1.5 mt-0.5">
                    <span class="text-2xl font-bold text-slate-900">{{ $kpiProgress }}</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">PENDING</span>
                </div>
            </div>
            <div class="w-1 h-7 bg-blue-100/60 rounded-full shrink-0"></div>
        </div>

        {{-- Financial Approval --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500">Financial Approval</p>
                <div class="flex items-baseline gap-1.5 mt-0.5">
                    <span class="text-2xl font-bold text-slate-900">{{ $kpiFinancial }}</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">PENDING</span>
                </div>
            </div>
            <div class="w-1 h-7 bg-emerald-100/60 rounded-full shrink-0"></div>
        </div>

        {{-- Addendum Approval --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500">Addendum Approval</p>
                <div class="flex items-baseline gap-1.5 mt-0.5">
                    <span class="text-2xl font-bold text-slate-900">{{ $kpiAddendum }}</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">PENDING</span>
                </div>
            </div>
            <div class="w-1 h-7 bg-orange-100/60 rounded-full shrink-0"></div>
        </div>
    </div>

    {{-- Active Approval Queue Table Card (Compact & Clean) --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        {{-- Card Top Header --}}
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold text-slate-900">Active Approval Queue</h2>
                <p class="text-[11px] text-slate-400 mt-0.5">Menampilkan {{ count($filteredSubmissions) }} dari {{ $totalAll }} submission</p>
            </div>
            @if($totalPending > 0)
            <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold px-3 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $totalPending }} Menunggu Tindakan
            </span>
            @endif
        </div>

        {{-- Compact Table without Avatar --}}
        <div class="overflow-x-auto">
            <table class="w-full min-w-[960px] text-left border-collapse table-auto">
                <thead>
                    <tr class="border-b border-slate-100 bg-white">
                        <th class="w-[16%] px-6 py-3 text-[10px] font-bold tracking-wider text-slate-400 uppercase whitespace-nowrap">Submission Date</th>
                        <th class="w-[30%] px-6 py-3 text-[10px] font-bold tracking-wider text-slate-400 uppercase whitespace-nowrap">Project Name</th>
                        <th class="w-[15%] px-6 py-3 text-[10px] font-bold tracking-wider text-slate-400 uppercase whitespace-nowrap">Category</th>
                        <th class="w-[18%] px-6 py-3 text-[10px] font-bold tracking-wider text-slate-400 uppercase whitespace-nowrap">Submitted By</th>
                        <th class="w-[13%] px-6 py-3 text-[10px] font-bold tracking-wider text-slate-400 uppercase text-center whitespace-nowrap">Status</th>
                        <th class="w-[8%] px-6 py-3 text-[10px] font-bold tracking-wider text-slate-400 uppercase text-center whitespace-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($filteredSubmissions as $sub)
                        @php
                            $catBadge = match($sub['category']) {
                                'Progress Payment'   => 'bg-blue-50 text-blue-600 border border-blue-200/60',
                                'Financial Addendum' => 'bg-emerald-50 text-emerald-600 border border-emerald-200/60',
                                'Scope Change'       => 'bg-orange-50 text-orange-600 border border-orange-200/60',
                                'Weekly Progress'    => 'bg-purple-50 text-purple-600 border border-purple-200/60',
                                default              => 'bg-slate-100 text-slate-600 border border-slate-200',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition">
                            {{-- Submission Date --}}
                            <td class="px-6 py-3.5 whitespace-nowrap align-middle">
                                <p class="text-xs font-bold text-slate-900 leading-tight whitespace-nowrap">{{ \Carbon\Carbon::parse($sub['submission_date'])->format('d M Y') }}</p>
                                <p class="text-[10px] text-slate-400 font-mono mt-0.5 whitespace-nowrap">{{ $sub['submission_id'] }}</p>
                            </td>

                            {{-- Project Name --}}
                            <td class="px-6 py-3.5 whitespace-nowrap align-middle">
                                <p class="text-xs font-bold text-slate-900 leading-tight whitespace-nowrap">{{ $sub['project_name'] }}</p>
                                <p class="text-[10px] text-slate-400 font-mono mt-0.5 whitespace-nowrap">{{ $sub['project_id'] }}</p>
                            </td>

                            {{-- Category --}}
                            <td class="px-6 py-3.5 whitespace-nowrap align-middle">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold whitespace-nowrap {{ $catBadge }}">
                                    {{ $sub['category'] }}
                                </span>
                            </td>

                            {{-- Submitted By (Clean, No Avatar, Compact) --}}
                            <td class="px-6 py-3.5 whitespace-nowrap align-middle">
                                <div>
                                    <p class="text-xs font-bold text-slate-900 leading-tight whitespace-nowrap">{{ $sub['submitted_by_name'] }}</p>
                                    <p class="text-[10px] text-slate-400 leading-tight mt-0.5 whitespace-nowrap">{{ $sub['submitted_by_role'] }}</p>
                                </div>
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-3.5 text-center whitespace-nowrap align-middle">
                                @if($sub['status'] === 'APPROVED')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold border border-emerald-300 text-emerald-700 bg-emerald-50/50 whitespace-nowrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Approved
                                    </span>
                                @elseif($sub['status'] === 'REJECTED')
                                    <div>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold border border-rose-300 text-rose-700 bg-rose-50/50 whitespace-nowrap">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Rejected
                                        </span>
                                        @if($sub['reject_reason'])
                                            <p class="text-[9px] text-rose-500 mt-0.5 max-w-[140px] mx-auto truncate" title="{{ $sub['reject_reason'] }}">{{ $sub['reject_reason'] }}</p>
                                        @endif
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold border border-amber-300 text-amber-700 bg-amber-50/50 whitespace-nowrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Menunggu Approval
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-3.5 text-center whitespace-nowrap align-middle">
                                @if($sub['status'] === 'PENDING')
                                    <div class="flex items-center justify-center gap-2">
                                        <button wire:click="showDetail('{{ $sub['submission_id'] }}')" title="Lihat Detail"
                                                class="text-slate-400 hover:text-blue-600 transition p-1 hover:bg-slate-100 rounded-md cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </button>
                                        <button wire:click="showApproveConfirm('{{ $sub['submission_id'] }}')" title="Approve"
                                                class="text-slate-400 hover:text-emerald-600 transition p-1 hover:bg-emerald-50 rounded-md cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                            </svg>
                                        </button>
                                        <button wire:click="showRejectModal('{{ $sub['submission_id'] }}')" title="Reject"
                                                class="text-slate-400 hover:text-rose-600 transition p-1 hover:bg-rose-50 rounded-md cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                @else
                                    <div class="flex items-center justify-center">
                                        <button wire:click="showDetail('{{ $sub['submission_id'] }}')" title="Lihat Detail"
                                                class="text-slate-400 hover:text-blue-600 transition p-1 hover:bg-slate-100 rounded-md cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 px-4">
                                <div class="flex flex-col items-center gap-2.5">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <p class="text-xs font-bold text-slate-500">No submissions found</p>
                                    <p class="text-[11px] text-slate-400">Tidak ada submission yang cocok dengan filter yang dipilih.</p>
                                    <button wire:click="handleClearFilters" class="mt-1 px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-[11px] font-semibold transition shadow-sm cursor-pointer">
                                        Reset Filter
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Card Footer --}}
        <div class="px-6 py-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500 bg-slate-50/40">
            <p>Showing <span class="font-bold text-slate-800">{{ count($filteredSubmissions) }}</span> of <span class="font-bold text-slate-800">{{ $totalAll }}</span> submissions</p>
            <button wire:click="handleClearFilters" class="inline-flex items-center gap-1.5 text-blue-600 hover:text-blue-700 font-semibold cursor-pointer transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Reset Filter
            </button>
        </div>
    </div>

    {{-- DETAIL MODAL --}}
    <div x-show="isDetailModalOpen"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
         style="display: none;">
        <div @click.outside="isDetailModalOpen = false; $wire.closeDetail()"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden border border-slate-100">
            @if($selectedSubmission)
            <div class="px-6 py-4 border-b border-slate-100 flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Detail Submission</p>
                    <h3 class="text-sm font-extrabold text-slate-900 mt-0.5">{{ $selectedSubmission['submission_id'] }}</h3>
                </div>
                <button @click="isDetailModalOpen = false; $wire.closeDetail()" class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 transition shrink-0 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="px-6 py-4 space-y-3.5 max-h-[65vh] overflow-y-auto">
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-50 rounded-xl p-3">
                        <p class="text-[10px] font-bold text-slate-400 uppercase">Proyek</p>
                        <p class="text-xs font-bold text-slate-900 mt-0.5 leading-snug">{{ $selectedSubmission['project_name'] }}</p>
                        <p class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $selectedSubmission['project_id'] }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3">
                        <p class="text-[10px] font-bold text-slate-400 uppercase">Diajukan Oleh</p>
                        <p class="text-xs font-bold text-slate-900 mt-0.5 leading-snug">{{ $selectedSubmission['submitted_by_name'] }}</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">{{ $selectedSubmission['submitted_by_role'] }}</p>
                    </div>
                </div>

                @if(in_array($selectedSubmission['category'], ['Progress Payment', 'Weekly Progress']))
                    <div class="bg-blue-50/70 rounded-xl p-3.5 space-y-2.5 border border-blue-100/60">
                        <p class="text-[10px] font-bold text-blue-700 uppercase tracking-wider">Laporan Progres — Minggu ke-{{ $selectedSubmission['detail']['week'] }}</p>
                        <div class="grid grid-cols-2 gap-2.5">
                            <div class="bg-white/80 rounded-lg p-2.5">
                                <p class="text-[10px] text-slate-500 font-semibold">Rencana (BCWS)</p>
                                <p class="text-base font-bold text-blue-700 mt-0.5">{{ number_format($selectedSubmission['detail']['plan_pct'], 2) }}%</p>
                            </div>
                            <div class="bg-white/80 rounded-lg p-2.5">
                                @php $diff = $selectedSubmission['detail']['actual_pct'] - $selectedSubmission['detail']['plan_pct']; @endphp
                                <p class="text-[10px] text-slate-500 font-semibold">Realisasi (BCWP)</p>
                                <p class="text-base font-bold {{ $diff >= 0 ? 'text-emerald-600' : 'text-rose-600' }} mt-0.5">{{ number_format($selectedSubmission['detail']['actual_pct'], 2) }}%</p>
                            </div>
                        </div>
                        <div class="text-[11px] text-slate-600">
                            <span class="font-semibold text-slate-500">Pelaksana:</span> {{ $selectedSubmission['detail']['executor'] }}
                        </div>
                        @if($selectedSubmission['detail']['note'])
                        <div class="bg-amber-50 border border-amber-200/80 rounded-lg p-2.5">
                            <p class="text-[10px] font-bold text-amber-700 uppercase mb-0.5">Catatan</p>
                            <p class="text-[11px] text-amber-800 leading-relaxed">{{ $selectedSubmission['detail']['note'] }}</p>
                        </div>
                        @endif
                    </div>
                @elseif($selectedSubmission['category'] === 'Financial Addendum')
                    <div class="bg-emerald-50/70 rounded-xl p-3.5 space-y-2.5 border border-emerald-100/60">
                        <p class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider">Detail Pengeluaran</p>
                        <div class="bg-white/80 rounded-lg p-2.5">
                            <p class="text-[10px] text-slate-500 font-semibold">Jenis Pengeluaran</p>
                            <p class="text-xs font-bold text-slate-900 mt-0.5">{{ $selectedSubmission['detail']['expense_type'] }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-2.5">
                            <div class="bg-white/80 rounded-lg p-2.5">
                                <p class="text-[10px] text-slate-500 font-semibold">Nominal</p>
                                <p class="text-sm font-bold text-emerald-700 mt-0.5">Rp {{ number_format($selectedSubmission['detail']['amount'], 0, ',', '.') }}</p>
                            </div>
                            <div class="bg-white/80 rounded-lg p-2.5">
                                <p class="text-[10px] text-slate-500 font-semibold">Tanggal</p>
                                <p class="text-xs font-bold text-slate-900 mt-0.5">{{ \Carbon\Carbon::parse($selectedSubmission['detail']['date'])->format('d M Y') }}</p>
                            </div>
                        </div>
                        @if($selectedSubmission['detail']['note'])
                        <div class="bg-white border border-emerald-200/80 rounded-lg p-2.5">
                            <p class="text-[10px] font-bold text-slate-500 uppercase mb-0.5">Catatan</p>
                            <p class="text-[11px] text-slate-700 leading-relaxed">{{ $selectedSubmission['detail']['note'] }}</p>
                        </div>
                        @endif
                    </div>
                @elseif($selectedSubmission['category'] === 'Scope Change')
                    <div class="bg-orange-50/70 rounded-xl p-3.5 space-y-2.5 border border-orange-100/60">
                        <p class="text-[10px] font-bold text-orange-700 uppercase tracking-wider">Perubahan Scope</p>
                        <div class="bg-white/80 rounded-lg p-2.5">
                            <p class="text-[10px] text-slate-500 font-semibold">Item Yang Diubah</p>
                            <p class="text-xs font-bold text-slate-900 mt-0.5">{{ $selectedSubmission['detail']['item'] }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-2.5">
                            <div class="bg-rose-50/80 border border-rose-100 rounded-lg p-2.5">
                                <p class="text-[10px] font-bold text-rose-600 uppercase mb-0.5">Nilai Lama</p>
                                <p class="text-xs font-bold text-rose-700 line-through">{{ $selectedSubmission['detail']['old_value'] }}</p>
                            </div>
                            <div class="bg-emerald-50/80 border border-emerald-100 rounded-lg p-2.5">
                                <p class="text-[10px] font-bold text-emerald-600 uppercase mb-0.5">Nilai Baru</p>
                                <p class="text-xs font-bold text-emerald-700">{{ $selectedSubmission['detail']['new_value'] }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-500 font-semibold">Alasan Perubahan</p>
                            <p class="text-[11px] text-slate-700 leading-relaxed mt-0.5">{{ $selectedSubmission['detail']['reason'] }}</p>
                        </div>
                        <div class="bg-amber-50 border border-amber-200/80 rounded-lg p-2.5 flex items-start gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <p class="text-[10px] text-amber-800 font-medium">Menyetujui perubahan ini akan otomatis menghitung ulang bobot dan BCWS proyek.</p>
                        </div>
                    </div>
                @endif

                @if($selectedSubmission['status'] === 'REJECTED' && $selectedSubmission['reject_reason'])
                <div class="bg-rose-50 border border-rose-200 rounded-xl p-3">
                    <p class="text-[10px] font-bold text-rose-700 uppercase tracking-wider mb-0.5">Alasan Penolakan</p>
                    <p class="text-[11px] text-rose-800 leading-relaxed">{{ $selectedSubmission['reject_reason'] }}</p>
                </div>
                @endif
            </div>
            <div class="px-6 py-3.5 border-t border-slate-100 flex items-center justify-end gap-2.5 bg-slate-50/50">
                <button @click="isDetailModalOpen = false; $wire.closeDetail()"
                        class="px-4 py-2 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-600 hover:bg-slate-50 transition shadow-xs cursor-pointer">
                    Tutup
                </button>
                @if($selectedSubmission['status'] === 'PENDING')
                    <button wire:click="showRejectModal('{{ $selectedSubmission['submission_id'] }}')"
                            x-on:click="isDetailModalOpen = false"
                            class="px-4 py-2 rounded-xl border border-rose-200 bg-rose-50 text-rose-700 text-xs font-semibold hover:bg-rose-100 transition cursor-pointer">
                        Reject
                    </button>
                    <button wire:click="showApproveConfirm('{{ $selectedSubmission['submission_id'] }}')"
                            x-on:click="isDetailModalOpen = false"
                            style="background-color: #16a34a !important; color: #ffffff !important;"
                            class="px-4 py-2 rounded-xl text-xs font-bold transition shadow-sm hover:opacity-90 flex items-center gap-1.5 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Setuju
                    </button>
                @endif
            </div>
            @endif
        </div>
    </div>

    {{-- APPROVE CONFIRMATION MODAL --}}
    <div x-show="isApproveModalOpen"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
         style="display: none;">
        <div @click.outside="isApproveModalOpen = false; $wire.closeApproveConfirm()"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-5 border border-slate-100">
            @if($confirmApproveData)
            <div class="flex items-center gap-3 mb-3.5">
                <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">Konfirmasi Approve</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Tindakan ini bersifat final dan tidak dapat dibatalkan.</p>
                </div>
            </div>
            <div class="bg-slate-50 rounded-xl p-3 space-y-1.5 mb-3.5">
                <div class="flex justify-between text-xs"><span class="text-slate-500 font-semibold">Proyek</span><span class="font-bold text-slate-900 text-right max-w-[55%] truncate">{{ $confirmApproveData['project_name'] }}</span></div>
                <div class="flex justify-between text-xs"><span class="text-slate-500 font-semibold">Kategori</span><span class="font-bold text-slate-900">{{ $confirmApproveData['category'] }}</span></div>
                <div class="flex justify-between text-xs"><span class="text-slate-500 font-semibold">Diajukan Oleh</span><span class="font-bold text-slate-900">{{ $confirmApproveData['submitted_by_name'] }}</span></div>
            </div>
            @php
                $approveWarning = match($confirmApproveData['category']) {
                    'Progress Payment', 'Weekly Progress' => 'Menyetujui ini akan memperbarui nilai BCWP dan memicu perhitungan ulang EVM (CPI & SPI) untuk proyek ini.',
                    'Financial Addendum' => 'Menyetujui ini akan memperbarui nilai ACWP aktual dan memicu perhitungan ulang EAC proyek.',
                    'Scope Change' => 'Menyetujui ini akan mengubah bobot RAB dan menghitung ulang BCWS seluruh periode proyek.',
                    default => 'Submission ini akan disetujui dan data proyek akan diperbarui.'
                };
            @endphp
            <div class="bg-amber-50 border border-amber-200/80 rounded-xl p-2.5 flex items-start gap-2 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <p class="text-[10px] text-amber-800 font-medium">{{ $approveWarning }}</p>
            </div>
            <div class="flex items-center justify-end gap-2">
                <button @click="isApproveModalOpen = false; $wire.closeApproveConfirm()" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer">Batal</button>
                <button wire:click="confirmApprove"
                        style="background-color: #16a34a !important; color: #ffffff !important;"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition shadow-sm hover:opacity-90 flex items-center gap-1.5 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Confirm Approve
                </button>
            </div>
            @endif
        </div>
    </div>

    {{-- REJECT REASON MODAL --}}
    <div x-show="isRejectModalOpen"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
         style="display: none;">
        <div @click.outside="isRejectModalOpen = false; $wire.closeRejectModal()"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-5 border border-slate-100">
            <div class="flex items-center gap-3 mb-3.5">
                <div class="w-9 h-9 rounded-xl bg-rose-100 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">Reject Submission</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">
                        @if($rejectSubmissionData){{ $rejectSubmissionData['submission_id'] }} — {{ $rejectSubmissionData['project_name'] }}@endif
                    </p>
                </div>
            </div>
            <div class="mb-3.5">
                <label for="reject-reason-textarea" class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                    Keterangan Alasan Penolakan <span class="text-rose-500">*</span>
                </label>
                <textarea id="reject-reason-textarea"
                          wire:model.live="rejectReason"
                          rows="3"
                          placeholder="Tuliskan alasan penolakan secara jelas dan spesifik..."
                          class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 transition resize-none"></textarea>
                @error('rejectReason')
                    <p class="text-[10px] text-rose-600 mt-0.5 font-semibold">{{ $message }}</p>
                @enderror
                <p class="text-[10px] text-slate-400 mt-0.5" x-text="($wire.rejectReason ? $wire.rejectReason.length : 0) + ' karakter (minimal 10)'">{{ strlen($rejectReason) }} karakter (minimal 10)</p>
            </div>
            <div class="flex items-center justify-end gap-2">
                <button @click="isRejectModalOpen = false; $wire.closeRejectModal()" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer">Batal</button>
                <button wire:click="confirmReject"
                        :disabled="(!$wire.rejectReason || $wire.rejectReason.length < 10)"
                        :style="($wire.rejectReason && $wire.rejectReason.length >= 10)
                            ? 'background-color: #dc2626 !important; color: #ffffff !important; cursor: pointer;'
                            : 'background-color: #e2e8f0 !important; color: #94a3b8 !important; cursor: not-allowed;'"
                        style="background-color: #e2e8f0; color: #94a3b8; cursor: not-allowed;"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition shadow-xs">
                    Confirm Reject
                </button>
            </div>
        </div>
    </div>

</div>
