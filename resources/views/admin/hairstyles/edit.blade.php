@extends('admin.layouts.app')

@section('content')
    <!-- Page Header -->
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div>
                <h1 class="title text-3xl font-bold text-gray-900 dark:text-white flex items-center mb-4">
                    <i class="fas fa-cut mr-3"></i> Edit Hairstyle
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Modify existing hairstyle recommendation</p>
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
                        <h2 class="text-2xl font-bold text-gray-900">Hairstyle Information</h2>
                        <p class="text-gray-600 mt-1">Update all required hairstyle information</p>
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
                            <i class="fas fa-tag mr-2 text-blue-600"></i>Hairstyle Name
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $hairstyle->name) }}"
                            class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            required>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-align-left mr-2 text-blue-600"></i>Description
                        </label>
                        <textarea name="description" id="description" rows="3"
                            class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('description', $hairstyle->description) }}</textarea>
                    </div>

                    <!-- Head Shape -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bentuk Kepala</label>
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
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipe Rambut</label>
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
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Style
                            Preference</label>
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
                        <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-image mr-2 text-blue-600"></i>Image
                        </label>
                        <div class="relative">
                            <input type="file" name="image" id="image" class="hidden" accept="image/*"
                                onchange="updateFileName()">
                            <label for="image"
                                class="inline-flex items-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 cursor-pointer">
                                <i class="fas fa-upload mr-2 text-blue-600"></i> Choose a file
                            </label>
                            <div id="file-name-container" class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                {{ $hairstyle->image ? $hairstyle->image : 'No file chosen' }}
                            </div>
                        </div>
                        @if ($hairstyle->image)
                            <img src="{{ asset('storage/hairstyles/' . $hairstyle->image) }}" class="mt-2 rounded"
                                width="100" />
                        @endif
                    </div>

                    <script>
                        function updateFileName() {
                            const fileInput = document.getElementById('image');
                            const fileName = fileInput.files.length > 0 ? fileInput.files[0].name : 'No file chosen';
                            document.getElementById('file-name-container').textContent = fileName;
                        }
                    </script>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('admin.hairstyles.index') }}"
                            class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                            <i class="fas fa-save mr-2"></i>Update Hairstyle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
