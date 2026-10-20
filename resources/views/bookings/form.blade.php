<x-layouts.app>
    <h1 class="text-2xl font-bold">Booking Meeting Room</h1>
    <p class="text-slate-500">Pastikan waktu dan jumlah peserta sudah sesuai.</p>

    <form method="post" action="{{ route('bookings.store') }}" class="card mt-6 max-w-2xl">
        @csrf
        <label for="meeting_room_id">Ruangan</label>
        <select id="meeting_room_id" class="field" name="meeting_room_id" required @disabled($rooms->isEmpty())>
            <option value="">{{ $rooms->isEmpty() ? 'Belum ada ruangan aktif' : 'Pilih ruangan' }}</option>
            @foreach ($rooms as $room)
                <option value="{{ $room->id }}" @selected((string) old('meeting_room_id', request('room')) === (string) $room->id)>
                    {{ $room->name }} — kapasitas {{ $room->capacity }} orang
                </option>
            @endforeach
        </select>
        @if ($rooms->isEmpty())
            <p class="mt-2 text-sm text-amber-700">Belum ada ruangan aktif. Hubungi administrator untuk menambahkan ruangan.</p>
        @endif

        <label class="mt-5 block">Judul meeting<input class="field" name="title" value="{{ old('title') }}" required></label>
        <label class="mt-5 block">Agenda / deskripsi<textarea class="field" name="description">{{ old('description') }}</textarea></label>
        <div class="mt-5 grid gap-4 sm:grid-cols-2"><label>Mulai<input class="field" type="datetime-local" name="start_at" value="{{ old('start_at') }}" required></label><label>Selesai<input class="field" type="datetime-local" name="end_at" value="{{ old('end_at') }}" required></label></div>
        <label class="mt-5 block">Jumlah peserta<input class="field" type="number" min="1" name="participant_count" value="{{ old('participant_count', 1) }}" required></label>
        <button class="btn-primary mt-6" @disabled($rooms->isEmpty())>Kirim permintaan</button>
    </form>
</x-layouts.app>
