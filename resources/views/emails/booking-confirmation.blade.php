<x-mail::message>
    # Booking Confirmed!

    Hi **{{ $user->name }}**,

    Thank you for booking with WOX Barbershop! Your appointment has been confirmed.

    ## Booking Details

    **Booking ID:** #{{ $booking->id }}
    **Date & Time:** {{ \Carbon\Carbon::parse($booking->date_time)->format('l, d F Y - H:i') }}
    **Service:** {{ $booking->service->name }}
    **Barber:** {{ $booking->barber->name }}
    **Status:** <span style="color: #10b981;">{{ ucfirst($booking->status) }}</span>

    <x-mail::panel>
        📍 **Location:** WOX Barbershop
        {{ config('app.url') }}
    </x-mail::panel>

    <x-mail::button :url="route('customer.bookings.show', $booking->id)" color="success">
        View Booking Details
    </x-mail::button>

    ## Important Notes
    - Please arrive 5 minutes before your appointment
    - If you need to reschedule, please do so at least 2 hours in advance
    - Payment can be made at the venue or online

    Need help? Contact us at **info@woxbarbershop.com**

    Thanks,<br>
    **WOX Barbershop Team**
</x-mail::message>
