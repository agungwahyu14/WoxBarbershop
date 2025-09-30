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

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Profile Photo Field -->
        {{-- <div>
            <label class="block font-medium text-sm text-gray-700 mb-2">
                Foto Profil
            </label>

            <!-- Current Photo Display -->
            <div class="flex items-center space-x-4 mb-4">
                <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-gray-300">
                    <img src="{{ $user->profile_photo_url }}" alt="Profile Photo" class="w-full h-full object-cover"
                        id="profile-preview">
                </div>
                <div class="flex-1">
                    <input type="file" name="profile_photo" id="profile_photo" accept="image/*" class="hidden"
                        onchange="previewImage(this)">
                    <label for="profile_photo"
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none cursor-pointer">
                        <i class="fas fa-camera mr-2"></i>
                        Pilih Foto
                    </label>
                    <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, GIF. Maksimal 2MB.</p>
                </div>
            </div>

            @if ($errors->get('profile_photo'))
                <p class="mt-2 text-sm text-red-600">
                    {{ $errors->first('profile_photo') }}
                </p>
            @endif
        </div> --}}

        <!-- Name Field -->
        <div>
            <label for="name" class="block font-medium text-sm text-gray-700">
                Nama
            </label>
            <input id="name" name="name" type="text"
                class="mt-1 block w-full py-3 border-0 border-b border-gray-300 shadow-none focus:border-[#d4af37] focus:ring-0 focus:ring-[#d4af37]"
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
                class="mt-1 block w-full py-3 border-0 border-b border-gray-300 shadow-none focus:border-[#d4af37] focus:ring-0 focus:ring-[#d4af37]"
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
                class="mt-1 block w-full py-3 border-0 border-b border-gray-300 shadow-none focus:border-[#d4af37] focus:ring-0 focus:ring-[#d4af37]"
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
                            class="underline text-sm text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#d4af37]">
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
                class="rounded-lg inline-flex items-center px-4 py-2 bg-[#d4af37] border border-transparent font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#c19b2e] focus:outline-none focus:ring-2 focus:ring-[#d4af37] focus:ring-offset-2 transition ease-in-out duration-150">
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

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];

            // Validate file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: 'Please select a file smaller than 2MB.',
                    toast: true,
                    position: 'top-end',
                    timer: 3000,
                    showConfirmButton: false
                });
                input.value = '';
                return;
            }

            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File Type',
                    text: 'Please select a valid image file (JPG, PNG, or GIF).',
                    toast: true,
                    position: 'top-end',
                    timer: 3000,
                    showConfirmButton: false
                });
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profile-preview').src = e.target.result;
                Swal.fire({
                    icon: 'success',
                    title: 'Photo Selected',
                    text: 'Click Save to upload your new profile photo.',
                    toast: true,
                    position: 'top-end',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
            reader.readAsDataURL(file);
        }
    }
</script>
