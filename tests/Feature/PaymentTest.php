<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Booking;
use App\Models\Transaction;
use App\Services\MidtransService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Mockery;

class PaymentTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->booking = Booking::factory()->create([
            'user_id' => $this->user->id,
            'total_price' => 50000,
            'payment_status' => 'unpaid'
        ]);
    }

    public function test_can_create_snap_token()
    {
        $this->actingAs($this->user);

        // Mock Midtrans response
        $mockResponse = [
            'success' => true,
            'snap_token' => 'fake-snap-token',
            'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/fake-token'
        ];

        $mockService = Mockery::mock(MidtransService::class);
        $mockService->shouldReceive('createSnapToken')
                   ->once()
                   ->with($this->booking)
                   ->andReturn($mockResponse);

        $this->app->instance(MidtransService::class, $mockService);

        $response = $this->post(route('payment.create'), [
            'booking_id' => $this->booking->id
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'snap_token',
            'redirect_url'
        ]);
    }

    public function test_can_handle_payment_notification()
    {
        $transaction = Transaction::factory()->create([
            'booking_id' => $this->booking->id,
            'order_id' => 'BKG-' . $this->booking->id . '-' . time(),
            'status' => 'pending'
        ]);

        $notification = [
            'order_id' => $transaction->order_id,
            'status_code' => '200',
            'gross_amount' => '50000.00',
            'signature_key' => hash('sha512', $transaction->order_id . '200' . '50000.00' . config('midtrans.server_key')),
            'transaction_status' => 'settlement',
            'payment_type' => 'bank_transfer',
            'transaction_id' => 'test-transaction-id'
        ];

        $response = $this->post('/api/midtrans/notification', $notification);

        $response->assertStatus(200);
        
        $transaction->refresh();
        $this->assertEquals('paid', $transaction->status);
        
        $this->booking->refresh();
        $this->assertEquals('paid', $this->booking->payment_status);
    }

    public function test_invalid_signature_rejected()
    {
        $transaction = Transaction::factory()->create([
            'booking_id' => $this->booking->id,
            'order_id' => 'BKG-' . $this->booking->id . '-' . time(),
            'status' => 'pending'
        ]);

        $notification = [
            'order_id' => $transaction->order_id,
            'status_code' => '200',
            'gross_amount' => '50000.00',
            'signature_key' => 'invalid-signature',
            'transaction_status' => 'settlement',
            'payment_type' => 'bank_transfer'
        ];

        $response = $this->post('/api/midtrans/notification', $notification);

        $response->assertStatus(400);
        
        $transaction->refresh();
        $this->assertEquals('pending', $transaction->status);
    }

    public function test_payment_status_updates_booking()
    {
        $transaction = Transaction::factory()->create([
            'booking_id' => $this->booking->id,
            'status' => 'pending'
        ]);

        // Test successful payment
        $transaction->update(['status' => 'paid']);
        $this->booking->refresh();
        
        // Booking should be updated
        $this->assertEquals('paid', $this->booking->payment_status);
    }

    public function test_expired_payment_cancels_booking()
    {
        $transaction = Transaction::factory()->create([
            'booking_id' => $this->booking->id,
            'status' => 'pending',
            'created_at' => now()->subHours(25) // Expired
        ]);

        $response = $this->post('/api/payment/check-expired');

        $response->assertStatus(200);
        
        $transaction->refresh();
        $this->booking->refresh();
        
        $this->assertEquals('failed', $transaction->status);
        $this->assertEquals('failed', $this->booking->payment_status);
    }

    public function test_payment_amount_validation()
    {
        $this->actingAs($this->user);

        // Test minimum amount
        $smallBooking = Booking::factory()->create([
            'user_id' => $this->user->id,
            'total_price' => 5000 // Below minimum
        ]);

        $response = $this->post(route('payment.create'), [
            'booking_id' => $smallBooking->id
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['amount']);
    }

    public function test_duplicate_payment_prevention()
    {
        $this->actingAs($this->user);

        // Create existing transaction
        Transaction::factory()->create([
            'booking_id' => $this->booking->id,
            'status' => 'paid'
        ]);

        $response = $this->post(route('payment.create'), [
            'booking_id' => $this->booking->id
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Booking sudah dibayar'
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}