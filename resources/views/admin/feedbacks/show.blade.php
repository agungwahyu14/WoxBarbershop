@extends('admin.layouts.app')

@section('content')
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <!-- Title Section -->
            <div>
                <h1 class="title text-3xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-comment-dots mr-3"></i> {{ __('admin.feedback_details') }}
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    {{ __('admin.feedback_details_subtitle') }}
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3">
                <!-- Toggle Public/Private Button -->
                <form action="{{ route('admin.feedbacks.toggle-public', $feedback) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 
                    {{ $feedback->is_public ? 'bg-blue-600 hover:bg-blue-700' : 'bg-green-600 hover:bg-green-700' }} 
                    text-white font-medium rounded-md shadow-sm transition-colors duration-200">
                        <i class="fas {{ $feedback->is_public ? 'fa-eye-slash' : 'fa-eye' }} mr-2"></i>
                        {{ $feedback->is_public ? __('admin.make_private') : __('admin.make_public') }}
                    </button>
                </form>

                <!-- Delete Button -->
                <form action="{{ route('admin.feedbacks.destroy', $feedback) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('{{ __('admin.delete_feedback_confirm') }}')"
                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 
                    text-white font-medium rounded-md shadow-sm transition-colors duration-200">
                        <i class="fas fa-trash mr-2"></i>
                        {{ __('admin.delete_feedback') }}
                    </button>
                </form>

                <!-- Back Button -->
                <a href="{{ route('admin.feedbacks.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 
                text-white font-medium rounded-md shadow-sm transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    {{ __('admin.back_to_feedbacks') }}
                </a>
            </div>
        </div>
    </section>

    <section class="section min-h-screen main-section">
        <!-- Feedback Rating Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('admin.customer_rating') }}</h3>
            </div>
            <div class="p-6">
                <div class="text-center">
                    <div class="text-6xl font-bold text-blue-600 dark:text-blue-400 mb-2">
                        {{ $feedback->rating }}/5
                    </div>
                    <div class="flex justify-center items-center mb-4">
                        @for ($i = 1; $i <= 5; $i++)
                            <i
                                class="fas fa-star text-2xl {{ $i <= $feedback->rating ? 'text-yellow-400' : 'text-gray-300' }} mx-1"></i>
                        @endfor
                    </div>
                    <div class="text-lg text-gray-600 dark:text-gray-400">
                        @if ($feedback->rating == 5)
                            {{ __('admin.excellent_service') }}
                        @elseif($feedback->rating >= 4)
                            {{ __('admin.good_service') }}
                        @elseif($feedback->rating >= 3)
                            {{ __('admin.average_service') }}
                        @elseif($feedback->rating >= 2)
                            {{ __('admin.below_average_service') }}
                        @else
                            {{ __('admin.poor_service') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Customer Information -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('admin.customer_information') }}
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    @if ($feedback->user)
                        <div class="flex items-center gap-4">
                            <div
                                class="w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center">
                                <span class="text-2xl font-bold text-blue-600 dark:text-blue-300">
                                    {{ strtoupper(substr($feedback->user->name, 0, 1)) }}
                                </span>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $feedback->user->name }}
                                </h4>
                                <p class="text-gray-600 dark:text-gray-400">{{ $feedback->user->email }}</p>
                                @if ($feedback->user->phone)
                                    <p class="text-gray-600 dark:text-gray-400">{{ $feedback->user->phone }}</p>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-user-slash text-3xl text-gray-400 mb-2"></i>
                            <p class="text-gray-500 dark:text-gray-400">{{ __('admin.customer_info_not_available') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Booking Information -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('admin.booking_information') }}
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    @if ($feedback->booking)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ __('admin.booking_id') }}
                            </label>
                            <div class="flex items-center gap-2">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    #{{ $feedback->booking->id }}
                                </span>
                            </div>
                        </div>

                        @if ($feedback->booking->service)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('admin.service') }}
                                </label>
                                <p class="text-gray-900 dark:text-white font-medium">
                                    {{ $feedback->booking->service->name }}
                                </p>
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ __('admin.booking_date') }}
                            </label>
                            <p class="text-gray-900 dark:text-white">
                                {{ $feedback->booking->booking_date ? \Carbon\Carbon::parse($feedback->booking->booking_date)->format('d M Y, H:i') : __('admin.not_scheduled') }}
                            </p>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times text-3xl text-gray-400 mb-2"></i>
                            <p class="text-gray-500 dark:text-gray-400">{{ __('admin.no_booking_info_available') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Feedback Content -->
        <div
            class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('admin.feedback_content') }}</h3>
            </div>
            <div class="p-6">
                @if ($feedback->comment)
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border-l-4 border-blue-500">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-quote-left text-blue-500 text-xl mt-1"></i>
                            <div class="flex-1">
                                <p class="text-gray-900 dark:text-white text-base leading-relaxed">
                                    {{ $feedback->comment }}
                                </p>
                            </div>
                            <i class="fas fa-quote-right text-blue-500 text-xl mt-1"></i>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-comment-slash text-3xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500 dark:text-gray-400">{{ __('admin.no_written_feedback') }}</p>
                        <p class="text-sm text-gray-400 dark:text-gray-500">{{ __('admin.rating_only_feedback') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Feedback Settings -->
        <div
            class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('admin.settings_and_actions') }}</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Visibility Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('admin.visibility') }}
                        </label>
                        <div class="flex items-center gap-2">
                            @if ($feedback->is_public)
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-eye mr-1"></i>
                                    {{ __('admin.public') }}
                                </span>
                                <span class="text-xs text-gray-500">{{ __('admin.visible_to_customers') }}</span>
                            @else
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-eye-slash mr-1"></i>
                                    {{ __('admin.private') }}
                                </span>
                                <span class="text-xs text-gray-500">{{ __('admin.admin_only') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Active Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('admin.status') }}
                        </label>
                        <div class="flex items-center gap-2">
                            @if ($feedback->is_active)
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <div class="w-2 h-2 rounded-full bg-green-600 mr-1"></div>
                                    {{ __('admin.active') }}
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <div class="w-2 h-2 rounded-full bg-red-600 mr-1"></div>
                                    {{ __('admin.inactive') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Date Submitted -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Submitted
                        </label>
                        <p class="text-gray-900 dark:text-white">
                            {{ $feedback->created_at->format('d M Y') }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $feedback->created_at->format('H:i') }} • {{ $feedback->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>


            </div>
        </div>
        </div>
    </section>
@endsection
