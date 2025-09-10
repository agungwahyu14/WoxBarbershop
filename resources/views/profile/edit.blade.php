@extends('layouts.app')

@section('content')
    <div class="py-20">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Loyalty Info -->
            <div class="p-4 sm:p-8 bg-white shadow rounded-lg mt-8">
                <div class="max-w-xl">
                    <h2 class="text-lg font-bold mb-4">Kesetiaan Kamu</h2>
                    @php
                        $points = Auth::user()->loyalty ? Auth::user()->loyalty->points : 0;
                    @endphp
                    <div class=" rounded-lg bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-2">
                        <span class="font-semibold text-yellow-700">Kunjungan: {{ $points }} / 10</span>
                        @if ($points == 9)
                            <div class="text-yellow-800 mt-2">Satu kali lagi booking, kamu dapat gratis potong rambut!</div>
                        @elseif($points == 0 && Auth::user()->loyalty)
                            <div class="text-green-700 mt-2">Selamat! Kamu mendapatkan gratis potong rambut!</div>
                        @endif
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
            <div class="p-4 sm:p-8 bg-white shadow rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection
