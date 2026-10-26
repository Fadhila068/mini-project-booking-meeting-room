<x-layouts.app>
    <div class="max-w-4xl">
        <!-- Breadcrumb & Header -->
        <div class="mb-6">
            <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-ink-600 hover:text-brand-600 transition-colors mb-3 group">
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Kembali ke Daftar Persetujuan</span>
            </a>
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-ink-950">Detail Pengajuan Booking</h1>
                    <p class="text-sm text-ink-500 mt-1">
                        Kode: <span class="font-mono font-bold text-ink-800">{{ $booking->booking_code }}</span> · Diajukan pada {{ $booking->created_at?->locale('id')->isoFormat('D MMMM Y, HH:mm') ?? '-' }} WIB
                    </p>
                </div>
                <x-badge :status="$booking->status" class="text-sm px-4 py-1.5" />
            </div>
        </div>

        <!-- 3 Kartu Ringkasan Informasi -->
        <div class="grid gap-5 md:grid-cols-3">
            <!-- 1. Pemohon -->
            <section class="card p-5">
                <div class="flex items-center gap-2 pb-3 mb-3 border-b border-ink-100">
                    <span class="w-7 h-7 rounded-full bg-brand-50 text-brand-700 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </span>
                    <h2 class="text-sm font-bold text-ink-950">Data Pemohon</h2>
                </div>
                <dl class="detail-list !mt-0 text-xs">
                    <dt>Nama</dt>
                    <dd>{{ $booking->user->name ?? '-' }}</dd>
                    <dt>Email</dt>
                    <dd>{{ $booking->user->email ?? '-' }}</dd>
                    <dt>Departemen</dt>
                    <dd>{{ $booking->user->department ?: '-' }}</dd>
                </dl>
            </section>

            <!-- 2. Ruangan Saat Ini -->
            <section class="card p-5">
                <div class="flex items-center gap-2 pb-3 mb-3 border-b border-ink-100">
                    <span class="w-7 h-7 rounded-full bg-brand-50 text-brand-700 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </span>
                    <h2 class="text-sm font-bold text-ink-950">Ruangan Saat Ini</h2>
                </div>
                <dl class="detail-list !mt-0 text-xs">
                    <dt>Nama</dt>
                    <dd class="font-bold text-brand-700">{{ $booking->room->name ?? '-' }}</dd>
                    <dt>Kode</dt>
                    <dd>{{ $booking->room->code ?? '-' }}</dd>
                    <dt>Lokasi</dt>
                    <dd>{{ $booking->room->location ?? '-' }}</dd>
                    <dt>Kapasitas</dt>
                    <dd>{{ $booking->room->capacity ? $booking->room->capacity.' orang' : '-' }}</dd>
                </dl>
            </section>

            <!-- 3. Status Pengajuan -->
            <section class="card p-5">
                <div class="flex items-center gap-2 pb-3 mb-3 border-b border-ink-100">
                    <span class="w-7 h-7 rounded-full bg-brand-50 text-brand-700 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                    <h2 class="text-sm font-bold text-ink-950">Status Pengajuan</h2>
                </div>
                <dl class="detail-list !mt-0 text-xs">
                    <dt>Status</dt>
                    <dd><x-badge :status="$booking->status" /></dd>
                    @if($booking->approver)
                        <dt>Oleh</dt>
                        <dd>{{ $booking->approver->name }}</dd>
                        <dt>Waktu</dt>
                        <dd>{{ $booking->approved_at?->format('d/m/Y H:i') ?? '-' }}</dd>
                    @endif
                    @if($booking->rejection_reason)
                        <dt>Alasan Tolak</dt>
                        <dd class="text-rose-600 font-semibold">{{ $booking->rejection_reason }}</dd>
                    @endif
                    @if($booking->cancelled_at)
                        <dt>Dibatalkan</dt>
                        <dd>{{ $booking->cancelled_at->format('d/m/Y H:i') }}</dd>
                    @endif
                </dl>
            </section>
        </div>

        <!-- Form Edit & Pengambilan Keputusan (Hanya jika pending/approved) -->
        @if(in_array($booking->status, ['pending', 'approved']))
            <form method="post" action="{{ route('admin.bookings.update', $booking) }}" class="card mt-6 p-6 sm:p-8 space-y-6">
                @csrf
                @method('PUT')

                <div class="border-b border-ink-100 pb-4">
                    <h2 class="text-lg font-bold text-ink-950">Edit Informasi Booking</h2>
                    <p class="text-xs text-ink-500 mt-0.5">Anda dapat memindahkan ruangan atau menyesuaikan waktu sebelum memberikan keputusan.</p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="auth-label">Kode Booking</label>
                        <input class="field bg-ink-100 text-ink-500 font-mono cursor-not-allowed" value="{{ $booking->booking_code }}" readonly>
                    </div>
                    <div>
                        <label class="auth-label">Pemohon</label>
                        <input class="field bg-ink-100 text-ink-500 cursor-not-allowed" value="{{ $booking->user->name }}" readonly>
                    </div>
                </div>

                <div>
                    <label for="meeting_room_id" class="auth-label">Ruangan <span class="text-rose-500">*</span></label>
                    <select id="meeting_room_id" class="field @error('meeting_room_id') border-rose-500 @enderror" name="meeting_room_id" required>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" @selected((string)old('meeting_room_id', $booking->meeting_room_id) === (string)$room->id)>
                                {{ $room->name }} ({{ $room->code }}) — {{ $room->capacity }} orang
                            </option>
                        @endforeach
                    </select>
                    @error('meeting_room_id')<span class="mt-1.5 block text-xs font-medium text-rose-600">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label for="title" class="auth-label">Judul Meeting <span class="text-rose-500">*</span></label>
                    <input id="title" class="field @error('title') border-rose-500 @enderror" name="title" value="{{ old('title', $booking->title) }}" required>
                    @error('title')<span class="mt-1.5 block text-xs font-medium text-rose-600">{{ $message }}</span>@enderror
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="start_at" class="auth-label">Waktu Mulai <span class="text-rose-500">*</span></label>
                        <input id="start_at" class="field @error('start_at') border-rose-500 @enderror" type="datetime-local" name="start_at" value="{{ old('start_at', $booking->start_at->format('Y-m-d\\TH:i')) }}" required>
                        @error('start_at')<span class="mt-1.5 block text-xs font-medium text-rose-600">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="end_at" class="auth-label">Waktu Selesai <span class="text-rose-500">*</span></label>
                        <input id="end_at" class="field @error('end_at') border-rose-500 @enderror" type="datetime-local" name="end_at" value="{{ old('end_at', $booking->end_at->format('Y-m-d\\TH:i')) }}" required>
                        @error('end_at')<span class="mt-1.5 block text-xs font-medium text-rose-600">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div>
                    <label for="participant_count" class="auth-label">Jumlah Peserta <span class="text-rose-500">*</span></label>
                    <input id="participant_count" class="field @error('participant_count') border-rose-500 @enderror" type="number" min="1" name="participant_count" value="{{ old('participant_count', $booking->participant_count) }}" required>
                    @error('participant_count')<span class="mt-1.5 block text-xs font-medium text-rose-600">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label for="description" class="auth-label">Agenda / Deskripsi</label>
                    <textarea id="description" class="field @error('description') border-rose-500 @enderror" name="description">{{ old('description', $booking->description) }}</textarea>
                    @error('description')<span class="mt-1.5 block text-xs font-medium text-rose-600">{{ $message }}</span>@enderror
                </div>

                <!-- Seksi Keputusan Booking (Mempertahankan judul persis untuk BookingTest) -->
                <div class="pt-6 border-t border-ink-100" data-booking-decision>
                    <div class="mb-4">
                        <label for="decision" class="block text-base font-bold text-ink-950">Keputusan Booking</label>
                        <p class="text-xs text-ink-500 mt-0.5">Tentukan keputusan persetujuan untuk pengajuan booking ruangan ini.</p>
                    </div>

                    <!-- 3 Kartu Pilihan Keputusan Interaktif -->
                    <div class="grid gap-3 sm:grid-cols-3 mb-4">
                        <!-- Kartu 1: Belum Diputuskan -->
                        <div 
                            class="card p-4 cursor-pointer border-2 transition-all select-none flex flex-col justify-between"
                            data-decision-card
                            data-decision-value=""
                            role="radio"
                            aria-checked="{{ old('decision', '') === '' ? 'true' : 'false' }}"
                        >
                            <div class="flex items-center justify-between mb-2">
                                <span class="w-8 h-8 rounded-full bg-ink-100 text-ink-700 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </span>
                                <span class="w-4 h-4 rounded-full border border-ink-300 flex items-center justify-center">
                                    <span class="w-2 h-2 rounded-full bg-brand-600 hidden check-dot"></span>
                                </span>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-ink-950">Belum Diputuskan</h3>
                                <p class="text-2xs text-ink-500 mt-0.5">Status tetap pending / menunggu tinjauan lebih lanjut.</p>
                            </div>
                        </div>

                        <!-- Kartu 2: Setujui -->
                        <div 
                            class="card p-4 cursor-pointer border-2 transition-all select-none flex flex-col justify-between {{ $booking->status !== 'pending' ? 'opacity-50 cursor-not-allowed' : '' }}"
                            data-decision-card
                            data-decision-value="approve"
                            role="radio"
                            aria-checked="{{ old('decision') === 'approve' ? 'true' : 'false' }}"
                            aria-disabled="{{ $booking->status !== 'pending' ? 'true' : 'false' }}"
                        >
                            <div class="flex items-center justify-between mb-2">
                                <span class="w-8 h-8 rounded-full bg-brand-50 text-brand-700 flex items-center justify-center border border-brand-200">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                <span class="w-4 h-4 rounded-full border border-ink-300 flex items-center justify-center">
                                    <span class="w-2 h-2 rounded-full bg-brand-600 hidden check-dot"></span>
                                </span>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-brand-800">Setujui Permintaan</h3>
                                <p class="text-2xs text-ink-500 mt-0.5">Kunci jadwal ruangan dan beritahu pemohon.</p>
                            </div>
                        </div>

                        <!-- Kartu 3: Tolak -->
                        <div 
                            class="card p-4 cursor-pointer border-2 transition-all select-none flex flex-col justify-between {{ $booking->status !== 'pending' ? 'opacity-50 cursor-not-allowed' : '' }}"
                            data-decision-card
                            data-decision-value="reject"
                            role="radio"
                            aria-checked="{{ old('decision') === 'reject' ? 'true' : 'false' }}"
                            aria-disabled="{{ $booking->status !== 'pending' ? 'true' : 'false' }}"
                        >
                            <div class="flex items-center justify-between mb-2">
                                <span class="w-8 h-8 rounded-full bg-rose-50 text-rose-700 flex items-center justify-center border border-rose-200">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </span>
                                <span class="w-4 h-4 rounded-full border border-ink-300 flex items-center justify-center">
                                    <span class="w-2 h-2 rounded-full bg-rose-600 hidden check-dot"></span>
                                </span>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-rose-800">Tolak Permintaan</h3>
                                <p class="text-2xs text-ink-500 mt-0.5">Batalkan pengajuan dengan mencantumkan alasan.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden/Synced Select Input (Dukungan penuh untuk Form Submit & Testing) -->
                    <div class="sr-only">
                        <select id="decision" name="decision" data-decision-select>
                            <option value="">Pilih keputusan</option>
                            <option value="approve" @selected(old('decision') === 'approve') @disabled($booking->status !== 'pending')>Setujui</option>
                            <option value="reject" @selected(old('decision') === 'reject') @disabled($booking->status !== 'pending')>Tolak</option>
                        </select>
                    </div>

                    @error('decision')<span class="mt-1.5 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror

                    <p class="mt-2 text-xs font-medium text-ink-500" data-decision-message>Pilih salah satu kartu keputusan di atas.</p>

                    <!-- Input Alasan Penolakan (Hanya muncul saat kartu Tolak dipilih) -->
                    <div class="mt-4 hidden p-4 rounded-[12px] bg-rose-50 border border-rose-200 space-y-2" data-rejection-reason>
                        <label for="rejection_reason" class="block text-xs font-bold uppercase tracking-wider text-rose-900">
                            Alasan Penolakan <span class="text-rose-600">*</span>
                        </label>
                        <textarea 
                            id="rejection_reason" 
                            class="field border-rose-300 focus:border-rose-500 focus:ring-rose-500/20" 
                            name="rejection_reason" 
                            data-rejection-input
                            placeholder="Tuliskan alasan penolakan secara jelas agar pemohon memahami pertimbangan Anda..."
                        >{{ old('rejection_reason', $booking->rejection_reason) }}</textarea>
                        @error('rejection_reason')<span class="mt-1 block text-xs font-bold text-rose-700">{{ $message }}</span>@enderror
                    </div>

                    @if($booking->status === 'approved')
                        <p class="mt-3 text-xs text-brand-700 bg-brand-50 p-2.5 rounded-lg border border-brand-200">
                            Booking telah disetujui sebelumnya. Anda masih dapat memperbarui rincian waktu/ruangan tanpa perlu mengubah keputusan.
                        </p>
                    @endif
                </div>

                <!-- Tombol Aksi Simpan Form -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-ink-100">
                    <a href="{{ route('admin.bookings.index') }}" class="btn-secondary text-sm">
                        Batal
                    </a>
                    <button type="submit" class="btn-primary text-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        @else
            <!-- Status Hanya-Baca jika Rejected / Cancelled -->
            <div class="card mt-6 p-6 bg-ink-50 border-ink-200 text-center">
                <svg class="w-10 h-10 mx-auto text-ink-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <p class="text-sm font-semibold text-ink-800">
                    Booking berstatus <strong>{{ ucfirst($booking->status) }}</strong> bersifat hanya-baca dan tidak dapat diubah.
                </p>
            </div>
        @endif

        <!-- Riwayat Pengajuan Booking -->
        @if($booking->histories->isNotEmpty())
            <section class="card mt-6 p-6">
                <div class="flex items-center gap-2 pb-3 mb-4 border-b border-ink-100">
                    <svg class="w-4 h-4 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h2 class="text-base font-bold text-ink-950">Riwayat Pengajuan</h2>
                </div>

                <ul class="space-y-3">
                    @foreach($booking->histories as $history)
                        <li class="p-3 rounded-[12px] bg-ink-50 border border-ink-100 flex items-start justify-between gap-4 text-xs">
                            <div>
                                <span class="font-bold text-ink-950">{{ ucfirst($history->status) }}</span>
                                @if($history->note)
                                    <p class="text-ink-600 mt-0.5">{{ $history->note }}</p>
                                @endif
                                <p class="text-2xs text-ink-400 mt-1">Oleh: {{ $history->actor?->name ?? 'Sistem' }}</p>
                            </div>
                            <span class="text-2xs text-ink-400 shrink-0">
                                {{ $history->created_at->format('d/m/Y H:i') }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif
    </div>
</x-layouts.app>
