@props(['status'])

@php
    $normalized = strtolower((string)$status);
    $labels = [
        'pending' => 'Menunggu',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak',
        'cancelled' => 'Dibatalkan',
    ];
    $label = $labels[$normalized] ?? ucfirst($status);

    $classes = match ($normalized) {
        'pending' => 'badge-pending',
        'approved' => 'badge-approved',
        'rejected' => 'badge-rejected',
        'cancelled' => 'badge-cancelled',
        default => 'bg-ink-100 text-ink-700 border-ink-200',
    };
@endphp

<span {{ $attributes->merge(['class' => "badge badge-{$normalized} {$classes}"]) }}>
    <span class="w-1.5 h-1.5 rounded-full shrink-0 {{ $normalized === 'approved' ? 'bg-[#065f46]' : ($normalized === 'pending' ? 'bg-[#fbbf24]' : ($normalized === 'rejected' ? 'bg-[#f43f5e]' : 'bg-ink-400')) }}"></span>
    <span>{{ $label }}</span>
</span>
