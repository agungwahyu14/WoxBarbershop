@extends('admin.layouts.app')

@section('content')
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
            <div>
                <h1 class="title">
                    <i class="fas fa-users mr-3"></i> {{ __('admin.users_page_title') }}
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    {{ __('admin.users_page_subtitle') }}
                </p>
            </div>
        </div>
    </section>

    <section class="section main-section">

        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            @role('admin')
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <!-- Kiri: Tombol Create -->
                        <a href="{{ route('admin.users.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md shadow-sm transition-colors duration-200">
                            <span class="icon mr-2"><i class="mdi mdi-plus"></i></span>
                            {{ __('admin.create_user_btn') }}
                        </a>

                        <!-- Kanan: Filter + Export -->
                        <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
                            <div class="flex items-center space-x-2">

                                <a href="{{ route('admin.users.export.csv') }}"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md shadow-sm transition-colors duration-200 text-sm">
                                    <i class="mdi mdi-file-delimited mr-2"></i> {{ __('admin.export_csv') }}
                                </a>
                                <a href="{{ route('admin.users.export.pdf') }}"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md shadow-sm transition-colors duration-200 text-sm">
                                    <i class="mdi mdi-file mr-2"></i> {{ __('admin.export_pdf') }}
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
            @endrole



            <div class="card-content">
                <table id="users-table">
                    <thead>
                        <tr>
                            <th>
                                #
                            </th>
                            <th>
                                {{ __('admin.name_column') }}
                            </th>
                            <th>
                                {{ __('admin.phone_column') }}
                            </th>
                            <th>
                                {{ __('admin.roles_column') }}
                            </th>

                            <th>
                                {{ __('admin.status_column') }}
                            </th>
                            <th>{{ __('admin.loyalty_points_column') }}</th>
                            <th>
                                {{ __('admin.created_date_column') }}
                            </th>
                            <th>
                                {{ __('admin.actions_column') }}
                            </th>
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
        const deleteWarning = '{{ __('admin.delete_user_warning') }}';
        const yesDeleteIt = '{{ __('admin.yes_delete_it') }}';
        const somethingWentWrong = '{{ __('admin.something_went_wrong') }}';
        const processing = '{{ __('admin.processing') }}';
        const search = '{{ __('admin.search') }}';
        const lengthMenu = '{{ __('admin.show_entries') }}';
        const info = '{{ __('admin.showing_entries') }}';
        const infoEmpty = '{{ __('admin.showing_empty') }}';
        const infoFiltered = '{{ __('admin.filtered_entries') }}';
        const noMatchingUsers = '{{ __('admin.no_matching_users') }}';
        const noUsersAvailable = '{{ __('admin.no_users_available') }}';
        const loadingUsers = '{{ __('admin.loading_users') }}';
        const firstPage = '{{ __('admin.first') }}';
        const lastPage = '{{ __('admin.last') }}';
        const nextPage = '{{ __('admin.next') }}';
        const previousPage = '{{ __('admin.previous') }}';
        const successTitle = '{{ __('admin.success_title') }}';
        const errorTitle = '{{ __('admin.error_title') }}';
        const deletedSuccessTitle = '{{ __('admin.deleted_success_title') }}';
        const userDeletedSuccessfully = '{{ __('admin.user_deleted_successfully') }}';
        const cancel = '{{ __('admin.cancel') }}';
        const resetLoyaltyTitle = '{{ __('admin.reset_loyalty_title') }}';
        const resetLoyaltyText = '{{ __('admin.reset_loyalty_text') }}';
        const yesResetIt = '{{ __('admin.yes_reset_it') }}';
        const loyaltyResetSuccess = '{{ __('admin.loyalty_reset_success') }}';
        const loyaltyResetFailed = '{{ __('admin.loyalty_reset_failed') }}';

        $(document).ready(function() {

            // Setup CSRF token for all Ajax requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let table = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.users.index') }}',
                    type: 'GET',
                    data: function(d) {
                        d.month_filter = $('#monthFilter').val();
                        d.year_filter = $('#yearFilter').val();
                        d.status_filter = $('#statusFilter').val();
                    }
                },
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
                        data: 'no_telepon',
                        name: 'no_telepon'
                    },
                    {
                        data: 'roles',
                        name: 'roles'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'loyalty_points',
                        name: 'loyalty_points'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
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
                    zeroRecords: noMatchingUsers,
                    emptyTable: noUsersAvailable,
                    loadingRecords: loadingUsers,
                    processing: `<div class="flex items-center justify-center ">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <span class="ml-2 text-gray-600 dark:text-gray-400">${processing}...</span>
        </div>`,
                    paginate: {
                        previous: '<i class="mdi mdi-chevron-left"></i><span class="sr-only">' +
                            previousPage + '</span>',
                        next: '<span class="sr-only">' + nextPage +
                            '</span><i class="mdi mdi-chevron-right"></i>'
                    },
                    lengthMenu: "_MENU_" // ⬅️ ini menampilkan hanya dropdown-nya tanpa teks
                },
                responsive: true,
                pageLength: 10,
                order: [
                    [1, 'asc']
                ]
            });


            // Filter event listeners
            $('#monthFilter, #yearFilter, #statusFilter').on('change', function() {
                table.ajax.reload();
            });

            // Reset filter button
            $('#resetFilter').on('click', function() {
                $('#monthFilter').val('');
                $('#yearFilter').val('');
                $('#statusFilter').val('');
                table.ajax.reload();
            });

            // Success popup
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: successTitle,
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end',
                    customClass: {
                        popup: 'swal2-desktop-toast'
                    }
                });
            @endif

            // Delete User
            $(document).on('click', '.deleteBtn', function() {
                const userId = $(this).data('id');
                const deleteUrl = '{{ route('admin.users.destroy', ':id') }}'.replace(':id', userId);

                Swal.fire({
                    title: areYouSure,
                    text: deleteWarning,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: yesDeleteIt,
                    customClass: {
                        popup: 'swal2-desktop-popup',
                        title: 'text-xl',
                        content: 'text-base',
                        confirmButton: 'px-6 py-3',
                        cancelButton: 'px-6 py-3'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: deleteUrl,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: deletedSuccessTitle,
                                    text: response.message ||
                                        userDeletedSuccessfully,
                                    timer: 3000,
                                    showConfirmButton: false,
                                    toast: true,
                                    position: 'top-end',
                                    customClass: {
                                        popup: 'swal2-desktop-toast'
                                    }
                                });
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: errorTitle,
                                    text: xhr.responseJSON?.message ||
                                        somethingWentWrong,
                                    toast: true,
                                    position: 'top-end',
                                    customClass: {
                                        popup: 'swal2-desktop-toast'
                                    }
                                });
                            }
                        });
                    }
                });
            });

            // Reset Loyalty Points
            $(document).on('click', '.resetLoyaltyBtn', function() {
                const userId = $(this).data('user-id');
                const userName = $(this).data('user-name');
                const points = $(this).data('points');
                const resetUrl = '{{ route('admin.users.reset-loyalty-points', ':id') }}'.replace(':id',
                    userId);

                Swal.fire({
                    title: resetLoyaltyTitle,
                    text: resetLoyaltyText.replace(':points', points).replace(':name', userName),
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#f59e0b',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: yesResetIt,
                    cancelButtonText: cancel,
                    customClass: {
                        popup: 'swal2-desktop-popup',
                        title: 'text-xl',
                        content: 'text-base',
                        confirmButton: 'px-6 py-3',
                        cancelButton: 'px-6 py-3'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: resetUrl,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: loyaltyResetSuccess,
                                    text: response.message,
                                    timer: 4000,
                                    showConfirmButton: false,
                                    toast: true,
                                    position: 'top-end',
                                    customClass: {
                                        popup: 'swal2-desktop-toast'
                                    }
                                });
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: loyaltyResetFailed,
                                    text: xhr.responseJSON?.message ||
                                        somethingWentWrong,
                                    toast: true,
                                    position: 'top-end',
                                    customClass: {
                                        popup: 'swal2-desktop-toast'
                                    }
                                });
                            }
                        });
                    }
                });
            });

            // Auto reload with authentication check
            let refreshInterval = setInterval(function() {
                fetch('{{ route('admin.users.index') }}', {
                    method: 'HEAD',
                    credentials: 'same-origin'
                }).then(response => {
                    if (response.ok) {
                        table.ajax.reload(null, false);
                    } else {
                        clearInterval(refreshInterval);
                        if (response.status === 401 || response.status === 419) {
                            window.location.href = '{{ route('login') }}';
                        }
                    }
                }).catch(error => {
                    clearInterval(refreshInterval);
                    console.log('Auto-refresh stopped due to connection error');
                });
            }, 30000);

            window.addEventListener('beforeunload', function() {
                clearInterval(refreshInterval);
            });
        });
    </script>
