<x-layouts.app>
    <div class="max-w-3xl">
        <div class="mb-6">
            <a href="{{ route('admin.rooms.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-ink-600 hover:text-brand-600 transition-colors mb-3 group">
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Kembali ke Kelola Ruangan</span>
            </a>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-ink-950">
                {{ $room->exists ? 'Ubah Data Ruangan' : 'Tambah Ruang Meeting' }}
            </h1>
            <p class="text-sm text-ink-500 mt-1">
                Lengkapi spesifikasi ruangan, jadwal operasional, serta kelengkapan fasilitas.
            </p>
        </div>

        <form method="post" enctype="multipart/form-data" action="{{ $room->exists ? route('admin.rooms.update', $room) : route('admin.rooms.store') }}" class="space-y-6">
            @csrf
            @if($room->exists)
                @method('PUT')
            @endif

            <!-- 1. Informasi Dasar -->
            <div class="card">
                <div class="flex items-center gap-2 pb-4 mb-4 border-b border-ink-100">
                    <span class="w-7 h-7 rounded-full bg-brand-50 text-brand-700 text-xs font-bold flex items-center justify-center border border-brand-200">1</span>
                    <h2 class="text-base font-bold text-ink-950">Informasi Dasar</h2>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="name" class="auth-label">Nama Ruangan <span class="text-rose-500">*</span></label>
                        <input id="name" class="field @error('name') border-rose-500 @enderror" name="name" value="{{ old('name', $room->name) }}" placeholder="Contoh: Ruang Meeting A" required>
                        @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="code" class="auth-label">Kode Ruangan <span class="text-rose-500">*</span></label>
                        <input id="code" class="field @error('code') border-rose-500 @enderror uppercase" name="code" value="{{ old('code', $room->code) }}" placeholder="Contoh: MR-A" required>
                        @error('code')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="location" class="auth-label">Lokasi / Lantai <span class="text-rose-500">*</span></label>
                        <input id="location" class="field @error('location') border-rose-500 @enderror" name="location" value="{{ old('location', $room->location) }}" placeholder="Contoh: Gedung Utama, Lantai 2" required>
                        @error('location')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="capacity" class="auth-label">Kapasitas (Orang) <span class="text-rose-500">*</span></label>
                        <input id="capacity" type="number" min="1" class="field @error('capacity') border-rose-500 @enderror" name="capacity" value="{{ old('capacity', $room->capacity) }}" placeholder="10" required>
                        @error('capacity')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label for="description" class="auth-label">Deskripsi Ruangan</label>
                    <textarea id="description" class="field @error('description') border-rose-500 @enderror" name="description" placeholder="Jelaskan peruntukan ruangan, suasana, dan panduan penggunaan...">{{ old('description', $room->description) }}</textarea>
                    @error('description')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- 2. Jam Operasional -->
            <div class="card">
                <div class="flex items-center gap-2 pb-4 mb-4 border-b border-ink-100">
                    <span class="w-7 h-7 rounded-full bg-brand-50 text-brand-700 text-xs font-bold flex items-center justify-center border border-brand-200">2</span>
                    <h2 class="text-base font-bold text-ink-950">Jam Operasional</h2>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="opens_at" class="auth-label">Jam Buka</label>
                        <input id="opens_at" class="field @error('opens_at') border-rose-500 @enderror" type="time" name="opens_at" value="{{ old('opens_at', $room->opens_at ? substr($room->opens_at, 0, 5) : '08:00') }}">
                        @error('opens_at')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="closes_at" class="auth-label">Jam Tutup</label>
                        <input id="closes_at" class="field @error('closes_at') border-rose-500 @enderror" type="time" name="closes_at" value="{{ old('closes_at', $room->closes_at ? substr($room->closes_at, 0, 5) : '18:00') }}">
                        @error('closes_at')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- 3. Fasilitas Ruangan -->
            <div class="card">
                <div class="flex items-center gap-2 pb-4 mb-4 border-b border-ink-100">
                    <span class="w-7 h-7 rounded-full bg-brand-50 text-brand-700 text-xs font-bold flex items-center justify-center border border-brand-200">3</span>
                    <h2 class="text-base font-bold text-ink-950">Fasilitas Ruangan</h2>
                </div>
                <p class="text-xs text-ink-500 mb-4">Pilih fasilitas yang tersedia dan dapat dipakai pada ruangan ini.</p>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @php
                        $selectedFacilities = old('facilities', $room->exists ? $room->facilities->pluck('id')->all() : []);
                    @endphp
                    @foreach($facilities as $f)
                        <label class="flex items-center gap-3 p-3 rounded-[12px] border border-ink-200 hover:border-brand-600 hover:bg-brand-50/30 cursor-pointer transition-colors select-none text-sm font-medium text-ink-800">
                            <input 
                                type="checkbox" 
                                name="facilities[]" 
                                value="{{ $f->id }}" 
                                class="w-4 h-4 rounded-[6px] border-ink-300 text-brand-600 focus:ring-brand-500"
                                @checked(in_array($f->id, $selectedFacilities))
                            >
                            <span>{{ $f->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('facilities')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>

            <!-- 4. Foto & Status Ruangan -->
            <div class="card">
                <div class="flex items-center gap-2 pb-4 mb-4 border-b border-ink-100">
                    <span class="w-7 h-7 rounded-full bg-brand-50 text-brand-700 text-xs font-bold flex items-center justify-center border border-brand-200">4</span>
                    <h2 class="text-base font-bold text-ink-950">Foto & Status Ruangan</h2>
                </div>

                <div class="space-y-5">
                    <div>
                        <label for="image" class="auth-label">Foto Ruangan</label>
                        @if($room->image_path)
                            <div class="mb-3 flex items-center gap-4 p-3 rounded-[12px] bg-ink-50 border border-ink-200">
                                <img src="{{ asset('storage/' . $room->image_path) }}" alt="{{ $room->name }}" class="w-20 h-14 rounded-lg object-cover">
                                <div class="text-xs text-ink-500">
                                    <p class="font-semibold text-ink-900">Foto saat ini terpasang</p>
                                    <p>Unggah berkas baru di bawah jika ingin mengganti.</p>
                                </div>
                            </div>
                        @endif
                        <input id="image" class="field py-2" type="file" name="image" accept="image/*">
                        <p class="mt-1 text-xs text-ink-400">Format didukung: JPG, PNG, WebP (maksimal 2MB).</p>
                        @error('image')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="pt-2">
                        <label class="flex items-start gap-3 p-3.5 rounded-[12px] bg-ink-50 border border-ink-200 cursor-pointer select-none">
                            <input 
                                type="checkbox" 
                                name="is_active" 
                                value="1" 
                                class="w-4 h-4 mt-0.5 rounded-[6px] border-ink-300 text-brand-600 focus:ring-brand-500"
                                @checked(old('is_active', $room->exists ? $room->is_active : true))
                            >
                            <div>
                                <span class="text-sm font-bold text-ink-950 block">Aktifkan Ruangan</span>
                                <span class="text-xs text-ink-500">Ruangan aktif akan muncul dalam pencarian dan dapat dipesan oleh karyawan.</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi Simpan -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.rooms.index') }}" class="btn-secondary text-sm">
                    Batal
                </a>
                <button type="submit" class="btn-primary text-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    <span>Simpan Ruangan</span>
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
