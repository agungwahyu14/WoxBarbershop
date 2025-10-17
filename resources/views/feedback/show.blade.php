@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-20 ">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Success Message -->
            {{-- @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif --}}

            <!-- Feedback Display -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mt-8">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('feedback.feedback_details') }}</h3>
                </div>

                <div class="p-6">
                    <!-- Rating Display -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('feedback.your_rating') }}</label>
                        <div class="flex items-center">
                            @for ($i = 1; $i <= 5; $i++)
                                <i
                                    class="fas fa-star text-xl {{ $i <= $feedback->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                            @endfor
                            <span class="ml-3 text-sm text-gray-600">
                                {{ $feedback->rating }}/5 -
                                @switch($feedback->rating)
                                    @case(1)
                                        {{ __('feedback.rating_poor') }}
                                    @break

                                    @case(2)
                                        {{ __('feedback.rating_fair') }}
                                    @break

                                    @case(3)
                                        {{ __('feedback.rating_good') }}
                                    @break

                                    @case(4)
                                        {{ __('feedback.rating_very_good') }}
                                    @break

                                    @case(5)
                                        {{ __('feedback.rating_excellent') }}
                                    @break
                                @endswitch
                            </span>
                        </div>
                    </div>

                    <!-- Comment Display -->
                    <div class="mb-6">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2">{{ __('feedback.your_comment') }}</label>
                        <div class="bg-gray-50 rounded-md p-4 border border-gray-200">
                            @if ($feedback->comment)
                                <p class="text-gray-900">{{ $feedback->comment }}</p>
                            @else
                                <p class="text-gray-500 italic">{{ __('feedback.no_comment_provided') }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Status Info -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('feedback.status') }}</label>
                        <div class="flex items-center space-x-4">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $feedback->is_public ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                <i class="fas {{ $feedback->is_public ? 'fa-eye' : 'fa-eye-slash' }} mr-1"></i>
                                {{ $feedback->is_public ? __('feedback.public_testimonial') : __('feedback.private_feedback') }}
                            </span>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-clock mr-1"></i>
                                {{ __('feedback.submitted') }} {{ $feedback->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>

                    <!-- Booking Info -->
                    {{-- <div class="mb-6 p-4 bg-gray-50 rounded-md border border-gray-200">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">{{ __('feedback.related_booking') }}</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">{{ __('feedback.booking_id') }}:</span>
                                <span class="ml-2 text-gray-900">#{{ $feedback->booking->id }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">{{ __('feedback.service') }}:</span>
                                <span
                                    class="ml-2 text-gray-900">{{ $feedback->booking->service->name ?? __('feedback.not_available') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">{{ __('feedback.date') }}:</span>
                                <span
                                    class="ml-2 text-gray-900">{{ $feedback->booking->booking_date ?? __('feedback.not_available') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">{{ __('feedback.time') }}:</span>
                                <span
                                    class="ml-2 text-gray-900">{{ $feedback->booking->booking_time ?? __('feedback.not_available') }}</span>
                            </div>
                        </div>
                    </div> --}}

                    <!-- Actions -->
                    <div class="flex justify-between items-center">
                        <a href="{{ route('bookings.show', $feedback->booking) }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-arrow-left mr-2"></i>
                            {{ __('feedback.back_to_booking') }}
                        </a>

                        <div class="flex space-x-3">
                            <a href="{{ route('feedback.edit', $feedback) }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm bg-blue-600 hover:bg-blue-700 text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-edit mr-2"></i>
                                {{ __('feedback.edit_feedback') }}
                            </a>

                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-home mr-2"></i>
                                {{ __('feedback.dashboard') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
