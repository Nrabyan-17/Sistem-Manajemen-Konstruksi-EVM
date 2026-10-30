<section class="space-y-5" aria-labelledby="pelaksana-projects-title">
    <div><h1 id="pelaksana-projects-title" class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">Proyek Saya</h1><p class="mt-2 text-sm text-slate-500">Daftar proyek dari penugasan Pelaksana Lapangan.</p></div>
    <div><label for="pelaksana-project-search" class="mb-2 block text-sm font-bold">Cari proyek</label><input id="pelaksana-project-search" type="search" wire:model.live.debounce.300ms="search" placeholder="Nama, kode proyek, atau lokasi" class="min-h-11 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100 sm:max-w-lg"></div>
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @forelse($filteredProjects as $project)
            <article wire:key="project-{{ $project['id'] }}" class="flex flex-col rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-bold tracking-wider text-blue-600">{{ $project['code'] }}</p><h2 class="mt-3 text-lg font-extrabold text-slate-900">{{ $project['name'] }}</h2><p class="mt-2 text-sm text-slate-500">{{ $project['location'] }}</p>
                <div class="mt-6 border-t border-slate-100 pt-4"><div class="flex justify-between text-sm"><span class="text-slate-500">Progres disetujui</span><strong>{{ number_format($project['progress'], 1, ',', '.') }}%</strong></div><div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full bg-blue-600" style="width: {{ max(0,min(100,$project['progress'])) }}%"></div></div></div>
                <p class="mt-5 rounded-xl bg-blue-50 p-3 text-xs leading-relaxed text-blue-800">Gunakan tab Input Progres untuk meninjau form mingguan. Akses operasional menunggu integrasi penugasan.</p>
            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center md:col-span-2"><h2 class="font-bold text-slate-900">{{ count($projects) ? 'Proyek tidak ditemukan' : 'Belum ada proyek ditampilkan' }}</h2><p class="mt-2 text-sm text-slate-500">{{ count($projects) ? 'Coba nama atau lokasi lainnya.' : 'Data penugasan belum terhubung. Hubungi Admin Proyek setelah integrasi tersedia.' }}</p></div>
        @endforelse
    </div>
</section>
