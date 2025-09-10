@component('mail::message')
    {{-- Greeting --}}
    # Reset Password - WOX Barbershop

    Halo {{ $user->name ?? 'Customer' }},

    Kami menerima permintaan untuk reset password akun Anda di **WOX Barbershop**.

    Klik tombol di bawah ini untuk membuat password baru:

    @component('mail::button', ['url' => $resetUrl, 'color' => 'primary'])
        Reset Password
    @endcomponent

    **Link ini akan kedaluwarsa dalam {{ config('auth.passwords.' . config('auth.defaults.passwords') . '.expire') }} menit.**

    Jika Anda tidak meminta reset password, tidak perlu melakukan tindakan apapun. Akun Anda tetap aman.

    ---

    ## Informasi Kontak
    - **Telepon:** (021) 1234-5678
    - **Email:** info@woxsbarbershop.com
    - **Alamat:** Jl. Barber No. 123, Jakarta Selatan

    Terima kasih,<br>
    **Tim WOX Barbershop**

    @component('mail::subcopy')
        Jika Anda mengalami masalah dengan tombol "Reset Password", salin dan tempel URL berikut ke browser Anda:
        [{{ $resetUrl }}]({{ $resetUrl }})
    @endcomponent
@endcomponent
