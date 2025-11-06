@extends('admin.layouts.app')

@section('title', __('admin.hairstyles'))

@section('content')
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div>
                <h1 class="title text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-cut mr-3"></i>
                    {{ __('admin.hairstyles') }}
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    {{ __('admin.manage_hairstyle_recommendations') }}
                </p>
            </div>
        </div>
    </section>

    <section class="section main-section">
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <a href="{{ route('admin.hairstyles.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md shadow-sm transition-colors duration-200">
                        <span class="icon mr-2"><i class="mdi mdi-plus"></i></span>
                        {{ __('admin.create_hairstyle_btn') }}
                    </a>
                </div>
            </div>

            <div class="card-content">
                <table id="hairstyles-table" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('admin.name') }}</th>
                            <th>{{ __('admin.description') }}</th>
                            <th>{{ __('admin.head_shape') }}</th>
                            <th>{{ __('admin.hair_type') }}</th>
                            <th>{{ __('admin.style_preference') }}</th>
                            <th>{{ __('admin.image') }}</th>
                            <th>{{ __('admin.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"></tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Translation variables
        const success = '{{ __('admin.success') }}';
        const error = '{{ __('admin.error') }}';
        const deleted = '{{ __('admin.deleted') }}';
        const areYouSure = '{{ __('admin.are_you_sure') }}';
        const deleteHairstyleWarning = '{{ __('admin.delete_hairstyle_warning') }}';
        const yesDeleteIt = '{{ __('admin.yes_delete_it') }}';
        const somethingWentWrong = '{{ __('admin.something_went_wrong') }}';
        const processing = '{{ __('admin.processing') }}';
        const search = '{{ __('admin.search') }}';
        const lengthMenu = '{{ __('admin.show_entries') }}';
        const info = '{{ __('admin.showing_entries') }}';
        const infoEmpty = '{{ __('admin.showing_empty') }}';
        const infoFiltered = '{{ __('admin.filtered_entries') }}';
        const noMatchingHairstyles = '{{ __('admin.no_matching_hairstyles') }}';
        const noHairstylesAvailable = '{{ __('admin.no_hairstyles_available') }}';
        const loadingHairstyles = '{{ __('admin.loading_hairstyles') }}';
        const firstPage = '{{ __('admin.first') }}';
        const lastPage = '{{ __('admin.last') }}';
        const nextPage = '{{ __('admin.next') }}';
        const previousPage = '{{ __('admin.previous') }}';
        const successTitle = '{{ __('admin.success_title') }}';
        const errorTitle = '{{ __('admin.error_title') }}';
        const deletedSuccessTitle = '{{ __('admin.deleted_success_title') }}';
        const loading = '{{ __('admin.loading') }}';

        $(document).ready(function() {
            const table = $('#hairstyles-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.hairstyles.index') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: 'text-center'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'bentuk_kepala',
                        name: 'bentuk_kepala',
                        render: data => data || '-'
                    },
                    {
                        data: 'tipe_rambut',
                        name: 'tipe_rambut',
                        render: data => data || '-'
                    },
                    {
                        data: 'style_preference',
                        name: 'style_preference',
                        render: data => data || '-'
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: data => data ||
                            '<div class="flex justify-center"><i class="fas fa-image text-gray-400"></i></div>'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                dom: "<'hidden'B><'flex flex-col md:flex-row justify-between items-center gap-4 mb-4'lf><'overflow-x-auto't><'flex flex-col md:flex-row justify-between items-center gap-4 mt-4'ip>",
                language: {
                    search: search,
                    lengthMenu: "_MENU_", // ✅ hanya tampil dropdown, tanpa teks
                    info: '',
                    infoEmpty: infoEmpty,
                    infoFiltered: infoFiltered,
                    zeroRecords: noMatchingHairstyles,
                    emptyTable: noHairstylesAvailable,
                    loadingRecords: loadingHairstyles,
                    processing: `<div class="flex items-center justify-center py-4">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        <span class="ml-2 text-gray-600 dark:text-gray-400">${processing}...</span>
    </div>`,
                    paginate: {
                        previous: '<i class="mdi mdi-chevron-left"></i><span class="sr-only">' +
                            previousPage + '</span>',
                        next: '<span class="sr-only">' + nextPage +
                            '</span><i class="mdi mdi-chevron-right"></i>'
                    }
                },
                responsive: true,
                pageLength: 10,
                order: [
                    [1, 'asc']
                ]

            });

            // Success popup
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: successTitle,
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                }).then(() => location.reload());
            @endif

            // Delete hairstyle
            $(document).on('click', '.deleteBtn', function() {
                const hairstyleId = $(this).data('id');
                const deleteUrl = '{{ route('admin.hairstyles.destroy', ':id') }}'.replace(':id',
                    hairstyleId);

                Swal.fire({
                    title: areYouSure,
                    text: deleteHairstyleWarning,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: yesDeleteIt
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: deleteUrl,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: response => {
                                Swal.fire({
                                    icon: 'success',
                                    title: deletedSuccessTitle,
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => location.reload());
                            },
                            error: xhr => {
                                Swal.fire({
                                    icon: 'error',
                                    title: errorTitle,
                                    text: xhr.responseJSON?.message ||
                                        somethingWentWrong,
                                });
                            }
                        });
                    }
                });
            });

            // Auto reload (with auth check)
            let refreshInterval = setInterval(() => {
                fetch('{{ route('admin.hairstyles.index') }}', {
                        method: 'HEAD',
                        credentials: 'same-origin'
                    })
                    .then(response => {
                        if (response.ok) {
                            table.ajax.reload(null, false);
                        } else {
                            clearInterval(refreshInterval);
                            if (response.status === 401 || response.status === 419) {
                                window.location.href = '{{ route('login') }}';
                            }
                        }
                    })
                    .catch(() => {
                        clearInterval(refreshInterval);
                        console.log('Auto-refresh stopped due to connection error');
                    });
            }, 30000);

            window.addEventListener('beforeunload', () => clearInterval(refreshInterval));
        });
    </script>
@endpush

@push('styles')
@endpush
