@extends('admin.layouts.app')

@section('content')
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div>
                <h1 class="title text-3xl font-bold text-gray-900 dark:text-white">
                    Role Management
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
            <!-- Table Header -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <a href="{{ route('roles.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md shadow-sm transition-colors duration-200">
                        <span class="icon mr-2"><i class="mdi mdi-plus"></i></span>
                        Create Role
                    </a>
                    <!-- Export buttons will be inserted here by DataTables -->
                    <div id="export-buttons" class="flex flex-wrap gap-2"></div>
                </div>
            </div>

            <!-- Table Content -->

            <div class="card-content rounded-md overflow-x-auto">

                <table id="roles-table" class="min-w-full table-fixed divide-y  divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-800 ">
                        <tr>
                            <th style="width: 60px"
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 tracking-wider">
                                <div class="flex items-center justify-center space-x-1">
                                    <i class="mdi mdi-cog text-sm"></i>
                                    <span>#</span>
                                </div>
                            </th>

                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300  tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <i class="mdi mdi-account-key text-sm"></i>
                                    <span>Role Name</span>
                                </div>
                            </th>

                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300  tracking-wider">
                                <div class="flex items-center justify-center space-x-1">
                                    <i class="mdi mdi-cog text-sm"></i>
                                    <span>Actions</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <!-- Data will be populated by DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable with improved styling
            const table = $('#roles-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('roles.index') }}',
                    type: 'GET',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: 'px-6 py-4 text-center text-sm font-medium text-gray-900 dark:text-gray-300',
                        width: '60px'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        className: 'px-6 py-4 ',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return `<div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white capitalize ">${data}</div>
                                    </div>
                                </div>`;
                            }
                            return data;
                        }
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center text-sm'
                    }
                ],
                dom: "<'hidden'B>" + // hanya sembunyikan tombol default
                    "<'flex flex-col md:flex-row justify-between items-center gap-4 mb-4'lf>" +
                    "<'overflow-x-auto't>" +
                    "<'flex flex-col md:flex-row justify-between items-center gap-4 mt-4'ip>", // tampilkan info & pagination
                // Hide default info and pagination
                buttons: [{
                        extend: 'copy',
                        text: '<i class="mdi mdi-content-copy mr-2"></i>Copy',
                        className: 'dt-btn dt-btn-copy'
                    },
                    {
                        extend: 'csv',
                        text: '<i class="mdi mdi-file-delimited mr-2"></i>CSV',
                        className: 'dt-btn dt-btn-csv'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="mdi mdi-file-excel mr-2"></i>Excel',
                        className: 'dt-btn dt-btn-excel'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="mdi mdi-file-pdf mr-2"></i>PDF',
                        className: 'dt-btn dt-btn-pdf'
                    },
                    {
                        extend: 'print',
                        text: '<i class="mdi mdi-printer mr-2"></i>Print',
                        className: 'dt-btn dt-btn-print'
                    }
                ],
                language: {
                    search: "",
                    lengthMenu: "_MENU_ ",
                    info: "Showing _START_ to _END_ of _TOTAL_ roles",
                    infoEmpty: "No roles found",
                    infoFiltered: "(filtered from _MAX_ total roles)",

                    searchPlaceholder: "Search roles...",
                    zeroRecords: "No matching roles found",
                    emptyTable: "No roles available",
                    loadingRecords: "Loading roles...",
                    processing: `<div class="flex items-center justify-center py-4">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        <span class="ml-2 text-gray-600 dark:text-gray-400">Loading...</span>
                    </div>`,
                    paginate: {
                        previous: '<i class="mdi mdi-chevron-left"></i><span class="sr-only">Previous</span>',
                        next: '<span class="sr-only">Next</span><i class="mdi mdi-chevron-right"></i>'
                    }
                },
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                order: [
                    [1, 'asc']
                ], // Sort by role name by default
                responsive: true,
                stateSave: true,
                initComplete: function() {
                    // Move export buttons to custom location
                    $('.dt-buttons').appendTo('#export-buttons');

                }
            });


            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            $(document).on('click', '.deleteBtn', function() {
                const roleId = $(this).data('id');
                const deleteUrl = '{{ route('roles.destroy', ':id') }}'.replace(':id', roleId);

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: deleteUrl,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Deleted!',
                                        text: response.message,
                                        timer: 3000,
                                        showConfirmButton: false
                                    });
                                    $('#roles-table').DataTable().ajax.reload();
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message ||
                                        'Something went wrong!',
                                });
                            }
                        });
                    }
                });
            });

            // Handle form submissions via AJAX (for create/update)
            $('form').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const formData = form.serialize();
                const url = form.attr('action');
                const method = form.attr('method');

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            timer: 3000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = '{{ route('roles.index') }}';
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = xhr.responseJSON?.message || 'An error occurred.';
                        if (xhr.responseJSON?.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            html: errorMessage,
                        });
                    }
                });
            });
        });


        // Refresh table data
        setInterval(function() {
            table.ajax.reload(null, false);
        }, 30000); // Refresh every 30 seconds
    </script>
@endpush

@push('styles')
    <style>
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
