@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Your Feedback</h1>
                <p class="mt-2 text-gray-600">Thank you for sharing your experience</p>
            </div>

            <!-- Success Message -->
            @if (session('success'))
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
            @endif

            <!-- Feedback Display -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Feedback Details</h3>
                </div>

                <div class="p-6">
                    <!-- Rating Display -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Your Rating</label>
                        <div class="flex items-center">
                            @for ($i = 1; $i <= 5; $i++)
                                <i
                                    class="fas fa-star text-xl {{ $i <= $feedback->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                            @endfor
                            <span class="ml-3 text-sm text-gray-600">
                                {{ $feedback->rating }}/5 -
                                @switch($feedback->rating)
                                    @case(1)
                                        Poor
                                    @break

                                    @case(2)
                                        Fair
                                    @break

                                    @case(3)
                                        Good
                                    @break

                                    @case(4)
                                        Very Good
                                    @break

                                    @case(5)
                                        Excellent
                                    @break
                                @endswitch
                            </span>
                        </div>
                    </div>

                    <!-- Comment Display -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Your Comment</label>
                        <div class="bg-gray-50 rounded-md p-4 border border-gray-200">
                            @if ($feedback->comment)
                                <p class="text-gray-900">{{ $feedback->comment }}</p>
                            @else
                                <p class="text-gray-500 italic">No comment provided</p>
                            @endif
                        </div>
                    </div>

                    <!-- Status Info -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <div class="flex items-center space-x-4">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $feedback->is_public ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                <i class="fas {{ $feedback->is_public ? 'fa-eye' : 'fa-eye-slash' }} mr-1"></i>
                                {{ $feedback->is_public ? 'Public Testimonial' : 'Private Feedback' }}
                            </span>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-clock mr-1"></i>
                                Submitted {{ $feedback->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>

                    <!-- Booking Info -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-md border border-gray-200">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Related Booking</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Booking ID:</span>
                                <span class="ml-2 text-gray-900">#{{ $feedback->booking->id }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Service:</span>
                                <span class="ml-2 text-gray-900">{{ $feedback->booking->service->name ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Date:</span>
                                <span class="ml-2 text-gray-900">{{ $feedback->booking->booking_date ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Time:</span>
                                <span class="ml-2 text-gray-900">{{ $feedback->booking->booking_time ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-between items-center">
                        <a href="{{ route('bookings.show', $feedback->booking) }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Booking
                        </a>

                        <div class="flex space-x-3">
                            <a href="{{ route('feedback.edit', $feedback) }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm bg-blue-600 hover:bg-blue-700 text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-edit mr-2"></i>
                                Edit Feedback
                            </a>

                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-home mr-2"></i>
                                Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
