@extends('admin.layouts.app')

@section('content')
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div>
                <h1 class="title text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-plus mr-3"></i>
                    Add New Product
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    Add a new product to your inventory
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
                        <h2 class="text-2xl font-bold text-gray-900">Product Information</h2>
                        <p class="text-gray-600 mt-1">Please fill in all required product information</p>
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-8">
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <!-- Product Name -->
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-tag mr-2 text-blue-600"></i>Product Name <span
                                        class="text-red-500">*</span>
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                    class="form-control w-full @error('name') border-red-500 @enderror"
                                    placeholder="Enter product name">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-align-left mr-2 text-blue-600"></i>Description
                                </label>
                                <textarea id="description" name="description" rows="4"
                                    class="form-control w-full @error('description') border-red-500 @enderror" placeholder="Enter product description">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-folder mr-2 text-blue-600"></i>Category
                                </label>
                                <input type="text" id="category" name="category" value="{{ old('category') }}"
                                    class="form-control w-full @error('category') border-red-500 @enderror"
                                    placeholder="e.g., Hair Care, Beard Care, Tools">
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
                                    <i class="fas fa-money-bill-wave mr-2 text-blue-600"></i>Price (Rp) <span
                                        class="text-red-500">*</span>
                                </label>
                                <div class="relative">

                                    <input type="number" id="price" name="price" value="{{ old('price') }}" required
                                        min="0" step="0.01"
                                        class="form-control w-full pl-12 @error('price') border-red-500 @enderror"
                                        placeholder="Enter price">
                                </div>
                                @error('price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Stock -->
                            <div>
                                <label for="stock" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-warehouse mr-2 text-blue-600"></i>Stock Quantity <span
                                        class="text-red-500">*</span>
                                </label>
                                <input type="number" id="stock" name="stock" value="{{ old('stock', 0) }}" required
                                    min="0" class="form-control w-full @error('stock') border-red-500 @enderror"
                                    placeholder="Enter stock quantity">
                                @error('stock')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Product Image -->
                            <div>
                                <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-image mr-2 text-blue-600"></i>Product Image
                                </label>
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
                                                <span>Upload a file</span>
                                                <input id="image" name="image" type="file" class="sr-only"
                                                    accept="image/*">
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
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
                    <div class="pt-6 border-t border-gray-200">
                        <div class="flex items-center">
                            <input type="checkbox" id="is_active" name="is_active" value="1"
                                {{ old('is_active', true) ? 'checked' : '' }}
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                Active (product will be visible to customers)
                            </label>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('admin.products.index') }}"
                                class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                <i class="fas fa-times mr-2"></i>Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                                <i class="fas fa-save mr-2"></i>Create Product
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
        // Image preview functionality
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // You can add image preview functionality here
                    console.log('Image selected:', file.name);
                };
                reader.readAsDataURL(file);
            }
        });

        // Price formatting
        document.getElementById('price').addEventListener('input', function(e) {
            // Remove non-numeric characters except decimal point
            let value = e.target.value.replace(/[^\d.]/g, '');
            e.target.value = value;
        });
    </script>
@endpush
