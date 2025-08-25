<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Mockery;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    protected $notificationService;

    protected $user;

    protected $booking;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'no_telepon' => '+6281234567890',
        ]);

        $this->booking = Booking::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $this->notificationService = new NotificationService;
    }

    public function test_booking_confirmation_email_sent()
    {
        Mail::fake();

        $result = $this->notificationService->sendBookingConfirmation($this->booking);

        $this->assertTrue($result);

        Mail::assertSent(\Illuminate\Mail\Mailable::class, function ($mail) {
            return $mail->hasTo($this->user->email) &&
                   $mail->hasSubject('Konfirmasi Booking - '.config('app.name'));
        });
    }

    public function test_booking_confirmation_sms_sent()
    {
        Http::fake([
            config('notifications.local_sms_api') => Http::response(['status' => 'success'], 200),
        ]);

        $result = $this->notificationService->sendBookingConfirmation($this->booking);

        $this->assertTrue($result);

        Http::assertSent(function ($request) {
            return $request->url() === config('notifications.local_sms_api') &&
                   $request['phone'] === $this->user->no_telepon &&
                   str_contains($request['message'], 'Booking dikonfirmasi');
        });
    }

    public function test_payment_confirmation_notification()
    {
        Mail::fake();
        Http::fake([
            config('notifications.local_sms_api') => Http::response(['status' => 'success'], 200),
        ]);

        $transaction = \App\Models\Transaction::factory()->create([
            'booking_id' => $this->booking->id,
            'user_id' => $this->user->id,
        ]);

        $result = $this->notificationService->sendPaymentConfirmation($transaction);

        $this->assertTrue($result);

        Mail::assertSent(\Illuminate\Mail\Mailable::class, function ($mail) {
            return $mail->hasTo($this->user->email) &&
                   str_contains($mail->subject, 'Konfirmasi Pembayaran');
        });
    }

    public function test_booking_reminder_sent()
    {
        Mail::fake();
        Http::fake([
            config('notifications.local_sms_api') => Http::response(['status' => 'success'], 200),
        ]);

        // Set booking time to 1 hour from now
        $this->booking->update([
            'date_time' => now()->addHour(),
        ]);

        $result = $this->notificationService->sendBookingReminder($this->booking);

        $this->assertTrue($result);

        Mail::assertSent(\Illuminate\Mail\Mailable::class, function ($mail) {
            return $mail->hasTo($this->user->email) &&
                   str_contains($mail->subject, 'Reminder Booking');
        });

        Http::assertSent(function ($request) {
            return str_contains($request['message'], 'Reminder: Booking Anda 1 jam lagi');
        });
    }

    public function test_status_update_notification()
    {
        Mail::fake();
        Http::fake([
            config('notifications.local_sms_api') => Http::response(['status' => 'success'], 200),
        ]);

        $result = $this->notificationService->sendBookingStatusUpdate(
            $this->booking,
            'pending',
            'confirmed'
        );

        $this->assertTrue($result);

        Mail::assertSent(\Illuminate\Mail\Mailable::class, function ($mail) {
            return $mail->hasTo($this->user->email) &&
                   str_contains($mail->subject, 'Update Status Booking');
        });

        Http::assertSent(function ($request) {
            return str_contains($request['message'], 'dikonfirmasi');
        });
    }

    public function test_notification_handles_email_failure()
    {
        Mail::fake();
        Http::fake([
            config('notifications.local_sms_api') => Http::response(['status' => 'success'], 200),
        ]);

        // Simulate email failure
        Mail::shouldReceive('send')->andThrow(new \Exception('Email service down'));

        $result = $this->notificationService->sendBookingConfirmation($this->booking);

        // Should still return true if SMS succeeds
        $this->assertTrue($result);
    }

    public function test_notification_handles_sms_failure()
    {
        Mail::fake();
        Http::fake([
            config('notifications.local_sms_api') => Http::response(['error' => 'SMS failed'], 500),
        ]);

        $result = $this->notificationService->sendBookingConfirmation($this->booking);

        // Should still return true if email succeeds
        $this->assertTrue($result);

        Mail::assertSent(\Illuminate\Mail\Mailable::class);
    }

    public function test_notification_queue_job_processing()
    {
        Queue::fake();

        // Test that notification jobs are queued
        dispatch(new \App\Jobs\SendBookingConfirmation($this->booking));

        Queue::assertPushed(\App\Jobs\SendBookingConfirmation::class, function ($job) {
            return $job->booking->id === $this->booking->id;
        });
    }

    public function test_bulk_notification_sending()
    {
        Mail::fake();
        Http::fake([
            config('notifications.local_sms_api') => Http::response(['status' => 'success'], 200),
        ]);

        // Create multiple bookings for today
        $bookings = Booking::factory()->count(5)->create([
            'date_time' => now()->addHour(),
        ]);

        foreach ($bookings as $booking) {
            $this->notificationService->sendBookingReminder($booking);
        }

        Mail::assertSent(\Illuminate\Mail\Mailable::class, 5);
        Http::assertSentCount(5);
    }

    public function test_notification_respects_user_preferences()
    {
        // Create user with email notifications disabled
        $user = User::factory()->create([
            'email_notifications' => false,
            'sms_notifications' => true,
        ]);

        $booking = Booking::factory()->create(['user_id' => $user->id]);

        Mail::fake();
        Http::fake([
            config('notifications.local_sms_api') => Http::response(['status' => 'success'], 200),
        ]);

        $this->notificationService->sendBookingConfirmation($booking);

        // Only SMS should be sent
        Mail::assertNothingSent();
        Http::assertSentCount(1);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
