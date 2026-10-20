<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\MeetingRoom;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings = Booking::with(['room', 'user'])->when(! $request->user()->isAdmin(), fn ($query) => $query->where('user_id', $request->user()->id))->when($request->status, fn ($query, $status) => $query->where('status', $status))->latest('start_at')->paginate()->withQueryString();
        return view('bookings.index', compact('bookings'));
    }

    public function create()
    {
        return view('bookings.form', ['rooms' => MeetingRoom::query()->where('is_active', true)->orderBy('name')->get(['id', 'name', 'capacity'])]);
    }

    public function store(StoreBookingRequest $request, BookingService $service)
    {
        $service->create($request->validated(), $request->user());
        return redirect()->route('bookings.index')->with('success', 'Permintaan booking dikirim.');
    }

    public function cancel(Request $request, Booking $booking, BookingService $service)
    {
        abort_unless($request->user()->isAdmin() || $booking->user_id === $request->user()->id, 403);
        $service->cancel($booking, $request->user());
        return back()->with('success', 'Booking dibatalkan.');
    }

    public function availability(Request $request)
    {
        $filters = $request->validate([
            'date' => ['nullable', 'date'],
            'start_time' => ['nullable', 'date_format:H:i', 'required_with:end_time'],
            'end_time' => ['nullable', 'date_format:H:i', 'required_with:start_time', 'after:start_time'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'room_id' => ['nullable', 'integer', 'exists:meeting_rooms,id'],
            'room' => ['nullable', 'integer', 'exists:meeting_rooms,id'],
        ]);

        $date = Carbon::parse($filters['date'] ?? today()->toDateString());
        $start = isset($filters['start_time']) ? Carbon::parse($date->toDateString().' '.$filters['start_time']) : $date->copy()->startOfDay();
        $end = isset($filters['end_time']) ? Carbon::parse($date->toDateString().' '.$filters['end_time']) : $date->copy()->endOfDay();

        $rooms = MeetingRoom::query()
            ->with(['facilities', 'bookings' => fn ($query) => $query->whereIn('status', Booking::BLOCKING)->where('start_at', '<', $end)->where('end_at', '>', $start)->orderBy('start_at')])
            ->when($filters['room_id'] ?? null, fn ($query, $roomId) => $query->whereKey($roomId))
            ->when($filters['capacity'] ?? null, fn ($query, $capacity) => $query->where('capacity', '>=', $capacity))
            ->orderBy('name')
            ->get();

        $rooms->each(function (MeetingRoom $room) {
            $room->setAttribute('availability_status', ! $room->is_active ? 'unavailable' : ($room->bookings->contains('status', 'approved') ? 'booked' : ($room->bookings->contains('status', 'pending') ? 'pending' : 'available')));
        });

        $selectedRoom = null;
        if (isset($filters['room'])) {
            $selectedRoom = MeetingRoom::with(['facilities', 'bookings' => fn ($query) => $query->whereIn('status', Booking::BLOCKING)->where('start_at', '<', $date->copy()->endOfDay())->where('end_at', '>', $date->copy()->startOfDay())->orderBy('start_at')])->findOrFail($filters['room']);
        }

        $roomOptions = MeetingRoom::orderBy('name')->get(['id', 'name', 'capacity', 'is_active']);

        return view('availability.index', compact('rooms', 'roomOptions', 'selectedRoom', 'date', 'start', 'end', 'filters'));
    }
}
