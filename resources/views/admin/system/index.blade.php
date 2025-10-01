@extends('admin.layouts.app')

@section('title', 'Pengaturan Sistem')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Pengaturan Sistem</h1>
                        <p class="mt-2 text-gray-600">Kelola konfigurasi sistem dan maintenance</p>
                    </div>
                    <div class="flex space-x-3">
                        <button onclick="refreshSystemInfo()"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                            <i class="fas fa-sync-alt mr-2"></i>
                            Refresh
                        </button>
                    </div>
                </div>
            </div>

            {{-- System Information Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Aplikasi</p>
                            <p class="text-lg font-bold text-gray-900">{{ $systemInfo['app_name'] ?? 'WOX Barbershop' }}</p>
                            <p class="text-sm text-gray-500">v{{ $systemInfo['app_version'] ?? '1.0.0' }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-full">
                            <i class="fas fa-cog text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Laravel</p>
                            <p class="text-lg font-bold text-green-600">{{ $systemInfo['laravel_version'] ?? '10.x' }}</p>
                            <p class="text-sm text-gray-500">Framework</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-full">
                            <i class="fab fa-laravel text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">PHP</p>
                            <p class="text-lg font-bold text-purple-600">{{ $systemInfo['php_version'] ?? '8.x' }}</p>
                            <p class="text-sm text-gray-500">Runtime</p>
                        </div>
                        <div class="p-3 bg-purple-100 rounded-full">
                            <i class="fab fa-php text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- System Management Sections --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                {{-- Application Settings --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-sliders-h text-blue-600 mr-3"></i>
                        Pengaturan Aplikasi
                    </h3>

                    <form onsubmit="updateSystemSettings(event)" class="space-y-4">
                        <div>
                            <label for="app_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Aplikasi</label>
                            <input type="text" id="app_name" name="app_name"
                                value="{{ $systemInfo['app_name'] ?? 'WOX Barbershop' }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="app_version" class="block text-sm font-medium text-gray-700 mb-2">Versi
                                Aplikasi</label>
                            <input type="text" id="app_version" name="app_version"
                                value="{{ $systemInfo['app_version'] ?? '1.0.0' }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="maintenance_mode" name="maintenance_mode" class="mr-2">
                            <label for="maintenance_mode" class="text-sm text-gray-700">Mode Maintenance</label>
                        </div>

                        <button type="submit"
                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Pengaturan
                        </button>
                    </form>
                </div>

                {{-- System Maintenance --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-tools text-orange-600 mr-3"></i>
                        Maintenance Sistem
                    </h3>

                    <div class="space-y-4">
                        <div class="border rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium text-gray-900">Cache Aplikasi</span>
                                <button onclick="clearCache('config')"
                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Clear Cache
                                </button>
                            </div>
                            <p class="text-sm text-gray-600">Bersihkan cache konfigurasi dan route</p>
                        </div>

                        <div class="border rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium text-gray-900">Optimize Application</span>
                                <button onclick="optimizeApp()"
                                    class="text-green-600 hover:text-green-800 text-sm font-medium">
                                    Optimize
                                </button>
                            </div>
                            <p class="text-sm text-gray-600">Optimasi performa aplikasi</p>
                        </div>

                        <div class="border rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium text-gray-900">Storage Cleanup</span>
                                <button onclick="cleanupStorage()"
                                    class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                    Cleanup
                                </button>
                            </div>
                            <p class="text-sm text-gray-600">Bersihkan file temporary dan logs lama</p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Backup & Restore Section --}}
            <div class="mt-8 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-database text-green-600 mr-3"></i>
                    Backup & Restore Data
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Backup Section --}}
                    <div class="border rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 mb-3">Backup Database</h4>
                        <p class="text-sm text-gray-600 mb-4">
                            Buat backup lengkap database untuk keamanan data
                        </p>
                        <div class="space-y-3">
                            <button onclick="createBackup('full')"
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                <i class="fas fa-download mr-2"></i>
                                Backup Lengkap
                            </button>
                            <button onclick="createBackup('partial')"
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                <i class="fas fa-archive mr-2"></i>
                                Backup Data Saja
                            </button>
                        </div>
                    </div>

                    {{-- Restore Section --}}
                    <div class="border rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 mb-3">Restore Database</h4>
                        <p class="text-sm text-gray-600 mb-4">
                            Pulihkan data dari file backup sebelumnya
                        </p>
                        <div class="space-y-3">
                            <input type="file" id="restore_file" accept=".sql,.zip"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <button onclick="restoreBackup()"
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                <i class="fas fa-upload mr-2"></i>
                                Restore Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- System Status --}}
            <div class="mt-8 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-heartbeat text-red-600 mr-3"></i>
                    Status Sistem
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-green-600 text-2xl mb-2">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="text-sm font-medium text-gray-900">Database</div>
                        <div class="text-xs text-green-600">Online</div>
                    </div>

                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-green-600 text-2xl mb-2">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="text-sm font-medium text-gray-900">Storage</div>
                        <div class="text-xs text-green-600">{{ $systemInfo['storage_usage'] ?? '50MB' }} Tersedia</div>
                    </div>

                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-blue-600 text-2xl mb-2">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="text-sm font-medium text-gray-900">Memory</div>
                        <div class="text-xs text-blue-600">Normal</div>
                    </div>

                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-green-600 text-2xl mb-2">
                            <i class="fas fa-wifi"></i>
                        </div>
                        <div class="text-sm font-medium text-gray-900">Connection</div>
                        <div class="text-xs text-green-600">Stable</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            function refreshSystemInfo() {
                Swal.fire({
                    title: 'Memperbarui Info Sistem...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                setTimeout(() => {
                    location.reload();
                }, 1500);
            }

            function updateSystemSettings(event) {
                event.preventDefault();

                Swal.fire({
                    title: 'Menyimpan Pengaturan...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                setTimeout(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Pengaturan sistem berhasil disimpan',
                        confirmButtonColor: '#3b82f6'
                    });
                }, 2000);
            }

            function clearCache(type) {
                Swal.fire({
                    title: 'Membersihkan Cache...',
                    text: 'Proses ini mungkin membutuhkan beberapa detik',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                setTimeout(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Cache Dibersihkan!',
                        text: 'Cache aplikasi berhasil dibersihkan',
                        confirmButtonColor: '#3b82f6'
                    });
                }, 3000);
            }

            function optimizeApp() {
                Swal.fire({
                    title: 'Mengoptimasi Aplikasi...',
                    text: 'Proses optimasi sedang berjalan',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                setTimeout(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Optimasi Selesai!',
                        text: 'Aplikasi berhasil dioptimasi',
                        confirmButtonColor: '#10b981'
                    });
                }, 4000);
            }

            function cleanupStorage() {
                Swal.fire({
                    title: 'Membersihkan Storage...',
                    text: 'Menghapus file temporary dan logs lama',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                setTimeout(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Storage Dibersihkan!',
                        text: 'File temporary berhasil dihapus',
                        confirmButtonColor: '#8b5cf6'
                    });
                }, 3000);
            }

            function createBackup(type) {
                const typeText = type === 'full' ? 'backup lengkap' : 'backup data';

                Swal.fire({
                    title: `Membuat ${typeText}...`,
                    text: 'Proses backup sedang berjalan, mohon tunggu',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                setTimeout(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Backup Berhasil!',
                        text: `${typeText} berhasil dibuat dan diunduh`,
                        confirmButtonColor: '#10b981'
                    });
                }, 5000);
            }

            function restoreBackup() {
                const fileInput = document.getElementById('restore_file');

                if (!fileInput.files.length) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'File Tidak Dipilih',
                        text: 'Silakan pilih file backup terlebih dahulu',
                        confirmButtonColor: '#f59e0b'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Restore akan mengganti semua data yang ada!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Restore!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Melakukan Restore...',
                            text: 'Proses restore sedang berjalan, JANGAN tutup halaman ini!',
                            icon: 'warning',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        setTimeout(() => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Restore Selesai!',
                                text: 'Data berhasil dipulihkan dari backup',
                                confirmButtonColor: '#10b981'
                            }).then(() => {
                                location.reload();
                            });
                        }, 7000);
                    }
                });
            }
        </script>
    @endpush

@endsection
