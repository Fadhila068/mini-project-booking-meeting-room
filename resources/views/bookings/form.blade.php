<x-layouts.app>
    <div class="mb-6">
        <a href="{{ route('bookings.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-ink-600 hover:text-brand-600 transition-colors mb-3 group">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Booking Saya</span>
        </a>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-ink-950">Form Booking Ruang Meeting</h1>
        <p class="text-sm text-ink-500 mt-1">Lengkapi 3 langkah mudah berikut untuk mengajukan reservasi ruangan.</p>
    </div>

    <!-- Layout 2 Kolom: Form di Kiri, Panel Ringkasan di Kanan -->
    <div class="grid gap-8 lg:grid-cols-12 items-start">
        <!-- Form Utama (3 Langkah) -->
        <div class="lg:col-span-8 space-y-6">
            <form method="post" action="{{ route('bookings.store') }}" class="space-y-6" data-booking-form>
                @csrf

                <!-- Langkah 1: Pilih Ruangan -->
                <div class="card">
                    <div class="flex items-center gap-2.5 pb-4 mb-4 border-b border-ink-100">
                        <span class="w-7 h-7 rounded-full bg-brand-50 text-brand-700 text-xs font-bold flex items-center justify-center border border-brand-200">1</span>
                        <div>
                            <h2 class="text-base font-bold text-ink-950">Pilih Ruangan</h2>
                            <p class="text-xs text-ink-500">Pilih ruangan yang sesuai dengan kebutuhan kapasitas pertemuan Anda.</p>
                        </div>
                    </div>

                    <div>
                        <label for="meeting_room_id" class="auth-label">Ruangan <span class="text-rose-500">*</span></label>
                        <select 
                            id="meeting_room_id" 
                            class="field @error('meeting_room_id') border-rose-500 @enderror" 
                            name="meeting_room_id" 
                            data-room-select 
                            required 
                            @disabled($rooms->isEmpty())
                        >
                            <option value="">{{ $rooms->isEmpty() ? 'Belum ada ruangan aktif' : 'Pilih ruangan yang tersedia' }}</option>
                            @foreach ($rooms as $room)
                                <option 
                                    value="{{ $room->id }}" 
                                    data-capacity="{{ $room->capacity }}"
                                    data-opens="{{ $room->opens_at ?: '08:00' }}"
                                    data-closes="{{ $room->closes_at ?: '18:00' }}"
                                    data-location="{{ $room->location }}"
                                    @selected((string) old('meeting_room_id', request('room')) === (string) $room->id)
                                >
                                    {{ $room->name }} — kapasitas {{ $room->capacity }} orang
                                </option>
                            @endforeach
                        </select>
                        @if ($rooms->isEmpty())
                            <p class="mt-2 text-xs font-medium text-amber-700 bg-amber-50 p-2.5 rounded-lg border border-amber-200">
                                Belum ada ruangan aktif. Hubungi administrator untuk menambahkan ruangan.
                            </p>
                        @endif
                        @error('meeting_room_id')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Langkah 2: Tentukan Waktu & Durasi -->
                <div class="card">
                    <div class="flex items-center gap-2.5 pb-4 mb-4 border-b border-ink-100">
                        <span class="w-7 h-7 rounded-full bg-brand-50 text-brand-700 text-xs font-bold flex items-center justify-center border border-brand-200">2</span>
                        <div>
                            <h2 class="text-base font-bold text-ink-950">Waktu & Durasi Meeting</h2>
                            <p class="text-xs text-ink-500">Tentukan jadwal mulai dan selesai.</p>
                        </div>
                    </div>

                    <!-- Tombol Durasi Cepat -->
                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-ink-700 uppercase tracking-wider mb-2">Pilih Durasi Cepat:</label>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" class="pill-tab pill-tab-inactive" data-duration-btn data-duration-minutes="30">
                                +30 Menit
                            </button>
                            <button type="button" class="pill-tab pill-tab-active" data-duration-btn data-duration-minutes="60">
                                +1 Jam (Standar)
                            </button>
                            <button type="button" class="pill-tab pill-tab-inactive" data-duration-btn data-duration-minutes="120">
                                +2 Jam
                            </button>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="start_at" class="auth-label">Waktu Mulai <span class="text-rose-500">*</span></label>
                            <input 
                                id="start_at" 
                                class="field @error('start_at') border-rose-500 @enderror" 
                                type="datetime-local" 
                                name="start_at" 
                                data-start-input
                                value="{{ old('start_at', request('start_at')) }}" 
                                required
                            >
                            @error('start_at')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="end_at" class="auth-label">Waktu Selesai <span class="text-rose-500">*</span></label>
                            <input 
                                id="end_at" 
                                class="field @error('end_at') border-rose-500 @enderror" 
                                type="datetime-local" 
                                name="end_at" 
                                data-end-input
                                value="{{ old('end_at', request('end_at')) }}" 
                                required
                            >
                            @error('end_at')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Peringatan Dinamis Jam Operasional -->
                    <div id="hours-warning" data-hours-warning class="hidden mt-3 p-3 rounded-[10px] bg-amber-50 border border-amber-200 text-xs font-medium text-amber-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>Peringatan jam operasional</span>
                    </div>
                </div>

                <!-- Langkah 3: Detail Meeting -->
                <div class="card">
                    <div class="flex items-center gap-2.5 pb-4 mb-4 border-b border-ink-100">
                        <span class="w-7 h-7 rounded-full bg-brand-50 text-brand-700 text-xs font-bold flex items-center justify-center border border-brand-200">3</span>
                        <div>
                            <h2 class="text-base font-bold text-ink-950">Detail Pertemuan</h2>
                            <p class="text-xs text-ink-500">Informasi agenda dan perkiraan jumlah peserta hadir.</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label for="title" class="auth-label">Judul Meeting <span class="text-rose-500">*</span></label>
                            <input 
                                id="title" 
                                class="field @error('title') border-rose-500 @enderror" 
                                name="title" 
                                value="{{ old('title') }}" 
                                placeholder="Contoh: Rapat Koordinasi Kuartal II" 
                                required
                            >
                            @error('title')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="participant_count" class="auth-label">Jumlah Peserta <span class="text-rose-500">*</span></label>
                            <input 
                                id="participant_count" 
                                class="field @error('participant_count') border-rose-500 @enderror" 
                                type="number" 
                                min="1" 
                                name="participant_count" 
                                data-participant-input
                                value="{{ old('participant_count', 1) }}" 
                                required
                            >
                            @error('participant_count')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror

                            <!-- Peringatan Kapasitas Dinamis -->
                            <div id="capacity-warning" data-capacity-warning class="hidden mt-2 p-2.5 rounded-[10px] bg-rose-50 border border-rose-200 text-xs font-medium text-rose-800 flex items-center gap-2">
                                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <span>Peringatan: Jumlah peserta melebihi kapasitas ruangan.</span>
                            </div>
                        </div>

                        <div>
                            <label for="description" class="auth-label">Agenda / Deskripsi Tambahan</label>
                            <textarea 
                                id="description" 
                                class="field @error('description') border-rose-500 @enderror" 
                                name="description" 
                                placeholder="Jelaskan poin agenda meeting atau catatan khusus..."
                            >{{ old('description') }}</textarea>
                            @error('description')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('bookings.index') }}" class="btn-secondary text-sm">
                        Batal
                    </a>
                    <button type="submit" class="btn-primary text-sm" @disabled($rooms->isEmpty())>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>Kirim Permintaan Booking</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Panel Ringkasan Ruangan di Kanan (Sticky Desktop) -->
        <div class="lg:col-span-4 sticky top-6 space-y-5">
            <div class="card bg-brand-50/60 border-brand-200 p-6">
                <div class="flex items-center gap-2 pb-3 mb-3 border-b border-brand-200/80">
                    <span class="w-8 h-8 rounded-[8px] bg-brand-600 text-white flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-bold text-ink-950">Ringkasan Pemesanan</h3>
                        <p class="text-xs text-ink-500">Informasi ruangan terpilih</p>
                    </div>
                </div>

                <div class="space-y-3 text-xs">
                    <div class="flex justify-between py-1.5 border-b border-brand-200/50">
                        <span class="text-ink-500">Pemohon:</span>
                        <span class="font-bold text-ink-950">{{ auth()->user()->name }}</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-brand-200/50">
                        <span class="text-ink-500">Departemen:</span>
                        <span class="font-medium text-ink-900">{{ auth()->user()->department ?: '-' }}</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-brand-200/50">
                        <span class="text-ink-500">Status Awal:</span>
                        <span class="font-semibold text-amber-700 bg-amber-100/80 px-2 py-0.5 rounded-full">Menunggu Persetujuan</span>
                    </div>
                </div>

                <div class="mt-5 p-3.5 rounded-[12px] bg-white border border-brand-200 text-xs text-ink-600 space-y-1.5">
                    <p class="font-bold text-brand-800 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-brand-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Ketentuan Reservasi</span>
                    </p>
                    <p class="text-2xs text-ink-500 leading-relaxed">
                        Permintaan booking Anda akan masuk ke antrean persetujuan General Affairs/Admin. Anda akan menerima status terkini di menu Booking Saya.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
