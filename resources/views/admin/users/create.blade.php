@extends('admin.layouts.app')

@section('content')
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div>
                <h1 class="title text-3xl font-bold text-gray-900 dark:text-white">
                    User Management
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    Create a new user and assign roles or permissions
                </p>
            </div>
        </div>
    </section>

    <section class="section main-section mt-8 h-screen">
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">

            <div class="card-content rounded-md overflow-x-auto">
                <div class="section main-section">

                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Create User</h1>

                    <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Name -->
                        <div>
                            <label for="name"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                required>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                required>
                        </div>

                        <div>
                            <label for="no_telepon" class="block text-sm font-medium text-gray-700 dark:text-gray-300">No
                                Telepon</label>
                            <input type="text" name="no_telepon" id="no_telepon"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                            <input type="password" name="password" id="password"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                required>
                        </div>

                        <!-- Roles -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Assign
                                Roles</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                @foreach ($roles as $role)
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                            class="text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <span class="text-gray-700 dark:text-gray-300">{{ $role->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Permissions -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Assign
                                Permissions</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                @foreach ($permissions as $permission)
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                            class="text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                        <span class="text-gray-700 dark:text-gray-300">{{ $permission->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end">
                            <a href="{{ route('users.index') }}"
                                class="mr-4 inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md">Cancel</a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md">Create</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>
@endsection
