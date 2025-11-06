    @extends('admin.layouts.app')

    @section('title', __('admin.bookings'))
    @section('meta_description', 'Comprehensive booking management system for WOX Barbershop')

    @section('content')
        <!-- Enhanced Page Header -->
        <div class="is-hero-bar">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div>
                    <h1 class="text-4xl font-bold mb-2">
                        <i class="fas fa-calendar-check mr-3"></i>{{ __('admin.booking_page_title') }}
                    </h1>
                    <p class="text-blacktext-lg">
                        {{ __('admin.booking_page_subtitle') }}
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
                            <p class="text-gray-600 text-sm font-medium">{{ __('admin.todays_bookings') }}</p>
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
                            <p class="text-gray-600 text-sm font-medium">{{ __('admin.pending_approval') }}</p>
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
                            <p class="text-gray-600 text-sm font-medium">{{ __('admin.in_progress') }}</p>
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
                            <p class="text-gray-600 text-sm font-medium">{{ __('admin.completed_today') }}</p>
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
                @hasrole('admin')
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">

                            @if (Auth::user()->hasRole('admin'))
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
                                    <select id="status-filter"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200 text-sm">
                                        <option value="">{{ __('admin.all_status') }}</option>
                                        <option value="pending">{{ __('booking.status_pending') }}</option>
                                        <option value="confirmed">{{ __('booking.status_confirmed') }}</option>
                                        <option value="in_progress">{{ __('booking.status_in_progress') }}</option>
                                        <option value="completed">{{ __('booking.status_completed') }}</option>
                                        <option value="cancelled">{{ __('booking.status_cancelled') }}</option>
                                    </select>
                                    <div class="flex items-center space-x-2">
                                        <button id="resetFilter"
                                            class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 font-medium rounded-md shadow-sm transition-colors duration-200 text-sm">
                                            <i class="mdi mdi-refresh mr-1"></i> {{ __('admin.reset') }}
                                        </button>
                                        <div id="filterIndicator"
                                            class="hidden px-3 py-2 bg-blue-100 text-blue-800 text-sm rounded-md">
                                            <i class="mdi mdi-filter mr-1"></i>
                                            <span id="filterText"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-wrap items-center gap-2">
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
                            @endif

                        </div>
                    </div>
                @endhasrole

                <div class="card-content ">
                    <table id="bookings-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('admin.customer') }}</th>
                                <th>{{ __('admin.booking_name') }}</th>
                                <th>{{ __('admin.contact') }}</th>
                                <th>{{ __('admin.service') }}</th>
                                <th>{{ __('admin.hairstyle') }}</th>
                                <th>{{ __('admin.date_time') }}</th>
                                <th>{{ __('admin.queue') }}</th>
                                <th>{{ __('admin.status') }}</th>
                                <th>{{ __('admin.actions') }}</th>
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
            // ✅ Translation variables (now working properly with Indonesian locale)
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
            const noMatchingBookings = '{{ __('admin.no_matching_bookings') }}';
            const noBookingsAvailable = '{{ __('admin.no_bookings_available') }}';
            const loadingBookings = '{{ __('admin.loading_bookings') }}';
            const firstPage = '{{ __('admin.first') }}';
            const lastPage = '{{ __('admin.last') }}';
            const nextPage = '{{ __('admin.next') }}';
            const previousPage = '{{ __('admin.previous') }}';
            const confirm = '{{ __('admin.confirm') }}';
            const cancel = '{{ __('admin.cancel') }}';
            const yes = '{{ __('admin.yes') }}';
            const no = '{{ __('admin.no') }}';
            const confirmBookingText = '{{ __('admin.confirm_booking') }}';
            const startServiceText = '{{ __('admin.start_service') }}';
            const completeServiceText = '{{ __('admin.complete_service') }}';
            const cancelBookingText = '{{ __('admin.cancel_booking') }}';
            const confirmBookingMessage = '{{ __('admin.confirm_booking_message') }}';
            const startServiceMessage = '{{ __('admin.start_service_message') }}';
            const completeServiceMessage = '{{ __('admin.complete_service_message') }}';
            const cancelBookingMessage = '{{ __('admin.cancel_booking_message') }}';
            const errorOccurred = '{{ __('admin.error_occurred') }}';

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
                            data: 'name',
                            name: 'name',
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
                        search: search,
                        lengthMenu: "_MENU_", // ✅ tampil dropdown saja tanpa teks
                        info: '',
                        infoEmpty: infoEmpty,
                        infoFiltered: infoFiltered,
                        zeroRecords: noMatchingBookings,
                        emptyTable: noBookingsAvailable,
                        loadingRecords: loadingBookings,
                        processing: `<div class="flex items-center justify-center py-8">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        <span class="ml-4 text-gray-600 text-lg">${processing}...</span>
    </div>`,
                        paginate: {
                            previous: '<i class="fas fa-chevron-left"></i><span class="sr-only">' +
                                previousPage + '</span>',
                            next: '<span class="sr-only">' + nextPage +
                                '</span><i class="fas fa-chevron-right"></i>'
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
                    updateExportLinks(); // Update export URLs when filters change
                });

                // Reset filter button
                $('#resetFilter').on('click', function() {
                    $('#monthFilter').val('');
                    $('#yearFilter').val('');
                    $('#status-filter').val('');
                    table.ajax.reload();
                    updateExportLinks(); // Update export URLs when filters are reset
                });

                // Function to update export links based on current filters
                function updateExportLinks() {
                    const month = $('#monthFilter').val();
                    const year = $('#yearFilter').val();
                    const status = $('#status-filter').val();

                    // Update button text to show filter info
                    const filterText = getFilterText(month, year, status);
                    $('#exportCsvBtn').html(`<i class="mdi mdi-file-delimited mr-2"></i>Export CSV${filterText}`);
                    $('#exportPdfBtn').html(`<i class="mdi mdi-file mr-2"></i>Export PDF${filterText}`);

                    // Update filter indicator
                    updateFilterIndicator(month, year, status);
                }

                // Function to build export URL with current filters
                function buildExportUrl(baseUrl) {
                    const month = $('#monthFilter').val();
                    const year = $('#yearFilter').val();
                    const status = $('#status-filter').val();

                    let params = new URLSearchParams();
                    if (month) params.append('month', month);
                    if (year) params.append('year', year);
                    if (status) params.append('status', status);

                    const queryString = params.toString();
                    return queryString ? `${baseUrl}?${queryString}` : baseUrl;
                }

                // Function to get filter text for button labels
                function getFilterText(month, year, status) {
                    if (!month && !year && !status) return '';

                    let parts = [];

                    if (month && year) {
                        const monthName = new Date(year, month - 1).toLocaleDateString('en-US', {
                            month: 'long'
                        });
                        parts.push(`${monthName} ${year}`);
                    } else if (year) {
                        parts.push(`${year}`);
                    } else if (month) {
                        const monthName = new Date(2024, month - 1).toLocaleDateString('en-US', {
                            month: 'long'
                        });
                        parts.push(`${monthName}`);
                    }

                    if (status) {
                        const statusText = {
                            'pending': '{{ __('booking.status_pending') }}',
                            'confirmed': '{{ __('booking.status_confirmed') }}',
                            'in_progress': '{{ __('booking.status_in_progress') }}',
                            'completed': '{{ __('booking.status_completed') }}',
                            'cancelled': '{{ __('booking.status_cancelled') }}'
                        };
                        parts.push(statusText[status] || status);
                    }

                    return parts.length > 0 ? ` (${parts.join(', ')})` : '';
                }

                // Function to update filter indicator
                function updateFilterIndicator(month, year, status) {
                    const $indicator = $('#filterIndicator');
                    const $filterText = $('#filterText');

                    if (month || year || status) {
                        let parts = [];

                        if (month && year) {
                            const monthName = new Date(year, month - 1).toLocaleDateString('en-US', {
                                month: 'long'
                            });
                            parts.push(`${monthName} ${year}`);
                        } else if (year) {
                            parts.push(`Year ${year}`);
                        } else if (month) {
                            const monthName = new Date(2024, month - 1).toLocaleDateString('en-US', {
                                month: 'long'
                            });
                            parts.push(`Month ${monthName}`);
                        }

                        if (status) {
                            const statusText = {
                                'pending': '{{ __('booking.status_pending') }}',
                                'confirmed': '{{ __('booking.status_confirmed') }}',
                                'in_progress': '{{ __('booking.status_in_progress') }}',
                                'completed': '{{ __('booking.status_completed') }}',
                                'cancelled': '{{ __('booking.status_cancelled') }}'
                            };
                            parts.push(`Status: ${statusText[status] || status}`);
                        }

                        const text = `{{ __('admin.filter_active') }}: ${parts.join(', ')}`;

                        $filterText.text(text);
                        $indicator.removeClass('hidden');
                    } else {
                        $indicator.addClass('hidden');
                    }
                }

                // Initialize export links on page load
                updateExportLinks();

                // Export click handlers with dynamic URL building
                $('#exportCsvBtn').on('click', function(e) {
                    e.preventDefault();
                    const csvUrl = buildExportUrl('{{ route('admin.bookings.export.csv') }}');
                    window.location.href = csvUrl;
                });

                $('#exportPdfBtn').on('click', function(e) {
                    e.preventDefault();
                    const pdfUrl = buildExportUrl('{{ route('admin.bookings.export.pdf') }}');
                    window.location.href = pdfUrl;
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
                    showNotification('success', success, '{{ session('success') }}');
                @endif

                // Error notification
                @if (session('error'))
                    showNotification('error', error, '{{ session('error') }}');
                @endif
            });

            // Booking status update functions
            function confirmBooking(bookingId) {
                updateBookingStatus(bookingId, 'confirmed', confirmBookingMessage);
            }

            function startService(bookingId) {
                updateBookingStatus(bookingId, 'in_progress', startServiceMessage);
            }

            function completeService(bookingId) {
                updateBookingStatus(bookingId, 'completed', completeServiceMessage);
            }

            function cancelBooking(bookingId) {
                updateBookingStatus(bookingId, 'cancelled', cancelBookingMessage);
            }

            function updateBookingStatus(bookingId, status, action) {
                Swal.fire({
                    title: confirm,
                    text: `${action}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d4af37',
                    cancelButtonColor: '#aaa',
                    confirmButtonText: yes,
                    cancelButtonText: cancel
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: processing,
                            text: processing + '...',
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
                                    showNotification('success', success, data.message);
                                    // Reload DataTable instead of whole page for better UX
                                    if (typeof table !== 'undefined' && table.ajax) {
                                        table.ajax.reload(null, false); // Keep pagination
                                    } else {
                                        location.reload();
                                    }
                                    // Update statistics
                                    updateStatistics();
                                } else {
                                    showNotification('error', error, data.message || errorOccurred);
                                }
                            })
                            .catch(error => {
                                Swal.close();
                                console.error('Error details:', error);
                                showNotification('error', error,
                                    errorOccurred + ': ' + error.message);
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