@endpush
@push('styles')
    <style>
        /* Filter styling */
        #monthFilter,
        #yearFilter {
            min-width: 120px;
        }

        #resetFilter {
            min-width: 80px;
        }

        /* Responsive filter layout */
        @media (max-width: 640px) {
            .flex.flex-col.sm\\:flex-row {
                align-items: stretch;
            }

            .flex.items-center.space-x-2 {
                flex-direction: column;
                gap: 0.5rem;
            }

            #monthFilter,
            #yearFilter,
            #resetFilter {
                width: 100%;
            }
        }

        th.text-center {
            text-align: center !important;
        }

        th.text-left {
            text-align: left !important;
        }

        /* Styling untuk tombol DataTable dengan warna yang lebih modern */
        .dt-buttons .dt-button.dt-btn-copy {
            background-color: #e0e7ff !important;
            /* Indigo soft */
            color: #312e81 !important;
            /* Indigo dark untuk kontras */
        }

        .dt-buttons .dt-button.dt-btn-copy:hover {
            background-color: #c7d2fe !important;
            /* Indigo lebih terang saat hover */
        }

        .dt-buttons .dt-button.dt-btn-csv {
            background-color: #34d399 !important;
            /* Emerald green */
            color: #ffffff !important;
            /* Putih untuk kontras */
        }

        .dt-buttons .dt-button.dt-btn-csv:hover {
            background-color: #6ee7b7 !important;
            /* Emerald lebih terang saat hover */
        }

        .dt-buttons .dt-button.dt-btn-excel {
            background-color: #10b981 !important;
            /* Green untuk Excel */
            color: #ffffff !important;
            /* Putih untuk kontras */
        }

        .dt-buttons .dt-button.dt-btn-excel:hover {
            background-color: #34d399 !important;
            /* Green lebih terang saat hover */
        }

        .dt-buttons .dt-button.dt-btn-pdf {
            background-color: #f87171 !important;
            /* Red soft untuk PDF */
            color: #ffffff !important;
            /* Putih untuk kontras */
        }

        .dt-buttons .dt-button.dt-btn-pdf:hover {
            background-color: #fca5a5 !important;
            /* Red lebih terang saat hover */
        }

        .dt-buttons .dt-button.dt-btn-print {
            background-color: #60a5fa !important;
            /* Blue soft untuk print */
            color: #ffffff !important;
            /* Putih untuk kontras */
        }

        .dt-buttons .dt-button.dt-btn-print:hover {
            background-color: #93c5fd !important;
            /* Blue lebih terang saat hover */
        }

        /* Styling umum untuk tombol */
        .dt-buttons .dt-button {
            border-radius: 0.375rem !important;
            padding: 0.5rem 0.75rem !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
            transition: background-color 0.2s ease-in-out !important;
        }



        /* Styling untuk tabel */
        #roles-table {
            width: 100% !important;
            table-layout: auto !important;
        }
    </style>
@endpush
