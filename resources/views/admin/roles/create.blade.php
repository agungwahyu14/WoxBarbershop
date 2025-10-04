@extends('admin.layouts.app')

@section('content')
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div>
                <h1 class="title text-3xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-user-shield mr-3"></i> Role Management
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    Manage user roles and permissions across your system
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
                        <i class="fas fa-user-shield text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Roles Information</h2>
                        <p class="text-gray-600 mt-1">Please fill in all required roles information</p>
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-8">
                <form action="{{ route('admin.roles.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf

                    <!-- Hairstyle Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-tag mr-2 text-blue-600"></i> Name
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            required>
                    </div>



                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('admin.roles.index') }}"
                            class="mr-4 inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                            <i class="fas fa-save mr-2"></i>Create Hairstyle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
