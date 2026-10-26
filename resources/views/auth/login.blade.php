<x-layouts.guest>
    <div class="card bg-white p-7 sm:p-9 border border-ink-200 rounded-[16px] shadow-xs">
        <div class="mb-6">
            <h2 class="text-2xl font-bold tracking-tight text-ink-950">Masuk ke RuangKita</h2>
            <p class="mt-1 text-sm text-ink-500">Gunakan akun kantor Anda untuk mengelola pemesanan ruangan.</p>
        </div>

        @if(session('error'))
            <div class="mb-5 rounded-[12px] bg-rose-50 border border-rose-200 p-3.5 text-sm font-medium text-rose-700 flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form method="post" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div class="auth-field-group">
                <label for="email" class="auth-label">Alamat Email</label>
                <input 
                    id="email" 
                    class="field @error('email') border-rose-500 focus:border-rose-500 focus:ring-rose-500/20 @enderror" 
                    name="email" 
                    value="{{ old('email') }}" 
                    type="email" 
                    autocomplete="email" 
                    placeholder="nama@perusahaan.com"
                    required 
                    autofocus
                >
                @error('email')
                    <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <x-auth.password-field name="password" label="Kata Sandi" />

            <div class="flex items-center justify-between pt-1">
                <label class="inline-flex items-center gap-2.5 text-sm text-ink-700 cursor-pointer select-none">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded-[6px] border-ink-300 text-brand-600 focus:ring-brand-500">
                    <span>Ingat saya di perangkat ini</span>
                </label>
            </div>

            <button type="submit" class="btn-primary w-full mt-2">
                <span>Masuk Sekarang</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>

        <div class="mt-7 pt-6 border-t border-ink-100 text-center text-sm text-ink-500">
            Belum memiliki akun? 
            <a href="{{ route('register') }}" class="font-semibold text-brand-600 hover:text-brand-700 underline underline-offset-4 focus:outline-none focus:ring-2 focus:ring-brand-500 rounded">
                Daftar sekarang
            </a>
        </div>
    </div>
</x-layouts.guest>
