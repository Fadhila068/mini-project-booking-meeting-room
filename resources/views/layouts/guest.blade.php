<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'RuangKita - Booking Ruang Meeting' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ink-50 text-ink-950 antialiased selection:bg-brand-100 selection:text-brand-800">
    <div class="min-h-screen grid lg:grid-cols-12">
        <!-- Panel Merek di Kiri (Khusus layar Desktop lg+) -->
        <div class="hidden lg:flex lg:col-span-5 xl:col-span-5 bg-gradient-to-b from-brand-800 via-brand-700 to-ink-950 text-white p-10 xl:p-14 flex-col justify-between relative overflow-hidden">
            <!-- Dekorasi Pola Latar -->
            <div class="absolute -right-20 -top-20 w-80 h-80 rounded-full bg-brand-600/20 blur-3xl pointer-events-none"></div>
            <div class="absolute -left-20 -bottom-20 w-80 h-80 rounded-full bg-brand-200/10 blur-3xl pointer-events-none"></div>

            <div class="relative z-10">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5 group">
                    <span class="w-10 h-10 rounded-[12px] bg-brand-600 flex items-center justify-center text-white shadow-sm ring-1 ring-white/20">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </span>
                    <span class="text-2xl font-bold tracking-tight text-white">Ruang<span class="text-brand-200">Kita</span></span>
                </a>
            </div>

            <div class="relative z-10 my-auto py-12">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-brand-600/40 border border-brand-200/20 text-xs font-semibold text-brand-100 mb-6">
                    <span class="w-2 h-2 rounded-full bg-brand-200 animate-pulse"></span>
                    Sistem Reservasi Modern & Efisien
                </div>
                <h1 class="text-3xl xl:text-4xl font-extrabold leading-tight text-white">
                    Kelola dan pesan ruang meeting tanpa bentrok jadwal.
                </h1>
                <p class="mt-4 text-brand-100 text-sm leading-relaxed max-w-md">
                    Dirancang untuk meningkatkan kolaborasi tim Anda dengan transparansi jadwal, fasilitas lengkap, dan persetujuan yang cepat.
                </p>

                <!-- Nilai Tambah / Poin Keunggulan -->
                <div class="mt-8 space-y-4">
                    <div class="flex items-center gap-3 text-sm text-brand-50">
                        <div class="w-6 h-6 rounded-full bg-brand-600/60 flex items-center justify-center text-brand-200 shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <span>Pengecekan ketersediaan real-time</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-brand-50">
                        <div class="w-6 h-6 rounded-full bg-brand-600/60 flex items-center justify-center text-brand-200 shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <span>Detail kapasitas & fasilitas interaktif</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-brand-50">
                        <div class="w-6 h-6 rounded-full bg-brand-600/60 flex items-center justify-center text-brand-200 shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <span>Riwayat pengajuan transparan</span>
                    </div>
                </div>
            </div>

            <div class="relative z-10 text-xs text-brand-200/80 pt-6 border-t border-brand-600/30">
                &copy; {{ date('Y') }} RuangKita · Sistem Booking Ruangan Perusahaan
            </div>
        </div>

        <!-- Panel Form di Kanan -->
        <div class="col-span-12 lg:col-span-7 xl:col-span-7 flex flex-col justify-center items-center p-6 sm:p-10 lg:p-16">
            <div class="w-full max-w-md">
                <!-- Mobile Logo Header -->
                <div class="lg:hidden mb-8 text-center">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5">
                        <span class="w-10 h-10 rounded-[12px] bg-brand-600 flex items-center justify-center text-white shadow-xs">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </span>
                        <span class="text-2xl font-bold tracking-tight text-ink-950">Ruang<span class="text-brand-600">Kita</span></span>
                    </a>
                </div>

                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
