@extends('admin.layouts.app')

@section('content')
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div>
                <h1 class="title text-3xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-cut mr-3"></i> Edit Hairstyle
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Modify existing hairstyle recommendation</p>
            </div>
        </div>
    </section>

    <section class="section main-section">
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden p-6">
            <form action="{{ route('admin.hairstyles.update', $hairstyle->id) }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $hairstyle->name) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                        required>
                </div>

                <div>
                    <label for="description"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <textarea name="description" id="description" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white">{{ old('description', $hairstyle->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bentuk Kepala</label>
                    <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-2">
                        @foreach ($bentukKepalas as $bk)
                            <label class="inline-flex items-center space-x-2">
                                <input type="checkbox" name="bentuk_kepala[]" value="{{ $bk->id }}"
                                    class="form-checkbox h-4 w-4 text-blue-600"
                                    {{ in_array($bk->id, old('bentuk_kepala', $hairstyle->bentuk_kepala->pluck('id')->toArray())) ? 'checked' : '' }}>
                                <span>{{ $bk->nama }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipe Rambut</label>
                    <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-2">
                        @foreach ($tipeRambuts as $tr)
                            <label class="inline-flex items-center space-x-2">
                                <input type="checkbox" name="tipe_rambut[]" value="{{ $tr->id }}"
                                    class="form-checkbox h-4 w-4 text-green-600"
                                    {{ in_array($tr->id, old('tipe_rambut', $hairstyle->tipe_rambut->pluck('id')->toArray())) ? 'checked' : '' }}>
                                <span>{{ $tr->nama }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Style Preference</label>
                    <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-2">
                        @foreach ($stylePreferences as $sp)
                            <label class="inline-flex items-center space-x-2">
                                <input type="checkbox" name="style_preference[]" value="{{ $sp->id }}"
                                    class="form-checkbox h-4 w-4 text-purple-600"
                                    {{ in_array($sp->id, old('style_preference', $hairstyle->style_preference->pluck('id')->toArray())) ? 'checked' : '' }}>
                                <span>{{ $sp->nama }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>


                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Image</label>
                    <input type="file" name="image" id="image"
                        class="mt-1 block w-full text-sm text-gray-700 dark:text-white">
                    @if ($hairstyle->image)
                        <img src="{{ asset('storage/hairstyles/' . $hairstyle->image) }}" class="mt-2 rounded"
                            width="100" />
                    @endif
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('admin.hairstyles.index') }}"
                        class="mr-4 inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md">Cancel</a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md">Update</button>
                </div>
            </form>
        </div>
    </section>
@endsection
