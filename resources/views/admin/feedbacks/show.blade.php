@extends('admin.layouts.app')

@section('content')
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div>
                <h1 class="title text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-comment-dots mr-3"></i>
                    Feedback Details
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    View detailed customer feedback
                </p>
            </div>
            <div>
                <a href="{{ route('admin.feedbacks.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-md shadow-sm transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Feedbacks
                </a>
            </div>
        </div>
    </section>

    <section class="section min-h-screen main-section">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Feedback Content -->
            <div class="lg:col-span-2">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Feedback Content</h3>
                    </div>

                    <div class="p-6">
                        <!-- Rating -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rating</label>
                            <div class="flex items-center">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i
                                        class="fas fa-star text-2xl {{ $i <= $feedback->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                @endfor
                                <span class="ml-3 text-xl font-semibold text-gray-700 dark:text-gray-300">
                                    {{ $feedback->rating }}/5
                                </span>
                            </div>
                        </div>

                        <!-- Comment -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Comment</label>
                            <div
                                class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                @if ($feedback->comment)
                                    <p class="text-gray-900 dark:text-white text-base leading-relaxed">
                                        {{ $feedback->comment }}
                                    </p>
                                @else
                                    <p class="text-gray-500 dark:text-gray-400 italic">
                                        No comment provided
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Status Controls -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <form action="{{ route('admin.feedbacks.update', $feedback) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div
                                    class="flex items-center justify-between p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                                    <div>
                                        <h4 class="font-medium text-green-800 dark:text-green-200">Public Status</h4>
                                        <p class="text-sm text-green-600 dark:text-green-300">Show as testimonial</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="is_public" value="1" class="sr-only peer"
                                            {{ $feedback->is_public ? 'checked' : '' }}>
                                        <div
                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-600">
                                        </div>
                                    </label>
                                </div>
                                <button type="submit"
                                    class="mt-2 w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition-colors duration-200">
                                    Update Public Status
                                </button>
                            </form>

                            <form action="{{ route('admin.feedbacks.update', $feedback) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div
                                    class="flex items-center justify-between p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                    <div>
                                        <h4 class="font-medium text-blue-800 dark:text-blue-200">Active Status</h4>
                                        <p class="text-sm text-blue-600 dark:text-blue-300">Enable/disable feedback</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                                            {{ $feedback->is_active ? 'checked' : '' }}>
                                        <div
                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                        </div>
                                    </label>
                                </div>
                                <button type="submit"
                                    class="mt-2 w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors duration-200">
                                    Update Active Status
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                <!-- Customer Info -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Customer Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="text-center mb-4">
                            <div
                                class="w-16 h-16 bg-gray-300 dark:bg-gray-600 rounded-full mx-auto mb-3 flex items-center justify-center">
                                <i class="fas fa-user text-2xl text-gray-600 dark:text-gray-400"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">{{ $feedback->user->name }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $feedback->user->email }}</p>
                        </div>

                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Phone:</span>
                                <span
                                    class="text-sm text-gray-900 dark:text-white">{{ $feedback->user->no_telepon ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Member since:</span>
                                <span
                                    class="text-sm text-gray-900 dark:text-white">{{ $feedback->user->created_at->format('M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Info -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Booking Details</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Booking ID:</span>
                                <span
                                    class="text-sm font-mono text-gray-900 dark:text-white">#{{ $feedback->booking->id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Service:</span>
                                <span
                                    class="text-sm text-gray-900 dark:text-white">{{ $feedback->booking->service->name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Date:</span>
                                <span
                                    class="text-sm text-gray-900 dark:text-white">{{ $feedback->booking->booking_date }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Time:</span>
                                <span
                                    class="text-sm text-gray-900 dark:text-white">{{ $feedback->booking->booking_time }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Status:</span>
                                <span
                                    class="text-sm font-semibold text-green-600">{{ ucfirst($feedback->booking->status) }}</span>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                            <a href="{{ route('admin.bookings.show', $feedback->booking) }}"
                                class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                <i class="fas fa-external-link-alt mr-1"></i>
                                View Full Booking
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Feedback Meta -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Feedback Info</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Submitted:</span>
                                <span
                                    class="text-sm text-gray-900 dark:text-white">{{ $feedback->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Last Updated:</span>
                                <span
                                    class="text-sm text-gray-900 dark:text-white">{{ $feedback->updated_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
