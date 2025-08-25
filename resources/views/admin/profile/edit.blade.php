@extends('admin.layouts.app')

@section('content')
     <div class="is-hero-bar">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div>
                    <h1 class="title text-3xl font-bold text-gray-900 dark:text-white">
                        Profile
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                        Edit your profile information here.
                    </p>
                </div>
                <div class="flex items-center space-x-4 mt-4 md:mt-0">
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>


    <section class="section main-section">
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">


            <div class=" rounded-md overflow-x-auto">
                <!-- Update Profile Information -->
                <div class="p-4 sm:p-8 bg-white shadow rounded-lg">
                    <div class="max-w-xl">
                        @include('admin.profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Update Password -->
                <div class="p-4 sm:p-8 bg-white shadow rounded-lg">
                    <div class="max-w-xl">
                        @include('admin.profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Delete Account -->
                <div class="p-4 sm:p-8 bg-white shadow rounded-lg">
                    <div class="max-w-xl">
                        @include('admin.profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
