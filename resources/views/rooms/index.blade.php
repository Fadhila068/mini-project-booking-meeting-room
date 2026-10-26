<x-layouts.app>
    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-ink-950">Daftar Ruang Meeting</h1>
            <p class="text-sm text-ink-500 mt-1">Pilih ruang yang representatif dan sesuai kapasitas tim Anda.</p>
        </div>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.rooms.create') }}" class="btn-primary text-sm self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                <span>Tambah Ruangan</span>
            </a>
        @endif
    </div>

    <!-- Filter Toolbar -->
    <form method="get" action="{{ route('rooms.index') }}" class="card mt-6 p-4 sm:p-5">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 items-end">
            <div>
                <label for="search" class="block text-xs font-semibold text-ink-700 uppercase tracking-wider mb-1.5">Nama Ruangan</label>
                <div class="relative">
                    <input id="search" class="field pl-10" name="search" placeholder="Cari nama ruangan..." value="{{ request('search') }}">
                    <svg class="w-5 h-5 text-ink-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <div>
                <label for="location" class="block text-xs font-semibold text-ink-700 uppercase tracking-wider mb-1.5">Lokasi / Lantai</label>
                <div class="relative">
                    <input id="location" class="field pl-10" name="location" placeholder="Contoh: Lantai 1..." value="{{ request('location') }}">
                    <svg class="w-5 h-5 text-ink-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>

            <div>
                <label for="capacity" class="block text-xs font-semibold text-ink-700 uppercase tracking-wider mb-1.5">Kapasitas Minimal</label>
                <select id="capacity" class="field" name="capacity">
                    <option value="">Semua kapasitas</option>
                    <option value="5" @selected(request('capacity') == '5')>Min. 5 orang</option>
                    <option value="10" @selected(request('capacity') == '10')>Min. 10 orang</option>
                    <option value="15" @selected(request('capacity') == '15')>Min. 15 orang</option>
                    <option value="20" @selected(request('capacity') == '20')>Min. 20 orang</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="btn-primary w-full">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>Cari</span>
                </button>
                @if(request()->anyFilled(['search', 'location', 'capacity']))
                    <a href="{{ route('rooms.index') }}" class="btn-secondary px-3 shrink-0" title="Reset filter">
                        <svg class="w-4 h-4 text-ink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </div>
        </div>
    </form>

    <!-- Grid Kartu Ruangan -->
    <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        @forelse($rooms as $room)
            <article class="card p-0 overflow-hidden flex flex-col justify-between group hover:border-brand-600 transition-all duration-200">
                <!-- Sampul Kartu: Hijau dengan Kode Ruangan & Titik Kursi Kapasitas -->
                <div class="relative h-44 bg-gradient-to-br from-brand-700 via-brand-600 to-brand-800 text-white p-5 flex flex-col justify-between overflow-hidden">
                    @if($room->image_path)
                        <img src="{{ asset('storage/' . $room->image_path) }}" alt="{{ $room->name }}" class="absolute inset-0 w-full h-full object-cover opacity-35 group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-brand-900/90 via-brand-800/40 to-transparent"></div>
                    @else
                        <!-- Pola Latar Geometris -->
                        <div class="absolute -right-8 -top-8 w-32 h-32 rounded-full bg-white/10 blur-xl"></div>
                    @endif

                    <div class="relative z-10 flex items-start justify-between">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 backdrop-blur-xs text-xs font-bold tracking-wider text-white border border-white/20">
                            {{ $room->code }}
                        </span>
                        @if(!$room->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-500/80 text-white">
                                Nonaktif
                            </span>
                        @endif
                    </div>

                    <!-- Titik Kursi Sesuai Kapasitas -->
                    <div class="relative z-10">
                        <div class="flex items-center justify-between text-xs text-brand-100 font-medium mb-1.5">
                            <span>Kapasitas: {{ $room->capacity }} Kursi</span>
                            <span>{{ $room->location }}</span>
                        </div>
                        <div class="flex flex-wrap items-center gap-1.5 max-h-8 overflow-hidden" title="Kapasitas {{ $room->capacity }} orang">
                            @php
                                $dotCount = min($room->capacity, 24);
                            @endphp
                            @for($i = 0; $i < $dotCount; $i++)
                                <span class="w-2.5 h-2.5 rounded-full bg-brand-200/90 shadow-2xs"></span>
                            @endfor
                            @if($room->capacity > 24)
                                <span class="text-2xs font-bold text-brand-200">+{{ $room->capacity - 24 }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Konten Rincian Ruangan -->
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-start justify-between gap-2">
                            <h2 class="text-lg font-bold text-ink-950 group-hover:text-brand-600 transition-colors">
                                {{ $room->name }}
                            </h2>
                            <span class="text-xs font-semibold text-ink-500 shrink-0 bg-ink-100 px-2 py-0.5 rounded-md">
                                {{ $room->capacity }} orang
                            </span>
                        </div>
                        <p class="text-xs text-ink-500 mt-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-ink-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Jam Operasional: {{ $room->opens_at ?: '08:00' }} - {{ $room->closes_at ?: '18:00' }}
                        </p>

                        <!-- Chip Fasilitas -->
                        <div class="mt-4 flex flex-wrap gap-1.5">
                            @forelse($room->facilities as $f)
                                <span class="facility-chip">
                                    {{ $f->name }}
                                </span>
                            @empty
                                <span class="text-xs text-ink-400">Standar meeting room</span>
                            @endforelse
                        </div>
                    </div>

                    <!-- Tombol Aksi di Bawah Kartu -->
                    <div class="mt-6 pt-4 border-t border-ink-100 flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('rooms.show', $room) }}" class="text-xs font-semibold text-ink-700 hover:text-brand-600 py-2 px-3 rounded-lg hover:bg-ink-100 transition-colors">
                                Detail
                            </a>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.rooms.edit', $room) }}" class="text-xs font-semibold text-amber-700 hover:text-amber-800 py-2 px-3 rounded-lg hover:bg-amber-50 transition-colors">
                                    Ubah
                                </a>
                            @endif
                        </div>

                        @if($room->is_active)
                            <a href="{{ route('bookings.create', ['room' => $room->id]) }}" class="btn-primary text-xs py-2 px-3.5">
                                <span>Booking</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @else
                            <span class="text-xs font-medium text-ink-400 py-2 px-3">Nonaktif</span>
                        @endif
                    </div>
                </div>
            </article>
        @empty
            <div class="card md:col-span-2 xl:col-span-3 text-center py-16">
                <svg class="w-12 h-12 mx-auto text-ink-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <h3 class="text-base font-bold text-ink-950">Tidak ada ruangan ditemukan</h3>
                <p class="text-sm text-ink-500 mt-1">Coba sesuaikan kata kunci pencarian atau kapasitas minimum yang Anda pilih.</p>
                <a href="{{ route('rooms.index') }}" class="btn-secondary text-xs mt-4 inline-flex">
                    Reset Filter Pencarian
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $rooms->links() }}
    </div>
</x-layouts.app>
