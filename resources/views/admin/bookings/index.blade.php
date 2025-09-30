@extends('admin.layouts.app')

@section('title', 'Booking Management')
@section('meta_description', 'Comprehensive booking management system for WOX Barbershop')

@section('content')
    <!-- Enhanced Page Header -->
    <div class="is-hero-bar">

        <div class="flex flex-col md:flex-row justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold mb-2">
                    <i class="fas fa-calendar-check mr-3"></i>Booking Management
                </h1>
                <p class="text-blacktext-lg">
                    Manage all customer appointments and reservations
                </p>
            </div>
            <div class="flex items-center space-x-4 mt-4 md:mt-0">
                <div class="glass-effect px-4 py-2 rounded-lg">
                    <i class="fas fa-clock mr-2"></i>
                    <span id="current-time"></span>
                </div>
            </div>
        </div>

    </div>

    <!-- Statistics Cards -->
    <div class=" mx-auto px-6 pt-12 -mt-8 mb-8">
        <div class="grid grid-cols-4 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Today's Bookings</p>
                        <p class="text-3xl font-bold text-blue-600" id="today-bookings">
                            {{ \App\Models\Booking::whereDate('date_time', today())->count() }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-calendar-day text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 card-hover transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Pending Approval</p>
                        <p class="text-3xl font-bold text-yellow-600" id="pending-bookings">
                            {{ \App\Models\Booking::where('status', 'pending')->count() }}</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 card-hover transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">In Progress</p>
                        <p class="text-3xl font-bold text-orange-600" id="progress-bookings">
                            {{ \App\Models\Booking::where('status', 'in_progress')->count() }}</p>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-full">
                        <i class="fas fa-cut text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 card-hover transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Completed Today</p>
                        <p class="text-3xl font-bold text-green-600" id="completed-bookings">
                            {{ \App\Models\Booking::where('status', 'completed')->whereDate('date_time', today())->count() }}
                        </p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
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

                    @if (Auth::user()->hasRole('admin'))

                        <div class="flex flex-wrap items-center gap-2">
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
                            <div class="flex items-center space-x-2">
                                <select id="status-filter"
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200 text-sm">
                                    <option value="">All Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="confirmed">Confirmed</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                                <button id="resetFilter"
                                    class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 font-medium rounded-md shadow-sm transition-colors duration-200 text-sm">
                                    <i class="mdi mdi-refresh mr-1"></i>Reset
                                </button>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('admin.bookings.export.csv') }}"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md shadow-sm transition-colors duration-200 text-sm">
                                    <i class="mdi mdi-file-delimited mr-2"></i> CSV
                                </a>
                                <a href="{{ route('admin.bookings.export.pdf') }}"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md shadow-sm transition-colors duration-200 text-sm">
                                    <i class="mdi mdi-file mr-2"></i> PDF
                                </a>
                            </div>
                        </div>
                    @elseif(Auth::user()->hasRole('pegawai'))
                        <div class="flex items-center space-x-2">
                            <select id="status-filter"
                                class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200 text-sm">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                            <button id="resetFilter"
                                class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 font-medium rounded-md shadow-sm transition-colors duration-200 text-sm">
                                <i class="mdi mdi-refresh mr-1"></i>Reset
                            </button>
                        </div>
                    @endif

                </div>
            </div>

            <div class="card-content ">
                <table id="bookings-table">
                    <thead>
                        <tr>
                            <th>
                                #
                            </th>
                            <th>
                                Customer
                            </th>
                            <th>
                                Contact
                            </th>
                            <th>
                                Service
                            </th>
                            <th>
                                Hairstyle
                            </th>
                            <th>
                                Date & Time
                            </th>
                            <th>
                                Queue
                            </th>
                            <th>
                                Status
                            </th>
                            <th>
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



