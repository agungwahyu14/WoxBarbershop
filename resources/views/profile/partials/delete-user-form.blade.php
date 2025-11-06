{{-- <section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('profile.delete_account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('profile.delete_account_info') }}
        </p>
    </header>

    <button type="button"
        class="rounded-lg inline-flex items-center px-4 py-2 bg-red-600 border border-transparent  font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
        x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
        {{ __('profile.delete_account') }}
    </button>

    <!-- Modal -->
    <div x-data="{ show: @json($errors->userDeletion->isNotEmpty()) }" x-show="show"
        x-on:open-modal.window="if ($event.detail === 'confirm-user-deletion') show = true"
        x-on:close.window="show = false" x-on:keydown.escape.window="show = false"
        class="fixed inset-0 overflow-y-auto z-50" style="display: none">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- Modal content -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                x-show="show" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                    @csrf
                    @method('delete')

                    <h2 class="text-lg font-medium text-gray-900">
                        {{ __('profile.delete_account_confirmation') }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('profile.delete_account_warning') }}
                    </p>

                    <div class="mt-6">
                        <label for="password" class="sr-only">{{ __('profile.password') }}</label>
                        <input id="password" name="password" type="password"
                            class="rounded-lg mt-1 block w-3/4  border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="{{ __('profile.password') }}" />

                        @if ($errors->userDeletion->get('password'))
                            <p class="mt-2 text-sm text-red-600">
                                {{ $errors->userDeletion->first('password') }}
                            </p>
                        @endif
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="button" x-on:click="$dispatch('close')"
                            class="rounded-lg inline-flex items-center px-4 py-2 bg-white border border-gray-300  font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('profile.cancel') }}
                        </button>

                        <button type="submit"
                            class="rounded-lg  inline-flex items-center px-4 py-2 bg-red-600 border border-transparent  font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 ms-3">
                            {{ __('profile.delete_account') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section> --}}
