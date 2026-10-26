<x-layouts.app>
    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-ink-950">Laporan Penggunaan Ruangan</h1>
            <p class="text-sm text-ink-500 mt-1">Ekspor dan analisis data utilisasi ruang rapat serta statistik reservasi.</p>
        </div>
        <a href="{{ route('admin.reports.csv', request()->query()) }}" class="btn-primary text-sm self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            <span>Unduh Berkas CSV</span>
        </a>
    </div>

    <!-- Toolbar Filter Laporan -->
    <form method="get" action="{{ route('admin.reports.index') }}" class="card mt-6 p-5">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 items-end">
            <div>
                <label for="from" class="block text-xs font-semibold text-ink-700 uppercase tracking-wider mb-1.5">Dari Tanggal</label>
                <input id="from" class="field" type="date" name="from" value="{{ request('from') }}">
            </div>

            <div>
                <label for="to" class="block text-xs font-semibold text-ink-700 uppercase tracking-wider mb-1.5">Sampai Tanggal</label>
                <input id="to" class="field" type="date" name="to" value="{{ request('to') }}">
            </div>

            <div>
                <label for="status" class="block text-xs font-semibold text-ink-700 uppercase tracking-wider mb-1.5">Status Booking</label>
                <select id="status" class="field" name="status">
                    <option value="">Semua Status</option>
                    @foreach(['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'cancelled' => 'Dibatalkan'] as $s => $label)
                        <option value="{{ $s }}" @selected(request('status') === $s)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="btn-primary w-full">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>Terapkan Filter</span>
                </button>
                @if(request()->anyFilled(['from', 'to', 'status']))
                    <a href="{{ route('admin.reports.index') }}" class="btn-secondary px-3 shrink-0" title="Reset filter">
                        <svg class="w-4 h-4 text-ink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </div>
        </div>
    </form>

    <!-- Tabel Data Laporan -->
    <div class="card mt-6 p-0 overflow-hidden">
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Ruangan</th>
                        <th>Pemohon</th>
                        <th>Judul Meeting</th>
                        <th>Waktu & Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @forelse($bookings as $b)
                        <tr class="hover:bg-ink-50/50 transition-colors">
                            <td class="font-mono text-xs font-bold text-ink-700">{{ $b->booking_code }}</td>
                            <td>
                                <div class="font-bold text-ink-950">{{ $b->room->name }}</div>
                                <div class="text-xs text-ink-500">{{ $b->room->location }}</div>
                            </td>
                            <td>
                                <div class="font-medium text-ink-950">{{ $b->user->name }}</div>
                                <div class="text-xs text-ink-500">{{ $b->user->department ?: '-' }}</div>
                            </td>
                            <td>
                                <div class="font-medium text-ink-900">{{ $b->title }}</div>
                                <div class="text-xs text-ink-400">{{ $b->participant_count }} orang</div>
                            </td>
                            <td>
                                <div class="font-medium text-ink-950">{{ $b->start_at->locale('id')->isoFormat('D MMM Y') }}</div>
                                <div class="text-xs text-ink-500">{{ $b->start_at->format('H:i') }} – {{ $b->end_at->format('H:i') }} WIB</div>
                            </td>
                            <td>
                                <x-badge :status="$b->status" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center text-ink-500">
                                <svg class="w-12 h-12 mx-auto text-ink-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p class="font-bold text-ink-950">Tidak ada data untuk filter laporan ini.</p>
                                <p class="text-xs text-ink-400 mt-1">Sesuaikan rentang tanggal atau status yang Anda cari.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Stacked Cards (< 768px) -->
        <div class="md:hidden divide-y divide-ink-100">
            @forelse($bookings as $b)
                <div class="p-5 space-y-3">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <span class="font-mono text-xs font-bold text-ink-500">{{ $b->booking_code }}</span>
                            <h2 class="font-bold text-ink-950 text-base mt-0.5">{{ $b->title }}</h2>
                        </div>
                        <x-badge :status="$b->status" />
                    </div>

                    <div class="p-3 rounded-[12px] bg-ink-50 text-xs space-y-1.5">
                        <div class="flex justify-between">
                            <span class="text-ink-500">Ruangan:</span>
                            <span class="font-bold text-ink-950">{{ $b->room->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-ink-500">Pemohon:</span>
                            <span class="font-medium text-ink-900">{{ $b->user->name }} ({{ $b->user->department ?: '-' }})</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-ink-500">Waktu:</span>
                            <span class="font-semibold text-ink-900">{{ $b->start_at->locale('id')->isoFormat('D MMM Y, HH:mm') }} - {{ $b->end_at->format('H:i') }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-ink-500 text-sm">
                    Tidak ada data untuk filter laporan ini.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $bookings->links() }}
    </div>
</x-layouts.app>
