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
            <a href="{{ route('roles.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors duration-200">
                <span class="icon mr-2"><i class="mdi mdi-plus"></i></span>
                Create Role
            </a>
        </div>
    </section>

    <section class="section main-section mt-8">
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <!-- Table Header -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Roles & Permissions</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Total roles in your system</p>
                    </div>
                    <!-- Export buttons will be inserted here by DataTables -->
                    <div id="export-buttons" class="flex flex-wrap gap-2"></div>
                </div>
            </div>

            <!-- Table Content -->
            <div class="overflow-hidden">
                <div class="overflow-x-auto">
                    <table id="roles-table" class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-100 dark:bg-gray-800">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    <div class="flex items-center space-x-1">
                                        <span>#</span>
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    <div class="flex items-center space-x-1">
                                        <i class="mdi mdi-account-key text-sm"></i>
                                        <span>Role Name</span>
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    <div class="flex items-center space-x-1">
                                        <i class="mdi mdi-shield-account text-sm"></i>
                                        <span>Permissions</span>
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    <div class="flex items-center justify-end space-x-1">
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

            <!-- Table Footer with pagination info -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div id="table-info" class="text-sm text-gray-600 dark:text-gray-400"></div>
                    <div id="table-pagination"></div>
                </div>
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
                        className: 'px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-300 whitespace-nowrap',
                        width: '80px'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        className: 'px-6 py-4 whitespace-nowrap',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return `<div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                            <i class="mdi mdi-account-key text-blue-600 dark:text-blue-400"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">${data}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">System Role</div>
                                    </div>
                                </div>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'permissions',
                        name: 'permissions',
                        className: 'px-6 py-4',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                if (data && data.length > 0) {
                                    const perms = data.split(', ');
                                    let html = '<div class="flex flex-wrap gap-1">';

                                    // Show first 3 permissions as badges
                                    const displayPerms = perms.slice(0, 3);
                                    displayPerms.forEach(perm => {
                                        html +=
                                            `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">${perm.trim()}</span>`;
                                    });

                                    // Show "+X more" if there are more permissions
                                    if (perms.length > 3) {
                                        html +=
                                            `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors" title="${data}">+${perms.length - 3} more</span>`;
                                    }

                                    html += '</div>';
                                    return html;
                                } else {
                                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">No permissions</span>';
                                }
                            }
                            return data;
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'px-6 py-4 text-right whitespace-nowrap text-sm'
                    }
                ],
                dom: "<'hidden'B>" + // Hide default buttons
                    "<'flex flex-col md:flex-row justify-between items-center gap-4 mb-4 px-6'lf>" +
                    "<'overflow-x-auto't>" +
                    "<'hidden'ip>", // Hide default info and pagination
                buttons: [{
                        extend: 'copy',
                        text: '<i class="mdi mdi-content-copy mr-2"></i>Copy',
                        className: 'inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors'
                    },
                    {
                        extend: 'csv',
                        text: '<i class="mdi mdi-file-delimited mr-2"></i>CSV',
                        className: 'inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="mdi mdi-file-excel mr-2"></i>Excel',
                        className: 'inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="mdi mdi-file-pdf mr-2"></i>PDF',
                        className: 'inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors'
                    },
                    {
                        extend: 'print',
                        text: '<i class="mdi mdi-printer mr-2"></i>Print',
                        className: 'inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors'
                    }
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Search roles and permissions...",
                    lengthMenu: "_MENU_ roles per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ roles",
                    infoEmpty: "No roles found",
                    infoFiltered: "(filtered from _MAX_ total roles)",
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

                    // Custom search styling
                    $('.dataTables_filter input').addClass(
                        'pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white'
                        );
                    $('.dataTables_filter').prepend(
                        '<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i class="mdi mdi-magnify text-gray-400"></i></div>'
                        ).addClass('relative');

                    // Custom length menu styling
                    $('.dataTables_length select').addClass(
                        'border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500'
                        );

                    // Move info and pagination to custom locations
                    $('.dataTables_info').appendTo('#table-info');
                    $('.dataTables_paginate').appendTo('#table-pagination');

                    // Style pagination buttons
                    $('.dataTables_paginate .paginate_button').addClass(
                        'px-3 py-2 mx-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors'
                        );
                    $('.dataTables_paginate .paginate_button.current').addClass(
                        'bg-blue-600 text-white border-blue-600 hover:bg-blue-700');
                    $('.dataTables_paginate .paginate_button.disabled').addClass(
                        'opacity-50 cursor-not-allowed');
                }
            });

            // Custom row hover effects
            $('#roles-table tbody').on('mouseenter', 'tr', function() {
                $(this).addClass('bg-gray-50 dark:bg-gray-700/50');
            }).on('mouseleave', 'tr', function() {
                $(this).removeClass('bg-gray-50 dark:bg-gray-700/50');
            });

            // Refresh table data
            setInterval(function() {
                table.ajax.reload(null, false);
            }, 30000); // Refresh every 30 seconds
        });
    </script>
@endpush

@push('styles')
    <style>
        /* Custom DataTables styling */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 0;
        }

        .dataTables_wrapper .dataTables_length label,
        .dataTables_wrapper .dataTables_filter label {
            font-weight: 500;
            color: #374151;
            margin-bottom: 0;
        }

        .dark .dataTables_wrapper .dataTables_length label,
        .dark .dataTables_wrapper .dataTables_filter label {
            color: #d1d5db;
        }

        /* Loading animation */
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        /* Smooth transitions */
        #roles-table tbody tr {
            transition: background-color 0.2s ease;
        }

        /* Responsive table */
        @media (max-width: 768px) {

            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                margin-bottom: 1rem;
            }

            #export-buttons {
                order: 3;
                width: 100%;
            }

            #export-buttons .dt-buttons {
                display: flex;
                flex-wrap: wrap;
                gap: 0.5rem;
                width: 100%;
            }

            #export-buttons .dt-button {
                flex: 1;
                min-width: 0;
                justify-content: center;
            }
        }
    </style>
@endpush
