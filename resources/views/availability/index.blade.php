<x-layouts.app>
    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-ink-950">Ketersediaan Ruang Meeting</h1>
            <p class="text-sm text-ink-500 mt-1">Cek jadwal kosong seluruh ruangan secara visual dan real-time.</p>
        </div>
        <a href="{{ route('bookings.create') }}" class="btn-primary text-sm self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            <span> Booking Meeting</span>
        </a>
    </div>

    <!-- Pintasan Cepat Waktu (Shortcuts) -->
    <div class="mt-6 flex flex-wrap items-center gap-2">
        <span class="text-xs font-bold uppercase tracking-wider text-ink-500 mr-2">Pintasan:</span>
        @php
            $todayStr = today()->toDateString();
            $tomorrowStr = today()->addDay()->toDateString();
            $currentDate = $date->toDateString();
            $currentStart = $filters['start_time'] ?? '';
            $currentEnd = $filters['end_time'] ?? '';
        @endphp

        <a href="{{ route('availability', array_merge(request()->query(), ['date' => $todayStr])) }}" 
           class="pill-tab {{ $currentDate === $todayStr && !$currentStart ? 'pill-tab-active' : 'pill-tab-inactive' }}">
            Hari Ini
        </a>
        <a href="{{ route('availability', array_merge(request()->query(), ['date' => $tomorrowStr])) }}" 
           class="pill-tab {{ $currentDate === $tomorrowStr && !$currentStart ? 'pill-tab-active' : 'pill-tab-inactive' }}">
            Besok
        </a>
        <a href="{{ route('availability', array_merge(request()->query(), ['date' => $currentDate, 'start_time' => '08:00', 'end_time' => '12:00'])) }}" 
           class="pill-tab {{ $currentStart === '08:00' && $currentEnd === '12:00' ? 'pill-tab-active' : 'pill-tab-inactive' }}">
            Pagi (08:00 - 12:00)
        </a>
        <a href="{{ route('availability', array_merge(request()->query(), ['date' => $currentDate, 'start_time' => '13:00', 'end_time' => '17:00'])) }}" 
           class="pill-tab {{ $currentStart === '13:00' && $currentEnd === '17:00' ? 'pill-tab-active' : 'pill-tab-inactive' }}">
            Siang (13:00 - 17:00)
        </a>
        <a href="{{ route('availability', array_merge(request()->query(), ['date' => $currentDate, 'start_time' => '08:00', 'end_time' => '18:00'])) }}" 
           class="pill-tab {{ $currentStart === '08:00' && $currentEnd === '18:00' ? 'pill-tab-active' : 'pill-tab-inactive' }}">
            Sepanjang Hari
        </a>
    </div>

    <!-- Filter Form -->
    <form method="get" action="{{ route('availability') }}" class="card mt-4 p-5">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5 items-end">
            <div>
                <label for="date" class="block text-xs font-semibold text-ink-700 uppercase tracking-wider mb-1.5">Tanggal Meeting <span class="text-rose-500">*</span></label>
                <input id="date" class="field" type="date" name="date" value="{{ old('date', $date->toDateString()) }}" required>
            </div>

            <div>
                <label for="start_time" class="block text-xs font-semibold text-ink-700 uppercase tracking-wider mb-1.5">Jam Mulai</label>
                <input id="start_time" class="field" type="time" name="start_time" value="{{ old('start_time', $filters['start_time'] ?? '') }}">
            </div>

            <div>
                <label for="end_time" class="block text-xs font-semibold text-ink-700 uppercase tracking-wider mb-1.5">Jam Selesai</label>
                <input id="end_time" class="field" type="time" name="end_time" value="{{ old('end_time', $filters['end_time'] ?? '') }}">
            </div>

            <div>
                <label for="capacity" class="block text-xs font-semibold text-ink-700 uppercase tracking-wider mb-1.5">Kapasitas Min.</label>
                <input id="capacity" class="field" type="number" min="1" name="capacity" value="{{ old('capacity', $filters['capacity'] ?? '') }}" placeholder="Contoh: 10">
            </div>

            <div>
                <label for="room_id" class="block text-xs font-semibold text-ink-700 uppercase tracking-wider mb-1.5">Nama Ruangan</label>
                <select id="room_id" class="field" name="room_id">
                    <option value="">Semua Ruangan</option>
                    @forelse($roomOptions as $roomOption)
                        <option value="{{ $roomOption->id }}" @selected((string)old('room_id', $filters['room_id'] ?? '') === (string)$roomOption->id)>
                            {{ $roomOption->name }} ({{ $roomOption->capacity }} org){{ $roomOption->is_active ? '' : ' — nonaktif' }}
                        </option>
                    @empty
                        <option value="" disabled>Belum ada data ruangan</option>
                    @endforelse
                </select>
            </div>
        </div>

        <div class="mt-5 pt-4 border-t border-ink-100 flex flex-wrap items-center justify-between gap-3">
            <!-- Legenda Warna Garis Waktu -->
            <div class="flex flex-wrap items-center gap-4 text-xs font-medium text-ink-600">
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-brand-200 border border-brand-600"></span>
                    Kosong / Tersedia
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-[#fbbf24] border border-[#92400e]"></span>
                    Menunggu Persetujuan
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-rose-400 border border-rose-700"></span>
                    Sudah Dipesan
                </span>
            </div>

            <div class="flex items-center gap-2">
                @if(request()->anyFilled(['start_time', 'end_time', 'capacity', 'room_id']))
                    <a href="{{ route('availability', ['date' => $date->toDateString()]) }}" class="btn-secondary text-xs">
                        Reset Filter
                    </a>
                @endif
                <button type="submit" class="btn-primary text-xs">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>Cari Ruangan Tersedia</span>
                </button>
            </div>
        </div>
    </form>

    <!-- Informasi Status Filter -->
    <div class="mt-6 flex items-center justify-between">
        <p class="text-sm font-semibold text-ink-700">
            Status ketersediaan untuk: <span class="text-brand-700">{{ $date->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
            @if(isset($filters['start_time']))
                <span class="text-ink-500 font-normal"> · Rentang {{ $start->format('H:i') }} – {{ $end->format('H:i') }} WIB</span>
            @endif
        </p>
    </div>

    <!-- Grid Kartu Ketersediaan Ruangan -->
    <div class="mt-4 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        @forelse($rooms as $room)
            <article class="card flex flex-col justify-between group hover:border-brand-600 transition-colors">
                <div>
                    <!-- Header Kartu: Nama Ruangan & Badge Ketersediaan -->
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-bold text-ink-950 group-hover:text-brand-600 transition-colors">{{ $room->name }}</h2>
                            <p class="mt-1 text-xs text-ink-500 flex items-center gap-2">
                                <span>{{ $room->location ?: '-' }}</span>
                                <span>·</span>
                                <span class="font-semibold text-ink-700">{{ $room->capacity }} orang</span>
                            </p>
                        </div>

                        <!-- Status Badge (Mempertahankan teks persis untuk BookingTest) -->
                        <span class="availability-badge availability-{{ $room->availability_status }}">
                            @switch($room->availability_status)
                                @case('booked')
                                    Sudah dipesan
                                    @break
                                @case('pending')
                                    Menunggu persetujuan
                                    @break
                                @case('unavailable')
                                    Tidak tersedia
                                    @break
                                @default
                                    Tersedia
                            @endswitch
                        </span>
                    </div>

                    <!-- Fasilitas -->
                    <div class="mt-4 flex flex-wrap gap-1.5">
                        @forelse($room->facilities as $facility)
                            <span class="facility-chip text-2xs">
                                {{ $facility->name }}
                            </span>
                        @empty
                            <span class="text-xs text-ink-400">Fasilitas standar</span>
                        @endforelse
                    </div>

                    <!-- Garis Waktu Visual Slot Hari Ini (08:00 - 18:00) -->
                    <div class="mt-5 pt-4 border-t border-ink-100">
                        <div class="flex items-center justify-between text-2xs font-semibold uppercase tracking-wider text-ink-400 mb-2">
                            <span>Garis Waktu (08.00 - 18.00)</span>
                            <span>{{ $room->bookings->count() }} Jadwal Terdaftar</span>
                        </div>

                        <div class="grid grid-cols-10 gap-1 h-6 p-1 bg-ink-100 rounded-lg">
                            @php
                                $hours = [8, 9, 10, 11, 12, 13, 14, 15, 16, 17];
                            @endphp
                            @foreach($hours as $h)
                                @php
                                    $slotStart = $date->copy()->setTime($h, 0);
                                    $slotEnd = $date->copy()->setTime($h + 1, 0);
                                    // Cek apakah slot ini bertabrakan dengan booking
                                    $slotBooking = $room->bookings->first(function($b) use ($slotStart, $slotEnd) {
                                        return $b->start_at < $slotEnd && $b->end_at > $slotStart;
                                    });
                                    $slotStatus = !$room->is_active ? 'unavailable' : ($slotBooking ? ($slotBooking->status === 'approved' ? 'booked' : 'pending') : 'empty');
                                @endphp
                                <div class="rounded-xs transition-transform hover:scale-110 {{ $slotStatus === 'booked' ? 'timeline-slot-booked' : ($slotStatus === 'pending' ? 'timeline-slot-pending' : ($slotStatus === 'empty' ? 'timeline-slot-empty' : 'bg-ink-300')) }}" 
                                     title="{{ sprintf('%02d:00 - %02d:00: %s', $h, $h+1, $slotStatus === 'booked' ? 'Dipesan' : ($slotStatus === 'pending' ? 'Menunggu' : 'Kosong')) }}">
                                </div>
                            @endforeach
                        </div>
                        <div class="flex justify-between text-3xs text-ink-400 mt-1 px-0.5">
                            <span>08:00</span>
                            <span>13:00</span>
                            <span>18:00</span>
                        </div>
                    </div>

                    <!-- Ringkasan Jadwal Bertabrakan -->
                    <div class="mt-4 text-xs text-ink-600 space-y-1">
                        @forelse($room->bookings as $booking)
                            <div class="flex items-center justify-between py-1 px-2.5 rounded-md bg-ink-50">
                                <span class="font-semibold text-ink-900">{{ $booking->start_at->format('H:i') }} – {{ $booking->end_at->format('H:i') }}</span>
                                <span class="text-ink-500 font-medium">{{ $booking->status === 'approved' ? 'Dipesan' : 'Menunggu persetujuan' }}</span>
                            </div>
                        @empty
                            <p class="text-ink-400 italic">Tidak ada jadwal yang bertabrakan pada rentang ini.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Tombol Aksi Bawah -->
                <div class="mt-6 pt-4 border-t border-ink-100 flex items-center justify-between gap-3">
                    <a href="{{ route('availability', array_filter(['date' => $date->toDateString(), 'room' => $room->id])) }}" 
                       class="btn-secondary text-xs py-2 px-3">
                        Lihat Jadwal
                    </a>

                    @if($room->availability_status === 'available')
                        @php
                            $bookingParams = ['room' => $room->id];
                            if(isset($filters['start_time']) && isset($filters['end_time'])) {
                                $bookingParams['start_at'] = $date->toDateString() . 'T' . $filters['start_time'];
                                $bookingParams['end_at'] = $date->toDateString() . 'T' . $filters['end_time'];
                            }
                        @endphp
                        <a href="{{ route('bookings.create', $bookingParams) }}" class="btn-primary text-xs py-2 px-3.5">
                            <span>Booking Sekarang</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @endif
                </div>
            </article>
        @empty
            <div class="card text-ink-500 md:col-span-2 xl:col-span-3 text-center py-14">
                <svg class="w-10 h-10 mx-auto text-ink-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <p class="font-bold text-ink-950">Tidak ada ruangan yang sesuai dengan filter.</p>
                <p class="text-xs text-ink-400 mt-1">Coba ubah tanggal, kurangi kapasitas minimal, atau pilih jam lainnya.</p>
            </div>
        @endforelse
    </div>

    <!-- Panel Jadwal Detail untuk Ruangan Terpilih -->
    @if($selectedRoom)
        <section class="card mt-8 max-w-3xl border-brand-600/30">
            <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-ink-100">
                <div>
                    <h2 class="text-lg font-bold text-ink-950">Jadwal {{ $selectedRoom->name }}</h2>
                    <p class="text-xs text-ink-500">{{ $date->locale('id')->isoFormat('dddd, D MMMM Y') }} · Detail booking pengguna lain disamarkan.</p>
                </div>
                <a href="{{ route('availability', ['date' => $date->toDateString()]) }}" class="btn-secondary text-xs">
                    Tutup jadwal
                </a>
            </div>

            <div class="mt-4 divide-y divide-ink-100">
                @forelse($selectedRoom->bookings as $booking)
                    <div class="py-3.5 flex items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-ink-950 text-sm">{{ $booking->start_at->format('H:i') }} – {{ $booking->end_at->format('H:i') }} WIB</span>
                                <span class="availability-badge availability-{{ $booking->status === 'approved' ? 'booked' : 'pending' }}">
                                    {{ $booking->status === 'approved' ? 'Dipesan' : 'Menunggu persetujuan' }}
                                </span>
                            </div>
                            <p class="mt-1 text-xs text-ink-600">
                                {{ auth()->id() === $booking->user_id || auth()->user()->isAdmin() ? $booking->title : 'Slot terisi (Privat)' }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="py-8 text-center text-sm text-ink-500">Tidak ada booking aktif pada tanggal ini.</p>
                @endforelse
            </div>
        </section>
    @endif
</x-layouts.app>
