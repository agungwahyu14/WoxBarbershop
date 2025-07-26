<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Service;
use App\Models\Hairstyle;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;

class BookingTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'pegawai']);
        Role::create(['name' => 'pelanggan']);
        
        // Create test data
        $this->service = Service::factory()->create([
            'name' => 'Haircut Premium',
            'price' => 50000,
            'duration' => 60,
            'is_active' => true
        ]);
        
        $this->hairstyle = Hairstyle::factory()->create([
            'name' => 'Modern Cut',
            'is_active' => true
        ]);
        
        $this->user = User::factory()->create();
        $this->user->assignRole('pelanggan');
    }

    public function test_authenticated_user_can_create_booking()
    {
        $this->actingAs($this->user);

        $bookingData = [
            'name' => 'John Doe',
            'service_id' => $this->service->id,
            'hairstyle_id' => $this->hairstyle->id,
            'date_time' => Carbon::now()->addDays(1)->setHour(14)->format('Y-m-d H:i:s'),
            'description' => 'Request for quick service'
        ];

        $response = $this->post(route('bookings.store'), $bookingData);

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'user_id' => $this->user->id,
            'name' => 'John Doe',
            'service_id' => $this->service->id,
            'hairstyle_id' => $this->hairstyle->id,
            'status' => 'pending'
        ]);
    }

    public function test_cannot_book_in_the_past()
    {
        $this->actingAs($this->user);

        $bookingData = [
            'name' => 'John Doe',
            'service_id' => $this->service->id,
            'hairstyle_id' => $this->hairstyle->id,
            'date_time' => Carbon::now()->subHours(1)->format('Y-m-d H:i:s'),
            'description' => 'Past booking attempt'
        ];

        $response = $this->post(route('bookings.store'), $bookingData);

        $response->assertSessionHasErrors(['date_time']);
    }

    public function test_cannot_book_on_sunday()
    {
        $this->actingAs($this->user);

        $nextSunday = Carbon::now()->next(Carbon::SUNDAY)->setHour(14);
        
        $bookingData = [
            'name' => 'John Doe',
            'service_id' => $this->service->id,
            'hairstyle_id' => $this->hairstyle->id,
            'date_time' => $nextSunday->format('Y-m-d H:i:s'),
            'description' => 'Sunday booking attempt'
        ];

        $response = $this->post(route('bookings.store'), $bookingData);

        $response->assertSessionHasErrors(['date_time']);
    }

    public function test_cannot_book_outside_business_hours()
    {
        $this->actingAs($this->user);

        $earlyMorning = Carbon::now()->addDays(1)->setHour(7); // Before 9 AM
        
        $bookingData = [
            'name' => 'John Doe',
            'service_id' => $this->service->id,
            'hairstyle_id' => $this->hairstyle->id,
            'date_time' => $earlyMorning->format('Y-m-d H:i:s'),
            'description' => 'Early booking attempt'
        ];

        $response = $this->post(route('bookings.store'), $bookingData);

        $response->assertSessionHasErrors(['date_time']);
    }

    public function test_user_can_view_their_bookings()
    {
        $this->actingAs($this->user);

        $booking = Booking::factory()->create([
            'user_id' => $this->user->id,
            'service_id' => $this->service->id,
            'hairstyle_id' => $this->hairstyle->id,
        ]);

        $response = $this->get(route('bookings.index'));

        $response->assertStatus(200);
        $response->assertSee($booking->name);
    }

    public function test_user_can_cancel_pending_booking()
    {
        $this->actingAs($this->user);

        $booking = Booking::factory()->create([
            'user_id' => $this->user->id,
            'service_id' => $this->service->id,
            'hairstyle_id' => $this->hairstyle->id,
            'status' => 'pending'
        ]);

        $response = $this->delete(route('bookings.destroy', $booking));

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'cancelled'
        ]);
    }

    public function test_guest_cannot_create_booking()
    {
        $bookingData = [
            'name' => 'John Doe',
            'service_id' => $this->service->id,
            'hairstyle_id' => $this->hairstyle->id,
            'date_time' => Carbon::now()->addDays(1)->setHour(14)->format('Y-m-d H:i:s'),
        ];

        $response = $this->post(route('bookings.store'), $bookingData);

        $response->assertRedirect(route('login'));
    }
}