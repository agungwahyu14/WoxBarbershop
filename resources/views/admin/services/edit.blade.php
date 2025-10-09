@extends('admin.layouts.app')

@section('content')
    <!-- Page Header -->
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div>
                <h1 class="title text-3xl font-bold text-gray-900 dark:text-white flex items-center mb-4">
                    <i class="fas fa-cogs mr-3"></i>
                    {{ __('admin.edit_service_title') }}
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    {{ __('admin.edit_service_subtitle') }}
                </p>
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
                        <i class="fas fa-edit text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ __('admin.service_information') }}</h2>
                        <p class="text-gray-600 mt-1">{{ __('admin.fill_required_info') }}</p>
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-8">
                <form action="{{ route('admin.services.update', $service->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Service Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-tag mr-2 text-blue-600"></i>{{ __('admin.service_name') }}
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $service->name) }}"
                            class="form-control w-full @error('name') border-red-500 @enderror"
                            placeholder="Enter service name" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-align-left mr-2 text-blue-600"></i>{{ __('admin.description_column') }}
                        </label>
                        <textarea name="description" id="description" rows="3"
                            class="form-control w-full @error('description') border-red-500 @enderror" placeholder="Optional">{{ old('description', $service->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-money-bill-wave mr-2 text-blue-600"></i>{{ __('admin.price_column') }} (Rp)
                        </label>
                        <input type="number" name="price" id="price" value="{{ old('price', $service->price) }}"
                            class="form-control w-full @error('price') border-red-500 @enderror" placeholder="Enter price"
                            required>
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('admin.services.index') }}"
                                class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                <i class="fas fa-times mr-2"></i>{{ __('admin.cancel_action') }}
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                                <i class="fas fa-save mr-2"></i>{{ __('admin.edit_service_title') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
