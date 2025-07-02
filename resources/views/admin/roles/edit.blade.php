@extends('admin.layouts.app')

@section('content')
    <section class="section main-section">
        <div class="container mx-auto max-w-2xl">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Edit Role</h1>

            <form action="{{ route('roles.update', $role->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Role Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        required>
                </div>

                <!-- Permissions -->
                <div>
                    <label for="permissions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Permissions
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach ($permissions as $permission)
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                    {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}
                                    class="rounded text-blue-600 border-gray-300 dark:bg-gray-800 dark:border-gray-600">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $permission->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('roles.index') }}"
                        class="mr-4 inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md">Cancel</a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md">Update</button>
                </div>
            </form>
        </div>
    </section>
@endsection
