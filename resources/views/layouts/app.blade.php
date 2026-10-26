<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'RuangKita') }} - Booking Ruang Meeting</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ink-50 text-ink-950 antialiased min-h-screen selection:bg-brand-100 selection:text-brand-800">
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Sidebar Desktop (288px = w-72) -->
        <aside class="hidden md:flex w-72 flex-col justify-between bg-ink-950 text-ink-300 p-6 shrink-0 border-r border-ink-900 sticky top-0 h-screen overflow-y-auto">
            <div>
                <!-- Brand Header -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group focus:outline-none focus:ring-2 focus:ring-brand-500 rounded-xl p-1">
                    <span class="w-10 h-10 rounded-[12px] bg-brand-600 flex items-center justify-center text-white shadow-xs group-hover:bg-brand-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </span>
                    <div>
                        <span class="text-xl font-bold tracking-tight text-white">Ruang<span class="text-brand-200">Kita</span></span>
                        <p class="text-xs text-ink-400">Meeting Room Booking</p>
                    </div>
                </a>

                <!-- Navigasi Menu -->
                <nav class="mt-8 space-y-1.5" aria-label="Menu Utama">
                    <a class="nav {{ request()->routeIs('dashboard') ? 'nav-active' : '' }}" href="{{ route('dashboard') }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Beranda</span>
                    </a>
                    <a class="nav {{ request()->routeIs('rooms.index', 'rooms.show') ? 'nav-active' : '' }}" href="{{ route('rooms.index') }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span>Daftar Ruangan</span>
                    </a>
                    <a class="nav {{ request()->routeIs('availability', 'calendar') ? 'nav-active' : '' }}" href="{{ route('availability') }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Cek Ketersediaan</span>
                    </a>
                    <a class="nav {{ request()->routeIs('bookings.*') && !request()->routeIs('admin.bookings.*') ? 'nav-active' : '' }}" href="{{ route('bookings.index') }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <span>Booking Saya</span>
                    </a>

                    @if(auth()->user()->isAdmin())
                        @php
                            $pendingBookingsCount = \App\Models\Booking::where('status', 'pending')->count();
                        @endphp
                        <div class="pt-6 pb-2">
                            <p class="px-3.5 text-xs font-bold uppercase tracking-wider text-ink-500">Administrasi</p>
                        </div>
                        <a class="nav {{ request()->routeIs('admin.rooms.*') ? 'nav-active' : '' }}" href="{{ route('admin.rooms.index') }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                            <span>Kelola Ruangan</span>
                        </a>
                        <a class="nav justify-between {{ request()->routeIs('admin.bookings.*') ? 'nav-active' : '' }}" href="{{ route('admin.bookings.index') }}">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Persetujuan</span>
                            </div>
                            @if($pendingBookingsCount > 0)
                                <span class="rounded-full bg-amber-400 px-2 py-0.5 text-xs font-bold text-ink-950">
                                    {{ $pendingBookingsCount }}
                                </span>
                            @endif
                        </a>
                        <a class="nav {{ request()->routeIs('admin.reports.*') ? 'nav-active' : '' }}" href="{{ route('admin.reports.index') }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span>Laporan</span>
                        </a>
                    @endif
                </nav>
            </div>

            <!-- User Profile & Logout Bottom Section -->
            <div class="pt-6 border-t border-ink-900 mt-6">
                <div class="flex items-center justify-between gap-3 p-2 rounded-[12px] bg-ink-900/60">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-[10px] bg-brand-800 text-brand-100 font-bold flex items-center justify-center shrink-0 border border-brand-700">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-ink-400 truncate">{{ auth()->user()->department ?: (auth()->user()->isAdmin() ? 'Administrator' : 'Karyawan') }}</p>
                        </div>
                    </div>
                    <form method="post" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 text-ink-400 hover:text-rose-400 hover:bg-ink-800 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-rose-500" title="Keluar">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span class="sr-only">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Bilah Atas Mobile (Header Mobile) -->
        <header class="md:hidden flex items-center justify-between bg-ink-950 text-white px-4 py-3.5 sticky top-0 z-30 border-b border-ink-900">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-[8px] bg-brand-600 flex items-center justify-center text-white shadow-xs">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </span>
                <span class="text-lg font-bold tracking-tight text-white">Ruang<span class="text-brand-200">Kita</span></span>
            </a>

            <div class="flex items-center gap-2">
                @if(auth()->user()->isAdmin())
                    @php $pendingCount = \App\Models\Booking::where('status', 'pending')->count(); @endphp
                    @if($pendingCount > 0)
                        <span class="rounded-full bg-amber-400 px-2 py-0.5 text-xs font-bold text-ink-950">
                            {{ $pendingCount }}
                        </span>
                    @endif
                @endif
                <button type="button" id="mobile-menu-open-btn" class="p-2 rounded-lg text-ink-300 hover:text-white hover:bg-ink-900 focus:outline-none focus:ring-2 focus:ring-brand-500" aria-label="Buka menu navigasi" aria-expanded="false">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </header>

        <!-- Menu Geser Mobile (Drawer Samping) -->
        <div id="mobile-menu-drawer" class="fixed inset-0 z-50 hidden transition-opacity duration-300" role="dialog" aria-modal="true" aria-label="Menu Samping">
            <!-- Backdrop gelap -->
            <div id="mobile-menu-backdrop" class="fixed inset-0 bg-ink-950/70 backdrop-blur-xs transition-opacity"></div>

            <!-- Drawer Container -->
            <div class="fixed inset-y-0 left-0 w-72 max-w-[85vw] bg-ink-950 text-ink-300 p-6 flex flex-col justify-between shadow-2xl z-10 overflow-y-auto transform transition-transform duration-300">
                <div>
                    <!-- Header Drawer -->
                    <div class="flex items-center justify-between pb-6 border-b border-ink-900">
                        <div class="flex items-center gap-2.5">
                            <span class="w-9 h-9 rounded-[10px] bg-brand-600 flex items-center justify-center text-white">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </span>
                            <span class="text-xl font-bold tracking-tight text-white">Ruang<span class="text-brand-200">Kita</span></span>
                        </div>
                        <button type="button" id="mobile-menu-close-btn" class="p-2 rounded-lg text-ink-400 hover:text-white hover:bg-ink-900 focus:outline-none focus:ring-2 focus:ring-brand-500" aria-label="Tutup menu navigasi">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Navigasi Menu Mobile -->
                    <nav class="mt-6 space-y-1.5" aria-label="Menu Mobile">
                        <a class="nav {{ request()->routeIs('dashboard') ? 'nav-active' : '' }}" href="{{ route('dashboard') }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span>Beranda</span>
                        </a>
                        <a class="nav {{ request()->routeIs('rooms.index', 'rooms.show') ? 'nav-active' : '' }}" href="{{ route('rooms.index') }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span>Daftar Ruangan</span>
                        </a>
                        <a class="nav {{ request()->routeIs('availability', 'calendar') ? 'nav-active' : '' }}" href="{{ route('availability') }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Cek Ketersediaan</span>
                        </a>
                        <a class="nav {{ request()->routeIs('bookings.*') && !request()->routeIs('admin.bookings.*') ? 'nav-active' : '' }}" href="{{ route('bookings.index') }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            <span>Booking Saya</span>
                        </a>

                        @if(auth()->user()->isAdmin())
                            <div class="pt-6 pb-2">
                                <p class="px-3.5 text-xs font-bold uppercase tracking-wider text-ink-500">Administrasi</p>
                            </div>
                            <a class="nav {{ request()->routeIs('admin.rooms.*') ? 'nav-active' : '' }}" href="{{ route('admin.rooms.index') }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                </svg>
                                <span>Kelola Ruangan</span>
                            </a>
                            <a class="nav justify-between {{ request()->routeIs('admin.bookings.*') ? 'nav-active' : '' }}" href="{{ route('admin.bookings.index') }}">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Persetujuan</span>
                                </div>
                                @if(isset($pendingCount) && $pendingCount > 0)
                                    <span class="rounded-full bg-amber-400 px-2 py-0.5 text-xs font-bold text-ink-950">
                                        {{ $pendingCount }}
                                    </span>
                                @endif
                            </a>
                            <a class="nav {{ request()->routeIs('admin.reports.*') ? 'nav-active' : '' }}" href="{{ route('admin.reports.index') }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                <span>Laporan</span>
                            </a>
                        @endif
                    </nav>
                </div>

                <!-- Profil Pengguna Mobile Drawer -->
                <div class="pt-6 border-t border-ink-900 mt-6">
                    <div class="flex items-center justify-between gap-3 p-2 rounded-[12px] bg-ink-900/60">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-[10px] bg-brand-800 text-brand-100 font-bold flex items-center justify-center shrink-0 border border-brand-700">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-ink-400 truncate">{{ auth()->user()->department ?: (auth()->user()->isAdmin() ? 'Administrator' : 'Karyawan') }}</p>
                            </div>
                        </div>
                        <form method="post" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="p-2 text-ink-400 hover:text-rose-400 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-rose-500" title="Keluar">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span class="sr-only">Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Konten Halaman Utama -->
        <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
            <!-- Header Atas Desktop -->
            <header class="hidden md:flex items-center justify-between border-b border-ink-200 bg-white px-8 py-4">
                <div class="flex items-center gap-3 text-sm text-ink-500">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-ink-100 text-xs font-semibold text-ink-700">
                        <span class="w-2 h-2 rounded-full bg-brand-600"></span>
                        {{ auth()->user()->department ?: (auth()->user()->isAdmin() ? 'Administrasi Ruang' : 'Divisi Kerja') }}
                    </span>
                    <span class="text-ink-400">·</span>
                    <span>{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ route('bookings.create') }}" class="btn-primary text-xs py-2 px-3.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Booking Ruangan</span>
                    </a>
                </div>
            </header>

            <!-- Bagian Isi / Body Halaman -->
            <section class="flex-1 p-5 md:p-8 lg:p-10 max-w-7xl w-full mx-auto relative">
                <!-- Toast Notifikasi Sukses Mengambang -->
                @if(session('success'))
                    <div id="toast-success" class="fixed top-5 right-5 z-50 flex items-center gap-3 rounded-[12px] bg-brand-800 text-white px-5 py-3.5 shadow-lg border border-brand-700 transition-all duration-300" role="alert">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-brand-600 text-white shrink-0">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                        <button type="button" id="toast-close-btn" class="ml-2 text-brand-200 hover:text-white p-1 rounded-md focus:outline-none" aria-label="Tutup notifikasi">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif

                <!-- Pesan Error Global jika ada -->
                @if($errors->any())
                    <div class="mb-6 rounded-[12px] bg-rose-50 border border-rose-200 p-4 text-sm text-rose-800" role="alert">
                        <div class="flex items-center gap-2 font-semibold text-rose-900 mb-1">
                            <svg class="w-5 h-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Terjadi kendala pada input Anda:</span>
                        </div>
                        <ul class="list-disc list-inside space-y-1 pl-6">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{ $slot }}
            </section>
        </main>
    </div>
</body>
</html>
