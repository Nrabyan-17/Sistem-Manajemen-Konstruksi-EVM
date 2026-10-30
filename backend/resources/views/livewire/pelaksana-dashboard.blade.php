<section class="space-y-4 sm:space-y-6" aria-labelledby="pelaksana-dashboard-title">
    <div><h1 id="pelaksana-dashboard-title" class="text-xl font-extrabold tracking-tight text-slate-900 sm:text-2xl lg:text-3xl">Dashboard Pelaksana</h1><p class="mt-0.5 text-xs text-slate-500 sm:mt-1 sm:text-sm">Pantau tugas dan tindak lanjut laporan mingguan Anda.</p></div>
    <div class="grid grid-cols-2 gap-3 sm:gap-4 xl:grid-cols-4">
        @foreach([['Proyek Saya', count($projects), 'text-blue-600'], ['Draft', $drafts, 'text-slate-900'], ['Menunggu Persetujuan', $pending, 'text-amber-700'], ['Perlu Revisi', $rejected, 'text-rose-700']] as [$label, $value, $tone])
            <div class="rounded-2xl border border-slate-200/80 bg-white p-3.5 shadow-sm transition hover:shadow-md sm:p-4"><p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500 sm:text-xs">{{ $label }}</p><p class="mt-0.5 text-xl font-extrabold sm:mt-1 sm:text-2xl {{ $tone }}">{{ $value }}</p></div>
        @endforeach
    </div>
    <div class="grid items-start gap-4 sm:gap-6 xl:grid-cols-2">
        <section class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6"><h2 class="text-base font-bold text-slate-900 sm:text-lg">Tindak lanjut laporan</h2><p class="mt-1 text-[11px] text-slate-500 sm:text-xs">Periksa catatan Admin Proyek sebelum mengajukan ulang.</p>
            <div class="mt-4 space-y-3">
                @forelse(array_filter($reports, fn($report) => $report['status'] === 'REJECTED') as $report)
                    <article class="rounded-xl border border-rose-200 bg-rose-50 p-4"><x-pelaksana-status status="REJECTED" /><h3 class="mt-3 text-sm font-bold">{{ $report['project_name'] }} · Minggu {{ $report['week'] }}</h3><p class="mt-2 text-sm text-rose-800">{{ $report['reason'] }}</p></article>
                @empty
                    <p class="rounded-xl bg-slate-50 p-5 text-sm text-slate-500">Tidak ada laporan yang perlu direvisi.</p>
                @endforelse
            </div>
        </section>
        <section class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6"><h2 class="text-base font-bold text-slate-900 sm:text-lg">Progres proyek</h2><p class="mt-1 text-[11px] text-slate-500 sm:text-xs">Realisasi approved terakhir dari sumber data yang diberikan.</p>
            <div class="mt-5 space-y-5">
                @forelse($projects as $project)
                    <div><div class="flex justify-between gap-3 text-sm"><span class="font-bold">{{ $project['name'] }}</span><span class="shrink-0 font-bold text-blue-600">{{ number_format($project['progress'], 1, ',', '.') }}%</span></div><div role="progressbar" aria-label="Progres {{ $project['name'] }}" aria-valuenow="{{ $project['progress'] }}" aria-valuemin="0" aria-valuemax="100" class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full bg-blue-600" style="width: {{ max(0,min(100,$project['progress'])) }}%"></div></div></div>
                @empty
                    <p class="rounded-xl bg-slate-50 p-5 text-sm text-slate-500">Belum ada proyek ditampilkan. Data penugasan belum terhubung.</p>
                @endforelse
            </div>
        </section>
    </div>
</section>
