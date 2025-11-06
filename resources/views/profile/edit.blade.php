@extends('layouts.app')

@section('content')
    <div class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Loyalty Info -->
            <div class="p-4 sm:p-8 bg-white shadow rounded-lg mt-8">
                <div class="max-w-xl">
                    <h2 class="text-lg font-bold mb-4">{{ __('profile.your_loyalty') }}</h2>

                    <p class="text-gray-600 mb-4 text-sm">
                        Program loyalitas ini memberikan penghargaan kepada pelanggan yang sering melakukan kunjungan.
                        Setiap kali Anda melakukan pemesanan dan menyelesaikan layanan, Anda akan memperoleh poin kunjungan.
                        Setelah mencapai jumlah poin tertentu, Anda berhak mendapatkan potongan harga atau layanan gratis.
                    </p>

                    @php
                        $points = Auth::user()->loyalty ? Auth::user()->loyalty->points : 0;
                    @endphp

                    <div class="rounded-lg bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-2">
                        <span class="font-semibold text-yellow-700">
                            {{ __('profile.visits') }}: {{ $points }} / 10
                        </span>
                    </div>
                </div>
            </div>


            <!-- Update Profile Information -->
            <div class="p-4 sm:p-8 bg-white shadow rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="p-4 sm:p-8 bg-white shadow rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account -->
            {{-- <div class="p-4 sm:p-8 bg-white shadow rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div> --}}
        </div>
    </div>
@endsection

@push('scripts')
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '{{ __('auth.success') }}',
                text: '{{ session('success') }}',
                confirmButtonColor: '#10B981',
                timer: 3000,
                timerProgressBar: true
            });
        </script>
    @endif
@endpush
