<div>
    <!-- Title & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 sm:gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">Executive Dashboard</h1>
            <p class="text-slate-500 text-xs sm:text-sm mt-0.5 sm:mt-1">
                Portfolio Performance &amp; Real-time Earned Value Management (EVM) Monitoring
            </p>
        </div>
    </div>

    <!-- Key Metrics Row (6 cards) -->
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3 sm:gap-4 mt-4 sm:mt-6">
        <div class="bg-white rounded-2xl border border-slate-200/80 p-3.5 sm:p-4 shadow-sm hover:shadow-md transition">
            <p class="text-[11px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wide truncate">Active Projects</p>
            <p class="text-xl sm:text-2xl font-extrabold text-slate-900 mt-0.5 sm:mt-1">42</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-3.5 sm:p-4 shadow-sm hover:shadow-md transition">
            <p class="text-[11px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wide truncate">On Track</p>
            <p class="text-xl sm:text-2xl font-extrabold text-slate-900 mt-0.5 sm:mt-1">31</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-3.5 sm:p-4 shadow-sm hover:shadow-md transition">
            <p class="text-[11px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wide truncate">Delayed</p>
            <p class="text-xl sm:text-2xl font-extrabold text-slate-900 mt-0.5 sm:mt-1">7</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-3.5 sm:p-4 shadow-sm hover:shadow-md transition">
            <p class="text-[11px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wide truncate">Critical</p>
            <p class="text-xl sm:text-2xl font-extrabold text-slate-900 mt-0.5 sm:mt-1">4</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-3.5 sm:p-4 shadow-sm hover:shadow-md transition">
            <p class="text-[11px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wide truncate">Contract Value</p>
            <p class="text-xl sm:text-2xl font-extrabold text-slate-900 mt-0.5 sm:mt-1">Rp 2.4 T</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-3.5 sm:p-4 shadow-sm hover:shadow-md transition">
            <p class="text-[11px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wide truncate">Total P&amp;L</p>
            <p class="text-xl sm:text-2xl font-extrabold text-emerald-600 mt-0.5 sm:mt-1">+Rp 142 M</p>
        </div>
    </div>

    <!-- Main Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mt-4 sm:mt-6">
        <!-- S-Curve Graph Card -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 sm:p-6 flex flex-col justify-between">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 sm:mb-6">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900">EVM Performance (S-Curve)</h2>
                    <p class="text-[11px] sm:text-xs text-slate-500">Comparing BCWS, BCWP, and ACWP cumulative progress</p>
                </div>
                <!-- Project Selector Toggle -->
                <div class="flex items-center gap-1 bg-slate-100/90 p-1 rounded-xl overflow-x-auto max-w-full no-scrollbar shrink-0">
                    <button wire:click="setProject('portfolio')" class="px-3 sm:px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ $projectKey === 'portfolio' ? 'bg-white text-blue-600 shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                        Portfolio
                    </button>
                    <button wire:click="setProject('horizon')" class="px-3 sm:px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ $projectKey === 'horizon' ? 'bg-white text-blue-600 shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                        Horizon
                    </button>
                    <button wire:click="setProject('metro')" class="px-3 sm:px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ $projectKey === 'metro' ? 'bg-white text-blue-600 shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                        Metro Line
                    </button>
                    <button wire:click="setProject('industrial')" class="px-3 sm:px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ $projectKey === 'industrial' ? 'bg-white text-blue-600 shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                        Industrial
                    </button>
                </div>
            </div>

            <!-- Alpine Chart Wrapper -->
            <div x-data="sCurveChart" wire:ignore
                 class="relative h-[240px] xs:h-[280px] sm:h-[320px] w-full overflow-hidden">
                <canvas x-ref="sCurveCanvas" class="w-full h-full"></canvas>
            </div>

            <!-- EVM Stats Footer -->
            <div class="grid grid-cols-3 gap-2 sm:gap-4 border-t border-slate-100 pt-4 sm:pt-5 mt-4 sm:mt-5">
                <div class="min-w-0">
                    <div class="flex items-center gap-1.5 min-w-0">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600 shrink-0"></span>
                        <p class="text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider truncate">
                            <span class="hidden sm:inline">Planned </span>(BCWS)
                        </p>
                    </div>
                    <p class="text-xs sm:text-base font-extrabold text-slate-900 mt-1 truncate">{{ $currentProject['statBcws'] }}</p>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-1.5 min-w-0">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0"></span>
                        <p class="text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider truncate">
                            <span class="hidden sm:inline">Earned </span>(BCWP)
                        </p>
                    </div>
                    <p class="text-xs sm:text-base font-extrabold text-slate-900 mt-1 truncate">{{ $currentProject['statBcwp'] }}</p>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-1.5 min-w-0">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 shrink-0"></span>
                        <p class="text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider truncate">
                            <span class="hidden sm:inline">Actual </span>(ACWP)
                        </p>
                    </div>
                    <p class="text-xs sm:text-base font-extrabold text-slate-900 mt-1 truncate">{{ $currentProject['statAcwp'] }}</p>
                </div>
            </div>
        </div>

        <!-- Budget Utilization Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 sm:p-6 flex flex-col justify-between">
            <div>
                <h2 class="text-base sm:text-lg font-bold text-slate-900">Budget Utilization</h2>
                <p class="text-[11px] sm:text-xs text-slate-500">Budget consumed percentage per WBS task area</p>
            </div>

            <!-- Finance Bar Chart -->
            <div x-data="financeChart" wire:ignore
                 class="relative h-[200px] sm:h-[240px] w-full overflow-hidden mt-4 sm:mt-6">
                <canvas x-ref="financeCanvas" class="w-full h-full"></canvas>
            </div>

            <!-- Footer Legend -->
            <div class="border-t border-slate-100 pt-3 sm:pt-4 mt-3 sm:mt-4 flex flex-wrap items-center justify-between gap-2 text-xs text-slate-500">
                <span class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded bg-blue-600 shrink-0"></span> Allocated
                </span>
                <span class="font-bold text-slate-700">Avg Utilization: 71%</span>
            </div>
        </div>
    </div>

    <!-- Project Status & Alerts Row -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 sm:gap-6 mt-4 sm:mt-6">
        <!-- Projects Grid Overview -->
        <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 sm:p-6">
            <div class="flex items-center justify-between gap-2 mb-4">
                <div class="min-w-0">
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 truncate">Project Status Overview</h2>
                    <p class="text-[11px] sm:text-xs text-slate-500 truncate">Live EVM index per active project milestone</p>
                </div>
                <a href="/projects" 
                   @click.prevent="switchTab('projects', '/projects')"
                   class="text-xs sm:text-sm font-semibold text-blue-600 hover:text-blue-700 shrink-0 cursor-pointer">
                    View All Projects →
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5 sm:gap-4">
                <!-- Card 1 -->
                <div class="border border-slate-200 rounded-xl p-4 hover:border-blue-400 hover:shadow-md transition cursor-pointer group bg-gradient-to-b from-white to-slate-50/50">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-sm font-bold text-slate-900 group-hover:text-blue-600 transition truncate">
                            Grand Horizon Tower
                        </p>
                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 shrink-0">
                            ON TRACK
                        </span>
                    </div>
                    <div class="mt-3.5">
                        <div class="flex items-center justify-between text-xs text-slate-500 mb-1.5">
                            <span class="font-medium">Progress</span>
                            <span class="font-bold text-slate-800">75%</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-2 rounded-full bg-emerald-500 transition-all duration-500" style="width: 75%"></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-1.5 sm:gap-2 mt-4 pt-3 border-t border-slate-100 text-center">
                        <div>
                            <p class="text-[10px] uppercase font-semibold text-slate-400">CPI</p>
                            <p class="text-xs sm:text-sm font-extrabold text-slate-800">1.04</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-semibold text-slate-400">SPI</p>
                            <p class="text-xs sm:text-sm font-extrabold text-slate-800">0.98</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-semibold text-slate-400">Remaining</p>
                            <p class="text-xs sm:text-sm font-extrabold text-slate-800">45d</p>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="border border-slate-200 rounded-xl p-4 hover:border-amber-400 hover:shadow-md transition cursor-pointer group bg-gradient-to-b from-white to-slate-50/50">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-sm font-bold text-slate-900 group-hover:text-amber-600 transition truncate">
                            Industrial Park Phase II
                        </p>
                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 shrink-0">
                            AT RISK
                        </span>
                    </div>
                    <div class="mt-3.5">
                        <div class="flex items-center justify-between text-xs text-slate-500 mb-1.5">
                            <span class="font-medium">Progress</span>
                            <span class="font-bold text-slate-800">42%</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-2 rounded-full bg-amber-500 transition-all duration-500" style="width: 42%"></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-1.5 sm:gap-2 mt-4 pt-3 border-t border-slate-100 text-center">
                        <div>
                            <p class="text-[10px] uppercase font-semibold text-slate-400">CPI</p>
                            <p class="text-xs sm:text-sm font-extrabold text-slate-800">0.88</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-semibold text-slate-400">SPI</p>
                            <p class="text-xs sm:text-sm font-extrabold text-slate-800">0.85</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-semibold text-slate-400">Remaining</p>
                            <p class="text-xs sm:text-sm font-extrabold text-slate-800">120d</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Alerts Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 sm:p-6">
            <h2 class="text-base sm:text-lg font-bold text-slate-900 mb-4">Critical Alerts</h2>
            <div class="space-y-3.5 sm:space-y-4">
                <div class="flex gap-3 items-start border-b border-slate-100 pb-3">
                    <div class="w-2.5 h-2.5 rounded-full bg-rose-500 mt-1 shrink-0"></div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-slate-900">Metro Line Project SPI is 0.72</p>
                        <p class="text-[10px] text-slate-500 mt-0.5">Critical schedule delay in excavation phase.</p>
                    </div>
                </div>
                <div class="flex gap-3 items-start border-b border-slate-100 pb-3">
                    <div class="w-2.5 h-2.5 rounded-full bg-amber-500 mt-1 shrink-0"></div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-slate-900">Industrial Park CPI drops below 0.90</p>
                        <p class="text-[10px] text-slate-500 mt-0.5">Material costs exceeding budgeted threshold.</p>
                    </div>
                </div>
                <div class="flex gap-3 items-start pb-1">
                    <div class="w-2.5 h-2.5 rounded-full bg-blue-500 mt-1 shrink-0"></div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-slate-900">Addendum approval pending for Grand Horizon</p>
                        <p class="text-[10px] text-slate-500 mt-0.5">Contract variance request of Rp 1.5 M needs review.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    const __dashboardProjectData = @json($projectData);
    const __dashboardWeeks = @json($weeks);
    const __dashboardInitialKey = @json($projectKey);

    Alpine.data('sCurveChart', () => ({
        chart: null,
        chartInitialized: false,
        dashboardActivatedHandler: null,
        resizeHandler: null,
        init() {
            this.dashboardActivatedHandler = () => {
                if (this.$el.offsetParent !== null) {
                    this.chartInitialized = true;
                    this.renderChart(this.$wire.projectKey || __dashboardInitialKey);
                }
            };
            this.resizeHandler = () => {
                if (this.chart && this.$el.offsetParent !== null) {
                    this.chart.resize();
                }
            };
            window.addEventListener('dashboard-activated', this.dashboardActivatedHandler);
            window.addEventListener('sidebar-toggled', this.resizeHandler);
            window.addEventListener('resize', this.resizeHandler);
            // Fallback: render if dashboard-activated microtask didn't fire first
            // setTimeout(0) = macrotask, fires AFTER $nextTick microtasks
            setTimeout(() => {
                if (this.$el.offsetParent !== null && !this.chartInitialized) {
                    this.chartInitialized = true;
                    this.renderChart(__dashboardInitialKey);
                }
            }, 0);
            this.$wire.on('project-changed', (event) => {
                setTimeout(() => this.renderChart(event.projectKey), 50);
            });
        },
        destroy() {
            if (this.dashboardActivatedHandler) {
                window.removeEventListener('dashboard-activated', this.dashboardActivatedHandler);
            }
            if (this.resizeHandler) {
                window.removeEventListener('sidebar-toggled', this.resizeHandler);
                window.removeEventListener('resize', this.resizeHandler);
            }
            if (this.chart) {
                this.chart.destroy();
                this.chart = null;
            }
            if (this.$refs.sCurveCanvas) {
                const existing = Chart.getChart(this.$refs.sCurveCanvas);
                if (existing) existing.destroy();
            }
        },
        renderChart(key) {
            if (!this.$refs.sCurveCanvas) return;
            
            const existing = Chart.getChart(this.$refs.sCurveCanvas);
            if (existing) existing.destroy();
            if (this.chart) {
                this.chart.destroy();
                this.chart = null;
            }

            const ctx = this.$refs.sCurveCanvas.getContext('2d');
            const allData = __dashboardProjectData;
            const data = allData[key] || allData[__dashboardInitialKey];
            const weeks = __dashboardWeeks;
            
            const blueGradient = ctx.createLinearGradient(0, 0, 0, 320);
            blueGradient.addColorStop(0, 'rgba(37, 99, 235, 0.25)');
            blueGradient.addColorStop(1, 'rgba(37, 99, 235, 0.00)');

            const greenGradient = ctx.createLinearGradient(0, 0, 0, 320);
            greenGradient.addColorStop(0, 'rgba(16, 185, 129, 0.25)');
            greenGradient.addColorStop(1, 'rgba(16, 185, 129, 0.00)');

            this.chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: weeks,
                    datasets: [
                        {
                            label: 'BCWS (Planned S-Curve)',
                            data: data.bcws,
                            borderColor: '#2563eb',
                            backgroundColor: blueGradient,
                            fill: true,
                            borderWidth: 2.5,
                            tension: 0.4,
                            pointRadius: 3,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#2563eb',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                        },
                        {
                            label: 'BCWP (Earned Value)',
                            data: data.bcwp,
                            borderColor: '#10b981',
                            backgroundColor: greenGradient,
                            fill: true,
                            borderWidth: 2.5,
                            tension: 0.4,
                            pointRadius: 3,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                        },
                        {
                            label: 'ACWP (Actual Cost)',
                            data: data.acwp,
                            borderColor: '#f59e0b',
                            backgroundColor: 'transparent',
                            fill: false,
                            borderWidth: 2,
                            borderDash: [5, 4],
                            tension: 0.4,
                            pointRadius: 3,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#f59e0b',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 500,
                        easing: 'easeOutQuart'
                    },
                    animations: {
                        y: {
                            type: 'number',
                            easing: 'easeOutQuart',
                            duration: 500,
                            from: (ctx) => {
                                if (ctx.type === 'data') {
                                    return ctx.chart.scales.y.getPixelForValue(0);
                                }
                            }
                        }
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { family: 'Plus Jakarta Sans', size: 11, weight: 'bold' },
                            bodyFont: { family: 'Plus Jakarta Sans', size: 11 },
                            padding: 10,
                            cornerRadius: 8,
                            boxPadding: 4,
                            usePointStyle: true,
                            callbacks: {
                                title: (items) => 'Milestone: ' + items[0].label + ' (Cumulative)',
                                label: (item) => {
                                    if (item.raw === null || item.raw === undefined) return item.dataset.label + ': Not Reached Yet';
                                    return ' ' + item.dataset.label + ': Rp ' + Number(item.raw).toFixed(2) + ' Milyar';
                                },
                            },
                        },
                    },
                    scales: {
                        x: {
                            grid: { display: true, color: '#f1f5f9' },
                            ticks: {
                                color: '#64748b',
                                font: { family: 'Plus Jakarta Sans', size: 10, weight: '500' },
                                maxRotation: 0,
                                autoSkip: true,
                                maxTicksLimit: 8,
                            },
                        },
                        y: {
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                color: '#64748b',
                                font: { family: 'Plus Jakarta Sans', size: 10 },
                                callback: (val) => 'Rp ' + val + ' M',
                            },
                        },
                    },
                }
            });
        }
    }));

    Alpine.data('financeChart', () => ({
        chart: null,
        chartInitialized: false,
        dashboardActivatedHandler: null,
        resizeHandler: null,
        init() {
            this.dashboardActivatedHandler = () => {
                if (this.$el.offsetParent !== null) {
                    this.chartInitialized = true;
                    this.renderFinanceChart();
                }
            };
            this.resizeHandler = () => {
                if (this.chart && this.$el.offsetParent !== null) {
                    this.chart.resize();
                }
            };
            window.addEventListener('dashboard-activated', this.dashboardActivatedHandler);
            window.addEventListener('sidebar-toggled', this.resizeHandler);
            window.addEventListener('resize', this.resizeHandler);
            // Fallback: render if dashboard-activated microtask didn't fire first
            setTimeout(() => {
                if (this.$el.offsetParent !== null && !this.chartInitialized) {
                    this.chartInitialized = true;
                    this.renderFinanceChart();
                }
            }, 0);
        },
        destroy() {
            if (this.dashboardActivatedHandler) {
                window.removeEventListener('dashboard-activated', this.dashboardActivatedHandler);
            }
            if (this.resizeHandler) {
                window.removeEventListener('sidebar-toggled', this.resizeHandler);
                window.removeEventListener('resize', this.resizeHandler);
            }
            if (this.chart) {
                this.chart.destroy();
                this.chart = null;
            }
            if (this.$refs.financeCanvas) {
                const existing = Chart.getChart(this.$refs.financeCanvas);
                if (existing) existing.destroy();
            }
        },
        renderFinanceChart() {
            if (!this.$refs.financeCanvas) return;

            const existing = Chart.getChart(this.$refs.financeCanvas);
            if (existing) existing.destroy();
            if (this.chart) {
                this.chart.destroy();
                this.chart = null;
            }

            const ctx = this.$refs.financeCanvas.getContext('2d');
            this.chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Structural', 'Electrical', 'Interior', 'Site Prep'],
                    datasets: [
                        {
                            label: 'Budget Utilization (%)',
                            data: [78, 64, 52, 90],
                            backgroundColor: [
                                'rgba(37, 99, 235, 0.9)',
                                'rgba(59, 130, 246, 0.8)',
                                'rgba(96, 165, 250, 0.8)',
                                'rgba(30, 64, 175, 0.9)',
                            ],
                            borderRadius: 6,
                            maxBarThickness: 18,
                        },
                    ],
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 500,
                        easing: 'easeOutQuart'
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            cornerRadius: 8,
                            padding: 8,
                            titleFont: { family: 'Plus Jakarta Sans', size: 11 },
                            bodyFont: { family: 'Plus Jakarta Sans', size: 11 },
                            callbacks: {
                                label: (ctx) => ' ' + ctx.raw + '% utilized',
                            },
                        },
                    },
                    scales: {
                        x: {
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                color: '#94a3b8',
                                font: { family: 'Plus Jakarta Sans', size: 10 },
                                callback: (v) => v + '%',
                            },
                            max: 100,
                        },
                        y: {
                            grid: { display: false },
                            ticks: {
                                color: '#475569',
                                font: { family: 'Plus Jakarta Sans', size: 10, weight: '600' },
                            },
                        },
                    },
                }
            });
        }
    }));
</script>
@endscript
