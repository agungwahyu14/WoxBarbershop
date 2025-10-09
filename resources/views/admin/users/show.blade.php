@extends('admin.layouts.app')

@section('title', __('admin.user_details') . ' - ' . $user->name)

@section('content')
    <!-- Enhanced Page Header -->
    <div class="is-hero-bar">

        <div class="flex flex-col md:flex-row justify-between items-center gap-6">

            <!-- Left: Title & Description -->
            <div class="text-center md:text-left">
                <h1 class="text-3xl md:text-4xl font-bold mb-2 flex items-center justify-center md:justify-start">
                    <i class="fas fa-user mr-3 text-gray-700"></i>
                    {{ __('admin.user_details_title') }}
                </h1>
                <p class="text-gray-700 text-lg">
                    {{ __('admin.user_details_subtitle') }}
                </p>
            </div>

            <!-- Right: Action Buttons -->
            <div class="flex flex-wrap gap-3 justify-center md:justify-end">
                <a href="{{ route('admin.users.edit', $user) }}"
                    class="flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors duration-200">
                    <i class="fas fa-edit mr-2"></i>{{ __('admin.edit_user_btn') }}
                </a>

                <a href="{{ route('admin.users.index') }}"
                    class="flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>{{ __('admin.back_to_users_btn') }}
                </a>

                @if ($user->is_active ?? true)
                    <button type="button" onclick="toggleUserStatus('deactivate')"
                        class="flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-200">
                        <i class="fas fa-user-slash mr-2"></i>{{ __('admin.deactivate_account_btn') }}
                    </button>
                @else
                    <button type="button" onclick="toggleUserStatus('activate')"
                        class="flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors duration-200">
                        <i class="fas fa-user-check mr-2"></i>{{ __('admin.activate_account_btn') }}
                    </button>
                @endif
            </div>
        </div>
    </div>



    <div class="section main-section">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - User Information -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Contact Information -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-address-book mr-3 text-blue-600"></i>{{ __('admin.user_information') }}
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex items-center space-x-4">
                                <div class="bg-blue-100 p-3 rounded-lg">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">{{ __('admin.name') }}</p>
                                    <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="bg-blue-100 p-3 rounded-lg">
                                    <i class="fas fa-user-tie text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">{{ __('admin.roles') }}</p>
                                    <p class="font-medium text-gray-900">
                                    <div class="flex flex-wrap justify-center md:justify-start gap-2">
                                        @foreach ($user->roles as $role)
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-user-tag mr-1"></i>{{ $role->name }}
                                            </span>
                                        @endforeach
                                        {{-- <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                {{ $user->hasVerifiedEmail() ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            <i
                                                class="fas fa-{{ $user->hasVerifiedEmail() ? 'check-circle' : 'exclamation-triangle' }} mr-1"></i>
                                            {{ $user->hasVerifiedEmail() ? __('admin.email_verified') : __('admin.email_unverified') }}
                                        </span> --}}
                                    </div>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="bg-blue-100 p-3 rounded-lg">
                                    <i class="fas fa-envelope text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">{{ __('admin.email_address') }}</p>
                                    <p class="font-medium text-gray-900">{{ $user->email }}</p>
                                </div>
                            </div>
                            <!-- Bagian Nomor Telepon (tetap sama) -->
                            <div class="flex items-center space-x-4">
                                <div class="bg-green-100 p-3 rounded-lg">
                                    <i class="fas fa-phone text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">{{ __('admin.phone_number') }}</p>
                                    <p class="font-medium text-gray-900">
                                        {{ $user->no_telepon ?: __('admin.not_provided') }}</p>
                                </div>
                            </div>
                            <!-- Bagian Poin Loyalitas (diubah ke format ternary) -->
                            @if ($user->loyalty)
                                <div class="flex items-center space-x-4">
                                    <div class="bg-yellow-100 p-3 rounded-lg">
                                        <i class="fas fa-star text-yellow-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">{{ __('admin.loyalty_points') }}</p>
                                        <p class="font-medium text-gray-900">
                                            {{ number_format($user->loyalty->points) }} {{ __('admin.points') }}
                                        </p>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center space-x-4 mt-4">
                                    <div class="bg-gray-100 p-3 rounded-lg">
                                        <i class="fas fa-star text-gray-400"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">{{ __('admin.loyalty_points') }}</p>
                                        <p class="font-medium text-gray-900">{{ __('admin.not_provided') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Account Status -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-user-check mr-3 text-blue-600"></i>{{ __('admin.account_information') }}
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <div
                                    class="text-2xl font-bold {{ $user->is_active ?? true ? 'text-green-600' : 'text-red-600' }} mb-2">
                                    <i class="fas fa-{{ $user->is_active ?? true ? 'check-circle' : 'times-circle' }}"></i>
                                </div>
                                <p class="text-sm text-gray-500">{{ __('admin.user_status') }}</p>
                                <p class="font-medium {{ $user->is_active ?? true ? 'text-green-900' : 'text-red-900' }}">
                                    {{ $user->is_active ?? true ? __('admin.active') : __('admin.inactive') }}
                                </p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <div
                                    class="text-2xl font-bold {{ $user->hasVerifiedEmail() ? 'text-green-600' : 'text-yellow-600' }} mb-2">
                                    <i
                                        class="fas fa-{{ $user->hasVerifiedEmail() ? 'check-circle' : 'exclamation-triangle' }}"></i>
                                </div>
                                <p class="text-sm text-gray-500">{{ __('admin.email_address') }}</p>
                                <p
                                    class="font-medium {{ $user->hasVerifiedEmail() ? 'text-green-900' : 'text-yellow-900' }}">
                                    {{ $user->hasVerifiedEmail() ? __('admin.verified') : __('admin.unverified') }}
                                </p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <div class="text-2xl font-bold text-blue-600 mb-2">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <p class="text-sm text-gray-500">{{ __('admin.last_login') }}</p>
                                <p class="font-medium text-gray-900">
                                    {{ $user->last_login_at ? $user->last_login_at->format('M d, Y') : __('admin.never') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Roles and Permissions -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-shield-alt mr-3 text-blue-600"></i>{{ __('admin.roles') }}
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Roles -->
                            <div>
                                <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-users-cog mr-2 text-blue-500"></i>{{ __('admin.assigned_roles') }}
                                </h4>
                                <div class="space-y-3">
                                    @forelse($user->roles as $role)
                                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                                            <div class="flex items-center space-x-3">
                                                <div class="bg-blue-100 p-2 rounded-lg">
                                                    <i class="fas fa-user-tag text-blue-600"></i>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-blue-900 capitalize">{{ $role->name }}
                                                    </p>
                                                    {{-- <p class="text-sm text-blue-600">{{ $role->permissions->count() }}
                                                        permissions</p> --}}
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-8 text-gray-500">
                                            <i class="fas fa-user-times text-4xl mb-4"></i>
                                            <p>{{ __('admin.no_roles_assigned') }}</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Permissions -->
                            {{-- <div>
                                <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-key mr-2 text-green-500"></i>All Permissions
                                </h4>
                                <div class="space-y-2 max-h-64 overflow-y-auto">
                                    @forelse($user->getAllPermissions() as $permission)
                                        <div class="flex items-center space-x-3 p-2 bg-green-50 rounded-lg">
                                            <div class="bg-green-100 p-1 rounded">
                                                <i class="fas fa-check text-green-600 text-xs"></i>
                                            </div>
                                            <span
                                                class="text-sm text-green-900 capitalize">{{ str_replace('_', ' ', $permission->name) }}</span>
                                        </div>
                                    @empty
                                        <div class="text-center py-8 text-gray-500">
                                            <i class="fas fa-key text-4xl mb-4"></i>
                                            <p>No permissions assigned</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                {{-- <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-history mr-3 text-blue-600"></i>Recent Activity
                        </h3>
                    </div>
                    <div class="p-6">
                        @if ($user->bookings && $user->bookings->count() > 0)
                            <div class="space-y-4">
                                @foreach ($user->bookings->sortByDesc('id')->take(5) as $booking)
                                    <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                                        <div class="bg-blue-100 p-3 rounded-lg">
                                            <i class="fas fa-calendar-check text-blue-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-900">Booking #{{ $booking->id }}</p>
                                            <p class="text-sm text-gray-600">
                                                {{ $booking->date_time->format('M d, Y g:i A') }}</p>
                                        </div>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if ($booking->status === 'confirmed') bg-green-100 text-green-800
                                            @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-calendar-times text-4xl mb-4"></i>
                                <p>No recent activity</p>
                            </div>
                        @endif
                    </div>
                </div> --}}

                <div class="">

                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-200">
                            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-chart-bar mr-3 text-blue-600"></i>{{ __('admin.account_statistics') }}
                            </h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-blue-600 mb-2">
                                    {{ $user->bookings ? $user->bookings->count() : 0 }}
                                </div>
                                <p class="text-sm text-gray-500">{{ __('admin.total_bookings') }}</p>
                            </div>

                            <div class="text-center">
                                <div class="text-3xl font-bold text-purple-600 mb-2">
                                    {{ $user->created_at->diffInDays(now()) }}
                                </div>
                                <p class="text-sm text-gray-500">{{ __('admin.days_as_member') }}</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Translation variables
        const translations = {
            reset_password_title: @json(__('admin.reset_password_title')),
            reset_password_text: @json(__('admin.reset_password_text')),
            yes_send_reset_email: @json(__('admin.yes_send_reset_email')),
            cancel: @json(__('admin.cancel')),
            activate_account_title: @json(__('admin.activate_account_title')),
            deactivate_account_title: @json(__('admin.deactivate_account_title')),
            activate_account_text: @json(__('admin.activate_account_text')),
            deactivate_account_text: @json(__('admin.deactivate_account_text')),
            yes_activate: @json(__('admin.yes_activate')),
            yes_deactivate: @json(__('admin.yes_deactivate')),
            password_reset: @json(__('admin.password_reset')),
            password_reset_success: @json(__('admin.password_reset_success')),
            account_updated: @json(__('admin.account_updated')),
            user_account_activated: @json(__('admin.user_account_activated')),
            user_account_deactivated: @json(__('admin.user_account_deactivated')),
            error: @json(__('admin.error')),
            failed_send_reset_email: @json(__('admin.failed_send_reset_email')),
            failed_activate_account: @json(__('admin.failed_activate_account')),
            failed_deactivate_account: @json(__('admin.failed_deactivate_account'))
        };

        function resetUserPassword() {
            Swal.fire({
                title: translations.reset_password_title,
                text: translations.reset_password_text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#6b7280',
                confirmButtonText: translations.yes_send_reset_email,
                cancelButtonText: translations.cancel
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading();

                    $.post('{{ route('admin.users.reset-password', $user) }}', {
                            _token: '{{ csrf_token() }}'
                        })
                        .done(function(response) {
                            hideLoading();
                            showNotification('success', translations.password_reset,
                                translations.password_reset_success);
                        })
                        .fail(function() {
                            hideLoading();
                            showNotification('error', translations.error, translations.failed_send_reset_email);
                        });
                }
            });
        }

        function toggleUserStatus(action) {
            const isActivating = action === 'activate';
            const title = isActivating ? translations.activate_account_title : translations.deactivate_account_title;
            const text = isActivating ? translations.activate_account_text : translations.deactivate_account_text;
            const confirmText = isActivating ? translations.yes_activate : translations.yes_deactivate;
            const color = isActivating ? '#10b981' : '#ef4444';

            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: color,
                cancelButtonColor: '#6b7280',
                confirmButtonText: confirmText,
                cancelButtonText: translations.cancel
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading();

                    $.post('{{ route('admin.users.toggle-status', $user) }}', {
                            _token: '{{ csrf_token() }}',
                            action: action
                        })
                        .done(function(response) {
                            hideLoading();
                            const successMessage = isActivating ? translations.user_account_activated :
                                translations.user_account_deactivated;
                            showNotification('success', translations.account_updated, successMessage);

                            // Reload the page to reflect changes
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        })
                        .fail(function() {
                            hideLoading();
                            const errorMessage = isActivating ? translations.failed_activate_account :
                                translations.failed_deactivate_account;
                            showNotification('error', translations.error, errorMessage);
                        });
                }
            });
        }
    </script>
@endpush
