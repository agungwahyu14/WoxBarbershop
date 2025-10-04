@extends('admin.layouts.app')

@section('title', 'Backup & Restore Data')

@section('content')

    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div>
                <h1 class="title text-3xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-box mr-3"></i> Backup & Restore Data
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    Kelola backup dan restore database sistem
                </p>
            </div>
        </div>
    </section>


    <section class="section min-h-screen main-section">
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-8">

                <!-- Backup and Restore Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Backup Section -->
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-xl p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-download text-white" aria-hidden="true"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Backup Database</h3>
                        </div>
                        <p class="text-gray-600 mb-6">
                            Buat backup database untuk melindungi data penting dari kehilangan atau kerusakan.
                        </p>
                        <div class="space-y-3">
                            <button onclick="createBackup('full')"
                                class="w-full inline-flex justify-center items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg"
                                aria-label="Backup Lengkap (Database + Files)">
                                <i class="fas fa-database mr-3" aria-hidden="true"></i>
                                Backup Lengkap (Database + Files)
                            </button>
                            <button onclick="createBackup('partial')"
                                class="w-full inline-flex justify-center items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg"
                                aria-label="Backup Data Saja">
                                <i class="fas fa-archive mr-3" aria-hidden="true"></i>
                                Backup Data Saja
                            </button>
                            <div class="text-xs text-gray-500 text-center mt-3">
                                Backup akan disimpan dengan timestamp untuk identifikasi
                            </div>
                        </div>
                    </div>

                    <!-- Restore Section -->
                    <div class="bg-gradient-to-br from-red-50 to-pink-50 border border-red-200 rounded-xl p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-upload text-white" aria-hidden="true"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Restore Database</h3>
                        </div>
                        <p class="text-gray-600 mb-6">
                            Pulihkan database dari file backup. <strong>Perhatian:</strong> Ini akan mengganti semua data
                            yang ada.
                        </p>
                        <div class="space-y-4">
                            <div>
                                <label for="restore_file" class="block text-sm font-medium text-gray-700 mb-2">
                                    Pilih File Backup (.sql, .zip)
                                </label>
                                <input type="file" id="restore_file" accept=".sql,.zip"
                                    class="w-full px-4 py-3 border-2 border-dashed border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-center cursor-pointer hover:border-red-400 transition-colors"
                                    aria-label="Pilih file backup untuk restore">
                            </div>
                            <button onclick="restoreBackup()"
                                class="w-full inline-flex justify-center items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg"
                                aria-label="Restore Database">
                                <i class="fas fa-exclamation-triangle mr-3" aria-hidden="true"></i>
                                Restore Database
                            </button>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle text-yellow-600 mr-2" aria-hidden="true"></i>
                                    <span class="text-xs text-yellow-800">
                                        <strong>Peringatan:</strong> Proses restore akan menghapus semua data saat ini!
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Backup History Section -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-history text-gray-600 mr-3" aria-hidden="true"></i>
                        Riwayat Backup Terbaru
                    </h4>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-center text-gray-500">
                            <i class="fas fa-folder-open text-3xl mb-2" aria-hidden="true"></i>
                            <p>Belum ada riwayat backup</p>
                            <p class="text-sm">Backup pertama akan muncul di sini</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            function createBackup(type) {
                const typeText = type === 'full' ? 'backup lengkap (database + files)' : 'backup data saja';

                Swal.fire({
                    title: 'Konfirmasi Backup',
                    text: `Apakah Anda yakin ingin membuat ${typeText}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Buat Backup!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: `Membuat ${typeText}...`,
                            html: `
                                <div class="text-center">
                                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-600 mx-auto mb-4"></div>
                                    <p>Proses backup sedang berjalan, mohon tunggu...</p>
                                    <p class="text-sm text-gray-600 mt-2">Jangan tutup halaman ini!</p>
                                </div>
                            `,
                            allowOutsideClick: false,
                            showConfirmButton: false,
                        });

                        // Create form data for backup
                        const formData = new FormData();
                        formData.append('type', type);
                        formData.append('_token', '{{ csrf_token() }}');

                        // Send backup request
                        fetch('{{ route('admin.system.backup') }}', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => {
                                if (response.ok) {
                                    return response.blob();
                                }
                                throw new Error('Backup gagal');
                            })
                            .then(blob => {
                                // Create download link
                                const url = window.URL.createObjectURL(blob);
                                const a = document.createElement('a');
                                a.style.display = 'none';
                                a.href = url;
                                a.download =
                                    `backup_${type}_${new Date().toISOString().slice(0, 19).replace(/:/g, '-')}.sql`;
                                document.body.appendChild(a);
                                a.click();
                                window.URL.revokeObjectURL(url);

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Backup Berhasil!',
                                    html: `
                                    <div class="text-center">
                                        <p class="mb-2">${typeText} berhasil dibuat!</p>
                                        <p class="text-sm text-gray-600">File backup telah diunduh otomatis</p>
                                    </div>
                                `,
                                    confirmButtonColor: '#10b981',
                                    confirmButtonText: 'OK'
                                });
                            })
                            .catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Backup Gagal!',
                                    text: 'Terjadi kesalahan saat membuat backup',
                                    confirmButtonColor: '#dc2626'
                                });
                            });
                    }
                });
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

                const fileName = fileInput.files[0].name;
                const fileSize = (fileInput.files[0].size / 1024 / 1024).toFixed(2);

                Swal.fire({
                    title: 'PERINGATAN PENTING!',
                    html: `
                        <div class="text-left">
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                                    <span class="font-bold text-red-800">Proses ini akan menghapus SEMUA data yang ada!</span>
                                </div>
                                <ul class="text-sm text-red-700 ml-6 list-disc">
                                    <li>Semua data user, booking, dan transaksi akan diganti</li>
                                    <li>Perubahan ini TIDAK dapat dibatalkan</li>
                                    <li>Pastikan Anda memiliki backup data saat ini</li>
                                </ul>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-sm"><strong>File:</strong> ${fileName}</p>
                                <p class="text-sm"><strong>Ukuran:</strong> ${fileSize} MB</p>
                            </div>
                        </div>
                    `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Saya Yakin Restore!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Melakukan Restore Database...',
                            html: `
                                <div class="text-center">
                                    <div class="animate-pulse">
                                        <div class="bg-red-100 rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-4">
                                            <i class="fas fa-database text-red-600 text-2xl"></i>
                                        </div>
                                    </div>
                                    <p class="font-semibold text-red-600">Proses restore sedang berjalan...</p>
                                    <p class="text-sm text-gray-600 mt-2">JANGAN tutup halaman ini atau browser!</p>
                                    <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                        <p class="text-xs text-yellow-800">Estimasi waktu: 2-5 menit tergantung ukuran file</p>
                                    </div>
                                </div>
                            `,
                            allowOutsideClick: false,
                            showConfirmButton: false,
                        });

                        // Create form data for restore
                        const formData = new FormData();
                        formData.append('backup_file', fileInput.files[0]);
                        formData.append('_token', '{{ csrf_token() }}');

                        // Send restore request
                        fetch('{{ route('admin.system.restore') }}', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Restore Selesai!',
                                        html: `
                                        <div class="text-center">
                                            <p class="mb-2">Database berhasil dipulihkan dari backup!</p>
                                            <p class="text-sm text-gray-600">File: <code>${fileName}</code></p>
                                            <div class="mt-4 bg-green-50 border border-green-200 rounded-lg p-3">
                                                <p class="text-sm text-green-800">Halaman akan dimuat ulang untuk menerapkan perubahan</p>
                                            </div>
                                        </div>
                                    `,
                                        confirmButtonColor: '#10b981',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Restore Gagal!',
                                        text: data.message || 'Terjadi kesalahan saat melakukan restore',
                                        confirmButtonColor: '#dc2626'
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Restore Gagal!',
                                    text: 'Terjadi kesalahan saat melakukan restore',
                                    confirmButtonColor: '#dc2626'
                                });
                            });
                    }
                });
            }

            // Enhanced file input styling
            document.addEventListener('DOMContentLoaded', function() {
                const fileInput = document.getElementById('restore_file');

                fileInput.addEventListener('change', function(e) {
                    if (e.target.files.length > 0) {
                        const file = e.target.files[0];
                        const fileSize = (file.size / 1024 / 1024).toFixed(2);
                        e.target.style.borderColor = '#10b981';
                        e.target.style.backgroundColor = '#f0fdf4';

                        // Show file info
                        const existingInfo = document.querySelector('.file-info');
                        if (existingInfo) existingInfo.remove();

                        const fileInfo = document.createElement('div');
                        fileInfo.className = 'file-info text-xs text-green-700 mt-2 text-center';
                        fileInfo.innerHTML =
                            `<i class="fas fa-check-circle mr-1"></i> ${file.name} (${fileSize} MB)`;
                        e.target.parentNode.appendChild(fileInfo);
                    }
                });
            });
        </script>
    @endpush

@endsection
