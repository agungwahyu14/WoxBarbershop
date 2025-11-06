@extends('admin.layouts.app')

@section('content')
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div>
                <h1 class="title text-3xl font-bold text-gray-900 dark:text-white"><i
                        class="fas fa-receipt mr-3"></i>{{ __('admin.transactions_page_title') }}</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    {{ __('admin.transactions_page_subtitle') }}
                </p>
            </div>
        </div>
    </section>

    <section class="section min-h-screen main-section">

        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            @hasrole('admin')
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">

                        {{-- 🔹 Bagian Kiri --}}
                        <div class="flex flex-wrap items-center gap-2">

                            <select id="monthFilter"
                                class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200 text-sm">
                                <option value="">{{ __('admin.all_months') }}</option>
                                <option value="01">{{ __('admin.january') }}</option>
                                <option value="02">{{ __('admin.february') }}</option>
                                <option value="03">{{ __('admin.march') }}</option>
                                <option value="04">{{ __('admin.april') }}</option>
                                <option value="05">{{ __('admin.may') }}</option>
                                <option value="06">{{ __('admin.june') }}</option>
                                <option value="07">{{ __('admin.july') }}</option>
                                <option value="08">{{ __('admin.august') }}</option>
                                <option value="09">{{ __('admin.september') }}</option>
                                <option value="10">{{ __('admin.october') }}</option>
                                <option value="11">{{ __('admin.november') }}</option>
                                <option value="12">{{ __('admin.december') }}</option>
                            </select>

                            <select id="yearFilter"
                                class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200 text-sm">
                                <option value="">{{ __('admin.all_years') }}</option>
                                @for ($year = date('Y'); $year >= 2020; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>

                            <select id="statusFilter"
                                class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200 text-sm">
                                <option value="">{{ __('admin.all_status') }}</option>
                                <option value="pending">{{ __('admin.pending') }}</option>
                                <option value="settlement">{{ __('admin.complete') }}</option>
                                <option value="cancel">{{ __('admin.cancelled') }}</option>
                            </select>

                            <button id="resetFilter"
                                class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 font-medium rounded-md shadow-sm transition-colors duration-200 text-sm">
                                <i class="mdi mdi-refresh mr-1"></i>{{ __('admin.reset') }}
                            </button>
                            <div id="filterIndicator" class="hidden px-3 py-2 bg-blue-100 text-blue-800 text-sm rounded-md">
                                <i class="mdi mdi-filter mr-1"></i>
                                <span id="filterText"></span>
                            </div>

                        </div>

                        {{-- 🔹 Bagian Kanan --}}
                        <div class="flex items-center space-x-2">



                            <div class="flex flex-wrap gap-2">
                                <button id="exportCsvBtn" type="button"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md shadow-sm transition-colors duration-200 text-sm">
                                    <i class="mdi mdi-file-delimited mr-2"></i> {{ __('admin.export_csv') }}
                                </button>
                                <button id="exportPdfBtn" type="button"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md shadow-sm transition-colors duration-200 text-sm">
                                    <i class="mdi mdi-file mr-2"></i> {{ __('admin.export_pdf') }}
                                </button>
                            </div>

                        </div>

                    </div>
                </div>
            @endhasrole



            <div class="card-content">
                <table id="transactions-table">
                    <thead>
                        <tr>
                            <th>
                                #
                            </th>
                            <th>
                                {{ __('admin.name_column') }}
                            </th>
                            <th>
                                {{ __('admin.email') }}
                            </th>
                            <th>{{ __('admin.date_column') }}</th>
                            <th>{{ __('admin.transaction_id_column') }}
                            </th>

                            <th>{{ __('admin.type') }}</th>
                            <th>{{ __('admin.status_column') }}
                            </th>
                            <th>{{ __('admin.amount_column') }}
                            </th>
                            <th>{{ __('admin.actions_column') }}
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
        // Translation variables
        const success = '{{ __('admin.success') }}';
        const error = '{{ __('admin.error') }}';
        const deleted = '{{ __('admin.deleted') }}';
        const areYouSure = '{{ __('admin.are_you_sure') }}';
        const somethingWentWrong = '{{ __('admin.something_went_wrong') }}';
        const processing = '{{ __('admin.processing') }}';
        const search = '{{ __('admin.search') }}';
        const lengthMenu = '{{ __('admin.show_entries') }}';
        const info = '{{ __('admin.showing_entries') }}';
        const infoEmpty = '{{ __('admin.showing_empty') }}';
        const infoFiltered = '{{ __('admin.filtered_entries') }}';
        const noMatchingTransactions = '{{ __('admin.no_matching_transactions') }}';
        const noTransactionsAvailable = '{{ __('admin.no_transactions_available') }}';
        const loadingTransactions = '{{ __('admin.loading_transactions') }}';
        const firstPage = '{{ __('admin.first') }}';
        const lastPage = '{{ __('admin.last') }}';
        const nextPage = '{{ __('admin.next') }}';
        const previousPage = '{{ __('admin.previous') }}';
        const showing = '{{ __('admin.showing') }}';
        const to = '{{ __('admin.to') }}';
        const of = '{{ __('admin.of') }}';
        const transactions = '{{ __('admin.transactions') }}';
        const noTransactionsFound = '{{ __('admin.no_transactions_found') }}';
        const confirmDelete = '{{ __('admin.confirm_delete') }}';
        const deleteWarning = '{{ __('admin.delete_warning') }}';
        const yesDelete = '{{ __('admin.yes_delete') }}';
        const cancel = '{{ __('admin.cancel') }}';
        const transactionDeleted = '{{ __('admin.transaction_deleted') }}';
        const deleteError = '{{ __('admin.delete_error') }}';
        const filteredBy = '{{ __('admin.filtered_by') }}';
        const month = '{{ __('admin.month') }}';
        const year = '{{ __('admin.year') }}';
        const and = '{{ __('admin.and') }}';
        const confirmSettlementText = '{{ __('admin.confirm_settlement') }}';
        const settlementWarning = '{{ __('admin.settlement_warning') }}';
        const yesSettlement = '{{ __('admin.yes_settlement') }}';
        const updateError = '{{ __('admin.update_error') }}';
        const jan = '{{ __('admin.jan') }}';
        const feb = '{{ __('admin.feb') }}';
        const mar = '{{ __('admin.mar') }}';
        const apr = '{{ __('admin.apr') }}';
        const may = '{{ __('admin.may') }}';
        const jun = '{{ __('admin.jun') }}';
        const jul = '{{ __('admin.jul') }}';
        const aug = '{{ __('admin.aug') }}';
        const sep = '{{ __('admin.sep') }}';
        const oct = '{{ __('admin.oct') }}';
        const nov = '{{ __('admin.nov') }}';
        const dec = '{{ __('admin.dec') }}';
        const pending = '{{ __('admin.pending') }}';
        const paid = '{{ __('admin.paid') }}';
        const complete = '{{ __('admin.complete') }}';
        const cancelled = '{{ __('admin.cancelled') }}';
        const filterActive = '{{ __('admin.filter_active') }}';

        $(document).ready(function() {
            let table = $('#transactions-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.transactions.index') }}',
                    data: function(d) {
                        d.month_filter = $('#monthFilter').val();
                        d.year_filter = $('#yearFilter').val();
                        d.status_filter = $('#statusFilter').val();
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

                // Custom pagination with sliding window
                pagingType: "full_numbers",
                lengthMenu: [
                    [6, 10, 25, 50],
                    [6, 10, 25, 50]
                ],
                pageLength: 6,

                initComplete: function() {
                    $('.dt-buttons').appendTo('#export-buttons');
                },
                language: {
                    search: search,
                    lengthMenu: "_MENU_", // ✅ hanya dropdown, tanpa teks "Show entries"
                    info: '',
                    infoEmpty: infoEmpty,
                    infoFiltered: infoFiltered,
                    zeroRecords: noMatchingTransactions,
                    emptyTable: noTransactionsAvailable,
                    loadingRecords: loadingTransactions,
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

            });

            // Custom pagination handler untuk sliding window
            table.on('draw', function() {
                customizePagination();
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

                // Update export links and hide filter indicator
                updateExportLinks();
                updateFilterIndicator();
            });

        });

        // Function to confirm settlement
        function confirmSettlement(transactionId) {
            Swal.fire({
                title: confirmSettlementText,
                text: settlementWarning,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#22c55e',
                cancelButtonColor: '#6b7280',
                confirmButtonText: yesSettlement,
                cancelButtonText: cancel,
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send AJAX request to mark as settlement
                    $.ajax({
                        url: '/admin/transactions/' + transactionId + '/settlement',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: success,
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonColor: '#22c55e'
                                });
                                // Reload DataTable
                                $('#transactions-table').DataTable().ajax.reload();
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: error,
                                text: updateError,
                                icon: 'error',
                                confirmButtonColor: '#ef4444'
                            });
                        }
                    });
                }
            });
        }

        // Export functionality with dynamic filters
        function updateExportLinks() {
            const csvBtn = $('#exportCsvBtn');
            const pdfBtn = $('#exportPdfBtn');

            // Build URL with current filters
            const baseUrlCsv = '{{ route('admin.transactions.export.csv') }}';
            const baseUrlPdf = '{{ route('admin.transactions.export.pdf') }}';

            const csvUrl = buildExportUrl(baseUrlCsv);
            const pdfUrl = buildExportUrl(baseUrlPdf);

            // Update button text with filter info
            const filterText = getFilterText();
            if (filterText) {
                csvBtn.html('<i class="mdi mdi-file-excel mr-2"></i>Export CSV ' + filterText);
                pdfBtn.html('<i class="mdi mdi-file-pdf mr-2"></i>Export PDF ' + filterText);
            } else {
                csvBtn.html('<i class="mdi mdi-file-excel mr-2"></i>Export CSV');
                pdfBtn.html('<i class="mdi mdi-file-pdf mr-2"></i>Export PDF');
            }

            // Store URLs in data attributes
            csvBtn.data('url', csvUrl);
            pdfBtn.data('url', pdfUrl);
        }

        function buildExportUrl(baseUrl) {
            const params = new URLSearchParams();

            const month = $('#monthFilter').val();
            const year = $('#yearFilter').val();
            const status = $('#statusFilter').val();

            if (month) params.append('month', month);
            if (year) params.append('year', year);
            if (status) params.append('status', status);

            return params.toString() ? `${baseUrl}?${params.toString()}` : baseUrl;
        }

        function getFilterText() {
            const month = $('#monthFilter').val();
            const year = $('#yearFilter').val();
            const status = $('#statusFilter').val();

            let parts = [];

            if (month && year) {
                const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                    'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
                ];
                parts.push(`${monthNames[month - 1]} ${year}`);
            } else if (year) {
                parts.push(year);
            } else if (month) {
                const monthNames = [jan, feb, mar, apr, may, jun, jul, aug, sep, oct, nov, dec];
                parts.push(`${monthNames[month - 1]}`);
            }

            if (status) {
                const statusText = {
                    'pending': pending,
                    'settlement': complete,
                    'cancel': cancelled
                };
                parts.push(statusText[status] || status);
            }

            return parts.length > 0 ? `(${parts.join(', ')})` : '';
        }

        function updateFilterIndicator() {
            const filterText = getFilterText();
            const indicator = $('#filterIndicator');
            const textSpan = $('#filterText');

            if (filterText) {
                textSpan.text(`${filterActive}: ${filterText}`);
                indicator.removeClass('hidden');
            } else {
                indicator.addClass('hidden');
            }
        }

        // Event handlers
        $('#exportCsvBtn').click(function() {
            const url = $(this).data('url');
            if (url) {
                window.location.href = url;
            }
        });

        $('#exportPdfBtn').click(function() {
            const url = $(this).data('url');
            if (url) {
                window.location.href = url;
            }
        });

        // Update export links and indicator when filters change
        $('#monthFilter, #yearFilter, #statusFilter').change(function() {
            updateExportLinks();
            updateFilterIndicator();
        });

        // Function to customize pagination for sliding window
        function customizePagination() {
            const paginationContainer = $('.dataTables_paginate .pagination');
            if (paginationContainer.length === 0) return;

            const pageButtons = paginationContainer.find('li:not(.previous):not(.next)');
            const currentPage = parseInt($('.dataTables_paginate .pagination .active span').text()) || 1;
            const totalPages = pageButtons.length;

            if (totalPages <= 3) return; // No need to customize if 3 or fewer pages

            // Hide all page buttons first
            pageButtons.hide();

            // Calculate sliding window
            const window = 3;
            let start = Math.max(1, currentPage - Math.floor(window / 2));
            let end = Math.min(totalPages, start + window - 1);

            if (end - start + 1 < window) {
                start = Math.max(1, end - window + 1);
            }

            // Show first page if not in window
            if (start > 1) {
                pageButtons.eq(0).show();
                if (start > 2) {
                    // Add ellipsis after first page
                    if (!pageButtons.eq(1).hasClass('ellipsis')) {
                        pageButtons.eq(1).html('<span>...</span>').addClass('ellipsis').show();
                    }
                }
            }

            // Show pages in window
            for (let i = start; i <= end; i++) {
                pageButtons.eq(i - 1).show();
            }

            // Show last page if not in window
            if (end < totalPages) {
                if (end < totalPages - 1) {
                    // Add ellipsis before last page
                    if (!pageButtons.eq(totalPages - 2).hasClass('ellipsis')) {
                        pageButtons.eq(totalPages - 2).html('<span>...</span>').addClass('ellipsis').show();
                    }
                }
                pageButtons.eq(totalPages - 1).show();
            }
        }

        // Initialize export links and filter indicator
        $(document).ready(function() {
            updateExportLinks();
            updateFilterIndicator();
        });
    </script>
