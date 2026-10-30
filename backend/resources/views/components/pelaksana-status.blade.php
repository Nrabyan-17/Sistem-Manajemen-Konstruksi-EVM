@props(['status' => ''])
@php
    $label = \App\Support\PelaksanaUiData::STATUSES[$status] ?? 'Status belum tersedia';
    $tone = match ($status) {
        'APPROVED' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'REJECTED' => 'bg-rose-50 text-rose-700 border-rose-200',
        'PENDING_APPROVAL' => 'bg-amber-50 text-amber-700 border-amber-200',
        default => 'bg-slate-100 text-slate-600 border-slate-200',
    };
@endphp
<span {{ $attributes->class(['inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-bold', $tone]) }}>
    <span aria-hidden="true">{{ $status === 'APPROVED' ? '✓' : ($status === 'REJECTED' ? '!' : '•') }}</span>{{ $label }}
</span>
