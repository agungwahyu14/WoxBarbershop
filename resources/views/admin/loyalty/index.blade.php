@extends('admin.layouts.app')

@section('content')
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div>
                <h1 class="title text-3xl font-bold text-gray-900 dark:text-white">
                    Loyalty Management
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    Manage loyalty points of customers at Wox’s Barbershop
                </p>
            </div>
        </div>
    </section>

    <section class="section main-section">
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div></div>
                    <div id="export-buttons" class="flex flex-wrap gap-2"></div>
                </div>
            </div>

            <div class="card-content rounded-md overflow-x-auto">
                <table id="loyalty-table" class="min-w-full table-fixed divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-800">
                        <tr>
                            <th class="w-12 px-6 py-4 text-center text-xs font-semibold">#</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold">Nama Pengguna</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold">Poin</th>
                            <th class="text-center text-xs font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const table = $('#loyalty-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.loyalty.index') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: 'text-center'
                    },
                    {
                        data: 'user_name',
                        name: 'user.name'
                    },
                    {
                        data: 'points',
                        name: 'points',
                        className: 'text-center'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                ],
                dom: "<'hidden'B><'flex flex-col md:flex-row justify-between items-center gap-4 mb-4'lf><'overflow-x-auto't><'flex flex-col md:flex-row justify-between items-center gap-4 mt-4'ip>",
                buttons: [{
                        extend: 'copy',
                        className: 'dt-btn dt-btn-copy',
                        text: '<i class="mdi mdi-content-copy mr-2"></i>Copy'
                    },
                    {
                        extend: 'csv',
                        className: 'dt-btn dt-btn-csv',
                        text: '<i class="mdi mdi-file-delimited mr-2"></i>CSV'
                    },
                    {
                        extend: 'excel',
                        className: 'dt-btn dt-btn-excel',
                        text: '<i class="mdi mdi-file-excel mr-2"></i>Excel'
                    },
                    {
                        extend: 'pdf',
                        className: 'dt-btn dt-btn-pdf',
                        text: '<i class="mdi mdi-file-pdf mr-2"></i>PDF'
                    },
                    {
                        extend: 'print',
                        className: 'dt-btn dt-btn-print',
                        text: '<i class="mdi mdi-printer mr-2"></i>Print'
                    },
                ],
                language: {
                    searchPlaceholder: "Cari pengguna...",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    infoEmpty: "Tidak ada data loyalty",
                    zeroRecords: "Tidak ditemukan data yang cocok",
                    emptyTable: "Belum ada data loyalty",
                    processing: `<div class="flex items-center justify-center py-4">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        <span class="ml-2 text-gray-600 dark:text-gray-400">Memuat...</span>
                    </div>`,
                    paginate: {
                        previous: '<i class="mdi mdi-chevron-left"></i>',
                        next: '<i class="mdi mdi-chevron-right"></i>'
                    }
                },
                responsive: true,
                pageLength: 10,
                order: [
                    [1, 'asc']
                ],
                initComplete: function() {
                    $('.dt-buttons').appendTo('#export-buttons');
                }
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        th.text-center {
            text-align: center !important;
        }

        th.text-left {
            text-align: left !important;
        }

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

        .dt-buttons .dt-button.dt-btn-copy {
            background-color: #e0e7ff !important;
            color: #312e81 !important;
        }

        .dt-buttons .dt-button.dt-btn-copy:hover {
            background-color: #c7d2fe !important;
        }

        .dt-buttons .dt-button.dt-btn-csv {
            background-color: #34d399 !important;
            color: #ffffff !important;
        }

        .dt-buttons .dt-button.dt-btn-csv:hover {
            background-color: #6ee7b7 !important;
        }

        .dt-buttons .dt-button.dt-btn-excel {
            background-color: #10b981 !important;
            color: #ffffff !important;
        }

        .dt-buttons .dt-button.dt-btn-excel:hover {
            background-color: #34d399 !important;
        }

        .dt-buttons .dt-button.dt-btn-pdf {
            background-color: #f87171 !important;
            color: #ffffff !important;
        }

        .dt-buttons .dt-button.dt-btn-pdf:hover {
            background-color: #fca5a5 !important;
        }

        .dt-buttons .dt-button.dt-btn-print {
            background-color: #60a5fa !important;
            color: #ffffff !important;
        }

        .dt-buttons .dt-button.dt-btn-print:hover {
            background-color: #93c5fd !important;
        }

        #loyalty-table {
            width: 100% !important;
            table-layout: auto !important;
        }
    </style>
@endpush
