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
            <h1 class="text-2xl font-extrabold text-slate-900">Projects</h1>
            <p class="text-slate-500 text-sm mt-1">Manage and monitor all construction projects</p>
        </div>
        <button @click="isNewModalOpen = true"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition shadow-sm shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            New Project
        </button>
    </div>

    <!-- KPI Dashboard Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 mt-6">
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
            <div class="flex items-start justify-between">
                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2M5 21H3m16 0h-5v-4a1 1 0 00-1-1h-2a1 1 0 00-1 1v4H5m5-17h4m-4 4h4m-4 4h4" />
                    </svg>
                </div>
            </div>
            <p class="text-xs text-slate-500 mt-3 font-semibold">Total Projects</p>
            <p class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ $kpiTotal }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
            <div class="flex items-start justify-between">
                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>
            <p class="text-xs text-slate-500 mt-3 font-semibold">Active Projects</p>
            <p class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ $kpiActive }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
            <div class="flex items-start justify-between">
                <div class="w-9 h-9 rounded-xl bg-green-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>
            <p class="text-xs text-slate-500 mt-3 font-semibold">On Track</p>
            <p class="text-2xl font-extrabold text-emerald-600 mt-0.5">{{ $kpiOnTrack }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
            <div class="flex items-start justify-between">
                <div class="w-9 h-9 rounded-xl bg-orange-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </div>
            </div>
            <p class="text-xs text-slate-500 mt-3 font-semibold">At Risk</p>
            <p class="text-2xl font-extrabold text-orange-600 mt-0.5">{{ $kpiAtRisk }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
            <div class="flex items-start justify-between">
                <div class="w-9 h-9 rounded-xl bg-red-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-xs text-slate-500 mt-3 font-semibold">Critical</p>
            <p class="text-2xl font-extrabold text-red-600 mt-0.5">{{ $kpiCritical }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
            <div class="flex items-start justify-between">
                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 8h6m-5 0a3 3 0 000 6h4a3 3 0 010 6H9m3-16v2m0 12v2" />
                    </svg>
                </div>
            </div>
            <p class="text-xs text-slate-500 mt-3 font-semibold">Total Contract Value</p>
            <p class="text-xl font-extrabold text-slate-900 mt-0.5">
                Rp {{ number_format($kpiTotalContract / 1e9, 1) }}B
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
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
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
                                    <p class="text-[10px] text-slate-400 font-medium">Location</p>
                                    <p class="font-semibold text-slate-700">{{ $p['location'] }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-400 font-medium">Contract Value</p>
                                    <p class="font-extrabold text-slate-900">Rp {{ number_format($p['contract_value'] / 1e9, 2) }}B</p>
                                </div>
                            </div>

                            <div class="mt-4">
                                <div class="flex items-center justify-between text-xs text-slate-500 mb-1">
                                    <span class="font-medium">Progress</span>
                                    <span class="font-bold text-slate-800">{{ $p['progress'] }}%</span>
                                </div>
                                <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                                    <div class="h-2 rounded-full transition-all duration-500
                                        {{ $p['status'] === 'ON TRACK' ? 'bg-emerald-500' : '' }}
                                        {{ $p['status'] === 'AT RISK' ? 'bg-amber-500' : '' }}
                                        {{ $p['status'] === 'CRITICAL' ? 'bg-rose-500' : '' }}
                                        {{ $p['status'] === 'COMPLETED' ? 'bg-blue-500' : '' }}
                                    " style="width: {{ $p['progress'] }}%"></div>
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
                        </div>

                        <div class="mt-5 pt-3 border-t border-slate-100 space-y-2">
                            <a href="{{ route('projects.show', $p['project_id']) }}" wire:navigate
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
                                <span class="text-[10px] font-bold text-slate-400">Est. Cost: Rp {{ number_format($p['eac'] / 1e9, 2) }}B</span>
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
                                <th class="p-4">PM</th>
                                <th class="p-4">Client</th>
                                <th class="p-4 cursor-pointer hover:text-slate-700" wire:click="handleSort('contract_value')">Contract Value</th>
                                <th class="p-4">Start Date</th>
                                <th class="p-4">BAST Date</th>
                                <th class="p-4 cursor-pointer hover:text-slate-700" wire:click="handleSort('progress')">Progress</th>
                                <th class="p-4 cursor-pointer hover:text-slate-700" wire:click="handleSort('cpi')">CPI</th>
                                <th class="p-4 cursor-pointer hover:text-slate-700" wire:click="handleSort('spi')">SPI</th>
                                <th class="p-4 cursor-pointer hover:text-slate-700" wire:click="handleSort('remaining_days')">Remaining</th>
                                <th class="p-4">Status</th>
                                <th class="p-4 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($paginatedProjects as $p)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="p-4 font-bold text-slate-900">{{ $p['project_name'] }}</td>
                                    <td class="p-4 text-slate-500 font-semibold">{{ $p['spk_number'] }}</td>
                                    <td class="p-4 text-slate-700 font-medium">{{ $p['project_manager'] }}</td>
                                    <td class="p-4 text-slate-500">{{ $p['client'] }}</td>
                                    <td class="p-4 font-extrabold text-slate-900">Rp {{ number_format($p['contract_value'] / 1e9, 2) }}B</td>
                                    <td class="p-4 text-slate-500">{{ date('d/m/Y', strtotime($p['start_date'])) }}</td>
                                    <td class="p-4 text-slate-500">{{ date('d/m/Y', strtotime($p['bast_date'])) }}</td>
                                    <td class="p-4 font-bold text-slate-700">{{ $p['progress'] }}%</td>
                                    <td class="p-4 font-bold {{ $p['cpi'] >= 1.0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ number_format($p['cpi'], 2) }}</td>
                                    <td class="p-4 font-bold {{ $p['spi'] >= 1.0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ number_format($p['spi'], 2) }}</td>
                                    <td class="p-4 font-bold text-slate-700">{{ $p['remaining_days'] }}d</td>
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
                                    <td class="p-4 text-center">
    <div class="flex items-center justify-center gap-3">
        <a href="{{ route('projects.show', $p['project_id']) }}" wire:navigate
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
                        <input type="number" wire:model="newValue" placeholder="e.g. 15000000000" required
                               class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
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

                <div class="grid grid-cols-3 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200/60 mt-4 text-center">
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase">Auto-calculated Duration</p>
                        <p class="text-sm font-extrabold text-slate-800 mt-1" x-text="calculatedDuration"></p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase">Time Remaining</p>
                        <p class="text-sm font-extrabold text-slate-800 mt-1" x-text="calculatedRemaining"></p>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Status</label>
                        <select wire:model="newStatus" class="px-2 py-1 border border-slate-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            <option value="ON TRACK">On Track</option>
                            <option value="AT RISK">At Risk</option>
                            <option value="CRITICAL">Critical</option>
                        </select>
                    </div>
                </div>

                <div class="pt-5 border-t border-slate-100 flex items-center justify-end gap-3 shrink-0">
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
