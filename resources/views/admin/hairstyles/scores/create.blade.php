@extends('admin.layouts.app')

@section('title', 'Tambah Score')

@section('content')
    <!-- Page Header with is-hero-bar -->
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div>
                <h1 class="title text-3xl font-bold text-gray-900 dark:text-white flex items-center mb-4">
                    <i class="fas fa-cogs mr-3"></i>{{ __('admin.add_hairstyle_score') }}
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ __('admin.add_new_score_recommendation') }}</p>
            </div>
        </div>
    </section>

    <!-- Form Section -->
    <section class="section min-h-screen main-section">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-gray-50 to-white px-8 py-6 border-b border-gray-200">
                <div class="flex items-center space-x-4">
                    <div class="bg-blue-600 p-3 rounded-lg">
                        <i class="fas fa-tag text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ __('admin.score_information') }}</h2>
                        <p class="text-gray-600 mt-1">{{ __('admin.fill_required_score_details') }}</p>
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-8">
                <form action="{{ route('admin.hairstyles.score.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Hairstyle Dropdown -->
                    <div class="space-y-2">
                        <label for="hairstyle_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <i class="fas fa-tag mr-2 text-blue-600"></i>{{ __('admin.hairstyle') }}
                        </label>
                        <select name="hairstyle_id" id="hairstyle_id"
                            class="block w-full h-12 px-4 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500 shadow-sm transition">
                            <option value="">{{ __('admin.select_hairstyle') }}</option>
                            @foreach ($hairstyles as $h)
                                <option value="{{ $h->id }}">{{ $h->name }}</option>
                            @endforeach
                        </select>
                        @error('hairstyle_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Criterion Dropdown -->
                    <div class="space-y-2">
                        <label for="criterion_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <i class="fas fa-align-left mr-2 text-blue-600"></i>{{ __('admin.criteria') }}
                        </label>
                        <select name="criterion_id" id="criterion_id"
                            class="block w-full h-12 px-4 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500 shadow-sm transition">
                            <option value="">{{ __('admin.select_criteria') }}</option>
                            @foreach ($criteria as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                        @error('criterion_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sub Criterion Dropdown (Dynamic based on Criterion) -->
                    <div class="space-y-2" id="sub-criterion-wrapper" style="display: none;">
                        <label for="sub_criterion_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <i class="fas fa-tag mr-2 text-green-600"></i>{{ __('admin.sub_criteria') }}
                        </label>
                        <select name="sub_criterion_id" id="sub_criterion_id"
                            class="block w-full h-12 px-4 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500 shadow-sm transition">
                            <option value="">{{ __('admin.select_sub_criteria') }}</option>
                        </select>
                        @error('sub_criterion_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Score Input -->
                    <div class="space-y-2">
                        <label for="score" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <i class="fas fa-star mr-2 text-blue-600"></i>{{ __('admin.score') }} (1-10)
                        </label>
                        <input type="number" name="score" id="score" step="1" min="1" max="10"
                            class="block w-full h-12 px-4 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500 shadow-sm transition"
                            value="{{ old('score') }}" placeholder="Masukkan skor 1-10">
                        @error('score')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 pt-4">
                        <a href="{{ route('admin.hairstyles.score.index') }}"
                            class="inline-flex items-center px-6 py-3 h-12 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <i class="fas fa-times mr-2"></i>{{ __('admin.cancel') }}
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 h-12 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                            <i class="fas fa-save mr-2"></i>{{ __('admin.save_score') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Data sub-criteria dari controller
            const subCriteriaData = {
                8: @json($bentukKepala), // Bentuk Kepala
                9: @json($tipeRambut), // Tipe Rambut
                10: @json($stylePreference) // Preferensi Gaya
            };

            // When criterion is selected, load sub-criteria
            $('#criterion_id').on('change', function() {
                const criterionId = $(this).val();
                const subCriterionWrapper = $('#sub-criterion-wrapper');
                const subCriterionSelect = $('#sub_criterion_id');

                if (criterionId && subCriteriaData[criterionId]) {
                    // Clear and show wrapper
                    subCriterionSelect.html(
                        '<option value="">{{ __('admin.select_sub_criteria') }}</option>');
                    subCriterionWrapper.show();

                    // Populate sub-criteria from pre-loaded data
                    const data = subCriteriaData[criterionId];
                    $.each(data, function(index, item) {
                        subCriterionSelect.append(
                            $('<option></option>').val(item.id).text(item.name)
                        );
                    });
                } else {
                    subCriterionWrapper.hide();
                    subCriterionSelect.html(
                        '<option value="">{{ __('admin.select_sub_criteria') }}</option>');
                }
            });

            // If there's old input after validation error, restore the selection
            @if (old('criterion_id'))
                $('#criterion_id').val('{{ old('criterion_id') }}').trigger('change');

                @if (old('sub_criterion_id'))
                    setTimeout(function() {
                        $('#sub_criterion_id').val('{{ old('sub_criterion_id') }}');
                    }, 100);
                @endif
            @endif
        });
    </script>
@endpush