@endpush

@push('styles')
    <style>
        /* Filter styling */
        #monthFilter,
        #yearFilter,
        #statusFilter {
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
            #statusFilter,
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

        /* Custom DataTables Pagination Styling */
        .dataTables_paginate .pagination {
            display: flex !important;
            justify-content: center !important;
            gap: 0.5rem !important;
        }

        .dataTables_paginate .pagination li {
            display: inline-block !important;
        }

        .dataTables_paginate .pagination li a,
        .dataTables_paginate .pagination li span {
            padding: 0.5rem 0.75rem !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            color: #374151 !important;
            text-decoration: none !important;
            transition: all 0.2s ease !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
        }

        .dataTables_paginate .pagination li a:hover {
            background-color: #f3f4f6 !important;
            border-color: #9ca3af !important;
        }

        .dataTables_paginate .pagination li.active span {
            background-color: #d4af37 !important;
            border-color: #d4af37 !important;
            color: white !important;
        }

        .dataTables_paginate .pagination li.disabled span,
        .dataTables_paginate .pagination li.disabled a {
            color: #9ca3af !important;
            background-color: #f9fafb !important;
            cursor: not-allowed !important;
        }

        .dataTables_paginate .pagination li.ellipsis span {
            border: none !important;
            background: none !important;
            color: #6b7280 !important;
            cursor: default !important;
        }

        .dataTables_paginate .pagination li.ellipsis:hover span {
            background: none !important;
        }

        /* Styling untuk tabel */
    </style>
@endpush
