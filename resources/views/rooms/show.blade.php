<x-layouts.app>
    <div class="max-w-4xl">
        <a href="{{ route('rooms.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-ink-600 hover:text-brand-600 transition-colors mb-6 group">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Daftar Ruangan</span>
        </a>

        <div class="card p-0 overflow-hidden">
            <!-- Header Cover Ruangan -->
            <div class="relative h-56 sm:h-72 bg-gradient-to-br from-brand-800 via-brand-700 to-ink-950 text-white p-6 sm:p-8 flex flex-col justify-between overflow-hidden">
                @if($room->image_path)
                    <img src="{{ asset('storage/' . $room->image_path) }}" alt="{{ $room->name }}" class="absolute inset-0 w-full h-full object-cover opacity-30">
                    <div class="absolute inset-0 bg-gradient-to-t from-ink-950/90 via-brand-900/40 to-transparent"></div>
                @else
                    <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full bg-white/10 blur-2xl"></div>
                @endif

                <div class="relative z-10 flex items-start justify-between">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 backdrop-blur-xs text-xs font-bold tracking-wider text-white border border-white/20">
                        {{ $room->code }}
                    </span>
                    @if(!$room->is_active)
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-500 text-white">
                            Nonaktif
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-brand-500 text-white">
                            Tersedia
                        </span>
                    @endif
                </div>

                <div class="relative z-10">
                    <h1 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight">{{ $room->name }}</h1>
                    <p class="text-sm text-brand-100 mt-2 flex flex-wrap items-center gap-x-4 gap-y-1">
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-brand-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $room->location }}
                        </span>
                        <span>·</span>
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-brand-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Kapasitas: {{ $room->capacity }} orang
                        </span>
                        <span>·</span>
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-brand-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Operasional: {{ $room->opens_at ?: '08:00' }} - {{ $room->closes_at ?: '18:00' }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- Konten Spesifikasi Lengkap -->
            <div class="p-6 sm:p-8 space-y-6">
                <!-- Deskripsi -->
                <div>
                    <h2 class="text-sm font-bold uppercase tracking-wider text-ink-500 mb-2">Deskripsi Ruangan</h2>
                    <p class="text-ink-700 leading-relaxed whitespace-pre-line text-sm sm:text-base">
                        {{ $room->description ?: 'Ruangan ini dirancang untuk mendukung aktivitas rapat kerja, koordinasi proyek, dan kolaborasi tim yang nyaman.' }}
                    </p>
                </div>

                <!-- Fasilitas Ruangan -->
                <div class="pt-4 border-t border-ink-100">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-ink-500 mb-3">Fasilitas Tersedia</h2>
                    <div class="flex flex-wrap gap-2">
                        @forelse($room->facilities as $f)
                            <span class="facility-chip text-sm py-1.5 px-3.5">
                                <svg class="w-4 h-4 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <span>{{ $f->name }}</span>
                            </span>
                        @empty
                            <p class="text-sm text-ink-400">Belum ada daftar fasilitas khusus untuk ruangan ini.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Titik Kursi Visual -->
                <div class="pt-4 border-t border-ink-100">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-ink-500 mb-2">Kapasitas Kursi ({{ $room->capacity }} orang)</h2>
                    <div class="flex flex-wrap gap-2 p-4 rounded-[12px] bg-ink-50 border border-ink-200">
                        @for($i = 1; $i <= $room->capacity; $i++)
                            <span class="w-8 h-8 rounded-full bg-brand-100 border border-brand-200 text-brand-800 text-xs font-bold flex items-center justify-center shadow-2xs" title="Kursi #{{ $i }}">
                                {{ $i }}
                            </span>
                        @endfor
                    </div>
                </div>

                <!-- Aksi Bawah -->
                <div class="pt-6 border-t border-ink-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-xs text-ink-500">
                        Pastikan waktu mulai dan selesai berada dalam jam operasional {{ $room->opens_at ?: '08:00' }} - {{ $room->closes_at ?: '18:00' }}.
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.rooms.edit', $room) }}" class="btn-secondary text-sm w-full sm:w-auto">
                                Ubah Ruangan
                            </a>
                        @endif
                        @if($room->is_active)
                            <a href="{{ route('bookings.create', ['room' => $room->id]) }}" class="btn-primary text-sm w-full sm:w-auto">
                                <span>Booking Ruangan Ini</span>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
