@extends('admin.layouts.app')

@section('content')
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div>
                <h1 class="title text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-edit mr-3"></i>
                    {{ __('admin.edit_product_btn') }}
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    {{ __('admin.edit_product_subtitle', ['name' => $product->name]) ?? "Update product information for $product->name" }}
                </p>
            </div>
        </div>
    </section>

    <section class="section min-h-screen main-section">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-gray-50 to-white px-8 py-6 border-b border-gray-200">
                <div class="flex items-center space-x-4">
                    <div class="bg-blue-600 p-3 rounded-lg">
                        <i class="fas fa-box text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ __('admin.product_info') }}</h2>
                        <p class="text-gray-600 mt-1">{{ __('admin.product_info_sub') }}</p>
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-8">
                <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <!-- Product Name -->
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-tag mr-2 text-blue-600"></i>{{ __('admin.product_name') }}
                                    <span class="text-red-500">{{ __('admin.required') }}</span>
                                </label>
                                <input type="text" id="name" name="name"
                                    value="{{ old('name', $product->name) }}" required
                                    class="form-control w-full @error('name') border-red-500 @enderror"
                                    placeholder="{{ __('admin.product_name') }}">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Multilingual Product Names -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="name_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-language mr-2 text-green-600"></i>{{ __('admin.product_name_id') }}
                                    </label>
                                    <input type="text" id="name_id" name="name_id"
                                        value="{{ old('name_id', $product->name_id) }}"
                                        class="form-control w-full @error('name_id') border-red-500 @enderror"
                                        placeholder="{{ __('admin.product_name_id_placeholder') }}">
                                    @error('name_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="name_en" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-language mr-2 text-blue-600"></i>{{ __('admin.product_name_en') }}
                                    </label>
                                    <input type="text" id="name_en" name="name_en"
                                        value="{{ old('name_en', $product->name_en) }}"
                                        class="form-control w-full @error('name_en') border-red-500 @enderror"
                                        placeholder="{{ __('admin.product_name_en_placeholder') }}">
                                    @error('name_en')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-align-left mr-2 text-blue-600"></i>{{ __('admin.description') }}
                                </label>
                                <textarea id="description" name="description" rows="3"
                                    class="form-control w-full @error('description') border-red-500 @enderror"
                                    placeholder="{{ __('admin.description') }}">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Multilingual Descriptions -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="description_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-language mr-2 text-green-600"></i>{{ __('admin.description_id') }}
                                    </label>
                                    <textarea id="description_id" name="description_id" rows="4"
                                        class="form-control w-full @error('description_id') border-red-500 @enderror"
                                        placeholder="{{ __('admin.description_id_placeholder') }}">{{ old('description_id', $product->description_id) }}</textarea>
                                    @error('description_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="description_en" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-language mr-2 text-blue-600"></i>{{ __('admin.description_en') }}
                                    </label>
                                    <textarea id="description_en" name="description_en" rows="4"
                                        class="form-control w-full @error('description_en') border-red-500 @enderror"
                                        placeholder="{{ __('admin.description_en_placeholder') }}">{{ old('description_en', $product->description_en) }}</textarea>
                                    @error('description_en')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-folder mr-2 text-blue-600"></i>{{ __('admin.category') }}
                                </label>
                                <input type="text" id="category" name="category"
                                    value="{{ old('category', $product->category) }}"
                                    class="form-control w-full @error('category') border-red-500 @enderror"
                                    placeholder="{{ __('admin.product_category') }}">
                                @error('category')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Price -->
                            <div>
                                <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-money-bill-wave mr-2 text-blue-600"></i>{{ __('admin.price') }}
                                    <span class="text-red-500">{{ __('admin.required') }}</span>
                                </label>
                                <input type="number" id="price" name="price"
                                    value="{{ old('price', $product->price) }}" required min="0" step="0.01"
                                    class="form-control w-full pl-12 @error('price') border-red-500 @enderror"
                                    placeholder="{{ __('admin.price') }}">
                                @error('price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Stock -->
                            <div>
                                <label for="stock" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-warehouse mr-2 text-blue-600"></i>{{ __('admin.stock') }}
                                    <span class="text-red-500">{{ __('admin.required') }}</span>
                                </label>
                                <input type="number" id="stock" name="stock"
                                    value="{{ old('stock', $product->stock) }}" required min="0"
                                    class="form-control w-full @error('stock') border-red-500 @enderror"
                                    placeholder="{{ __('admin.stock') }}">
                                @error('stock')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Product Image -->
                            <div>
                                <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-image mr-2 text-blue-600"></i>{{ __('admin.image') }}
                                </label>

                                @if ($product->image)
                                    <div class="mb-3">
                                        <img id="preview" src="{{ asset('storage/' . $product->image) }}"
                                            alt="Current Image" class="w-40 h-40 object-cover rounded-lg border">
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
                                                <span>{{ __('Upload a file') }}</span>
                                                <input id="image" name="image" type="file" class="sr-only"
                                                    accept="image/*">
                                            </label>
                                            <p class="pl-1">{{ __('or drag and drop') }}</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                    </div>
                                </div>
                                @error('image')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Active Status -->
                    <div class="flex items-center space-x-4 pt-2">
                        <label class="flex items-center">
                            <input type="radio" name="is_active" value="1"
                                {{ old('is_active', $product->is_active) == 1 ? 'checked' : '' }}>
                            <span class="ml-2">{{ __('admin.active') }}</span>
                        </label>

                        <label class="flex items-center">
                            <input type="radio" name="is_active" value="0"
                                {{ old('is_active', $product->is_active) == 0 ? 'checked' : '' }}>
                            <span class="ml-2">{{ __('admin.inactive') }}</span>
                        </label>
                    </div>


                    <!-- Action Buttons -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('admin.products.index') }}"
                                class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                <i class="fas fa-times mr-2"></i>{{ __('admin.cancel') }}
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                                <i class="fas fa-save mr-2"></i>{{ __('admin.edit_product_btn') }}
                            </button>
                        </div>
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

        // Price formatting
        document.getElementById('price').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d.]/g, '');
            e.target.value = value;
        });
    </script>
@endpush
