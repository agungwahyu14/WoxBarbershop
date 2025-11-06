@extends('admin.layouts.app')

@section('content')
    <!-- Page Header -->
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div>
                <h1 class="title text-3xl font-bold text-gray-900 dark:text-white flex items-center mb-4">
                    <i class="fas fa-cut mr-3"></i> {{ __('admin.edit_hairstyle') }}
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ __('admin.modify_existing_hairstyle') }}</p>
            </div>
        </div>
    </section>

    <!-- Form Section -->
    <section class="section main-section">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-gray-50 to-white px-8 py-6 border-b border-gray-200">
                <div class="flex items-center space-x-4">
                    <div class="bg-blue-600 p-3 rounded-lg">
                        <i class="fas fa-cut text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ __('admin.hairstyle_information') }}</h2>
                        <p class="text-gray-600 mt-1">{{ __('admin.update_required_hairstyle_info') }}</p>
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-8">
                <form action="{{ route('admin.hairstyles.update', $hairstyle->id) }}" method="POST"
                    enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Hairstyle Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-tag mr-2 text-blue-600"></i>{{ __('admin.hairstyle_name') }}
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $hairstyle->name) }}"
                            class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            required>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-align-left mr-2 text-blue-600"></i>{{ __('admin.description') }}
                        </label>
                        <textarea name="description" id="description" rows="3"
                            class=" form-control block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('description', $hairstyle->description) }}</textarea>
                    </div>

                    <!-- Description Indonesian -->
                    <div>
                        <label for="description_in"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-align-left mr-2 text-green-600"></i>{{ __('admin.description') }} (Indonesian)
                        </label>
                        <textarea name="description_in" id="description_in" rows="3"
                            class="form-control block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-green-500 focus:border-green-500 sm:text-sm"
                            placeholder="Deskripsi dalam bahasa Indonesia">{{ old('description_in', $hairstyle->description_in) }}</textarea>
                    </div>

                    <!-- Description English -->
                    <div>
                        <label for="description_en"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-align-left mr-2 text-purple-600"></i>{{ __('admin.description') }} (English)
                        </label>
                        <textarea name="description_en" id="description_en" rows="3"
                            class="form-control block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                            placeholder="Description in English">{{ old('description_en', $hairstyle->description_en) }}</textarea>
                    </div>

                    <!-- Head Shape -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.head_shape') }}</label>
                        <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-2">
                            @foreach ($bentukKepalas as $bk)
                                <label class="inline-flex items-center space-x-2">
                                    <input type="checkbox" name="bentuk_kepala[]" value="{{ $bk->id }}"
                                        class="form-checkbox h-4 w-4 text-blue-600"
                                        {{ in_array($bk->id, old('bentuk_kepala', $hairstyle->bentuk_kepala->pluck('id')->toArray())) ? 'checked' : '' }}>
                                    <span class="text-gray-700 dark:text-gray-300">{{ $bk->nama }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Hair Type -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.hair_type') }}</label>
                        <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-2">
                            @foreach ($tipeRambuts as $tr)
                                <label class="inline-flex items-center space-x-2">
                                    <input type="checkbox" name="tipe_rambut[]" value="{{ $tr->id }}"
                                        class="form-checkbox h-4 w-4 text-green-600"
                                        {{ in_array($tr->id, old('tipe_rambut', $hairstyle->tipe_rambut->pluck('id')->toArray())) ? 'checked' : '' }}>
                                    <span class="text-gray-700 dark:text-gray-300">{{ $tr->nama }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Style Preferences -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.style_preference') }}</label>
                        <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-2">
                            @foreach ($stylePreferences as $sp)
                                <label class="inline-flex items-center space-x-2">
                                    <input type="checkbox" name="style_preference[]" value="{{ $sp->id }}"
                                        class="form-checkbox h-4 w-4 text-purple-600"
                                        {{ in_array($sp->id, old('style_preference', $hairstyle->style_preference->pluck('id')->toArray())) ? 'checked' : '' }}>
                                    <span class="text-gray-700 dark:text-gray-300">{{ $sp->nama }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div>
                        <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-image mr-2 text-blue-600"></i>{{ __('admin.image') }}
                        </label>

                        @if ($hairstyle->image)
                            <div class="mb-3">
                                <img id="preview" src="{{ asset('storage/' . $hairstyle->image) }}" alt="Current Image"
                                    class="w-40 h-40 object-cover rounded-lg border">
                            </div>
                        @else
                            <img id="preview" class="w-40 h-40 object-cover rounded-lg border hidden">
                        @endif

                        <div
                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-blue-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="image"
                                        class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>{{ __('admin.upload_file') }}</span>
                                        <input id="image" name="image" type="file" class="sr-only"
                                            accept="image/*">
                                    </label>
                                    <p class="pl-1">{{ __('admin.drag_and_drop') }}</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                            </div>
                        </div>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('admin.hairstyles.index') }}"
                            class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <i class="fas fa-times mr-2"></i>{{ __('admin.cancel') }}
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                            <i class="fas fa-save mr-2"></i>{{ __('admin.edit_hairstyle') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Preview selected image
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('preview');
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
