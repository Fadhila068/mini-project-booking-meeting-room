<x-layouts.app>
    <h1 class="text-2xl font-bold">Persetujuan Booking</h1>
    <form class="mt-5"><select class="field max-w-xs" name="status" onchange="this.form.submit()"><option value="">Semua status</option>@foreach(['pending','approved','rejected','cancelled'] as $s)<option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>@endforeach</select></form>
    <div class="card mt-5 overflow-x-auto"><table><thead><tr><th>Pemohon</th><th>Ruang / waktu</th><th>Judul</th><th>Status</th><th>Tindakan</th></tr></thead><tbody>@forelse($bookings as $booking)<tr>
        <td>{{ $booking->user->name }}</td><td>{{ $booking->room->name }}<br><span class="text-xs text-slate-500">{{ $booking->start_at->format('d M H:i') }}–{{ $booking->end_at->format('H:i') }}</span></td><td>{{ $booking->title }}</td><td><span class="badge badge-{{ $booking->status }}">{{ $booking->status }}</span></td>
        <td><a class="inline-flex rounded-lg border border-blue-200 px-3 py-2 text-sm font-medium text-blue-700 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500" href="{{ route('admin.bookings.edit', $booking) }}">Edit</a></td>
    </tr>@empty<tr><td colspan="5" class="py-10 text-center text-slate-500">Belum ada booking.</td></tr>@endforelse</tbody></table></div>
    <div class="mt-5">{{ $bookings->links() }}</div>
</x-layouts.app>
