@extends('admin.layouts.app')

@section('content')
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div>
                <h1 class="title text-3xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-box mr-3"></i> Product Details
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    View detailed information about {{ $product->name }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.products.edit', $product) }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md shadow-sm transition-colors duration-200">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Product
                </a>
                <form action="{{ route('admin.products.toggle-status', $product) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 
        {{ $product->is_active ? 'bg-blue-600 hover:bg-blue-700' : 'bg-green-600 hover:bg-green-700' }} 
        text-white font-medium rounded-md shadow-sm transition-colors duration-200">
                        <i class="fas {{ $product->is_active ? 'fa-pause' : 'fa-play' }} mr-2"></i>
                        {{ $product->is_active ? 'Deactivate' : 'Activate' }}
                    </button>

                </form>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        onclick="return confirm('Are you sure you want to delete this product? This action cannot be undone.')"
                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-md shadow-sm transition-colors duration-200">
                        <i class="fas fa-trash mr-2"></i>
                        Delete Product
                    </button>
                </form>
                <a href="{{ route('admin.products.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-md shadow-sm transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Products
                </a>
            </div>
        </div>
    </section>

    <section class="section min-h-screen main-section">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Product Image -->
            <div class="lg:col-span-1">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Product Image</h3>
                        <div class="aspect-square rounded-lg overflow-hidden border-2 border-gray-200 dark:border-gray-600">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-100 dark:bg-gray-700">
                                    <div class="text-center">
                                        <i class="fas fa-image text-4xl text-gray-400 mb-2"></i>
                                        <p class="text-gray-500 dark:text-gray-400">No image available</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Information -->
            <div class="lg:col-span-2">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Product Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Product Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Product Name
                                </label>
                                <div
                                    class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md border border-gray-200 dark:border-gray-600">
                                    <span class="text-gray-900 dark:text-white font-medium">{{ $product->name }}</span>
                                </div>
                            </div>

                            <!-- Category -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Category
                                </label>
                                <div
                                    class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md border border-gray-200 dark:border-gray-600">
                                    @if ($product->category)
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                            {{ $product->category }}
                                        </span>
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400">No category assigned</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Price -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Price
                                </label>
                                <div
                                    class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md border border-gray-200 dark:border-gray-600">
                                    <span class="text-2xl font-bold text-green-600 dark:text-green-400">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Stock -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Stock Quantity
                                </label>
                                <div
                                    class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md border border-gray-200 dark:border-gray-600">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="text-xl font-bold text-gray-900 dark:text-white">{{ $product->stock }}</span>
                                        <span class="text-gray-500 dark:text-gray-400">pieces</span>
                                        @if ($product->stock <= 5)
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                Low Stock
                                            </span>
                                        @elseif($product->stock <= 10)
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-warning mr-1"></i>
                                                Medium Stock
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                In Stock
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Status
                                </label>
                                <div
                                    class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md border border-gray-200 dark:border-gray-600">
                                    @if ($product->is_active)
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <div class="w-2 h-2 rounded-full bg-green-600 mr-2"></div>
                                            Active
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                            <div class="w-2 h-2 rounded-full bg-red-600 mr-2"></div>
                                            Inactive
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Created Date -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Created Date
                                </label>
                                <div
                                    class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md border border-gray-200 dark:border-gray-600">
                                    <span class="text-gray-900 dark:text-white">
                                        {{ $product->created_at->format('d M Y, H:i') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Description -->
                            @if ($product->description)
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Description
                                    </label>
                                    <div
                                        class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md border border-gray-200 dark:border-gray-600">
                                        <p class="text-gray-900 dark:text-white whitespace-pre-line">
                                            {{ $product->description }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->

            </div>
        </div>
    </section>
@endsection
