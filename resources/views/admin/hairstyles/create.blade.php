@extends('admin.layouts.app')

@section('content')
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div>
                <h1 class="title text-3xl font-bold text-gray-900 dark:text-white">
                    Create Hairstyle
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Add new hairstyle recommendation</p>
            </div>
        </div>
    </section>

    <section class="section main-section">
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden p-6">
            <form action="{{ route('hairstyles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input type="text" name="name" id="name"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                        required>
                </div>

                <div>
                    <label for="description"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <textarea name="description" id="description" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white"></textarea>
                </div>

                <div>
                    <label for="bentuk_kepala" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bentuk
                        Kepala</label>
                    <input type="text" name="bentuk_kepala" id="bentuk_kepala"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                        required>
                </div>

                <div>
                    <label for="tipe_rambut" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipe
                        Rambut</label>
                    <input type="text" name="tipe_rambut" id="tipe_rambut"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                        required>
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Image</label>
                    <input type="file" name="image" id="image"
                        class="mt-1 block w-full text-sm text-gray-700 dark:text-white">
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('hairstyles.index') }}"
                        class="mr-4 inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md">Cancel</a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md">Create</button>
                </div>
            </form>
        </div>
    </section>
@endsection
