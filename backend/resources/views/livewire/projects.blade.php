<div x-data="{ 
    isNewModalOpen: false, 
    isHistoryModalOpen: false,
    toastMessage: '',
    showToast: false
}"
@open-history-modal.window="isHistoryModalOpen = true"
@project-created.window="
    isNewModalOpen = false;
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
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Projects</h1>
            <p class="text-slate-500 text-sm mt-1">Manage and monitor all construction projects</p>
        </div>
        <button @click="isNewModalOpen = true"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition shadow-sm shrink-0">
            <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <rect x="3.5" y="3.5" width="17" height="17" rx="4.5" stroke="currentColor" stroke-width="1.8"/>
                <path d="M12 8.2V15.8M8.2 12H15.8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            New Project
        </button>
    </div>

    <!-- KPI Dashboard Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 mt-6">
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
            <p class="text-xs text-slate-500 font-semibold">Total Projects</p>
            <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ $kpiTotal }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
            <p class="text-xs text-slate-500 font-semibold">Completed</p>
            <p class="text-2xl font-extrabold text-blue-600 mt-1">{{ $kpiCompleted }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
            <p class="text-xs text-slate-500 font-semibold">On Track</p>
            <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $kpiOnTrack }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
            <p class="text-xs text-slate-500 font-semibold">At Risk</p>
            <p class="text-2xl font-extrabold text-orange-600 mt-1">{{ $kpiAtRisk }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
            <p class="text-xs text-slate-500 font-semibold">Critical</p>
            <p class="text-2xl font-extrabold text-red-600 mt-1">{{ $kpiCritical }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
            <p class="text-xs text-slate-500 font-semibold">Total Contract Value</p>
            <p class="text-xl font-extrabold text-slate-900 mt-1" title="{{ \App\Support\CurrencyHelper::formatFull($kpiTotalContract) }}">
                {{ \App\Support\CurrencyHelper::format($kpiTotalContract) }}
            </p>
        </div>
    </div>

    <!-- Filtering Section -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 mt-6">
        <div class="flex flex-col lg:flex-row gap-3">
            <div class="relative flex-1">
                <svg viewBox="0 0 24 24" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <circle cx="10.5" cy="10.5" r="5.5" stroke="currentColor" stroke-width="1.8"/>
                    <path d="M15.3 15.3L19 19" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search project, location, or project manager..." 
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
            </div>

            <select wire:model.live="statusFilter" class="px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                <option value="all">All Status</option>
                <option value="ON TRACK">On Track</option>
                <option value="AT RISK">At Risk</option>
                <option value="CRITICAL">Critical</option>
                <option value="COMPLETED">Completed</option>
            </select>

            <select wire:model.live="pmFilter" class="px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                <option value="all">All Managers</option>
                <option value="Andi Pratama">Andi Pratama</option>
                <option value="Budi Santoso">Budi Santoso</option>
                <option value="Dimas Wijaya">Dimas Wijaya</option>
                <option value="Rizky Ramadhan">Rizky Ramadhan</option>
                <option value="Fajar Nugroho">Fajar Nugroho</option>
            </select>

            <select wire:model.live="periodFilter" class="px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                <option value="all">All Period</option>
                <option value="month">This Month</option>
                <option value="quarter">This Quarter</option>
                <option value="year">This Year</option>
            </select>

            <button wire:click="handleClearFilters"
                    class="flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition shrink-0">
                <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="1.8"/>
                    <path d="M9.1 14.9L14.9 9.1M14.9 14.9L9.1 9.1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Clear Filters
            </button>
        </div>
    </div>

    <!-- Portfolio Content Section -->
    <div class="mt-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold text-slate-900">Project Portfolio</h2>
            <div class="flex items-center gap-1 bg-slate-100 rounded-xl p-1">
                <button wire:click="setViewMode('cards')" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $viewMode === 'cards' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                    Cards
                </button>
                <button wire:click="setViewMode('table')" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $viewMode === 'table' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                    Table
                </button>
            </div>
        </div>

        @if($viewMode === 'cards')
            <!-- Card View Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($filteredProjects as $p)
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-3 mb-2">
                                <p class="text-sm font-extrabold text-slate-900 hover:text-blue-600 transition">{{ $p['project_name'] }}</p>
                                <span class="text-[10px] font-extrabold tracking-wider px-2 py-0.5 rounded-full shrink-0
                                    {{ $p['status'] === 'ON TRACK' ? 'bg-emerald-50 text-emerald-700' : '' }}
                                    {{ $p['status'] === 'AT RISK' ? 'bg-amber-50 text-amber-700' : '' }}
                                    {{ $p['status'] === 'CRITICAL' ? 'bg-rose-50 text-rose-700' : '' }}
                                    {{ $p['status'] === 'COMPLETED' ? 'bg-blue-50 text-blue-700' : '' }}
                                ">
                                    {{ $p['status'] }}
                                </span>
                            </div>
                            <p class="text-[10px] font-bold text-slate-400">ID: {{ $p['project_id'] }} | SPK: {{ $p['spk_number'] }}</p>

                            <div class="grid grid-cols-2 gap-x-2 gap-y-1.5 mt-4 text-xs">
                                <div>
                                    <p class="text-[10px] text-slate-400 font-medium">Client</p>
                                    <p class="font-semibold text-slate-700 truncate">{{ $p['client'] }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-400 font-medium">Project Manager</p>
                                    <p class="font-semibold text-slate-700 truncate">{{ $p['project_manager'] }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-400 font-medium">Tgl SPK</p>
                                    <p class="font-semibold text-slate-700">{{ date('d/m/Y', strtotime($p['start_date'])) }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-400 font-medium">Tgl BAST</p>
                                    <p class="font-semibold text-slate-700">{{ date('d/m/Y', strtotime($p['bast_date'])) }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-400 font-medium">Contract Value</p>
                                    <p class="font-extrabold text-slate-900" title="{{ \App\Support\CurrencyHelper::formatFull($p['contract_value']) }}">
                                        {{ \App\Support\CurrencyHelper::format($p['contract_value']) }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-400 font-medium">Actual Cost</p>
                                    <p class="font-extrabold text-slate-900" title="{{ \App\Support\CurrencyHelper::formatFull($p['acwp'] ?? 0) }}">
                                        {{ \App\Support\CurrencyHelper::format($p['acwp'] ?? 0) }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-4">
                                <div class="flex items-center justify-between text-xs text-slate-500 mb-1">
                                    <span class="font-medium">Realisasi (BCWP)</span>
                                    <span class="font-bold text-slate-800">{{ number_format($p['bcwp_pct'] ?? $p['progress'], 2) }}%</span>
                                </div>
                                <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                                    <div class="h-2 rounded-full transition-all duration-500
                                        {{ $p['status'] === 'ON TRACK' ? 'bg-emerald-500' : '' }}
                                        {{ $p['status'] === 'AT RISK' ? 'bg-amber-500' : '' }}
                                        {{ $p['status'] === 'CRITICAL' ? 'bg-rose-500' : '' }}
                                        {{ $p['status'] === 'COMPLETED' ? 'bg-blue-500' : '' }}
                                    " style="width: {{ $p['progress'] }}%"></div>
                                </div>
                                <div class="flex items-center justify-between text-[10px] text-slate-400 mt-1">
                                    <span>Rencana (BCWS): {{ number_format($p['bcws_pct'] ?? 0, 2) }}%</span>
                                    <span class="font-bold {{ ($p['deviasi'] ?? 0) > 0 ? 'text-rose-600' : 'text-emerald-600' }}">Deviasi: {{ ($p['deviasi'] ?? 0) > 0 ? '+' : '' }}{{ number_format($p['deviasi'] ?? 0, 2) }}%</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-2 mt-4 pt-3 border-t border-slate-100 text-center text-xs">
                                <div>
                                    <p class="text-[10px] font-medium text-slate-400 uppercase">CPI</p>
                                    <p class="font-bold {{ $p['cpi'] >= 1.0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ number_format($p['cpi'], 2) }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-medium text-slate-400 uppercase">SPI</p>
                                    <p class="font-bold {{ $p['spi'] >= 1.0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ number_format($p['spi'], 2) }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-medium text-slate-400 uppercase">Remaining</p>
                                    <p class="font-bold text-slate-700">{{ $p['remaining_days'] }}d</p>
                                </div>
                            </div>
                            @if(isset($p['last_updated']))
                                <p class="text-[10px] text-slate-400 mt-2">Last Update: {{ date('d/m/Y', strtotime($p['last_updated'])) }}</p>
                            @endif
                        </div>

                        <div class="mt-5 pt-3 border-t border-slate-100 space-y-2">
                            <a href="{{ route('projects.show', $p['project_id']) }}"
                               class="flex items-center justify-center gap-1.5 w-full text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 px-3 py-2 rounded-lg transition">
                                View Project Details
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                            <div class="flex items-center justify-between">
                                <button wire:click="showHistory('{{ $p['project_id'] }}')" 
                                        class="text-xs font-semibold text-slate-500 hover:text-slate-700 px-3 py-1.5 rounded-lg hover:bg-slate-50 transition border border-slate-100">
                                    View History
                                </button>
                                <span class="text-[10px] font-bold text-slate-400" title="{{ \App\Support\CurrencyHelper::formatFull($p['eac']) }}">
                                    Est. Cost: {{ \App\Support\CurrencyHelper::format($p['eac']) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Table View -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-400 uppercase font-bold border-b border-slate-100">
                                <th class="p-4 cursor-pointer hover:text-slate-700" wire:click="handleSort('project_name')">Project Name</th>
                                <th class="p-4">SPK</th>
                                <th class="p-4 cursor-pointer hover:text-slate-700" wire:click="handleSort('contract_value')">Contract Value</th>
                                <th class="p-4">Tgl SPK</th>
                                <th class="p-4">Tgl BAST</th>
                                <th class="p-4">BCWS %</th>
                                <th class="p-4 cursor-pointer hover:text-slate-700" wire:click="handleSort('progress')">BCWP %</th>
                                <th class="p-4">ACWP</th>
                                <th class="p-4">Deviasi</th>
                                <th class="p-4 cursor-pointer hover:text-slate-700" wire:click="handleSort('cpi')">CPI</th>
                                <th class="p-4 cursor-pointer hover:text-slate-700" wire:click="handleSort('spi')">SPI</th>
                                <th class="p-4">Status</th>
                                <th class="p-4">Last Update</th>
                                <th class="p-4 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($paginatedProjects as $p)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="p-4">
                                        <p class="font-bold text-slate-900">{{ $p['project_name'] }}</p>
                                        <p class="text-[10px] text-slate-400 mt-0.5">{{ $p['project_manager'] }} · {{ $p['client'] }}</p>
                                    </td>
                                    <td class="p-4 text-slate-500 font-semibold">{{ $p['spk_number'] }}</td>
                                    <td class="p-4 font-extrabold text-slate-900" title="{{ \App\Support\CurrencyHelper::formatFull($p['contract_value']) }}">
                                        {{ \App\Support\CurrencyHelper::format($p['contract_value']) }}
                                    </td>
                                    <td class="p-4 text-slate-500">{{ date('d/m/Y', strtotime($p['start_date'])) }}</td>
                                    <td class="p-4 text-slate-500">{{ date('d/m/Y', strtotime($p['bast_date'])) }}</td>
                                    <td class="p-4 font-bold text-blue-600">{{ number_format($p['bcws_pct'] ?? 0, 2) }}%</td>
                                    <td class="p-4 font-bold text-emerald-600">{{ number_format($p['bcwp_pct'] ?? $p['progress'], 2) }}%</td>
                                    <td class="p-4 font-semibold text-slate-700" title="{{ \App\Support\CurrencyHelper::formatFull($p['acwp'] ?? 0) }}">{{ \App\Support\CurrencyHelper::format($p['acwp'] ?? 0) }}</td>
                                    <td class="p-4 font-bold {{ ($p['deviasi'] ?? 0) > 0 ? 'text-rose-600' : 'text-emerald-600' }}">{{ ($p['deviasi'] ?? 0) > 0 ? '+' : '' }}{{ number_format($p['deviasi'] ?? 0, 2) }}%</td>
                                    <td class="p-4 font-bold {{ $p['cpi'] >= 1.0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ number_format($p['cpi'], 2) }}</td>
                                    <td class="p-4 font-bold {{ $p['spi'] >= 1.0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ number_format($p['spi'], 2) }}</td>
                                    <td class="p-4">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full
                                            {{ $p['status'] === 'ON TRACK' ? 'bg-emerald-50 text-emerald-700' : '' }}
                                            {{ $p['status'] === 'AT RISK' ? 'bg-amber-50 text-amber-700' : '' }}
                                            {{ $p['status'] === 'CRITICAL' ? 'bg-rose-50 text-rose-700' : '' }}
                                            {{ $p['status'] === 'COMPLETED' ? 'bg-blue-50 text-blue-700' : '' }}
                                        ">
                                            {{ $p['status'] }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-slate-500 text-[10px]">{{ isset($p['last_updated']) ? date('d/m/Y', strtotime($p['last_updated'])) : '—' }}</td>
                                    <td class="p-4 text-center">
    <div class="flex items-center justify-center gap-3">
        <a href="{{ route('projects.show', $p['project_id']) }}"
           class="text-xs font-bold text-blue-600 hover:text-blue-700 transition">
            View
        </a>
        <button wire:click="showHistory('{{ $p['project_id'] }}')" 
                class="text-xs font-semibold text-slate-500 hover:text-slate-700 transition">
            History
        </button>
    </div>
</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination footer -->
                @if($totalPages > 1)
                    <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between">
                        <p class="text-xs text-slate-500">
                            Showing page <span class="font-bold text-slate-800">{{ $currentPage }}</span> of <span class="font-bold text-slate-800">{{ $totalPages }}</span>
                        </p>
                        <div class="flex items-center gap-1.5">
                            <button @if($currentPage == 1) disabled @endif 
                                    wire:click="$set('currentPage', {{ max(1, $currentPage - 1) }})"
                                    class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 disabled:opacity-50 disabled:pointer-events-none transition">
                                Previous
                            </button>
                            <button @if($currentPage == $totalPages) disabled @endif 
                                    wire:click="$set('currentPage', {{ min($totalPages, $currentPage + 1) }})"
                                    class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 disabled:opacity-50 disabled:pointer-events-none transition">
                                Next
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- CREATE NEW PROJECT MODAL -->
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
             class="bg-white w-full max-w-2xl rounded-2xl border border-slate-200 shadow-2xl overflow-hidden flex flex-col justify-between max-h-[90vh]">
            
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between shrink-0">
                <h3 class="text-base font-extrabold text-slate-900">Add New Project</h3>
                <button @click="isNewModalOpen = false" class="text-slate-400 hover:text-slate-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Scrollable form container -->
            <form wire:submit.prevent="createProject" 
                  class="flex-1 overflow-y-auto px-6 py-5 space-y-4 text-xs text-slate-700"
                  x-data="{
                      startDate: @entangle('newStartDate'),
                      bastDate: @entangle('newBastDate'),
                      get calculatedDuration() {
                          if (!this.startDate || !this.bastDate) return 'Auto-calculated';
                          const s = new Date(this.startDate);
                          const b = new Date(this.bastDate);
                          const diff = Math.abs(b - s);
                          const days = Math.ceil(diff / (1000 * 60 * 60 * 24));
                          return isNaN(days) ? 'Auto-calculated' : days + ' Days';
                      },
                      get calculatedRemaining() {
                          if (!this.bastDate) return '—';
                          const b = new Date(this.bastDate);
                          const today = new Date();
                          const diff = b - today;
                          const days = Math.ceil(diff / (1000 * 60 * 60 * 24));
                          return isNaN(days) ? '—' : (days < 0 ? 0 : days) + ' Days';
                      }
                  }">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-800 mb-1.5">Project ID</label>
                        <input type="text" wire:model="newProjId" placeholder="e.g. PRJ-007" required
                               class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
                    </div>
                    <div>
                        <label class="block font-bold text-slate-800 mb-1.5">Project Name</label>
                        <input type="text" wire:model="newProjName" placeholder="e.g. Bintang Tower" required
                               class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-800 mb-1.5">SPK Number</label>
                        <input type="text" wire:model="newSpk" placeholder="e.g. SPK-2026-007" required
                               class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
                    </div>
                    <div>
                        <label class="block font-bold text-slate-800 mb-1.5">Client</label>
                        <input type="text" wire:model="newClient" placeholder="e.g. PT Bintang" required
                               class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block font-bold text-slate-800 mb-1.5">Project Manager</label>
                        <select wire:model="newPm" required class="w-full px-3 py-2 border border-slate-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            <option value="">Select PM</option>
                            <option value="Andi Pratama">Andi Pratama</option>
                            <option value="Budi Santoso">Budi Santoso</option>
                            <option value="Dimas Wijaya">Dimas Wijaya</option>
                            <option value="Rizky Ramadhan">Rizky Ramadhan</option>
                            <option value="Fajar Nugroho">Fajar Nugroho</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-800 mb-1.5">Location</label>
                        <input type="text" wire:model="newLocation" placeholder="e.g. Jakarta" required
                               class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
                    </div>
                    <div>
                        <label class="block font-bold text-slate-800 mb-1.5">Contract Value (Rupiah)</label>
                        <div x-data="{
                            rawValue: @entangle('newValue'),
                            displayValue: '',
                            init() {
                                if (this.rawValue) this.displayValue = this.formatCurrency(this.rawValue);
                            },
                            formatCurrency(val) {
                                if (!val) return '';
                                return 'Rp ' + String(val).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                            },
                            onInput(e) {
                                let digits = e.target.value.replace(/[^0-9]/g, '');
                                this.rawValue = digits;
                                this.displayValue = digits ? this.formatCurrency(digits) : '';
                            }
                        }">
                            <input type="text" x-model="displayValue" @input="onInput($event)" placeholder="Rp 0" required
                                   class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
                            @if($newValue && is_numeric($newValue))
                                <p class="text-[10px] text-blue-600 font-semibold mt-1">
                                    {{ \App\Support\CurrencyHelper::format($newValue) }} &bull; {{ \App\Support\CurrencyHelper::formatFull($newValue) }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-800 mb-1.5">Start Date</label>
                        <input type="date" x-model="startDate" required
                               class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
                    </div>
                    <div>
                        <label class="block font-bold text-slate-800 mb-1.5">BAST Date (Target Completion)</label>
                        <input type="date" x-model="bastDate" required
                               class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200/60 mt-4 text-center">
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase">Auto-calculated Duration</p>
                        <p class="text-sm font-extrabold text-slate-800 mt-1" x-text="calculatedDuration"></p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase">Time Remaining</p>
                        <p class="text-sm font-extrabold text-slate-800 mt-1" x-text="calculatedRemaining"></p>
                    </div>
                </div>

                {{-- Excel Import Section (Poin 10 Revisi) --}}
                <div class="mt-4 pt-4 border-t border-slate-100">
                    <p class="text-xs font-bold text-slate-800 mb-3">Import Data (Opsional)</p>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 text-center hover:border-blue-400 hover:bg-blue-50/30 transition cursor-pointer">
                            <svg viewBox="0 0 24 24" class="w-6 h-6 mx-auto text-slate-400" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M8 3.8V8.5C8 9.05 8.45 9.5 9 9.5H14.5L18 13V18.2C18 18.75 17.55 19.2 17 19.2H7C6.45 19.2 6 18.75 6 18.2V4.8C6 4.25 6.45 3.8 7 3.8H8Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                                <path d="M14.5 9.5V3.8L18 7.3H14.5V9.5Z" fill="currentColor" opacity="0.2"/>
                                <path d="M8.5 13.5H15.5M8.5 16.2H13.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                                <path d="M9.5 9.5V11.5M12 9.5V11.5M14.5 9.5V11.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                            </svg>
                            <p class="text-[10px] font-bold text-slate-600 mt-2">Import Excel RAB</p>
                            <p class="text-[9px] text-slate-400 mt-0.5">No, Item, Sat, Vol, Hrg Sat, Subtotal</p>
                        </div>
                        <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 text-center hover:border-blue-400 hover:bg-blue-50/30 transition cursor-pointer">
                            <svg viewBox="0 0 24 24" class="w-6 h-6 mx-auto text-slate-400" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M5 15.5L9.5 11L12.5 14L18.5 8.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M15.8 8.5H18.5V11.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                <rect x="4" y="4" width="16" height="16" rx="3" stroke="currentColor" stroke-width="1.7"/>
                            </svg>
                            <p class="text-[10px] font-bold text-slate-600 mt-2">Import Kurva S</p>
                            <p class="text-[9px] text-slate-400 mt-0.5">Mg ke, Tgl Awal, Tgl Akhir, Rencana, Kumulatif</p>
                        </div>
                    </div>
                </div>

                <div class="pt-5 border-t border-slate-100 flex items-center justify-end gap-3 shrink-0 mt-4">
                    <button type="button" @click="isNewModalOpen = false" 
                            class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition shadow-md shadow-blue-500/25">
                        Add Project
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- PROJECT HISTORY DETAILS MODAL -->
    <div x-show="isHistoryModalOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm"
         style="display: none;">
        
        <div @click.away="isHistoryModalOpen = false"
             class="bg-white w-full max-w-lg rounded-2xl border border-slate-200 shadow-2xl overflow-hidden flex flex-col justify-between max-h-[85vh]">
            
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between shrink-0">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Project History Logs</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5">Audit log details for project: {{ $selectedHistoryId }}</p>
                </div>
                <button @click="isHistoryModalOpen = false" class="text-slate-400 hover:text-slate-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Scrollable list of logs -->
            <div class="flex-1 overflow-y-auto px-6 py-5">
                @if($selectedHistoryId && isset($projectHistory[$selectedHistoryId]))
                    <div class="relative border-l border-slate-100 pl-5 ml-2.5 space-y-6">
                        @foreach($projectHistory[$selectedHistoryId] as $log)
                            <div class="relative">
                                <!-- dot indicator -->
                                <span class="absolute -left-[27px] top-1 w-3.5 h-3.5 rounded-full border-2 border-white bg-blue-500 shadow-sm"></span>
                                <div class="text-xs">
                                    <div class="flex items-center justify-between gap-3 text-slate-400 font-semibold text-[10px]">
                                        <span>{{ date('d M Y', strtotime($log['date'])) }}</span>
                                        <span>User: {{ $log['user'] }}</span>
                                    </div>
                                    <p class="text-sm font-bold text-slate-800 mt-1">{{ $log['action'] }}</p>
                                    
                                    <div class="bg-slate-50 border border-slate-200/50 p-2.5 rounded-lg mt-2 font-mono text-[10px] text-slate-600 grid grid-cols-2 gap-3">
                                        <div>
                                            <p class="text-[9px] uppercase font-bold text-slate-400 mb-0.5">Previous Value</p>
                                            <p class="truncate">{{ $log['old'] }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[9px] uppercase font-bold text-slate-400 mb-0.5">New Value</p>
                                            <p class="truncate text-blue-600 font-bold">{{ $log['new'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400 text-center py-5">No logs available for this project.</p>
                @endif
            </div>

            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end shrink-0 bg-slate-50/50">
                <button @click="isHistoryModalOpen = false" 
                        class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold transition shadow-sm">
                    Close Audit Log
                </button>
            </div>
        </div>
    </div>

</div>
