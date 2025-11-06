<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('profile.update_password') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('profile.password_security_info') }}
        </p>
    </header>

    <form id="password-update-form" method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block font-medium text-sm text-gray-700">
                {{ __('profile.current_password') }}
            </label>
            <div class="relative">
                <input id="update_password_current_password" name="current_password" type="password"
                    class="mt-1 block w-full py-3 pr-10 border-0 border-b border-gray-300 shadow-none focus:border-[#d4af37] focus:ring-0 focus:ring-[#d4af37]"
                    autocomplete="current-password" />
                <button type="button" id="toggleCurrentPassword"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <svg id="eyeCurrentIcon" class="w-5 h-5 text-gray-400 hover:text-gray-600" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                        </path>
                    </svg>
                    <svg id="eyeSlashCurrentIcon" class="w-5 h-5 text-gray-400 hover:text-gray-600 hidden"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L8.464 8.464M9.878 9.878l-1.414-1.414M14.12 14.12l1.415 1.414M14.12 14.12L15.535 15.535M14.12 14.12l1.415-1.414M3 3l18 18">
                        </path>
                    </svg>
                </button>
            </div>
            @if ($errors->updatePassword->get('current_password'))
                <p class="mt-2 text-sm text-red-600">
                    {{ $errors->updatePassword->first('current_password') }}
                </p>
            @endif
        </div>

        <div>
            <label for="update_password_password" class="block font-medium text-sm text-gray-700">
                {{ __('profile.new_password') }}
            </label>
            <div class="relative">
                <input id="update_password_password" name="password" type="password"
                    class="mt-1 block w-full py-3 pr-10 border-0 border-b border-gray-300 shadow-none focus:border-[#d4af37] focus:ring-0 focus:ring-[#d4af37]"
                    autocomplete="new-password" />
                <button type="button" id="toggleNewPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <svg id="eyeNewIcon" class="w-5 h-5 text-gray-400 hover:text-gray-600" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                        </path>
                    </svg>
                    <svg id="eyeSlashNewIcon" class="w-5 h-5 text-gray-400 hover:text-gray-600 hidden" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L8.464 8.464M9.878 9.878l-1.414-1.414M14.12 14.12l1.415 1.414M14.12 14.12L15.535 15.535M14.12 14.12l1.415-1.414M3 3l18 18">
                        </path>
                    </svg>
                </button>
            </div>
            @if ($errors->updatePassword->get('password'))
                <p class="mt-2 text-sm text-red-600">
                    {{ $errors->updatePassword->first('password') }}
                </p>
            @endif
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block font-medium text-sm text-gray-700">
                {{ __('profile.confirm_password') }}
            </label>
            <div class="relative">
                <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                    class="mt-1 block w-full py-3 pr-10 border-0 border-b border-gray-300 shadow-none focus:border-[#d4af37] focus:ring-0 focus:ring-[#d4af37]"
                    autocomplete="new-password" />
                <button type="button" id="toggleConfirmNewPassword"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <svg id="eyeConfirmNewIcon" class="w-5 h-5 text-gray-400 hover:text-gray-600" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                        </path>
                    </svg>
                    <svg id="eyeSlashConfirmNewIcon" class="w-5 h-5 text-gray-400 hover:text-gray-600 hidden"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L8.464 8.464M9.878 9.878l-1.414-1.414M14.12 14.12l1.415 1.414M14.12 14.12L15.535 15.535M14.12 14.12l1.415-1.414M3 3l18 18">
                        </path>
                    </svg>
                </button>
            </div>
            @if ($errors->updatePassword->get('password_confirmation'))
                <p class="mt-2 text-sm text-red-600">
                    {{ $errors->updatePassword->first('password_confirmation') }}
                </p>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit"
                class="rounded-lg inline-flex items-center px-4 py-2 bg-[#d4af37] border border-transparent font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#c2a033] active:bg-[#b08d2e] focus:outline-none focus:ring-2 focus:ring-[#d4af37] focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('profile.save') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="rounded-lg text-sm text-gray-600">
                    {{ __('profile.password_updated') }}
                </p>
            @endif
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle current password visibility
            const toggleCurrentPassword = document.getElementById('toggleCurrentPassword');
            const currentPasswordInput = document.getElementById('update_password_current_password');
            const eyeCurrentIcon = document.getElementById('eyeCurrentIcon');
            const eyeSlashCurrentIcon = document.getElementById('eyeSlashCurrentIcon');

            if (toggleCurrentPassword && currentPasswordInput && eyeCurrentIcon && eyeSlashCurrentIcon) {
                toggleCurrentPassword.addEventListener('click', function() {
                    const type = currentPasswordInput.getAttribute('type') === 'password' ? 'text' :
                        'password';
                    currentPasswordInput.setAttribute('type', type);
                    eyeCurrentIcon.classList.toggle('hidden');
                    eyeSlashCurrentIcon.classList.toggle('hidden');
                });
            }

            // Toggle new password visibility
            const toggleNewPassword = document.getElementById('toggleNewPassword');
            const newPasswordInput = document.getElementById('update_password_password');
            const eyeNewIcon = document.getElementById('eyeNewIcon');
            const eyeSlashNewIcon = document.getElementById('eyeSlashNewIcon');

            if (toggleNewPassword && newPasswordInput && eyeNewIcon && eyeSlashNewIcon) {
                toggleNewPassword.addEventListener('click', function() {
                    const type = newPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    newPasswordInput.setAttribute('type', type);
                    eyeNewIcon.classList.toggle('hidden');
                    eyeSlashNewIcon.classList.toggle('hidden');
                });
            }

            // Toggle confirm password visibility
            const toggleConfirmNewPassword = document.getElementById('toggleConfirmNewPassword');
            const confirmNewPasswordInput = document.getElementById('update_password_password_confirmation');
            const eyeConfirmNewIcon = document.getElementById('eyeConfirmNewIcon');
            const eyeSlashConfirmNewIcon = document.getElementById('eyeSlashConfirmNewIcon');

            if (toggleConfirmNewPassword && confirmNewPasswordInput && eyeConfirmNewIcon &&
                eyeSlashConfirmNewIcon) {
                toggleConfirmNewPassword.addEventListener('click', function() {
                    const type = confirmNewPasswordInput.getAttribute('type') === 'password' ? 'text' :
                        'password';
                    confirmNewPasswordInput.setAttribute('type', type);
                    eyeConfirmNewIcon.classList.toggle('hidden');
                    eyeSlashConfirmNewIcon.classList.toggle('hidden');
                });
            }
        });
    </script>
</section>