@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Enhanced DataTable configuration
            let table = $('#bookings-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: '{{ route('admin.bookings.index') }}',
                    data: function(d) {
                        d.status_filter = $('#status-filter').val();
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
                        data: 'customer_info',
                        name: 'user.name',
                        className: 'text-left'
                    },
                    {
                        data: 'contact_info',
                        name: 'user.no_telepon',
                        className: 'text-left'
                    },
                    {
                        data: 'service_info',
                        name: 'service.name',
                        className: 'text-left'
                    },
                    {
                        data: 'hairstyle_info',
                        name: 'hairstyle.name',
                        className: 'text-left'
                    },
                    {
                        data: 'datetime_formatted',
                        name: 'date_time',
                        className: 'text-left'
                    },
                    {
                        data: 'queue_display',
                        name: 'queue_number',
                        className: 'text-center'
                    },
                    {
                        data: 'status_badge',
                        name: 'status',
                        className: 'text-center'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center space-x-2'
                    }
                ],
                dom: "<'hidden'B><'flex flex-col md:flex-row justify-between items-center gap-4 mb-6'lf><'overflow-x-auto't><'flex flex-col md:flex-row justify-between items-center gap-4 mt-6'ip>",
                buttons: [{
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

                    info: "Showing _START_ to _END_ of _TOTAL_ bookings",
                    infoEmpty: "No bookings found",
                    zeroRecords: "No matching bookings found",
                    emptyTable: "No bookings available",
                    processing: `
                <div class="flex items-center justify-center py-8">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                    <span class="ml-4 text-gray-600 text-lg">Loading bookings...</span>
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
                    [5, 'desc']
                ], // Order by date_time descending
                initComplete: function() {
                    $('.dt-buttons').appendTo('#export-buttons');
                }
            });

            // Month/Year/Status filter event listeners
            $('#monthFilter, #yearFilter, #status-filter').on('change', function() {
                table.ajax.reload();
            });

            // Reset filter button
            $('#resetFilter').on('click', function() {
                $('#monthFilter').val('');
                $('#yearFilter').val('');
                $('#status-filter').val('');
                table.ajax.reload();
            });

            // Refresh button
            $('#refresh-table').on('click', function() {
                showNotification('info', 'Refreshing', 'Updating booking data...');
                table.ajax.reload();
                updateStats();
            });

            // Real-time clock
            function updateClock() {
                const now = new Date();
                const timeString = now.toLocaleTimeString('en-US', {
                    hour12: true,
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
                $('#current-time').text(timeString);
            }

            setInterval(updateClock, 1000);
            updateClock();

            // Update statistics
            function updateStats() {
                // This would typically fetch updated stats via AJAX
                // For now, we'll just refresh the page stats
            }

            // Auto-refresh every 30 seconds with authentication check
            let refreshInterval = setInterval(function() {
                // Check if user is still authenticated
                fetch('{{ route('admin.bookings.index') }}', {
                    method: 'HEAD',
                    credentials: 'same-origin'
                }).then(response => {
                    if (response.ok) {
                        // User is still authenticated, reload table and update stats
                        table.ajax.reload(null, false);
                        updateStats();
                    } else {
                        // User is not authenticated, clear interval and redirect
                        clearInterval(refreshInterval);
                        if (response.status === 401 || response.status === 419) {
                            window.location.href = '{{ route('login') }}';
                        }
                    }
                }).catch(error => {
                    // Connection error, clear interval
                    clearInterval(refreshInterval);
                    console.log('Auto-refresh stopped due to connection error');
                });
            }, 30000);

            // Stop refresh when page is about to unload
            window.addEventListener('beforeunload', function() {
                clearInterval(refreshInterval);
            });

            // Modal functions

            // Success notification
            @if (session('success'))
                showNotification('success', 'Success!', '{{ session('success') }}');
            @endif

            // Error notification
            @if (session('error'))
                showNotification('error', 'Error!', '{{ session('error') }}');
            @endif
        });

        // Booking status update functions
        function confirmBooking(bookingId) {
            updateBookingStatus(bookingId, 'confirmed', 'confirm this booking');
        }

        function startService(bookingId) {
            updateBookingStatus(bookingId, 'in_progress', 'start the service for this booking');
        }

        function completeService(bookingId) {
            updateBookingStatus(bookingId, 'completed', 'mark this booking as completed');
        }

        function cancelBooking(bookingId) {
            updateBookingStatus(bookingId, 'cancelled', 'cancel this booking');
        }

        function updateBookingStatus(bookingId, status, action) {
            Swal.fire({
                title: 'Konfirmasi',
                text: `Apakah Anda yakin ingin ${action}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d4af37',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Ya, lanjutkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Sedang memproses permintaan Anda',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });

                    fetch('{{ route('admin.bookings.updateStatus', ':bookingId') }}'.replace(':bookingId',
                            bookingId), {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                status: status
                            })
                        })
                        .then(response => {
                            console.log('Response status:', response.status);
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Response data:', data);
                            Swal.close();
                            if (data.success) {
                                showNotification('success', 'Success!', data.message);
                                // Reload DataTable instead of whole page for better UX
                                if (typeof table !== 'undefined' && table.ajax) {
                                    table.ajax.reload(null, false); // Keep pagination
                                } else {
                                    location.reload();
                                }
                                // Update statistics
                                updateStatistics();
                            } else {
                                showNotification('error', 'Error!', data.message || 'Terjadi kesalahan');
                            }
                        })
                        .catch(error => {
                            Swal.close();
                            console.error('Error details:', error);
                            showNotification('error', 'Error!',
                                'Terjadi kesalahan saat update status booking: ' + error.message);
                        });
                }
            });
        }

        // Function to update statistics after status change
        function updateStatistics() {
            // Update today's bookings count
            fetch('{{ route('admin.bookings.statistics') }}', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('today-bookings').textContent = data.today_bookings || '0';
                        document.getElementById('pending-bookings').textContent = data.pending_bookings || '0';
                        document.getElementById('progress-bookings').textContent = data.progress_bookings || '0';
                        document.getElementById('completed-bookings').textContent = data.completed_bookings || '0';
                    }
                })
                .catch(error => {
                    console.log('Error updating statistics:', error);
                });
        }
    </script>
@endpush

@push('styles')
    <style>
        /* Filter styling */
        #monthFilter,
        #yearFilter,
        #status-filter {
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
            #status-filter,
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
