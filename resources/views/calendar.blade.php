<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-ink-950">Jadwal Ketersediaan</h1>
        <p class="text-sm text-ink-500 mt-1">Jadwal 7 hari ke depan. Detail meeting pengguna lain disamarkan untuk privasi.</p>
    </div>

    <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @forelse($events as $e)
            <article class="card hover:border-brand-600 transition-colors">
                <div class="flex items-center justify-between pb-2 mb-2 border-b border-ink-100">
                    <span class="font-bold text-brand-800 text-sm">{{ $e['room'] }}</span>
                    <span class="text-2xs font-semibold text-ink-500 bg-ink-100 px-2 py-0.5 rounded-full">Terjadwal</span>
                </div>
                <h2 class="font-bold text-ink-950 text-base">{{ $e['title'] }}</h2>
                <p class="mt-2 text-xs text-ink-500 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-ink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ \Carbon\Carbon::parse($e['start'])->locale('id')->isoFormat('D MMM Y, HH:mm') }} – {{ \Carbon\Carbon::parse($e['end'])->format('H:i') }} WIB</span>
                </p>
            </article>
        @empty
            <div class="card md:col-span-2 xl:col-span-3 text-center py-12 text-ink-500">
                <p class="font-bold text-ink-950">Tidak ada jadwal terisi pada periode ini.</p>
                <p class="text-xs text-ink-400 mt-1">Seluruh ruangan siap digunakan.</p>
            </div>
        @endforelse
    </div>
</x-layouts.app>
