@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-6">
                    <svg class="w-8 h-8 text-[#d4af37] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 2.676-1.148 6-4.148 6-7.622a12.02 12.02 0 00.382-2.016z">
                        </path>
                    </svg>
                    <h1 class="text-2xl font-bold text-gray-800">Pengaturan Keamanan</h1>
                </div>

                <!-- Remember Me Status -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-700 mb-3">Status "Ingat Saya"</h2>
                    <div class="bg-gray-50 rounded-lg p-4">
                        @if (auth()->user()->remember_token)
                            <div class="flex items-center text-green-600 mb-2">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-medium">Aktif - Anda akan diingat selama 30 hari</span>
                            </div>
                            <p class="text-sm text-gray-600">
                                Last login:
                                {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->format('d M Y, H:i') : 'Tidak tersedia' }}
                            </p>
                        @else
                            <div class="flex items-center text-gray-500 mb-2">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-medium">Tidak Aktif - Anda perlu login setiap kali</span>
                            </div>
                            <p class="text-sm text-gray-600">
                                Untuk kemudahan akses, aktifkan "Ingat saya" saat login berikutnya
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Security Information -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-700 mb-3">Informasi Keamanan</h2>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <div class="w-2 h-2 bg-[#d4af37] rounded-full mt-2 mr-3"></div>
                            <div>
                                <p class="font-medium text-gray-700">Durasi Remember Token</p>
                                <p class="text-sm text-gray-600">Token "Ingat saya" berlaku selama 30 hari sejak login
                                    terakhir</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-2 h-2 bg-[#d4af37] rounded-full mt-2 mr-3"></div>
                            <div>
                                <p class="font-medium text-gray-700">Keamanan Perangkat</p>
                                <p class="text-sm text-gray-600">Token hanya berlaku untuk perangkat dan browser yang Anda
                                    gunakan</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-2 h-2 bg-[#d4af37] rounded-full mt-2 mr-3"></div>
                            <div>
                                <p class="font-medium text-gray-700">Pembersihan Otomatis</p>
                                <p class="text-sm text-gray-600">Token yang sudah kedaluwarsa akan dibersihkan secara
                                    otomatis</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="border-t pt-4">
                    <h2 class="text-lg font-semibold text-gray-700 mb-3">Tindakan</h2>
                    <div class="flex flex-wrap gap-3">
                        @if (auth()->user()->remember_token)
                            <form action="{{ route('security.revoke-remember-token') }}" method="POST"
                                id="revokeTokenForm">
                                @csrf
                                <button type="button" onclick="confirmRevokeToken()"
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                    Batalkan "Ingat Saya"
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('profile.edit') }}"
                            class="bg-[#d4af37] hover:bg-[#111111] text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                            Pengaturan Profil
                        </a>

                        <a href="{{ route('password.request') }}"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                            Ubah Password
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmRevokeToken() {
            Swal.fire({
                title: 'Batalkan "Ingat Saya"?',
                text: 'Anda akan perlu login ulang di semua perangkat.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('revokeTokenForm').submit();
                }
            });
        }

        // Show success message if redirected from revoke action
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#d4af37'
            });
        @endif
    </script>
@endsection
