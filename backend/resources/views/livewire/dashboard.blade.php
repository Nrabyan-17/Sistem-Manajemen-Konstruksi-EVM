<div>
    <!-- Title & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Executive Dashboard</h1>
            <p class="text-slate-500 text-sm mt-1">
                Portfolio Performance &amp; Real-time Earned Value Management (EVM) Monitoring
            </p>
        </div>
        <div class="flex items-center gap-3">
            <button class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                </svg>
                Import Data
            </button>
        </div>
    </div>

    <!-- Key Metrics Row (6 cards) -->
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 mt-6">
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm hover:shadow-md transition">
            <p class="text-xs font-semibold text-slate-500">Active Projects</p>
            <p class="text-2xl font-extrabold text-slate-900 mt-1">42</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm hover:shadow-md transition">
            <p class="text-xs font-semibold text-slate-500">On Track</p>
            <p class="text-2xl font-extrabold text-slate-900 mt-1">31</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm hover:shadow-md transition">
            <p class="text-xs font-semibold text-slate-500">Delayed</p>
            <p class="text-2xl font-extrabold text-slate-900 mt-1">7</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm hover:shadow-md transition">
            <p class="text-xs font-semibold text-slate-500">Critical</p>
            <p class="text-2xl font-extrabold text-slate-900 mt-1">4</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm hover:shadow-md transition">
            <p class="text-xs font-semibold text-slate-500">Contract Value</p>
            <p class="text-2xl font-extrabold text-slate-900 mt-1">Rp 2.4 T</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm hover:shadow-md transition">
            <p class="text-xs font-semibold text-slate-500">Total P&amp;L</p>
            <p class="text-2xl font-extrabold text-emerald-600 mt-1">+Rp 142 M</p>
        </div>
    </div>

    <!-- Main Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <!-- S-Curve Graph Card -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 sm:p-6 flex flex-col justify-between">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">EVM Performance (S-Curve)</h2>
                    <p class="text-xs text-slate-500">Comparing BCWS, BCWP, and ACWP cumulative progress</p>
                </div>
                <!-- Project Selector Toggle -->
                <div class="flex items-center gap-1 bg-slate-100 rounded-xl p-1">
                    <button wire:click="setProject('portfolio')" class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ $projectKey === 'portfolio' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
                        Portfolio
                    </button>
                    <button wire:click="setProject('horizon')" class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ $projectKey === 'horizon' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
                        Horizon
                    </button>
                    <button wire:click="setProject('metro')" class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ $projectKey === 'metro' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
                        Metro Line
                    </button>
                    <button wire:click="setProject('industrial')" class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ $projectKey === 'industrial' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
                        Industrial
                    </button>
                </div>
            </div>

            <!-- Alpine Chart Wrapper -->
            <div x-data="sCurveChart"
                 x-on:dashboard-activated.window="setTimeout(() => renderChart($wire.projectKey), 150)"
                 class="relative h-[320px]">
                <canvas x-ref="sCurveCanvas"></canvas>
            </div>

            <!-- EVM Stats Footer -->
            <div class="grid grid-cols-3 gap-4 border-t border-slate-100 pt-5 mt-5">
                <div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                        <p class="text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider">Planned Value (BCWS)</p>
                    </div>
                    <p class="text-sm sm:text-base font-extrabold text-slate-900 mt-1">{{ $currentProject['statBcws'] }}</p>
                </div>
                <div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <p class="text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider">Earned Value (BCWP)</p>
                    </div>
                    <p class="text-sm sm:text-base font-extrabold text-slate-900 mt-1">{{ $currentProject['statBcwp'] }}</p>
                </div>
                <div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                        <p class="text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider">Actual Cost (ACWP)</p>
                    </div>
                    <p class="text-sm sm:text-base font-extrabold text-slate-900 mt-1">{{ $currentProject['statAcwp'] }}</p>
                </div>
            </div>
        </div>

        <!-- Budget Utilization Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 sm:p-6 flex flex-col justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Budget Utilization</h2>
                <p class="text-xs text-slate-500">Budget consumed percentage per WBS task area</p>
            </div>

            <!-- Finance Bar Chart -->
            <div x-data="financeChart"
                 x-on:dashboard-activated.window="setTimeout(() => renderFinanceChart(), 150)"
                 class="relative h-[240px] mt-6">
                <canvas x-ref="financeCanvas"></canvas>
            </div>

            <!-- Footer Legend -->
            <div class="border-t border-slate-100 pt-4 mt-4 flex items-center justify-between text-xs text-slate-500">
                <span class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded bg-blue-600"></span> Allocated
                </span>
                <span class="font-bold text-slate-700">Avg Utilization: 71%</span>
            </div>
        </div>
    </div>

    <!-- Project Status & Alerts Row -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mt-6">
        <!-- Projects Grid Overview -->
        <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900">Project Status Overview</h2>
                    <p class="text-xs text-slate-500">Live EVM index per active project milestone</p>
                </div>
                <a href="/projects" class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                    View All Projects →
                </a>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <!-- Card 1 -->
                <div class="border border-slate-200 rounded-xl p-4 hover:border-blue-400 hover:shadow-md transition cursor-pointer group bg-gradient-to-b from-white to-slate-50/50">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-bold text-slate-900 group-hover:text-blue-600 transition">
                            Grand Horizon Tower
                        </p>
                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800">
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
                    <div class="grid grid-cols-3 gap-2 mt-4 pt-3 border-t border-slate-100 text-center">
                        <div>
                            <p class="text-[10px] uppercase font-semibold text-slate-400">CPI</p>
                            <p class="text-sm font-extrabold text-slate-800">1.04</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-semibold text-slate-400">SPI</p>
                            <p class="text-sm font-extrabold text-slate-800">0.98</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-semibold text-slate-400">Remaining</p>
                            <p class="text-sm font-extrabold text-slate-800">45d</p>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="border border-slate-200 rounded-xl p-4 hover:border-amber-400 hover:shadow-md transition cursor-pointer group bg-gradient-to-b from-white to-slate-50/50">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-bold text-slate-900 group-hover:text-amber-600 transition">
                            Industrial Park Phase II
                        </p>
                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-amber-100 text-amber-800">
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
                    <div class="grid grid-cols-3 gap-2 mt-4 pt-3 border-t border-slate-100 text-center">
                        <div>
                            <p class="text-[10px] uppercase font-semibold text-slate-400">CPI</p>
                            <p class="text-sm font-extrabold text-slate-800">0.88</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-semibold text-slate-400">SPI</p>
                            <p class="text-sm font-extrabold text-slate-800">0.85</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-semibold text-slate-400">Remaining</p>
                            <p class="text-sm font-extrabold text-slate-800">120d</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Alerts Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 sm:p-6">
            <h2 class="text-base sm:text-lg font-bold text-slate-900 mb-4">Critical Alerts</h2>
            <div class="space-y-4">
                <div class="flex gap-3 items-start border-b border-slate-100 pb-3">
                    <div class="w-2 h-2 rounded-full bg-rose-500 mt-1.5 shrink-0"></div>
                    <div>
                        <p class="text-xs font-bold text-slate-900">Metro Line Project SPI is 0.72</p>
                        <p class="text-[10px] text-slate-500 mt-0.5">Critical schedule delay in excavation phase.</p>
                    </div>
                </div>
                <div class="flex gap-3 items-start border-b border-slate-100 pb-3">
                    <div class="w-2 h-2 rounded-full bg-amber-500 mt-1.5 shrink-0"></div>
                    <div>
                        <p class="text-xs font-bold text-slate-900">Industrial Park CPI drops below 0.90</p>
                        <p class="text-[10px] text-slate-500 mt-0.5">Material costs exceeding budgeted threshold.</p>
                    </div>
                </div>
                <div class="flex gap-3 items-start pb-3">
                    <div class="w-2 h-2 rounded-full bg-blue-500 mt-1.5 shrink-0"></div>
                    <div>
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
    Alpine.data('sCurveChart', () => ({
        chart: null,
        init() {
            // Use setTimeout to ensure canvas has proper dimensions on initial load
            setTimeout(() => this.renderChart(this.$wire.projectKey), 50);
            this.$wire.on('project-changed', (event) => {
                setTimeout(() => this.renderChart(event.projectKey), 50);
            });
        },
        destroy() {
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
            const data = this.$wire.projectData[key];
            const weeks = this.$wire.weeks;
            
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
                            borderWidth: 3,
                            tension: 0.4,
                            pointRadius: 4,
                            pointHoverRadius: 7,
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
                            borderWidth: 3,
                            tension: 0.4,
                            pointRadius: 4,
                            pointHoverRadius: 7,
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
                            borderWidth: 2.5,
                            borderDash: [6, 4],
                            tension: 0.4,
                            pointRadius: 4,
                            pointHoverRadius: 7,
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
                        duration: 1200,
                        easing: 'easeInOutQuart'
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { family: 'Plus Jakarta Sans', size: 12, weight: 'bold' },
                            bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
                            padding: 12,
                            cornerRadius: 10,
                            boxPadding: 4,
                            usePointStyle: true,
                            callbacks: {
                                title: (items) => 'Milestone: ' + items[0].label + ' (Cumulative Progress)',
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
                                font: { family: 'Plus Jakarta Sans', size: 11, weight: '500' },
                            },
                        },
                        y: {
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                color: '#64748b',
                                font: { family: 'Plus Jakarta Sans', size: 11 },
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
        init() {
            setTimeout(() => this.renderFinanceChart(), 50);
        },
        destroy() {
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
                            maxBarThickness: 20,
                        },
                    ],
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1200,
                        easing: 'easeInOutQuart'
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            cornerRadius: 8,
                            padding: 8,
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
                                font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' },
                            },
                        },
                    },
                },
            });
        }
    }));
</script>
@endscript
