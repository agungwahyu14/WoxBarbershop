@extends('admin.layouts.app')

@section('title', 'Backup & Restore Data')

@section('content')
    <!-- Page Header -->
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div>
                <h1 class="title text-3xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-box mr-3"></i> {{ __('admin.system_page_title') }}
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    {{ __('admin.system_page_subtitle') }}
                </p>
            </div>
        </div>
    </section>
    <!-- Main Section -->
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
                            <h3 class="text-lg font-bold text-gray-900">{{ __('admin.backup_database') }}</h3>
                        </div>
                        <p class="text-gray-600 mb-6">
                            {{ __('admin.backup_description') }}
                        </p>
                        <div class="space-y-3">
                            <button onclick="createBackup('full')"
                                class="w-full inline-flex justify-center items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg"
                                aria-label="Backup Lengkap (Database + Files)">
                                <i class="fas fa-database mr-3" aria-hidden="true"></i>
                                {{ __('admin.backup_full') }}
                            </button>
                            <button onclick="createBackup('partial')"
                                class="w-full inline-flex justify-center items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg"
                                aria-label="Backup Data Saja">
                                <i class="fas fa-archive mr-3" aria-hidden="true"></i>
                                {{ __('admin.backup_partial') }}
                            </button>
                            <div class="text-xs text-gray-500 text-center mt-3">
                                {{ __('admin.backup_timestamp_info') }}
                            </div>
                        </div>
                    </div>

                    <!-- Restore Section -->
                    <div class="bg-gradient-to-br from-red-50 to-pink-50 border border-red-200 rounded-xl p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-upload text-white" aria-hidden="true"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">{{ __('admin.restore_database') }}</h3>
                        </div>
                        <p class="text-gray-600 mb-6">
                            {{ __('admin.restore_description') }}
                        </p>
                        <div class="space-y-4">
                            <div>
                                <label for="restore_file" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('admin.select_backup_file') }}
                                </label>
                                <input type="file" id="restore_file" accept=".sql,.zip"
                                    class="w-full px-4 py-3 border-2 border-dashed border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-center cursor-pointer hover:border-red-400 transition-colors"
                                    aria-label="Pilih file backup untuk restore">
                            </div>
                            <button onclick="restoreBackup()"
                                class="w-full inline-flex justify-center items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg"
                                aria-label="Restore Database">
                                <i class="fas fa-exclamation-triangle mr-3" aria-hidden="true"></i>
                                {{ __('admin.restore_database') }}
                            </button>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle text-yellow-600 mr-2" aria-hidden="true"></i>
                                    <span class="text-xs text-yellow-800">
                                        {{ __('admin.restore_warning') }}
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
                        {{ __('admin.backup_history') }}
                    </h4>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-center text-gray-500">
                            <i class="fas fa-folder-open text-3xl mb-2" aria-hidden="true"></i>
                            <p>{{ __('admin.no_backup_history') }}</p>
                            <p class="text-sm">{{ __('admin.first_backup_message') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            // Translation variables
            const translations = {
                confirm_backup: @json(__('admin.confirm_backup')),
                backup_question: @json(__('admin.backup_question')),
                yes_create_backup: @json(__('admin.yes_create_backup')),
                cancel: @json(__('admin.cancel')),
                creating_backup: @json(__('admin.creating_backup')),
                backup_process_wait: @json(__('admin.backup_process_wait')),
                dont_close_page: @json(__('admin.dont_close_page')),
                backup_failed: @json(__('admin.backup_failed')),
                backup_success: @json(__('admin.backup_success')),
                backup_created_successfully: @json(__('admin.backup_created_successfully')),
                file_downloaded: @json(__('admin.file_downloaded')),
                backup_error: @json(__('admin.backup_error')),
                file_not_selected: @json(__('admin.file_not_selected')),
                select_backup_first: @json(__('admin.select_backup_first')),
                important_warning: @json(__('admin.important_warning')),
                restore_warning_text: @json(__('admin.restore_warning_text')),
                data_will_be_replaced: @json(__('admin.data_will_be_replaced')),
                changes_irreversible: @json(__('admin.changes_irreversible')),
                ensure_current_backup: @json(__('admin.ensure_current_backup')),
                file_label: @json(__('admin.file_label')),
                size_label: @json(__('admin.size_label')),
                yes_sure_restore: @json(__('admin.yes_sure_restore')),
                restoring_database: @json(__('admin.restoring_database')),
                restore_in_progress: @json(__('admin.restore_in_progress')),
                estimated_time: @json(__('admin.estimated_time')),
                restore_complete: @json(__('admin.restore_complete')),
                database_restored: @json(__('admin.database_restored')),
                page_will_reload: @json(__('admin.page_will_reload')),
                restore_failed: @json(__('admin.restore_failed')),
                restore_error: @json(__('admin.restore_error')),
                backup_full_desc: @json(__('admin.backup_full_desc')),
                backup_partial_desc: @json(__('admin.backup_partial_desc'))
            };

            function createBackup(type) {
                const typeText = type === 'full' ? translations.backup_full_desc : translations.backup_partial_desc;

                Swal.fire({
                    title: translations.confirm_backup,
                    text: translations.backup_question.replace(':type', typeText),
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: translations.yes_create_backup,
                    cancelButtonText: translations.cancel
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: translations.creating_backup.replace(':type', typeText),
                            html: `
                                <div class="text-center">
                                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-600 mx-auto mb-4"></div>
                                    <p>${translations.backup_process_wait}</p>
                                    <p class="text-sm text-gray-600 mt-2">${translations.dont_close_page}</p>
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
                                throw new Error(translations.backup_failed);
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
                                    title: translations.backup_success,
                                    html: `
                                    <div class="text-center">
                                        <p class="mb-2">${translations.backup_created_successfully.replace(':type', typeText)}</p>
                                        <p class="text-sm text-gray-600">${translations.file_downloaded}</p>
                                    </div>
                                `,
                                    confirmButtonColor: '#10b981',
                                    confirmButtonText: 'OK'
                                });
                            })
                            .catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: translations.backup_failed,
                                    text: translations.backup_error,
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
                        title: translations.file_not_selected,
                        text: translations.select_backup_first,
                        confirmButtonColor: '#f59e0b'
                    });
                    return;
                }

                const fileName = fileInput.files[0].name;
                const fileSize = (fileInput.files[0].size / 1024 / 1024).toFixed(2);

                Swal.fire({
                    title: translations.important_warning,
                    html: `
                        <div class="text-left">
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                                    <span class="font-bold text-red-800">${translations.restore_warning_text}</span>
                                </div>
                                <ul class="text-sm text-red-700 ml-6 list-disc">
                                    <li>${translations.data_will_be_replaced}</li>
                                    <li>${translations.changes_irreversible}</li>
                                    <li>${translations.ensure_current_backup}</li>
                                </ul>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-sm">${translations.file_label}: ${fileName}</p>
                                <p class="text-sm">${translations.size_label}: ${fileSize} MB</p>
                            </div>
                        </div>
                    `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: translations.yes_sure_restore,
                    cancelButtonText: translations.cancel
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: translations.restoring_database,
                            html: `
                                <div class="text-center">
                                    <div class="animate-pulse">
                                        <div class="bg-red-100 rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-4">
                                            <i class="fas fa-database text-red-600 text-2xl"></i>
                                        </div>
                                    </div>
                                    <p class="font-semibold text-red-600">${translations.restore_in_progress}</p>
                                    <p class="text-sm text-gray-600 mt-2">${translations.dont_close_page}</p>
                                    <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                        <p class="text-xs text-yellow-800">${translations.estimated_time}</p>
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
                                        title: translations.restore_complete,
                                        html: `
                                        <div class="text-center">
                                            <p class="mb-2">${translations.database_restored}</p>
                                            <p class="text-sm text-gray-600">${translations.file_label}: <code>${fileName}</code></p>
                                            <div class="mt-4 bg-green-50 border border-green-200 rounded-lg p-3">
                                                <p class="text-sm text-green-800">${translations.page_will_reload}</p>
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
                                        title: translations.restore_failed,
                                        text: data.message || translations.restore_error,
                                        confirmButtonColor: '#dc2626'
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: translations.restore_failed,
                                    text: translations.restore_error,
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
