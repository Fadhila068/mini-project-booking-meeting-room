<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\MeetingRoom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_cannot_access_admin_area(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'employee']))->get('/admin/rooms')->assertForbidden();
    }

    public function test_booking_form_lists_only_active_rooms(): void
    {
        $user = User::factory()->create();
        $active = $this->room('Ruang Aktif', 'ACTIVE');
        $this->room('Ruang Nonaktif', 'OFF', false);

        $this->actingAs($user)->get('/bookings/create')->assertOk()->assertSee('Ruang Aktif')->assertSee('value="'.$active->id.'"', false)->assertDontSee('Ruang Nonaktif');
    }

    public function test_admin_can_edit_pending_booking_without_a_decision(): void
    {
        [$admin, $booking, $room] = $this->pendingBooking();

        $this->actingAs($admin)->get(route('admin.bookings.edit', $booking))->assertOk()->assertSee('Keputusan Booking');
        $this->actingAs($admin)->put(route('admin.bookings.update', $booking), $this->payload($room, ['title' => 'Judul Baru']))->assertRedirect(route('admin.bookings.index'));

        $booking->refresh();
        $this->assertSame('Judul Baru', $booking->title);
        $this->assertSame('pending', $booking->status);
        $this->assertSame('KEEP-ME', $booking->booking_code);
    }

    public function test_admin_can_approve_a_pending_booking_through_edit_form(): void
    {
        [$admin, $booking, $room] = $this->pendingBooking();

        $this->actingAs($admin)->put(route('admin.bookings.update', $booking), $this->payload($room, ['decision' => 'approve']))->assertRedirect();

        $booking->refresh();
        $this->assertSame('approved', $booking->status);
        $this->assertSame($admin->id, $booking->approved_by);
        $this->assertNotNull($booking->approved_at);
    }

    public function test_rejection_requires_a_reason_and_saves_it(): void
    {
        [$admin, $booking, $room] = $this->pendingBooking();
        $this->actingAs($admin)->put(route('admin.bookings.update', $booking), $this->payload($room, ['decision' => 'reject']))->assertSessionHasErrors('rejection_reason');
        $this->assertSame('pending', $booking->fresh()->status);

        $this->actingAs($admin)->put(route('admin.bookings.update', $booking), $this->payload($room, ['decision' => 'reject', 'rejection_reason' => 'Jadwal tim sudah penuh.']))->assertRedirect();
        $booking->refresh();
        $this->assertSame('rejected', $booking->status);
        $this->assertSame('Jadwal tim sudah penuh.', $booking->rejection_reason);
    }

    public function test_rejected_booking_is_read_only_for_admin(): void
    {
        [$admin, $booking, $room] = $this->pendingBooking();
        $booking->update(['status' => 'rejected']);
        $this->actingAs($admin)->put(route('admin.bookings.update', $booking), $this->payload($room))->assertSessionHasErrors('status');
    }

    public function test_availability_uses_booking_intervals_and_ignores_rejected_bookings(): void
    {
        $user = User::factory()->create();
        $available = $this->room('Ruang Kosong', 'EMPTY');
        $approved = $this->room('Ruang Terpesan', 'BOOKED');
        $pending = $this->room('Ruang Menunggu', 'PENDING');
        $rejected = $this->room('Ruang Ditolak', 'REJECTED');
        $day = now()->addDays(4)->startOfDay();

        foreach ([[$approved, 'approved'], [$pending, 'pending'], [$rejected, 'rejected']] as [$room, $status]) {
            Booking::create(['booking_code' => 'AVAIL-'.$room->id, 'user_id' => $user->id, 'meeting_room_id' => $room->id, 'title' => 'Meeting', 'start_at' => $day->copy()->setTime(10, 0), 'end_at' => $day->copy()->setTime(11, 0), 'participant_count' => 2, 'status' => $status]);
        }

        $response = $this->actingAs($user)->get(route('availability', ['date' => $day->toDateString(), 'start_time' => '10:00', 'end_time' => '11:00']));
        $response->assertOk()->assertSeeInOrder(['Ruang Kosong', 'Tersedia'])->assertSeeInOrder(['Ruang Terpesan', 'Sudah dipesan'])->assertSeeInOrder(['Ruang Menunggu', 'Menunggu persetujuan'])->assertSeeInOrder(['Ruang Ditolak', 'Tersedia']);
        $this->assertNotNull($available);
    }

    public function test_availability_filters_by_a_valid_database_room_id(): void
    {
        $user = User::factory()->create();
        $selected = $this->room('Ruang Dipilih', 'SELECTED');
        $this->room('Ruang Lain', 'OTHER');

        $this->actingAs($user)->get(route('availability', ['date' => now()->toDateString(), 'room_id' => $selected->id]))
            ->assertOk()
            ->assertSee('Ruang Dipilih')
            ->assertDontSee('Ruang Lain</h2>', false);
    }

    private function pendingBooking(): array
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $owner = User::factory()->create();
        $room = $this->room('Ruang A', 'RA');
        $booking = Booking::create(['booking_code' => 'KEEP-ME', 'user_id' => $owner->id, 'meeting_room_id' => $room->id, 'title' => 'Judul Awal', 'start_at' => now()->addDays(2)->setTime(10, 0), 'end_at' => now()->addDays(2)->setTime(11, 0), 'participant_count' => 2, 'status' => 'pending']);

        return [$admin, $booking, $room];
    }

    private function room(string $name, string $code, bool $active = true): MeetingRoom
    {
        return MeetingRoom::create(['name' => $name, 'code' => $code, 'location' => 'Lantai 1', 'capacity' => 10, 'is_active' => $active]);
    }

    private function payload(MeetingRoom $room, array $overrides = []): array
    {
        return array_merge(['meeting_room_id' => $room->id, 'title' => 'Judul Awal', 'description' => 'Agenda', 'start_at' => now()->addDays(2)->setTime(12, 0)->format('Y-m-d H:i:s'), 'end_at' => now()->addDays(2)->setTime(13, 0)->format('Y-m-d H:i:s'), 'participant_count' => 2], $overrides);
    }
}
