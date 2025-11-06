@extends('admin.layouts.app')

@section('content')
    <!-- Page Header -->
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div>
                <h1 class="title text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-cogs mr-3"></i>
                    {{ __('admin.services_page_title') }}
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    {{ __('admin.services_page_subtitle') }}
                </p>
            </div>
        </div>
    </section>

    <section class="section min-h-screen main-section">
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <!-- Header Actions -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('admin.services.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md shadow-sm transition-colors duration-200">
                            <span class="icon mr-2"><i class="mdi mdi-plus"></i></span>
                            {{ __('admin.create_service_btn') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Table Content -->
            <div class="card-content">
                <table id="services-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('admin.name_column') }}</th>
                            <th>{{ __('admin.description_column') }}</th>
                            <th>{{ __('admin.price_column') }}</th>
                            <th>{{ __('admin.duration_column') }}</th>
                            <th>{{ __('admin.status') }}</th>
                            <th>{{ __('admin.actions_column') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
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
        const deleteServiceWarning = '{{ __('admin.delete_service_confirm') }}';
        const yesDeleteIt = '{{ __('admin.yes_delete_it') }}';
        const somethingWentWrong = '{{ __('admin.something_went_wrong') }}';
        const processing = '{{ __('admin.processing') }}';
        const search = '{{ __('admin.search') }}';
        const lengthMenu = '{{ __('admin.show_entries') }}';
        const info = '{{ __('admin.showing_entries') }}';
        const infoEmpty = '{{ __('admin.showing_empty') }}';
        const infoFiltered = '{{ __('admin.filtered_entries') }}';
        const noMatchingServices = '{{ __('admin.no_matching_services') }}';
        const noServicesAvailable = '{{ __('admin.no_services_available') }}';
        const loadingServices = '{{ __('admin.loading_services') }}';
        const firstPage = '{{ __('admin.first') }}';
        const lastPage = '{{ __('admin.last') }}';
        const nextPage = '{{ __('admin.next') }}';
        const previousPage = '{{ __('admin.previous') }}';
        const confirmTitle = '{{ __('admin.confirm_title') }}';
        const deletedTitle = '{{ __('admin.deleted_title') }}';
        const serviceDeletedSuccess = '{{ __('admin.service_deleted_success') }}';
        const errorTitle = '{{ __('admin.error_title') }}';
        const errorMessage = '{{ __('admin.error_message') }}';
        const tryAgain = '{{ __('admin.try_again') }}';
        const cancel = '{{ __('admin.cancel') }}';
        const successTitle = '{{ __('admin.success_title') }}';

        $(document).ready(function() {
            // Setup CSRF token for all Ajax requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let table = $('#services-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.services.index') }}',
                    type: 'GET',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: 'text-center',
                        orderable: false,
                        searchable: false,
                        width: '60px'
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
                        data: 'price',
                        name: 'price',
                        className: 'text-right'
                    },
                    {
                        data: 'duration',
                        name: 'duration',
                        className: 'text-center'
                    },
                    {
                        data: 'is_active',
                        name: 'is_active',
                        className: 'text-center',
                        render: function(data, type, row) {
                            let color = data ? 'green' : 'red';
                            let status = data ? '{{ __('admin.active') }}' :
                                '{{ __('admin.inactive') }}';

                            return `
            <div class="text-center">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-${color}-100 text-${color}-800">
                    <div class="w-1.5 h-1.5 rounded-full bg-${color}-600 mr-1"></div>
                    ${status}
                </span>
            </div>
        `;
                        }
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                language: {
                    search: search,
                    info: '',
                    infoEmpty: infoEmpty,
                    infoFiltered: infoFiltered,
                    zeroRecords: noMatchingServices,
                    emptyTable: noServicesAvailable,
                    loadingRecords: loadingServices,
                    processing: `<div class="flex items-center justify-center py-4">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        <span class="ml-2 text-gray-600 dark:text-gray-400">${processing}...</span>
    </div>`,
                    paginate: {
                        previous: '<i class="mdi mdi-chevron-left"></i><span class="sr-only">' +
                            previousPage + '</span>',
                        next: '<span class="sr-only">' + nextPage +
                            '</span><i class="mdi mdi-chevron-right"></i>'
                    },
                    lengthMenu: "_MENU_" // ✅ hanya tampil dropdown, tanpa teks "Show entries"
                },
                responsive: true,
                pageLength: 10,
                order: [
                    [1, 'asc']
                ],
                dom: '<"flex flex-col md:flex-row justify-between items-center mb-6"lf>rt<"flex flex-col md:flex-row justify-between items-center mt-6"ip>',
                initComplete: function() {
                    // Style DataTable elements
                    $('.dataTables_filter input').addClass(
                        'border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full md:w-80 text-base'
                    );
                    $('.dataTables_length select').addClass(
                        'border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500'
                    );

                    // Add hover effect to table rows
                    $('#services-table tbody tr').hover(
                        function() {
                            $(this).addClass('bg-blue-50');
                        },
                        function() {
                            $(this).removeClass('bg-blue-50');
                        }
                    );
                },
                drawCallback: function() {
                    // Re-apply hover effects after each redraw
                    $('#services-table tbody tr').hover(
                        function() {
                            $(this).addClass('bg-blue-50');
                        },
                        function() {
                            $(this).removeClass('bg-blue-50');
                        }
                    );
                }
            });

            // Success popup
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: successTitle,
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            // Delete Service
            $(document).on('click', '.deleteBtn', function() {
                const id = $(this).data('id');
                const deleteUrl = '{{ route('admin.services.destroy', ':id') }}'.replace(':id', id);

                Swal.fire({
                    title: confirmTitle,
                    text: deleteServiceWarning,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: yesDeleteIt,
                    cancelButtonText: cancel
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: deleteUrl,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {

                                Swal.fire({
                                    icon: 'success',
                                    title: deletedTitle,
                                    text: serviceDeletedSuccess,
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: errorTitle,
                                    text: xhr.responseJSON?.message ||
                                        somethingWentWrong,
                                    icon: 'error',
                                    confirmButtonText: tryAgain
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
