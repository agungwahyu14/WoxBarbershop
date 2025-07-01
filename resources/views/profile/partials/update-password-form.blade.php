<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block font-medium text-sm text-gray-700">
                {{ __('Current Password') }}
            </label>
            <input id="update_password_current_password" name="current_password" type="password"
                class="mt-1 block w-full py-3 border-0 border-b border-gray-300 shadow-none focus:border-[#d4af37] focus:ring-0 focus:ring-[#d4af37]""
                autocomplete="current-password" />
            @if ($errors->updatePassword->get('current_password'))
                <p class="mt-2 text-sm text-red-600">
                    {{ $errors->updatePassword->first('current_password') }}
                </p>
            @endif
        </div>

        <div>
            <label for="update_password_password" class="block font-medium text-sm text-gray-700">
                {{ __('New Password') }}
            </label>
            <input id="update_password_password" name="password" type="password"
                class="mt-1 block w-full py-3 border-0 border-b border-gray-300 shadow-none focus:border-[#d4af37] focus:ring-0 focus:ring-[#d4af37]"
                autocomplete="new-password" />
            @if ($errors->updatePassword->get('password'))
                <p class="mt-2 text-sm text-red-600">
                    {{ $errors->updatePassword->first('password') }}
                </p>
            @endif
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block font-medium text-sm text-gray-700">
                {{ __('Confirm Password') }}
            </label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="mt-1 block w-full py-3 border-0 border-b border-gray-300 shadow-none focus:border-[#d4af37] focus:ring-0 focus:ring-[#d4af37]"
                autocomplete="new-password" />
            @if ($errors->updatePassword->get('password_confirmation'))
                <p class="mt-2 text-sm text-red-600">
                    {{ $errors->updatePassword->first('password_confirmation') }}
                </p>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-[#d4af37] border border-transparent font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#c2a033] active:bg-[#b08d2e] focus:outline-none focus:ring-2 focus:ring-[#d4af37] focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Simpan') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">
                    {{ __('Simpan.') }}
                </p>
            @endif
        </div>
    </form>
</section>
