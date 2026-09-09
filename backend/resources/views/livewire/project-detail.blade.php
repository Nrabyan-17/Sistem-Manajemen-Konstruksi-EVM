<div x-data="{ tab: 'overview' }">

    {{-- BACK LINK --}}
    <a href="{{ route('projects') }}" wire:navigate
       class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-blue-600 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Projects
    </a>

    {{-- PROJECT HEADER --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 lg:p-6 mt-4">
        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4">
            <div class="min-w-0">
                <div class="flex items-center gap-3 flex-wrap">
                    <span class="text-xs font-bold text-blue-600">{{ $project['project_id'] }}</span>
                    <span class="text-[10px] font-extrabold tracking-wider px-2 py-0.5 rounded-full
                        {{ $project['status'] === 'ON TRACK' ? 'bg-emerald-50 text-emerald-700' : '' }}
                        {{ $project['status'] === 'AT RISK' ? 'bg-amber-50 text-amber-700' : '' }}
                        {{ $project['status'] === 'CRITICAL' ? 'bg-rose-50 text-rose-700' : '' }}
                        {{ $project['status'] === 'COMPLETED' ? 'bg-blue-50 text-blue-700' : '' }}">
                        {{ $project['status'] }}
                    </span>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 mt-1">{{ $project['project_name'] }}</h1>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-2 text-sm text-slate-500">
                    <span>{{ $project['location'] }}</span>
                    <span>Manager: {{ $project['project_manager'] }}</span>
                    <span>Client: {{ $project['client'] }}</span>
                    <span>SPK: {{ $project['spk_number'] }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- TABS --}}
    <div class="flex items-center gap-1 bg-slate-100 rounded-xl p-1 w-fit flex-wrap mt-6">
        <button @click="tab = 'overview'" :class="tab === 'overview' ? 'bg-blue-600 text-white' : 'text-slate-500'" class="px-4 py-2 rounded-lg text-sm font-semibold transition">Overview</button>
        <button @click="tab = 'evm'" :class="tab === 'evm' ? 'bg-blue-600 text-white' : 'text-slate-500'" class="px-4 py-2 rounded-lg text-sm font-semibold transition">EVM &amp; S-Curve</button>
        <button @click="tab = 'financial'" :class="tab === 'financial' ? 'bg-blue-600 text-white' : 'text-slate-500'" class="px-4 py-2 rounded-lg text-sm font-semibold transition">Financial</button>
        <button @click="tab = 'addendum'" :class="tab === 'addendum' ? 'bg-blue-600 text-white' : 'text-slate-500'" class="px-4 py-2 rounded-lg text-sm font-semibold transition">Addendum</button>
        <button @click="tab = 'activity'" :class="tab === 'activity' ? 'bg-blue-600 text-white' : 'text-slate-500'" class="px-4 py-2 rounded-lg text-sm font-semibold transition">Activity</button>
    </div>

    {{-- TAB: OVERVIEW --}}
    <div x-show="tab === 'overview'" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 mt-4">
        <h2 class="text-base font-bold text-slate-900 mb-4">Project Information</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 text-sm">
            <div><p class="text-xs text-slate-400">Project ID</p><p class="font-semibold text-slate-800 mt-1">{{ $project['project_id'] }}</p></div>
            <div><p class="text-xs text-slate-400">SPK Number</p><p class="font-semibold text-slate-800 mt-1">{{ $project['spk_number'] }}</p></div>
            <div><p class="text-xs text-slate-400">Client</p><p class="font-semibold text-slate-800 mt-1">{{ $project['client'] }}</p></div>
            <div><p class="text-xs text-slate-400">Project Manager</p><p class="font-semibold text-slate-800 mt-1">{{ $project['project_manager'] }}</p></div>
            <div><p class="text-xs text-slate-400">Location</p><p class="font-semibold text-slate-800 mt-1">{{ $project['location'] }}</p></div>
            <div><p class="text-xs text-slate-400">Contract Value</p><p class="font-semibold text-slate-800 mt-1" title="{{ \App\Support\CurrencyHelper::formatFull($project['contract_value']) }}">{{ \App\Support\CurrencyHelper::format($project['contract_value']) }}</p></div>
            <div><p class="text-xs text-slate-400">Start Date</p><p class="font-semibold text-slate-800 mt-1">{{ date('d M Y', strtotime($project['start_date'])) }}</p></div>
            <div><p class="text-xs text-slate-400">BAST Date</p><p class="font-semibold text-slate-800 mt-1">{{ date('d M Y', strtotime($project['bast_date'])) }}</p></div>
            <div><p class="text-xs text-slate-400">Status</p><p class="font-semibold text-slate-800 mt-1">{{ $project['status'] }}</p></div>
        </div>
    </div>

    {{-- TAB: EVM & S-CURVE --}}
    <div x-show="tab === 'evm'" class="space-y-6 mt-4">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <h2 class="text-base font-bold text-slate-900">EVM S-Curve Analysis</h2>
            <p class="text-xs text-slate-500 mt-0.5">Budgeted Cost vs Earned Value vs Actual Cost</p>

            <div class="grid grid-cols-3 gap-3 mt-4">
                <div class="rounded-xl bg-blue-50 p-3">
                    <p class="text-[11px] font-semibold text-blue-700">BCWS (Planned)</p>
                    <p class="text-lg font-extrabold text-blue-800 mt-0.5" title="{{ \App\Support\CurrencyHelper::formatFull($bcws) }}">{{ \App\Support\CurrencyHelper::format($bcws) }}</p>
                </div>
                <div class="rounded-xl bg-emerald-50 p-3">
                    <p class="text-[11px] font-semibold text-emerald-700">BCWP (Earned)</p>
                    <p class="text-lg font-extrabold text-emerald-800 mt-0.5" title="{{ \App\Support\CurrencyHelper::formatFull($bcwp) }}">{{ \App\Support\CurrencyHelper::format($bcwp) }}</p>
                </div>
                <div class="rounded-xl bg-orange-50 p-3">
                    <p class="text-[11px] font-semibold text-orange-700">ACWP (Actual)</p>
                    <p class="text-lg font-extrabold text-orange-800 mt-0.5" title="{{ \App\Support\CurrencyHelper::formatFull($acwp) }}">{{ \App\Support\CurrencyHelper::format($acwp) }}</p>
                </div>
            </div>

            <div class="relative w-full mt-4" style="height:320px;">
                <canvas id="sCurveChart"></canvas>
            </div>
        </div>

        <div class="grid sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4">
                <p class="text-xs text-slate-500">CV (Cost Variance)</p>
                <p class="text-lg font-extrabold mt-1 {{ $cv >= 0 ? 'text-emerald-600' : 'text-rose-600' }}" title="{{ \App\Support\CurrencyHelper::formatFull($cv, true) }}">{{ \App\Support\CurrencyHelper::formatDiff($cv) }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4">
                <p class="text-xs text-slate-500">SV (Schedule Variance)</p>
                <p class="text-lg font-extrabold mt-1 {{ $sv >= 0 ? 'text-emerald-600' : 'text-rose-600' }}" title="{{ \App\Support\CurrencyHelper::formatFull($sv, true) }}">{{ \App\Support\CurrencyHelper::formatDiff($sv) }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4">
                <p class="text-xs text-slate-500">VAC (Variance at Completion)</p>
                <p class="text-lg font-extrabold mt-1 {{ $vac >= 0 ? 'text-emerald-600' : 'text-rose-600' }}" title="{{ \App\Support\CurrencyHelper::formatFull($vac, true) }}">{{ \App\Support\CurrencyHelper::formatDiff($vac) }}</p>
            </div>
        </div>
    </div>

    {{-- TAB: FINANCIAL --}}
    <div x-show="tab === 'financial'" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 mt-4">
        <h2 class="text-base font-bold text-slate-900 mb-4">Financial Summary</h2>
        @php $util = min(100, round(($acwp / $bac) * 100)); @endphp
        <div class="mb-4">
            <div class="flex items-center justify-between text-xs text-slate-500 mb-1">
                <span>Budget Utilization (ACWP / Contract Value)</span><span class="font-semibold text-slate-700">{{ $util }}%</span>
            </div>
            <div class="w-full h-2 rounded-full bg-slate-100">
                <div class="h-2 rounded-full bg-blue-600" style="width: {{ $util }}%"></div>
            </div>
        </div>
        <div class="grid sm:grid-cols-3 gap-3">
            <div class="rounded-xl bg-slate-50 p-3">
                <p class="text-[11px] text-slate-500">Contract Value</p>
                <p class="text-base font-extrabold text-slate-900 mt-0.5" title="{{ \App\Support\CurrencyHelper::formatFull($bac) }}">{{ \App\Support\CurrencyHelper::format($bac) }}</p>
            </div>
            <div class="rounded-xl bg-slate-50 p-3">
                <p class="text-[11px] text-slate-500">Actual Cost (ACWP)</p>
                <p class="text-base font-extrabold text-slate-900 mt-0.5" title="{{ \App\Support\CurrencyHelper::formatFull($acwp) }}">{{ \App\Support\CurrencyHelper::format($acwp) }}</p>
            </div>
            <div class="rounded-xl bg-slate-50 p-3">
                <p class="text-[11px] text-slate-500">Modal Berjalan</p>
                @php $modal = $bcwp - $acwp; @endphp
                <p class="text-base font-extrabold mt-0.5 {{ $modal >= 0 ? 'text-emerald-600' : 'text-rose-600' }}" title="{{ \App\Support\CurrencyHelper::formatFull($modal, true) }}">{{ \App\Support\CurrencyHelper::formatDiff($modal) }}</p>
            </div>
        </div>
    </div>

    {{-- TAB: ADDENDUM --}}
    <div x-show="tab === 'addendum'" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden mt-4">
        <div class="px-5 py-4 border-b border-slate-100">
            <h2 class="text-base font-bold text-slate-900">Contract Addendums</h2>
            <p class="text-xs text-slate-500 mt-0.5">List of contract addendums recorded for this project</p>
        </div>
        @if(count($addendums) === 0)
            <div class="py-10 text-center">
                <p class="text-xs text-slate-400">Belum ada data addendum untuk project ini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 uppercase font-bold border-b border-slate-100">
                            <th class="p-4">Addendum ID</th>
                            <th class="p-4">Title</th>
                            <th class="p-4">Value Variance</th>
                            <th class="p-4">Submission Date</th>
                            <th class="p-4">Description</th>
                            <th class="p-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($addendums as $a)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-4 font-bold text-slate-900">{{ $a['addendum_id'] }}</td>
                                <td class="p-4 text-slate-700 font-semibold">{{ $a['title'] }}</td>
                                <td class="p-4 font-extrabold {{ $a['value'] >= 0 ? 'text-slate-900' : 'text-emerald-600' }}" title="{{ \App\Support\CurrencyHelper::formatFull($a['value'], true) }}">
                                    {{ \App\Support\CurrencyHelper::formatDiff($a['value']) }}
                                </td>
                                <td class="p-4 text-slate-500">{{ date('d M Y', strtotime($a['date'])) }}</td>
                                <td class="p-4 text-slate-500 max-w-xs">{{ $a['description'] }}</td>
                                <td class="p-4">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full
                                        {{ $a['status'] === 'APPROVED' ? 'bg-emerald-50 text-emerald-700' : '' }}
                                        {{ $a['status'] === 'PENDING' ? 'bg-amber-50 text-amber-700' : '' }}
                                        {{ $a['status'] === 'REJECTED' ? 'bg-rose-50 text-rose-700' : '' }}
                                    ">
                                        {{ $a['status'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- TAB: ACTIVITY --}}
    <div x-show="tab === 'activity'" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 mt-4">
        <h2 class="text-base font-bold text-slate-900 mb-4">Project Activity / History</h2>
        @if(count($history) === 0)
            <p class="text-sm text-slate-500">No history recorded yet.</p>
        @else
            <ol class="relative border-l-2 border-slate-100 space-y-6 pl-6">
                @foreach($history as $h)
                    <li class="relative">
                        <span class="absolute -left-[27px] top-1 w-3 h-3 rounded-full bg-blue-600 ring-4 ring-white"></span>
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-sm font-semibold text-slate-900">{{ $h['action'] }}</p>
                            <p class="text-xs text-slate-400 shrink-0">{{ date('d M Y', strtotime($h['date'])) }}</p>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">by {{ $h['user'] }}</p>
                        <div class="mt-2 flex items-center gap-2 text-xs flex-wrap">
                            <span class="px-2 py-1 rounded bg-rose-50 text-rose-600 line-through">{{ $h['old'] }}</span>
                            <span class="px-2 py-1 rounded bg-emerald-50 text-emerald-700 font-semibold">{{ $h['new'] }}</span>
                        </div>
                    </li>
                @endforeach
            </ol>
        @endif
    </div>

</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<script>
document.addEventListener('livewire:navigated', initSCurveChart);
document.addEventListener('DOMContentLoaded', initSCurveChart);

function initSCurveChart() {
    const canvas = document.getElementById('sCurveChart');
    if (!canvas || canvas.dataset.rendered) return;
    canvas.dataset.rendered = '1';

    new Chart(canvas, {
        type: 'line',
        data: {
            labels: @json($sCurveWeeks),
            datasets: [
                { label: 'BCWS (Planned)', data: @json($sCurveBcws), borderColor: '#2563EB', backgroundColor: 'rgba(37,99,235,0.08)', cubicInterpolationMode: 'monotone', tension: 0.4, fill: true, pointRadius: 3, borderWidth: 2 },
                { label: 'BCWP (Earned)', data: @json($sCurveBcwp), borderColor: '#16A34A', backgroundColor: 'rgba(22,163,74,0.08)', cubicInterpolationMode: 'monotone', tension: 0.4, fill: true, pointRadius: 3, borderWidth: 2, spanGaps: false },
                { label: 'ACWP (Actual)', data: @json($sCurveAcwp), borderColor: '#F97316', backgroundColor: 'rgba(249,115,22,0.08)', cubicInterpolationMode: 'monotone', tension: 0.4, fill: true, pointRadius: 3, borderWidth: 2, borderDash: [5,3], spanGaps: false }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } },
                tooltip: { 
                    backgroundColor: '#0f172a', 
                    padding: 10,
                    callbacks: {
                        label: (ctx) => ctx.dataset.label + ': Rp ' + ctx.parsed.y.toLocaleString('id-ID') + ' Miliar'
                    }
                }
            },
            scales: {
                x: { grid: { display: false } },
                y: { grid: { color: '#f1f5f9' }, ticks: { callback: (v) => 'Rp ' + v.toLocaleString('id-ID') + ' Miliar' } }
            }
        }
    });
}
</script>
@endpush
