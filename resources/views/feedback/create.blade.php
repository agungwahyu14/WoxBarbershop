@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">{{ __('feedback.give_feedback') }}</h1>
                <p class="mt-2 text-gray-600">{{ __('feedback.share_experience') }}</p>
            </div>

            <!-- Booking Info Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('feedback.booking_details') }}</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">{{ __('feedback.booking_id') }}</label>
                            <p class="mt-1 text-sm text-gray-900">#{{ $booking->id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">{{ __('feedback.service') }}</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $booking->service->name ?? __('feedback.not_available') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">{{ __('feedback.date') }}</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $booking->booking_date ?? __('feedback.not_available') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">{{ __('feedback.time') }}</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $booking->booking_time ?? __('feedback.not_available') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feedback Form -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('feedback.your_feedback') }}</h3>
                </div>

                <form action="{{ route('feedback.store', $booking) }}" method="POST" class="p-6">
                    @csrf

                    <!-- Rating -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            {{ __('feedback.rating') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center space-x-2">
                            @for ($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer">
                                    <input type="radio" name="rating" value="{{ $i }}"
                                        class="sr-only rating-input" required>
                                    <i class="fas fa-star text-2xl text-gray-300 hover:text-yellow-400 transition-colors rating-star"
                                        data-rating="{{ $i }}"></i>
                                </label>
                            @endfor
                            <span class="ml-3 text-sm text-gray-600 rating-text">{{ __('feedback.click_to_rate') }}</span>
                        </div>
                        @error('rating')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Comment -->
                    <div class="mb-6">
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('feedback.comment_optional') }}
                        </label>
                        <textarea id="comment" name="comment" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="{{ __('feedback.comment_placeholder') }}">{{ old('comment') }}</textarea>
                        @error('comment')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Public Option -->
                    <div class="mb-6">
                        <div class="flex items-center">
                            <input type="checkbox" id="is_public" name="is_public" value="1"
                                {{ old('is_public', true) ? 'checked' : '' }}
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_public" class="ml-2 block text-sm text-gray-700">
                                {{ __('feedback.allow_testimonial') }}
                            </label>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('bookings.show', $booking) }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('feedback.cancel') }}
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm bg-blue-600 hover:bg-blue-700 text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-paper-plane mr-2"></i>
                            {{ __('feedback.submit_feedback') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .rating-star.active {
            color: #fbbf24 !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.rating-star');
            const ratingInputs = document.querySelectorAll('.rating-input');
            const ratingText = document.querySelector('.rating-text');

            const ratingLabels = @json([
                __('feedback.rating_poor'),
                __('feedback.rating_fair'),
                __('feedback.rating_good'),
                __('feedback.rating_very_good'),
                __('feedback.rating_excellent'),
            ]);

            stars.forEach((star, index) => {
                star.addEventListener('mouseover', function() {
                    highlightStars(index + 1);
                    ratingText.textContent = ratingLabels[index];
                });

                star.addEventListener('click', function() {
                    const rating = index + 1;
                    ratingInputs[index].checked = true;
                    ratingText.textContent = ratingLabels[index];
                    highlightStars(rating, true);
                });
            });

            document.querySelector('.flex.items-center.space-x-2').addEventListener('mouseleave', function() {
                const checkedInput = document.querySelector('.rating-input:checked');
                if (checkedInput) {
                    const rating = parseInt(checkedInput.value);
                    highlightStars(rating, true);
                    ratingText.textContent = ratingLabels[rating - 1];
                } else {
                    highlightStars(0);
                    ratingText.textContent = @json(__('feedback.click_to_rate'));
                }
            });

            function highlightStars(rating, permanent = false) {
                stars.forEach((star, index) => {
                    if (index < rating) {
                        star.classList.add('active');
                        star.style.color = '#fbbf24';
                    } else {
                        if (!permanent) {
                            star.classList.remove('active');
                            star.style.color = '#d1d5db';
                        }
                    }
                });
            }
        });
    </script>
@endsection
