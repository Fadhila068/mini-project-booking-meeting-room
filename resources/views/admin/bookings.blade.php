<x-layouts.app>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-ink-950">Persetujuan Booking</h1>
            <p class="text-sm text-ink-500 mt-1">Tinjau, setujui, atau tolak permohonan reservasi ruang meeting dari seluruh karyawan.</p>
        </div>
    </div>

    <!-- Filter Status -->
    <div class="mt-6 flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-2">
            @php
                $currStatus = request('status', '');
                $statusTabs = [
                    '' => 'Semua Pengajuan',
                    'pending' => 'Menunggu Persetujuan',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                    'cancelled' => 'Dibatalkan',
                ];
            @endphp
            @foreach($statusTabs as $sVal => $sTitle)
                <a href="{{ route('admin.bookings.index', array_filter(['status' => $sVal])) }}" 
                   class="pill-tab {{ $currStatus === $sVal ? 'pill-tab-active' : 'pill-tab-inactive' }}">
                    {{ $sTitle }}
                </a>
            @endforeach
        </div>

        <form method="get" action="{{ route('admin.bookings.index') }}" class="sm:hidden w-full">
            <select class="field" name="status" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                @foreach(['pending', 'approved', 'rejected', 'cancelled'] as $s)
                    <option value="{{ $s }}" @selected(request('status') === $s)>
                        {{ $statusTabs[$s] ?? ucfirst($s) }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Tabel Data Pengajuan -->
    <div class="card mt-6 p-0 overflow-hidden">
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Pemohon</th>
                        <th>Ruang / Waktu</th>
                        <th>Judul Meeting</th>
                        <th>Status</th>
                        <th class="text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-ink-50/50 transition-colors">
                            <td>
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-full bg-brand-100 text-brand-800 text-xs font-bold flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($booking->user->name, 0, 1)) }}
                                    </span>
                                    <div>
                                        <div class="font-bold text-ink-950">{{ $booking->user->name }}</div>
                                        <div class="text-xs text-ink-500">{{ $booking->user->department ?: $booking->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="font-semibold text-ink-950">{{ $booking->room->name }}</div>
                                <div class="text-xs text-ink-500">{{ $booking->start_at->locale('id')->isoFormat('D MMM Y, HH:mm') }} – {{ $booking->end_at->format('H:i') }} WIB</div>
                            </td>
                            <td>
                                <div class="font-medium text-ink-900">{{ $booking->title }}</div>
                                <div class="text-xs text-ink-400">{{ $booking->participant_count }} orang</div>
                            </td>
                            <td>
                                <x-badge :status="$booking->status" />
                            </td>
                            <td class="text-right">
                                <a href="{{ route('admin.bookings.edit', $booking) }}" class="inline-flex items-center gap-1.5 rounded-[10px] border border-ink-300 bg-white px-3.5 py-2 text-xs font-semibold text-ink-700 hover:bg-ink-100 hover:text-ink-900 focus:outline-none focus:ring-2 focus:ring-brand-500 transition-colors">
                                    <span>Edit</span>
                                    <svg class="w-3.5 h-3.5 text-ink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-16 text-center text-ink-500">
                                <svg class="w-12 h-12 mx-auto text-ink-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="font-bold text-ink-950">Tidak ada pengajuan booking yang perlu ditinjau.</p>
                                <p class="text-xs text-ink-400 mt-1">Semua reservasi telah diproses.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards (< 768px) -->
        <div class="md:hidden divide-y divide-ink-100">
            @forelse($bookings as $booking)
                <div class="p-5 space-y-3">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-full bg-brand-100 text-brand-800 text-xs font-bold flex items-center justify-center shrink-0">
                                {{ strtoupper(substr($booking->user->name, 0, 1)) }}
                            </span>
                            <div>
                                <h3 class="font-bold text-ink-950 text-sm">{{ $booking->user->name }}</h3>
                                <p class="text-2xs text-ink-500">{{ $booking->user->department ?: '-' }}</p>
                            </div>
                        </div>
                        <x-badge :status="$booking->status" />
                    </div>

                    <div class="p-3 rounded-[12px] bg-ink-50 text-xs space-y-1.5">
                        <div class="flex justify-between">
                            <span class="text-ink-500">Judul:</span>
                            <span class="font-bold text-ink-950">{{ $booking->title }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-ink-500">Ruangan:</span>
                            <span class="font-medium text-ink-900">{{ $booking->room->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-ink-500">Waktu:</span>
                            <span class="font-semibold text-ink-900">{{ $booking->start_at->locale('id')->isoFormat('D MMM Y, HH:mm') }} - {{ $booking->end_at->format('H:i') }}</span>
                        </div>
                    </div>

                    <div class="pt-2 flex items-center justify-end">
                        <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn-primary text-xs py-2 px-3 w-full text-center">
                            Periksa & Berikan Keputusan
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-ink-500 text-sm">
                    Tidak ada pengajuan booking yang perlu ditinjau.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $bookings->links() }}
    </div>
</x-layouts.app>
