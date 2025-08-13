<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="w-full max-w-md bg-white p-10 rounded-3xl shadow-xl border border-gray-200">
        <div class="text-center mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-[#d4af37]" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H8m0 0l4-4m-4 4l4 4" />
            </svg>
            <h2 class="mt-4 text-2xl font-bold text-gray-800">{{ __('Verify Your Email Address') }}</h2>
            <p class="mt-2 text-gray-600 text-sm leading-relaxed">
                {{ __('Thanks for signing up! Please verify your email address by clicking on the link we just sent you. If you didn\'t receive the email, we will gladly send you another.') }}
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div
                class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm border border-green-200 text-center font-medium">
                ✅
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <div class="mt-6 space-y-4">
            {{-- Resend Verification --}}
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit"
                    class="w-full bg-[#d4af37] text-white py-3 rounded-xl shadow-md hover:bg-[#b5942b] transition duration-200 font-semibold">
                    🔄 {{ __('Resend Verification Email') }}
                </button>
            </form>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full bg-gray-100 text-gray-700 py-3 rounded-xl shadow-md hover:bg-gray-200 transition duration-200 font-semibold">
                    🚪 {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</div>
