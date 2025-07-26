@extends('admin.layouts.app')

@section('title', 'Transaction Management')
@section('meta_description', 'Comprehensive transaction management system for WOX Barbershop')

@section('content')

    <div class="is-hero-bar">

        <div class="flex flex-col md:flex-row justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold mb-2">
                    <i class="fas fa-calendar-check mr-3"></i>Transaction Management
                </h1>
                <p class="text-blacktext-lg">
                    Monitor all payment transactions and financial activities
                </p>
            </div>
            <div class="flex items-center space-x-4 mt-4 md:mt-0">
                <div class="glass-effect px-4 py-2 rounded-lg">
                    <i class="fas fa-coins mr-2"></i>
                    <span class="font-semibold">Total Revenue: Rp
                        {{ number_format(\App\Models\Transaction::where('payment_status', 'settlement')->sum('total_amount'), 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Statistics Cards -->
    <div class="mx-auto px-6 pt-12 -mt-8 mb-8">
        <div class="grid grid-cols-4 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Today's Revenue</p>
                        <p class="text-2xl font-bold text-green-600">Rp
                            {{ number_format(\App\Models\Transaction::whereDate('created_at', today())->where('payment_status', 'settlement')->sum('total_amount'), 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-chart-line text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 card-hover transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Pending Payments</p>
                        <p class="text-2xl font-bold text-yellow-600">
                            {{ \App\Models\Transaction::where('payment_status', 'pending')->count() }}</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <i class="fas fa-hourglass-half text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 card-hover transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Completed Today</p>
                        <p class="text-2xl font-bold text-blue-600">
                            {{ \App\Models\Transaction::whereDate('created_at', today())->where('payment_status', 'settlement')->count() }}
                        </p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-check-circle text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 card-hover transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Failed Payments</p>
                        <p class="text-2xl font-bold text-red-600">
                            {{ \App\Models\Transaction::where('payment_status', 'failed')->count() }}</p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <section class="section main-section">
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">

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
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300">
                                Customer
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300">
                                Booking
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300">
                                Service
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300">
                                Amount
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300">
                                Payment Method
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300">
                                Status
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300">
                                Transaction Date
                            </th>
                            <th class="text-center text-xs font-semibold text-gray-700 dark:text-gray-300">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    </tbody>
                </table>
            </div>
        </div>
    </section>


    <!-- Enhanced Transaction Detail Modal -->
    <div id="transaction-detail-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-screen overflow-y-auto">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-gray-900">Transaction Details</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeTransactionModal()">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>
                <div id="transaction-detail-content" class="p-6">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Enhanced DataTable configuration
            const table = $('#transactions-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: '{{ route('transactions.index') }}',
                    data: function(d) {
                        d.payment_method_filter = $('#payment-method-filter').val();
                        d.status_filter = $('#status-filter').val();
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
                        data: 'customer_info',
                        name: 'user.name',
                        className: 'text-left'
                    },
                    {
                        data: 'booking_info',
                        name: 'booking.id',
                        className: 'text-left'
                    },
                    {
                        data: 'service_info',
                        name: 'service.name',
                        className: 'text-left'
                    },
                    {
                        data: 'amount_formatted',
                        name: 'total_amount',
                        className: 'text-right'
                    },
                    {
                        data: 'payment_method_display',
                        name: 'payment_method',
                        className: 'text-left'
                    },
                    {
                        data: 'status_badge',
                        name: 'payment_status',
                        className: 'text-center'
                    },
                    {
                        data: 'transaction_date',
                        name: 'created_at',
                        className: 'text-left'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                dom: "<'hidden'B><'flex flex-col md:flex-row justify-between items-center gap-4 mb-6'lf><'overflow-x-auto't><'flex flex-col md:flex-row justify-between items-center gap-4 mt-6'ip>",
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
                    searchPlaceholder: "Search transactions...",
                    info: "Showing _START_ to _END_ of _TOTAL_ transactions",
                    infoEmpty: "No transactions found",
                    zeroRecords: "No matching transactions found",
                    emptyTable: "No transactions available",
                    processing: `
                <div class="flex items-center justify-center py-8">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-600"></div>
                    <span class="ml-4 text-gray-600 text-lg">Loading transactions...</span>
                </div>
            `,
                    paginate: {
                        previous: '<i class="fas fa-chevron-left"></i>',
                        next: '<i class="fas fa-chevron-right"></i>'
                    }
                },
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                order: [
                    [7, 'desc']
                ], // Order by transaction date descending
                initComplete: function() {
                    $('.dt-buttons').appendTo('#export-buttons');
                }
            });

            // Filter change events
            $('#payment-method-filter, #status-filter').on('change', function() {
                table.draw();
            });

            // Refresh button
            $('#refresh-table').on('click', function() {
                showNotification('info', 'Refreshing', 'Updating transaction data...');
                table.ajax.reload();
                updateStats();
            });

            // Update statistics
            function updateStats() {
                // This would typically fetch updated stats via AJAX
                // For now, we'll just refresh the page stats
            }

            // Auto-refresh every 30 seconds
            setInterval(function() {
                table.ajax.reload(null, false);
                updateStats();
            }, 30000);

            // Modal functions
            window.openTransactionDetail = function(transactionId) {
                showLoading();
                // Fetch transaction details via AJAX
                $.get(`/admin/transactions/${transactionId}/detail`)
                    .done(function(response) {
                        $('#transaction-detail-content').html(response);
                        $('#transaction-detail-modal').removeClass('hidden');
                        hideLoading();
                    })
                    .fail(function() {
                        hideLoading();
                        showNotification('error', 'Error', 'Failed to load transaction details');
                    });
            };

            window.closeTransactionModal = function() {
                $('#transaction-detail-modal').addClass('hidden');
            };

            // Transaction actions
            window.processRefund = function(transactionId) {
                Swal.fire({
                    title: 'Process Refund',
                    text: 'Are you sure you want to process a refund for this transaction?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f59e0b',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, process refund',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        showLoading();
                        $.post(`/admin/transactions/${transactionId}/refund`, {
                                _token: '{{ csrf_token() }}'
                            })
                            .done(function(response) {
                                hideLoading();
                                showNotification('success', 'Success', response.message);
                                table.ajax.reload();
                            })
                            .fail(function(xhr) {
                                hideLoading();
                                showNotification('error', 'Error', xhr.responseJSON?.message ||
                                    'Failed to process refund');
                            });
                    }
                });
            };

            window.markAsSettled = function(transactionId) {
                Swal.fire({
                    title: 'Mark as Settled',
                    text: 'Mark this transaction as settled/completed?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, mark as settled',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        showLoading();
                        $.post(`/admin/transactions/${transactionId}/settle`, {
                                _token: '{{ csrf_token() }}'
                            })
                            .done(function(response) {
                                hideLoading();
                                showNotification('success', 'Success', response.message);
                                table.ajax.reload();
                            })
                            .fail(function(xhr) {
                                hideLoading();
                                showNotification('error', 'Error', xhr.responseJSON?.message ||
                                    'Failed to mark as settled');
                            });
                    }
                });
            };

            // Success notification
            @if (session('success'))
                showNotification('success', 'Success!', '{{ session('success') }}');
            @endif

            // Error notification
            @if (session('error'))
                showNotification('error', 'Error!', '{{ session('error') }}');
            @endif
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
