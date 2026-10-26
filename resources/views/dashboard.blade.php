<x-layouts.app>
    @php
        $hour = (int) now()->format('H');
        $greeting = match(true) {
            $hour >= 4 && $hour < 11 => 'Selamat Pagi',
            $hour >= 11 && $hour < 15 => 'Selamat Siang',
            $hour >= 15 && $hour < 18 => 'Selamat Sore',
            default => 'Selamat Malam',
        };

        // Cari meeting berikutnya (disetujui atau mendatang)
        $nextMeeting = $bookings->where('start_at', '>=', now())
            ->where('status', 'approved')
            ->sortBy('start_at')
            ->first() ?? $bookings->where('start_at', '>=', now())->sortBy('start_at')->first();
    @endphp

    <!-- Header Sapaan Beranda -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-50 text-brand-700 text-xs font-semibold mb-2 border border-brand-200">
                <span class="w-2 h-2 rounded-full bg-brand-600"></span>
                {{ $greeting }}
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-ink-950">
                {{ $greeting }}, {{ auth()->user()->name }}!
            </h1>
            <p class="text-ink-500 text-sm mt-1">
                Berikut ringkasan jadwal dan ketersediaan ruang pertemuan hari ini.
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('availability') }}" class="btn-secondary text-sm">
                <svg class="w-4 h-4 text-ink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Cek Jadwal</span>
            </a>
            <a href="{{ route('bookings.create') }}" class="btn-primary text-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Booking Ruangan</span>
            </a>
        </div>
    </div>

    <!-- Kartu Sorotan "Meeting Berikutnya" -->
    @if($nextMeeting)
        <div class="mt-7 card bg-gradient-to-r from-brand-800 to-brand-700 text-white border-0 shadow-sm relative overflow-hidden p-6 sm:p-7">
            <div class="absolute -right-12 -bottom-12 w-48 h-48 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                <div class="space-y-3">
                    <div class="flex items-center gap-2.5">
                        <span class="px-2.5 py-1 rounded-full bg-white/20 text-white text-xs font-semibold backdrop-blur-xs">
                            Meeting Berikutnya
                        </span>
                        <x-badge :status="$nextMeeting->status" class="bg-white/90 !text-ink-900 border-0" />
                    </div>

                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-white">{{ $nextMeeting->title }}</h2>
                        <p class="text-brand-100 text-sm mt-1 flex flex-wrap items-center gap-x-4 gap-y-1">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-brand-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                <strong>{{ $nextMeeting->room->name }}</strong> ({{ $nextMeeting->room->location }})
                            </span>
                            <span>·</span>
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-brand-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $nextMeeting->start_at->locale('id')->isoFormat('dddd, D MMMM Y · HH:mm') }} - {{ $nextMeeting->end_at->format('H:i') }}
                            </span>
                            <span>·</span>
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-brand-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                {{ $nextMeeting->participant_count }} Peserta
                            </span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('bookings.index') }}" class="inline-flex items-center justify-center gap-2 rounded-[12px] bg-white text-brand-800 hover:bg-brand-50 px-5 py-2.5 text-sm font-semibold transition-colors min-h-[44px]">
                        <span>Lihat Rincian</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- 3-4 Angka Ringkas yang Bisa Diklik -->
    <div class="mt-7 grid gap-4 grid-cols-2 lg:grid-cols-4">
        @php
            $statConfigs = [
                'rooms' => [
                    'label' => 'Ruangan Aktif',
                    'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                    'url' => route('rooms.index'),
                    'accent' => 'text-brand-600 bg-brand-50 border-brand-200',
                ],
                'upcoming' => [
                    'label' => 'Meeting Mendatang',
                    'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                    'url' => route('bookings.index'),
                    'accent' => 'text-brand-600 bg-brand-50 border-brand-200',
                ],
                'today' => [
                    'label' => 'Jadwal Hari Ini',
                    'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                    'url' => route('availability', ['date' => today()->toDateString()]),
                    'accent' => 'text-brand-600 bg-brand-50 border-brand-200',
                ],
                'pending' => [
                    'label' => 'Menunggu Persetujuan',
                    'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
                    'url' => auth()->user()->isAdmin() ? route('admin.bookings.index', ['status' => 'pending']) : route('bookings.index', ['status' => 'pending']),
                    'accent' => 'text-[#92400e] bg-[#fffbeb] border-[#fbbf24]/50',
                ],
                'approved' => [
                    'label' => 'Booking Disetujui',
                    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                    'url' => auth()->user()->isAdmin() ? route('admin.bookings.index', ['status' => 'approved']) : route('bookings.index', ['status' => 'approved']),
                    'accent' => 'text-[#065f46] bg-[#ecfdf5] border-[#a0d6cb]/60',
                ],
            ];
        @endphp

        @foreach($stats as $key => $value)
            @php
                $cfg = $statConfigs[$key] ?? [
                    'label' => ucfirst(str_replace('_', ' ', $key)),
                    'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                    'url' => route('bookings.index'),
                    'accent' => 'text-ink-700 bg-ink-100 border-ink-200',
                ];
            @endphp
            <a href="{{ $cfg['url'] }}" class="card p-5 group hover:border-brand-600 transition-all hover:translate-y-[-2px] flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-ink-500">{{ $cfg['label'] }}</span>
                    <span class="w-8 h-8 rounded-[8px] flex items-center justify-center shrink-0 border {{ $cfg['accent'] }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $cfg['icon'] }}" />
                        </svg>
                    </span>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <p class="text-3xl font-extrabold text-ink-950 tracking-tight">{{ $value }}</p>
                    <span class="text-xs text-brand-600 font-semibold group-hover:underline flex items-center gap-0.5">
                        Lihat
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </span>
                </div>
            </a>
        @endforeach
    </div>

    <!-- Booking Terbaru -->
    <div class="card mt-7 p-0 overflow-hidden">
        <div class="p-5 md:p-6 border-b border-ink-100 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-ink-950">Booking Terbaru</h2>
                <p class="text-xs text-ink-500 mt-0.5">Aktivitas pemesanan ruang meeting yang baru diajukan.</p>
            </div>
            <a href="{{ route('bookings.index') }}" class="text-sm font-semibold text-brand-600 hover:text-brand-700 flex items-center gap-1">
                <span>Semua Booking</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <!-- Tabel di Desktop, Kartu di Mobile -->
        <div class="hidden md:block overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Ruangan</th>
                        <th>Judul Meeting</th>
                        <th>Waktu & Tanggal</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @forelse($bookings as $b)
                        <tr class="hover:bg-ink-50/50 transition-colors">
                            <td>
                                <div class="font-semibold text-ink-950">{{ $b->room->name }}</div>
                                <div class="text-xs text-ink-500">{{ $b->room->location }}</div>
                            </td>
                            <td>
                                <div class="font-medium text-ink-900">{{ $b->title }}</div>
                                <div class="text-xs text-ink-400">{{ $b->participant_count }} orang</div>
                            </td>
                            <td>
                                <div class="font-medium text-ink-950">{{ $b->start_at->locale('id')->isoFormat('D MMM Y') }}</div>
                                <div class="text-xs text-ink-500">{{ $b->start_at->format('H:i') }} - {{ $b->end_at->format('H:i') }} WIB</div>
                            </td>
                            <td>
                                <x-badge :status="$b->status" />
                            </td>
                            <td class="text-right">
                                <a href="{{ route('bookings.index') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-ink-500">
                                <svg class="w-10 h-10 mx-auto text-ink-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p class="font-medium">Belum ada booking tercatat.</p>
                                <p class="text-xs text-ink-400 mt-1">Mulai ajukan pemesanan ruang meeting pertama Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Tampilan Kartu Bertumpuk di Mobile (< 768px) -->
        <div class="md:hidden divide-y divide-ink-100">
            @forelse($bookings as $b)
                <div class="p-4 space-y-2">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <span class="text-xs font-medium text-brand-700 bg-brand-50 px-2 py-0.5 rounded-md">{{ $b->room->name }}</span>
                            <h3 class="font-bold text-ink-950 mt-1">{{ $b->title }}</h3>
                        </div>
                        <x-badge :status="$b->status" />
                    </div>
                    <div class="flex items-center justify-between text-xs text-ink-500 pt-1">
                        <span>{{ $b->start_at->locale('id')->isoFormat('D MMM Y, HH:mm') }} - {{ $b->end_at->format('H:i') }}</span>
                        <span>{{ $b->participant_count }} orang</span>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-ink-500 text-sm">
                    Belum ada booking tercatat.
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.app>
