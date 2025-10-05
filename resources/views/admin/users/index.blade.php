@extends('admin.layouts.app')

@section('content')
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
            <div>
                <h1 class="title">
                    <i class="fas fa-users mr-3"></i> Users 
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    Manage user roles and permissions across your system
                </p>
            </div>
        </div>
    </section>

    <section class="section main-section">

        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <!-- Kiri: Tombol Create -->
                    <a href="{{ route('admin.users.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md shadow-sm transition-colors duration-200">
                        <span class="icon mr-2"><i class="mdi mdi-plus"></i></span>
                        Create User
                    </a>

                    <!-- Kanan: Filter + Export -->
                    <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
                        <div class="flex items-center space-x-2">

                            <a href="{{ route('admin.users.export.csv') }}"
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md shadow-sm transition-colors duration-200 text-sm">
                                <i class="mdi mdi-file-delimited mr-2"></i> CSV
                            </a>
                            <a href="{{ route('admin.users.export.pdf') }}"
                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md shadow-sm transition-colors duration-200 text-sm">
                                <i class="mdi mdi-file mr-2"></i> PDF
                            </a>

                        </div>
                    </div>
                </div>
            </div>


            <div class="card-content">
                <table id="users-table">
                    <thead>
                        <tr>
                            <th>
                                #
                            </th>
                            <th>
                                Name
                            </th>
                            <th>
                                No Telepon
                            </th>
                            <th>
                                Roles
                            </th>

                            <th>
                                Status
                            </th>
                            <th>Loyalty Points</th> <!-- Kolom baru -->
                            <th>
                                Created Date
                            </th>
                            <th>
                                Actions
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
                    info: "Showing _START_ to _END_ of _TOTAL_ users",
                    infoEmpty: "No users found",
                    zeroRecords: "No matching users",
                    emptyTable: "No users available",
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
                        'border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-base'
                    );
                    $('.dataTables_paginate .paginate_button').addClass(
                        'px-4 py-2 mx-1 rounded-lg text-base font-medium');
                    $('.dataTables_paginate .current').addClass('bg-blue-600 text-white');
                    $('.dataTables_info').addClass('text-gray-600 dark:text-gray-400 text-base');

                    // Add hover effect to table rows
                    $('#users-table tbody tr').hover(
                        function() {
                            $(this).addClass('bg-gray-50 dark:bg-gray-700/50');
                        },
                        function() {
                            $(this).removeClass('bg-gray-50 dark:bg-gray-700/50');
                        }
                    );
                }
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
                    title: 'Success!',
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
                    title: 'Are you sure?',
                    text: "This will permanently delete the user.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
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
                                    title: 'Deleted!',
                                    text: response.message,
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
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message ||
                                        'Something went wrong.',
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
