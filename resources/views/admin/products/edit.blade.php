@extends('admin.layouts.app')

@section('content')
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-edit mr-3"></i> {{ __('admin.edit_product_btn') }}
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
                        <i class="fas fa-edit text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ __('admin.edit_product_btn') }}</h2>
                        <p class="text-gray-600 mt-1">{{ __('admin.product_info_sub') }}</p>
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-8">
                <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="aspect-square rounded-lg overflow-hidden border-2 border-gray-200 mb-4">
                            @if ($product->image)
                                <img id="current-image" src="{{ asset('storage/' . $product->image) }}"
                                    alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <div id="current-image" class="w-full h-full flex items-center justify-center bg-gray-100">
                                    <div class="text-center">
                                        <i class="fas fa-image text-4xl text-gray-400 mb-2"></i>
                                        <p class="text-gray-500">{{ __('No image') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div>
                            <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i
                                    class="fas fa-upload mr-2 text-blue-600"></i>{{ __('admin.update_image_optional') ?? 'Update Image (Optional)' }}
                            </label>
                            <input type="file" id="image" name="image" accept="image/*"
                                class="form-control w-full @error('image') border-red-500 @enderror"
                                onchange="previewImage(event)" aria-label="Update Product Image">
                            <p class="mt-1 text-xs text-gray-500">
                                PNG, JPG, GIF up to 2MB
                            </p>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-tag mr-2 text-blue-600"></i>{{ __('admin.name') }} <span
                                    class="text-red-500">{{ __('admin.required') }}</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}"
                                class="form-control w-full @error('name') border-red-500 @enderror"
                                placeholder="{{ __('admin.product_name') }}" required aria-label="Product Name">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Category -->
                            <div>
                                <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-folder mr-2 text-blue-600"></i>{{ __('admin.category') }}
                                </label>
                                <input type="text" id="category" name="category"
                                    value="{{ old('category', $product->category) }}"
                                    class="form-control w-full @error('category') border-red-500 @enderror"
                                    placeholder="{{ __('admin.product_category') }}" aria-label="Category">
                                @error('category')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Price -->
                            <div>
                                <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-money-bill-wave mr-2 text-blue-600"></i>{{ __('admin.price') }} <span
                                        class="text-red-500">{{ __('admin.required') }}</span>
                                </label>
                                <input type="number" id="price" name="price"
                                    value="{{ old('price', $product->price) }}" min="0" step="100"
                                    class="form-control w-full @error('price') border-red-500 @enderror"
                                    placeholder="{{ __('admin.price') }}" required aria-label="Price">
                                @error('price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Stock -->
                            <div>
                                <label for="stock" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-warehouse mr-2 text-blue-600"></i>{{ __('admin.stock') }} <span
                                        class="text-red-500">{{ __('admin.required') }}</span>
                                </label>
                                <input type="number" id="stock" name="stock"
                                    value="{{ old('stock', $product->stock) }}" min="0"
                                    class="form-control w-full @error('stock') border-red-500 @enderror"
                                    placeholder="{{ __('admin.stock') }}" required aria-label="Stock Quantity">
                                @error('stock')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-toggle-on mr-2 text-blue-600"></i>{{ __('admin.status') ?? 'Status' }}
                                </label>
                                <div class="flex items-center space-x-4 pt-2">
                                    <label class="flex items-center">
                                        <input type="radio" name="is_active" value="1"
                                            {{ old('is_active', $product->is_active) == 1 ? 'checked' : '' }}
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                            aria-label="Active Status">
                                        <span class="ml-2 text-sm text-gray-700">{{ __('admin.active') }}</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="is_active" value="0"
                                            {{ old('is_active', $product->is_active) == 0 ? 'checked' : '' }}
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                            aria-label="Inactive Status">
                                        <span
                                            class="ml-2 text-sm text-gray-700">{{ __('admin.inactive') ?? 'Inactive' }}</span>
                                    </label>
                                </div>
                                @error('is_active')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-align-left mr-2 text-blue-600"></i>{{ __('admin.description') }}
                            </label>
                            <textarea id="description" name="description" rows="4"
                                class="form-control w-full @error('description') border-red-500 @enderror"
                                placeholder="{{ __('admin.description') }}" aria-label="Product Description">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="mt-6 flex justify-end space-x-4">
                        <a href="{{ route('admin.products.index') }}"
                            class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <i class="fas fa-times mr-2"></i>{{ __('admin.cancel') }}
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                            <i class="fas fa-save mr-2"></i>{{ __('admin.edit_product_btn') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const currentImage = document.getElementById('current-image');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    currentImage.innerHTML =
                        `<img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">`;
                };
                reader.readAsDataURL(file);
            }
        }

        // Auto-format price input
        document.getElementById('price').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d]/g, '');
            if (value) {
                // Round to nearest 100
                value = Math.round(value / 100) * 100;
                e.target.value = value;
            }
        });

        // Stock validation alert
        document.getElementById('stock').addEventListener('change', function(e) {
            const stock = parseInt(e.target.value);
            if (stock <= 5 && stock > 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Low Stock Warning',
                    text: 'This stock level is considered low. Consider restocking soon.',
                    timer: 3000,
                    showConfirmButton: false
                });
            }
        });
    </script>
@endpush
