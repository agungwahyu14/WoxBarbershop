@extends('admin.layouts.app')

@section('title', 'Create User')

@section('content')

    <!-- Enhanced Page Header -->


    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
            <div>
                <h1 class="title">
                    <i class="fas fa-user-plus mr-3"></i> {{ __('admin.create_user_btn') }}
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    {{ __('admin.add_new_user_desc') }}
                </p>
            </div>
        </div>
    </section>

    <!-- Enhanced Form Container -->
    <div class=" section main-section">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-white px-8 py-6 border-b border-gray-200">
                <div class="flex items-center space-x-4">
                    <div class="bg-blue-600 p-3 rounded-lg">
                        <i class="fas fa-user-plus text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ __('admin.user_information') }}</h2>
                        <p class="text-gray-600 mt-1">{{ __('admin.fill_required_info') }}</p>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user mr-2 text-blue-600"></i> {{ __('admin.name') }}
                            </label>
                            <input type="text" name="name" id="name"
                                class="form-control w-full @error('name') border-red-500 @enderror"
                                value="{{ old('name') }}" placeholder="{{ __('admin.enter_name') }}" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-2 text-blue-600"></i>{{ __('admin.email_address') }}
                            </label>
                            <input type="email" name="email" id="email"
                                class="form-control w-full @error('email') border-red-500 @enderror"
                                value="{{ old('email') }}" placeholder="{{ __('admin.enter_email') }}" required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Phone Field -->
                        <div>
                            <label for="no_telepon" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-phone mr-2 text-blue-600"></i>{{ __('admin.phone_number') }}
                            </label>
                            <input type="text" name="no_telepon" id="no_telepon"
                                class="form-control w-full @error('no_telepon') border-red-500 @enderror"
                                value="{{ old('no_telepon') }}" placeholder="{{ __('admin.enter_phone') }}" required>
                            @error('no_telepon')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-lock mr-2 text-blue-600"></i>{{ __('admin.password') }}
                            </label>
                            <input type="password" name="password" id="password"
                                class="form-control w-full @error('password') border-red-500 @enderror"
                                placeholder="{{ __('admin.enter_password') }}" required>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Password Confirmation Field -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-blue-600"></i>{{ __('admin.confirm_password') }}
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="form-control w-full @error('password_confirmation') border-red-500 @enderror"
                            placeholder="{{ __('admin.enter_confirm_password') }}" required>
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Roles Section -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-users-cog mr-2 text-blue-600"></i>{{ __('admin.assign_roles') }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            @forelse($roles as $role)
                                <label
                                    class="flex items-center p-4 border border-gray-300 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors cursor-pointer">
                                    <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                        {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
                                    <span
                                        class="ml-3 text-sm font-medium text-gray-700 capitalize">{{ $role->name }}</span>
                                </label>
                            @empty
                                <p class="text-gray-500 col-span-full">{{ __('admin.no_roles') }}</p>
                            @endforelse
                        </div>
                        @error('roles')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Permissions Section -->
                    {{-- <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-shield-alt mr-2 text-blue-600"></i>Assign Permissions
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @forelse($permissions as $permission)
                                <label
                                    class="flex items-center p-3 border border-gray-300 rounded-lg hover:border-green-300 hover:bg-green-50 transition-colors cursor-pointer">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                        class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                                        {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                    <span
                                        class="ml-3 text-sm font-medium text-gray-700 capitalize">{{ str_replace('_', ' ', $permission->name) }}</span>
                                </label>
                            @empty
                                <p class="text-gray-500 col-span-full">No permissions available</p>
                            @endforelse
                        </div>
                        @error('permissions')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div> --}}

                    <!-- Action Buttons -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('admin.users.index') }}"
                                class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <i class="fas fa-times mr-2"></i>{{ __('admin.cancel') }}
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <i class="fas fa-save mr-2"></i>{{ __('admin.create_user_btn') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Translation variables
        const translations = {
            field_required: @json(__('admin.field_required')),
            email_invalid: @json(__('admin.email_invalid')),
            validation_error_title: @json(__('admin.validation_error')),
            fill_required_fields: @json(__('admin.fill_required_fields')),
            role_required_title: @json(__('admin.role_required')),
            assign_role_message: @json(__('admin.assign_role_message')),
            creating_user: @json(__('admin.creating_user')),
            very_weak: @json(__('admin.very_weak')),
            weak: @json(__('admin.weak')),
            fair: @json(__('admin.fair')),
            good: @json(__('admin.good')),
            strong: @json(__('admin.strong')),
            password_strength: @json(__('admin.password_strength'))
        };

        $(document).ready(function() {
            // Form validation and enhancement
            const form = $('form');
            const submitButton = $('button[type="submit"]');

            // Real-time validation
            $('input[required]').on('blur', function() {
                const input = $(this);
                const value = input.val().trim();

                if (!value) {
                    input.addClass('border-red-500').removeClass('border-gray-300');
                    showFieldError(input, translations.field_required);
                } else {
                    input.removeClass('border-red-500').addClass('border-gray-300');
                    hideFieldError(input);
                }
            });

            // Email validation
            $('#email').on('blur', function() {
                const email = $(this).val();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (email && !emailRegex.test(email)) {
                    $(this).addClass('border-red-500').removeClass('border-gray-300');
                    showFieldError($(this), translations.email_invalid);
                }
            });

            // Phone validation
            $('#no_telepon').on('input', function() {
                let value = $(this).val().replace(/\D/g, ''); // Remove non-digits
                $(this).val(value);
            });

            // Password strength indicator
            $('#password').on('input', function() {
                const password = $(this).val();
                const strength = checkPasswordStrength(password);
                showPasswordStrength(strength);
            });

            // Form submission
            form.on('submit', function(e) {
                e.preventDefault();

                // Validate required fields
                let isValid = true;
                $('input[required]').each(function() {
                    if (!$(this).val().trim()) {
                        $(this).addClass('border-red-500');
                        isValid = false;
                    }
                });

                if (!isValid) {
                    showNotification('error', translations.validation_error_title, translations
                        .fill_required_fields);
                    return;
                }

                // Check if at least one role is selected
                if ($('input[name="roles[]"]:checked').length === 0) {
                    showNotification('warning', translations.role_required_title,
                        translations.assign_role_message);
                    return;
                }

                showLoading();
                submitButton.prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin mr-2"></i>' + translations.creating_user);

                // Submit the form
                this.submit();
            });

            function showFieldError(input, message) {
                const existing = input.siblings('.field-error');
                if (existing.length) {
                    existing.text(message);
                } else {
                    input.after(`<p class="field-error mt-1 text-sm text-red-600">${message}</p>`);
                }
            }

            function hideFieldError(input) {
                input.siblings('.field-error').remove();
            }

            function checkPasswordStrength(password) {
                let score = 0;
                if (password.length >= 8) score++;
                if (/[a-z]/.test(password)) score++;
                if (/[A-Z]/.test(password)) score++;
                if (/[0-9]/.test(password)) score++;
                if (/[^A-Za-z0-9]/.test(password)) score++;
                return score;
            }

            function showPasswordStrength(strength) {
                const strengthIndicator = $('#password-strength');
                if (strengthIndicator.length === 0) {
                    $('#password').after('<div id="password-strength" class="mt-2"></div>');
                }

                const labels = [translations.very_weak, translations.weak, translations.fair, translations.good,
                    translations.strong
                ];
                const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-blue-500', 'bg-green-500'];

                let html = '<div class="flex space-x-1 mb-1">';
                for (let i = 0; i < 5; i++) {
                    html +=
                        `<div class="h-1 flex-1 rounded ${i < strength ? colors[strength-1] : 'bg-gray-200'}"></div>`;
                }
                html += '</div>';
                html +=
                    `<p class="text-xs text-gray-600">${translations.password_strength}: ${labels[strength-1] || translations.very_weak}</p>`;

                $('#password-strength').html(html);
            }
        });
    </script>
@endpush
