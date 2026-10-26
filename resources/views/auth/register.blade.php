<x-layouts.guest>
    <div class="card bg-white p-7 sm:p-9 border border-ink-200 rounded-[16px] shadow-xs">
        <div class="mb-6">
            <h2 class="text-2xl font-bold tracking-tight text-ink-950">Daftar Akun Baru</h2>
            <p class="mt-1 text-sm text-ink-500">Lengkapi data diri Anda untuk mulai memesan ruang pertemuan.</p>
        </div>

        <form method="post" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div class="auth-field-group">
                <label for="name" class="auth-label">Nama Lengkap</label>
                <input 
                    id="name" 
                    class="field @error('name') border-rose-500 focus:border-rose-500 focus:ring-rose-500/20 @enderror" 
                    name="name" 
                    value="{{ old('name') }}" 
                    autocomplete="name" 
                    placeholder="Contoh: Budi Santoso"
                    required 
                    autofocus
                >
                @error('name')
                    <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <div class="auth-field-group">
                <label for="email" class="auth-label">Alamat Email Kantor</label>
                <input 
                    id="email" 
                    class="field @error('email') border-rose-500 focus:border-rose-500 focus:ring-rose-500/20 @enderror" 
                    name="email" 
                    value="{{ old('email') }}" 
                    type="email" 
                    autocomplete="email" 
                    placeholder="nama@perusahaan.com"
                    required
                >
                @error('email')
                    <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <div class="auth-field-group">
                <label for="department" class="auth-label">Departemen / Divisi</label>
                <input 
                    id="department" 
                    class="field @error('department') border-rose-500 focus:border-rose-500 focus:ring-rose-500/20 @enderror" 
                    name="department" 
                    value="{{ old('department') }}" 
                    autocomplete="organization" 
                    placeholder="Contoh: Teknologi Informasi / Marketing"
                >
                @error('department')
                    <p class="mt-1.5 text-xs font-medium text-rose-600 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <x-auth.password-field name="password" label="Kata Sandi" />
            <x-auth.password-field name="password_confirmation" label="Konfirmasi Kata Sandi" />

            <button type="submit" class="btn-primary w-full mt-4">
                <span>Daftar Akun</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>

        <div class="mt-7 pt-6 border-t border-ink-100 text-center text-sm text-ink-500">
            Sudah memiliki akun? 
            <a href="{{ route('login') }}" class="font-semibold text-brand-600 hover:text-brand-700 underline underline-offset-4 focus:outline-none focus:ring-2 focus:ring-brand-500 rounded">
                Masuk di sini
            </a>
        </div>
    </div>
</x-layouts.guest>
