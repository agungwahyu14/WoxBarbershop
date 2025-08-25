<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Informasi Profil
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Perbarui informasi profil dan alamat email akun Anda.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Name Field -->
        <div>
            <label for="name" class="block font-medium text-sm text-gray-700">
                Nama
            </label>
            <input id="name" name="name" type="text"
                class="mt-1 block w-full py-3 border-0 border-b border-gray-300 shadow-none focus:border-blue focus:ring-0 focus:ring-blue"
                value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            @if ($errors->get('name'))
                <p class="mt-2 text-sm text-red-600">
                    {{ $errors->first('name') }}
                </p>
            @endif
        </div>

        <!-- Phone Field -->
        <div>
            <label for="no_telepon" class="block font-medium text-sm text-gray-700">
                Nomor Telepon
            </label>
            <input id="no_telepon" name="no_telepon" type="tel"
                class="mt-1 block w-full py-3 border-0 border-b border-gray-300 shadow-none focus:border-blue focus:ring-0 focus:ring-blue"
                value="{{ old('no_telepon', $user->no_telepon) }}" autocomplete="tel" placeholder="+62 123-4567-8901">
            @if ($errors->get('no_telepon'))
                <p class="mt-2 text-sm text-red-600">
                    {{ $errors->first('no_telepon') }}
                </p>
            @endif
        </div>

        <!-- Email Field -->
        <div>
            <label for="email" class="block font-medium text-sm text-gray-700">
                Email
            </label>
            <input id="email" name="email" type="email"
                class="mt-1 block w-full py-3 border-0 border-b border-gray-300 shadow-none focus:border-blue focus:ring-0 focus:ring-blue"
                value="{{ old('email', $user->email) }}" required autocomplete="username" />
            @if ($errors->get('email'))
                <p class="mt-2 text-sm text-red-600">
                    {{ $errors->first('email') }}
                </p>
            @endif

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm text-gray-800">
                        {{ __('Alamat email Anda belum terverifikasi.') }}

                        <button type="submit" form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue">
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Save Button -->
        <div class="flex items-center gap-4">
            <button type="submit"
                class="rounded-lg inline-flex items-center px-4 py-2 bg-blue-700 border border-transparent font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700  focus:outline-none focus:ring-2 focus:ring-blue focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Simpan') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="rounded-lg text-sm text-gray-600">
                    {{ __('Tersimpan.') }}
                </p>
            @endif
        </div>
    </form>
</section>
