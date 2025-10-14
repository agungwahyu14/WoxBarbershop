@extends('admin.layouts.app')

@section('title', __('admin.edit_user'))

@section('content')


    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0 mt-4">
            <div>
                <h1 class="title">
                    <i class="fas fa-user-edit mr-3"></i>
                    {{ __('admin.edit_user_title') }}
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    {{ __('admin.edit_user_subtitle') }} {{ $user->name }}
                </p>
            </div>
        </div>
    </section>


    <!-- Enhanced Form Container -->
    <div class="section main-section">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-white px-8 py-6 border-b border-gray-200">
                <div class="flex items-center space-x-4">
                    <div class="bg-blue-600 p-3 rounded-lg">
                        <i class="fas fa-user-edit text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ __('admin.user_information_title') }}</h2>
                        <p class="text-gray-600 mt-1">{{ __('admin.user_information_subtitle') }}</p>
                    </div>
                    {{-- <div class="ml-auto">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                            {{ $user->hasVerifiedEmail() ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            <i
                                class="fas fa-{{ $user->hasVerifiedEmail() ? 'check-circle' : 'exclamation-triangle' }} mr-1"></i>
                            {{ $user->hasVerifiedEmail() ? __('admin.verified') : __('admin.unverified') }}
                        </span>
                    </div> --}}
                </div>
            </div>

            <div class="p-8">
                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user mr-2 text-blue-600"></i> {{ __('admin.name') }}
                            </label>
                            <input type="text" name="name" id="name"
                                class="form-control w-full @error('name') border-red-500 @enderror"
                                value="{{ old('name', $user->name) }}" placeholder="{{ __('admin.enter_name') }}" required>
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
                                value="{{ old('email', $user->email) }}" placeholder="{{ __('admin.enter_email') }}"
                                required>
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
                                value="{{ old('no_telepon', $user->no_telepon) }}"
                                placeholder="{{ __('admin.enter_phone') }}" required>
                            @error('no_telepon')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-lock mr-2 text-blue-600"></i>{{ __('admin.new_password') }}
                            </label>
                            <input type="password" name="password" id="password"
                                class="form-control w-full @error('password') border-red-500 @enderror"
                                placeholder="{{ __('admin.leave_blank_keep_password') }}">
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>{{ __('admin.leave_empty_keep_password') }}
                            </p>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-lock mr-2 text-blue-600"></i>{{ __('admin.confirm_password') }}
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control w-full @error('password_confirmation') border-red-500 @enderror"
                                placeholder="{{ __('admin.enter_confirm_password') }}">
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>{{ __('admin.leave_empty_keep_password') }}
                            </p>
                            @error('password_confirmation')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Account Status Section -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-user-check mr-2 text-blue-600"></i>{{ __('admin.account_status') }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="email_verified" value="1"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                        {{ $user->hasVerifiedEmail() ? 'checked' : '' }}>
                                    <span
                                        class="ml-3 text-sm font-medium text-gray-700">{{ __('admin.email_verified') }}</span>
                                </label>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1"
                                        class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                                        {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}>
                                    <span
                                        class="ml-3 text-sm font-medium text-gray-700">{{ __('admin.account_active') }}</span>
                                </label>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="text-sm">
                                    <span class="text-gray-600">{{ __('admin.last_login') }}:</span>
                                    <br>
                                    <span class="font-medium">
                                        {{ $user->last_login_at ? $user->last_login_at->format('M d, Y g:i A') : __('admin.never') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Current Roles & Permissions Info -->
                    {{-- <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-info-circle mr-2 text-blue-600"></i>{{ __('admin.roles') }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h4 class="font-medium text-blue-900 mb-2">{{ __('admin.assigned_roles') }}</h4>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($user->roles as $role)
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="text-gray-500 text-sm">{{ __('admin.no_roles_assigned') }}</span>
                                    @endforelse
                                </div>
                            </div> --}}
                    {{-- <div class="bg-green-50 p-4 rounded-lg">
                                <h4 class="font-medium text-green-900 mb-2">Current Permissions</h4>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($user->getAllPermissions()->take(5) as $permission)
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ str_replace('_', ' ', $permission->name) }}
                                        </span>
                                    @empty
                                        <span class="text-gray-500 text-sm">No permissions assigned</span>
                                    @endforelse
                                    @if ($user->getAllPermissions()->count() > 5)
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            +{{ $user->getAllPermissions()->count() - 5 }} more
                                        </span>
                                    @endif
                                </div>
                            </div> --}}
                    {{-- </div>
                    </div> --}}

                    <!-- Roles Section -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-users-cog mr-2 text-blue-600"></i>{{ __('admin.assign_roles') }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            @forelse($roles as $role)
                                <label
                                    class="flex items-center p-4 border border-gray-300 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors cursor-pointer 
                                    {{ $user->hasRole($role->name) ? 'border-blue-500 bg-blue-50' : '' }}">
                                    <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                        {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'checked' : '' }}>
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

                    {{-- <!-- Permissions Section -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-shield-alt mr-2 text-blue-600"></i>Assign Permissions
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @forelse($permissions as $permission)
                                <label
                                    class="flex items-center p-3 border border-gray-300 rounded-lg hover:border-green-300 hover:bg-green-50 transition-colors cursor-pointer
        {{ $user->hasPermissionTo($permission->name) ? 'border-green-500 bg-green-50' : '' }}">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                        class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                                        {{ in_array($permission->id, old('permissions', $user->getAllPermissions()->pluck('id')->toArray())) ? 'checked' : '' }}>
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
                        <div class="flex justify-between">
                            <div class="flex space-x-4">

                            </div>
                            <div class="flex space-x-4">

                                {{-- <a href="{{ route('admin.users.show', $user) }}"
                                    class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    <i class="fas fa-eye mr-2"></i>{{ __('admin.view_user_btn') }}
                                </a> --}}
                                <a href="{{ route('admin.users.index') }}"
                                    class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    <i class="fas fa-times mr-2"></i>{{ __('admin.cancel') }}
                                </a>
                                <button type="submit"
                                    class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    <i class="fas fa-save mr-2"></i>{{ __('admin.edit_user_btn') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Translation variables
            const translations = {
                field_required: @json(__('admin.field_required')),
                invalid_email: @json(__('admin.invalid_email')),
                validation_error: @json(__('admin.validation_error')),
                fill_required_fields: @json(__('admin.fill_required_fields')),
                role_required: @json(__('admin.role_required')),
                assign_role_message: @json(__('admin.assign_role_message')),
                updating_user: @json(__('admin.updating_user')),
                password_strength: @json(__('admin.password_strength')),
                very_weak: @json(__('admin.very_weak')),
                weak: @json(__('admin.weak')),
                fair: @json(__('admin.fair')),
                good: @json(__('admin.good')),
                strong: @json(__('admin.strong'))
            };

            // Form validation and enhancement
            const form = $('form');
            const submitButton = $('button[type="submit"]');

            // Track original values for change detection
            const originalValues = {
                name: $('#name').val(),
                email: $('#email').val(),
                no_telepon: $('#no_telepon').val(),
                roles: $('input[name="roles[]"]:checked').map(function() {
                    return this.value;
                }).get(),
                // permissions: $('input[name="permissions[]"]:checked').map(function() {
                //     return this.value;
                // }).get()
            };

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
                    showFieldError($(this), translations.invalid_email);
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
                if (password) {
                    const strength = checkPasswordStrength(password);
                    showPasswordStrength(strength);
                } else {
                    $('#password-strength').remove();
                }
            });

            // Change detection
            function detectChanges() {
                const currentValues = {
                    name: $('#name').val(),
                    email: $('#email').val(),
                    no_telepon: $('#no_telepon').val(),
                    roles: $('input[name="roles[]"]:checked').map(function() {
                        return this.value;
                    }).get(),
                    permissions: $('input[name="permissions[]"]:checked').map(function() {
                        return this.value;
                    }).get()
                };

                const hasChanges = JSON.stringify(originalValues) !== JSON.stringify(currentValues) || $(
                    '#password').val();

                submitButton.prop('disabled', !hasChanges);
                if (hasChanges) {
                    submitButton.removeClass('bg-gray-400').addClass('bg-blue-600');
                } else {
                    submitButton.removeClass('bg-blue-600').addClass('bg-gray-400');
                }
            }

            // Monitor form changes
            $('input, select, textarea').on('input change', detectChanges);
            detectChanges(); // Initial check



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
                    showNotification('error', translations.validation_error, translations
                        .fill_required_fields);
                    return;
                }

                // Check if at least one role is selected
                if ($('input[name="roles[]"]:checked').length === 0) {
                    showNotification('warning', translations.role_required, translations
                        .assign_role_message);
                    return;
                }

                showLoading();
                submitButton.prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin mr-2"></i>' + translations.updating_user);

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
