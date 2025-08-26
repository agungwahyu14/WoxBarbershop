@extends('admin.layouts.app')

@section('content')
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div>
                <h1 class="title text-3xl font-bold text-gray-900 dark:text-white">Transactions</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    Latest payment transactions from Wox's Barbershop
                </p>
            </div>
        </div>
    </section>

    <section class="section min-h-screen main-section">

        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="flex items-center space-x-2">
                        <select id="monthFilter"
                            class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200 text-sm">
                            <option value="">All Months</option>
                            <option value="01">January</option>
                            <option value="02">February</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                        <select id="yearFilter"
                            class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200 text-sm">
                            <option value="">All Years</option>
                            @for ($year = date('Y'); $year >= 2020; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                        <button id="resetFilter"
                            class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 font-medium rounded-md shadow-sm transition-colors duration-200 text-sm">
                            <i class="mdi mdi-refresh mr-1"></i>Reset
                        </button>
                    </div>
                    <div id="export-buttons" class="flex flex-wrap gap-2"></div>
                </div>
            </div>

            <div class="card-content rounded-md overflow-x-auto">
                <table id="transactions-table" class="min-w-full table-fixed divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-800">
                        <tr>
                            <th class="w-12 px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300">
                                #
                            </th>
                            <th class=" px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300">
                                Name
                            </th>
                            <th class=" px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300">
                                Email
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300">Order ID
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300">Type</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300">Status
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300">Amount
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300">Action
                            </th>
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
            const table = $('#transactions-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.transactions.index') }}',
                    data: function(d) {
                        d.month_filter = $('#monthFilter').val();
                        d.year_filter = $('#yearFilter').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'transaction_time',
                        name: 'transaction_time'
                    },
                    {
                        data: 'order_id',
                        name: 'order_id'
                    },

                    {
                        data: 'payment_type',
                        name: 'payment_type'
                    },
                    {
                        data: 'transaction_status',
                        name: 'transaction_status'
                    },
                    {
                        data: 'gross_amount',
                        name: 'gross_amount'
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center space-x-2'
                    },
                ],
                responsive: true,
                pageLength: 10,
                order: [
                    [0, 'desc']
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
                initComplete: function() {
                    $('.dt-buttons').appendTo('#export-buttons');
                },
                language: {
                    searchPlaceholder: "Search transactions...",
                    info: "Showing _START_ to _END_ of _TOTAL_ transactions",
                    infoEmpty: "No transactions found",
                    zeroRecords: "No matching transactions",
                    emptyTable: "No transactions available",
                    paginate: {
                        previous: '<i class="mdi mdi-chevron-left"></i>',
                        next: '<i class="mdi mdi-chevron-right"></i>'
                    }
                },
            });

            // Filter event listeners
            $('#monthFilter, #yearFilter').on('change', function() {
                table.ajax.reload();
            });

            // Reset filter button
            $('#resetFilter').on('click', function() {
                $('#monthFilter').val('');
                $('#yearFilter').val('');
                table.ajax.reload();
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
    </style>
@endpush
