@extends('admin.layouts.app')

@section('title', 'Tambah Score')

@section('content')
    <!-- Page Header with is-hero-bar -->
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div>
                <h1 class="title text-3xl font-bold text-gray-900 dark:text-white flex items-center mb-4">
                    <i class="fas fa-cogs mr-3"></i>Tambah Hairstyle Score
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Add a new score for a hairstyle recommendation</p>
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
                        <h2 class="text-2xl font-bold text-gray-900">Score Information</h2>
                        <p class="text-gray-600 mt-1">Please fill in the required score details</p>
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
                            <i class="fas fa-tag mr-2 text-blue-600"></i>Hairstyle
                        </label>
                        <select name="hairstyle_id" id="hairstyle_id"
                            class="block w-full h-12 px-4 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500 shadow-sm transition">
                            <option value="">Pilih Hairstyle</option>
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
                            <i class="fas fa-align-left mr-2 text-blue-600"></i>Kriteria
                        </label>
                        <select name="criterion_id" id="criterion_id"
                            class="block w-full h-12 px-4 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500 shadow-sm transition">
                            <option value="">Pilih Kriteria</option>
                            @foreach ($criteria as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                        @error('criterion_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Score Input -->
                    <div class="space-y-2">
                        <label for="score" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <i class="fas fa-star mr-2 text-blue-600"></i>Score
                        </label>
                        <input type="number" name="score" id="score" step="0.01" min="0"
                            class="block w-full h-12 px-4 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500 shadow-sm transition"
                            value="{{ old('score') }}">
                        @error('score')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 pt-4">
                        <a href="{{ route('admin.hairstyles.score.index') }}"
                            class="inline-flex items-center px-6 py-3 h-12 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 h-12 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                            <i class="fas fa-save mr-2"></i>Save Score
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
