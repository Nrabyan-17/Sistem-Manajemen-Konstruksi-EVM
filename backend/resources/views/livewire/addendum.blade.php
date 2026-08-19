<div x-data="{ 
    isNewModalOpen: false, 
    toastMessage: '',
    showToast: false
}"
@addendum-created.window="
    isNewModalOpen = false;
    toastMessage = $event.detail.message;
    showToast = true;
    setTimeout(() => showToast = false, 3000);
"
@addendum-updated.window="
    toastMessage = $event.detail.message;
    showToast = true;
    setTimeout(() => showToast = false, 3000);
">

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
            <h1 class="text-2xl font-extrabold text-slate-900">Contract Addendums</h1>
            <p class="text-slate-500 text-sm mt-1">Manage and track project contract variations and modifications</p>
        </div>
        <button @click="isNewModalOpen = true"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition shadow-sm shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Request Addendum
        </button>
    </div>

    <!-- KPI Dashboard Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
            <div class="flex items-start justify-between">
                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <p class="text-xs text-slate-500 mt-3 font-semibold">Total Submissions</p>
            <p class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ $kpiTotal }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
            <div class="flex items-start justify-between">
                <div class="w-9 h-9 rounded-xl bg-green-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-xs text-slate-500 mt-3 font-semibold">Approved Addendums</p>
            <p class="text-2xl font-extrabold text-emerald-600 mt-0.5">{{ $kpiApproved }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
            <div class="flex items-start justify-between">
                <div class="w-9 h-9 rounded-xl bg-orange-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-xs text-slate-500 mt-3 font-semibold">Pending Review</p>
            <p class="text-2xl font-extrabold text-orange-600 mt-0.5">{{ $kpiPending }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
            <div class="flex items-start justify-between">
                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-xs text-slate-500 mt-3 font-semibold">Approved Addendum Value</p>
            <p class="text-2xl font-extrabold text-slate-900 mt-0.5">
                Rp {{ number_format($kpiTotalValue / 1e9, 2) }}B
            </p>
        </div>
    </div>

    <!-- Filtering Section -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 mt-6">
        <div class="flex flex-col lg:flex-row gap-3">
            <div class="relative flex-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10.5A6.5 6.5 0 114 10.5a6.5 6.5 0 0113 0z" />
                </svg>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search addendum title, ID, or project name..." 
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
            </div>

            <select wire:model.live="statusFilter" class="px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                <option value="all">All Status</option>
                <option value="APPROVED">Approved</option>
                <option value="PENDING">Pending</option>
                <option value="REJECTED">Rejected</option>
            </select>

            <select wire:model.live="projectFilter" class="px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                <option value="all">All Projects</option>
                @foreach($projects as $p)
                    <option value="{{ $p['project_id'] }}">{{ $p['project_name'] }}</option>
                @endforeach
            </select>

            <button wire:click="$set('search', ''); $set('statusFilter', 'all'); $set('projectFilter', 'all');"
                    class="flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Clear Filters
            </button>
        </div>
    </div>

    <!-- Addendums Table View -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden mt-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 uppercase font-bold border-b border-slate-100">
                        <th class="p-4">Addendum ID</th>
                        <th class="p-4">Project</th>
                        <th class="p-4">Title</th>
                        <th class="p-4">Value Variance</th>
                        <th class="p-4">Submission Date</th>
                        <th class="p-4">Description</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($filteredAddendums as $a)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4 font-bold text-slate-900">{{ $a['addendum_id'] }}</td>
                            <td class="p-4 text-slate-500 font-semibold">{{ $a['project_name'] }}</td>
                            <td class="p-4 text-slate-700 font-semibold">{{ $a['title'] }}</td>
                            <td class="p-4 font-extrabold {{ $a['value'] >= 0 ? 'text-slate-900' : 'text-emerald-600' }}">
                                Rp {{ number_format($a['value'] / 1e6, 1) }}M
                            </td>
                            <td class="p-4 text-slate-500">{{ date('d/m/Y', strtotime($a['date'])) }}</td>
                            <td class="p-4 text-slate-500 max-w-xs truncate" title="{{ $a['description'] }}">{{ $a['description'] }}</td>
                            <td class="p-4">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full
                                    {{ $a['status'] === 'APPROVED' ? 'bg-emerald-50 text-emerald-700' : '' }}
                                    {{ $a['status'] === 'PENDING' ? 'bg-amber-50 text-amber-700' : '' }}
                                    {{ $a['status'] === 'REJECTED' ? 'bg-rose-50 text-rose-700' : '' }}
                                ">
                                    {{ $a['status'] }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                @if($a['status'] === 'PENDING')
                                    <div class="flex items-center justify-center gap-2">
                                        <button wire:click="approveAddendum('{{ $a['addendum_id'] }}')"
                                                class="px-2 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-[10px] font-bold transition shadow-sm">
                                            Approve
                                        </button>
                                        <button wire:click="rejectAddendum('{{ $a['addendum_id'] }}')"
                                                class="px-2 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded text-[10px] font-bold transition shadow-sm">
                                            Reject
                                        </button>
                                    </div>
                                @else
                                    <span class="text-slate-400 font-medium text-[11px]">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-400">
                                No addendum records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- REQUEST NEW ADDENDUM MODAL -->
    <div x-show="isNewModalOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm"
         style="display: none;">
        
        <div @click.away="isNewModalOpen = false"
             class="bg-white w-full max-w-xl rounded-2xl border border-slate-200 shadow-2xl overflow-hidden flex flex-col justify-between max-h-[90vh]">
            
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between shrink-0">
                <h3 class="text-base font-extrabold text-slate-900">Request Contract Addendum</h3>
                <button @click="isNewModalOpen = false" class="text-slate-400 hover:text-slate-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form wire:submit.prevent="createAddendum" class="flex-1 overflow-y-auto px-6 py-5 space-y-4 text-xs text-slate-700">
                
                <div>
                    <label class="block font-bold text-slate-800 mb-1.5">Select Project</label>
                    <select wire:model="newProjectId" required class="w-full px-3 py-2 border border-slate-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        <option value="">Choose Project...</option>
                        @foreach($projects as $p)
                            <option value="{{ $p['project_id'] }}">{{ $p['project_name'] }} ({{ $p['project_id'] }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-800 mb-1.5">Addendum Title</label>
                    <input type="text" wire:model="newTitle" placeholder="e.g. Foundation Design Revision" required
                           class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-800 mb-1.5">Cost Variance Value (Rupiah)</label>
                        <input type="number" wire:model="newValue" placeholder="e.g. 1200000000 (can be negative)" required
                               class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
                    </div>
                    <div>
                        <label class="block font-bold text-slate-800 mb-1.5">Submission Date</label>
                        <input type="date" wire:model="newDate" required
                               class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-800 mb-1.5">Initial Status</label>
                    <select wire:model="newStatus" required class="w-full px-3 py-2 border border-slate-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        <option value="PENDING">Pending</option>
                        <option value="APPROVED">Approved</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-800 mb-1.5">Detailed Description</label>
                    <textarea wire:model="newDescription" rows="3" placeholder="Provide justification or scope of change..."
                              class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"></textarea>
                </div>

                <div class="pt-5 border-t border-slate-100 flex items-center justify-end gap-3 shrink-0">
                    <button type="button" @click="isNewModalOpen = false" 
                            class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition shadow-md shadow-blue-500/25">
                        Submit Addendum
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
