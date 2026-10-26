<x-layouts.app>
    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-ink-950">
                {{ auth()->user()->isAdmin() ? 'Daftar Semua Booking' : 'Booking Saya' }}
            </h1>
            <p class="text-sm text-ink-500 mt-1">Pantau status persetujuan, jadwal pertemuan, dan riwayat reservasi Anda.</p>
        </div>
        <a href="{{ route('bookings.create') }}" class="btn-primary text-sm self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            <span> Booking Baru</span>
        </a>
    </div>

    <!-- Tab Filter Status (Pill Tabs) -->
    <div class="mt-6 flex flex-wrap items-center gap-2">
        @php
            $currentStatus = request('status', '');
            $tabs = [
                '' => 'Semua Status',
                'pending' => 'Menunggu',
                'approved' => 'Disetujui',
                'rejected' => 'Ditolak',
                'cancelled' => 'Dibatalkan',
            ];
        @endphp

        @foreach($tabs as $val => $name)
            <a href="{{ route('bookings.index', array_filter(['status' => $val])) }}" 
               class="pill-tab {{ $currentStatus === $val ? 'pill-tab-active' : 'pill-tab-inactive' }}">
                {{ $name }}
            </a>
        @endforeach
    </div>

    <!-- Kontainer Data: Tabel Desktop & Kartu Mobile -->
    <div class="card mt-6 p-0 overflow-hidden">
        <!-- Tabel Layar Desktop md+ -->
        <div class="hidden md:block overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Ruangan</th>
                        <th>Judul Meeting</th>
                        <th>Waktu & Tanggal</th>
                        <th>Status</th>
                        <th class="text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @forelse($bookings as $b)
                        <tr class="hover:bg-ink-50/50 transition-colors">
                            <td class="font-mono text-xs font-bold text-ink-700">
                                {{ $b->booking_code }}
                            </td>
                            <td>
                                <div class="font-bold text-ink-950">{{ $b->room->name }}</div>
                                <div class="text-xs text-ink-500">{{ $b->room->location }}</div>
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
                                @if($b->rejection_reason)
                                    <p class="mt-1.5 text-xs text-rose-600 bg-rose-50 p-1.5 rounded-md border border-rose-200/60 max-w-xs">
                                        <strong>Alasan:</strong> {{ $b->rejection_reason }}
                                    </p>
                                @endif
                            </td>
                            <td class="text-right">
                                @if(in_array($b->status, ['pending', 'approved']))
                                    <form method="post" action="{{ route('bookings.cancel', $b) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan booking {{ $b->booking_code }} ini?')">
                                        @csrf
                                        <button type="submit" class="text-xs font-semibold text-rose-600 hover:text-rose-800 hover:bg-rose-50 px-3 py-1.5 rounded-lg transition-colors">
                                            Batalkan
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-ink-400 italic">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center text-ink-500">
                                <svg class="w-12 h-12 mx-auto text-ink-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                <p class="font-bold text-ink-950">Belum ada booking ditemukan.</p>
                                <p class="text-xs text-ink-400 mt-1">Anda dapat melakukan pemesanan ruangan baru kapan saja.</p>
                                <a href="{{ route('bookings.create') }}" class="btn-primary text-xs mt-4 inline-flex">
                                    Booking Sekarang
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Tampilan Kartu Bertumpuk di Layar Mobile (< 768px) -->
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
                            <span class="font-bold text-ink-950">{{ $b->room->name }} ({{ $b->room->location }})</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-ink-500">Waktu:</span>
                            <span class="font-semibold text-ink-900">{{ $b->start_at->locale('id')->isoFormat('D MMM Y, HH:mm') }} - {{ $b->end_at->format('H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-ink-500">Peserta:</span>
                            <span class="font-medium text-ink-900">{{ $b->participant_count }} orang</span>
                        </div>
                    </div>

                    @if($b->rejection_reason)
                        <div class="text-xs text-rose-700 bg-rose-50 p-2.5 rounded-lg border border-rose-200">
                            <strong>Alasan Penolakan:</strong> {{ $b->rejection_reason }}
                        </div>
                    @endif

                    <div class="pt-2 flex items-center justify-end">
                        @if(in_array($b->status, ['pending', 'approved']))
                            <form method="post" action="{{ route('bookings.cancel', $b) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan booking {{ $b->booking_code }} ini?')">
                                @csrf
                                <button type="submit" class="btn-danger text-xs py-2 px-3">
                                    Batalkan Booking
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-ink-500 text-sm">
                    Belum ada booking ditemukan.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $bookings->links() }}
    </div>
</x-layouts.app>
