@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">


            <!-- Booking Info Card -->
            {{-- <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 mt-8">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('feedback.booking_details') }}</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">{{ __('feedback.booking_id') }}</label>
                            <p class="mt-1 text-sm text-gray-900">#{{ $feedback->booking->id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">{{ __('feedback.service') }}</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $feedback->booking->service->name ?? __('feedback.not_available') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">{{ __('feedback.date') }}</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $feedback->booking->booking_date ?? __('feedback.not_available') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">{{ __('feedback.time') }}</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $feedback->booking->booking_time ?? __('feedback.not_available') }}</p>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Feedback Form -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mt-8">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('feedback.update_feedback') }}</h3>
                </div>

                <form action="{{ route('feedback.update', $feedback) }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <!-- Rating -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            {{ __('feedback.rating') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center space-x-2">
                            @for ($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer">
                                    <input type="radio" name="rating" value="{{ $i }}"
                                        class="sr-only rating-input" {{ $feedback->rating == $i ? 'checked' : '' }}
                                        required>
                                    <i class="fas fa-star text-2xl {{ $feedback->rating >= $i ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400 transition-colors rating-star"
                                        data-rating="{{ $i }}"></i>
                                </label>
                            @endfor
                            <span class="ml-3 text-sm text-gray-600 rating-text">
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

                                    @default
                                        {{ __('feedback.click_to_rate') }}
                                @endswitch
                            </span>
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
                            placeholder="{{ __('feedback.comment_placeholder') }}">{{ old('comment', $feedback->comment) }}</textarea>
                        @error('comment')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Public Option -->
                    <div class="mb-6">
                        <div class="flex items-center">
                            <input type="checkbox" id="is_public" name="is_public" value="1"
                                {{ old('is_public', $feedback->is_public) ? 'checked' : '' }}
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_public" class="ml-2 block text-sm text-gray-700">
                                {{ __('feedback.testimonial_permission') }}
                            </label>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('feedback.show', $feedback) }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('feedback.cancel') }}
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm bg-blue-600 hover:bg-blue-700 text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i>
                            {{ __('feedback.update_feedback') }}
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

    {{-- JavaScript untuk handling form --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.rating-star');
            const ratingInputs = document.querySelectorAll('.rating-input');
            const ratingText = document.querySelector('.rating-text');

            // Rating labels data
            const ratingLabels = {
                1: '{{ __('feedback.rating_poor') }}',
                2: '{{ __('feedback.rating_fair') }}',
                3: '{{ __('feedback.rating_good') }}',
                4: '{{ __('feedback.rating_very_good') }}',
                5: '{{ __('feedback.rating_excellent') }}'
            };

            const clickToRateText = '{{ __('feedback.click_to_rate') }}';

            stars.forEach((star, index) => {
                star.addEventListener('mouseover', function() {
                    highlightStars(index + 1);
                    ratingText.textContent = ratingLabels[index + 1];
                });

                star.addEventListener('click', function() {
                    const rating = index + 1;
                    ratingInputs[index].checked = true;
                    ratingText.textContent = ratingLabels[rating];
                    highlightStars(rating, true);
                });
            });

            if (document.querySelector('.flex.items-center.space-x-2')) {
                document.querySelector('.flex.items-center.space-x-2').addEventListener('mouseleave', function() {
                    const checkedInput = document.querySelector('.rating-input:checked');
                    if (checkedInput) {
                        const rating = parseInt(checkedInput.value);
                        highlightStars(rating, true);
                        ratingText.textContent = ratingLabels[rating];
                    } else {
                        highlightStars(0);
                        ratingText.textContent = clickToRateText;
                    }
                });
            }

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
