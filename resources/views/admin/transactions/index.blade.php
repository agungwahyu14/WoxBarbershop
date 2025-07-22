@extends('admin.layouts.app')

@section('content')
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div>
                <h1 class="title text-3xl font-bold text-gray-900 dark:text-white">
                    Transaction 
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    Manage transactions for Wox’s Barbershop
                </p>
            </div>
        </div>
    </section>

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
                            <th class="w-12 px-6 py-4 text-center text-xs font-semibold">#</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold">User</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold">User Booking</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold">Service</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold">Total Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold">Payment Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold">Payment Method</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold">Transaction Date</th>
                            <th class="text-center text-xs font-semibold">Actions</th>
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
        let table;

        $(document).ready(function() {
            table = $('#transactions-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('transactions.index') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-start'
                    },
                    {
                        data: 'user_id',
                        name: 'user_id',
                        className: 'text-start'
                    },
                    {
                        data: 'booking_id',
                        name: 'booking_id',
                        className: 'text-start'
                    },
                    {
                        data: 'service_id',
                        name: 'service_id',
                        className: 'text-start'
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount',
                        className: 'text-start'
                    },
                    {
                        data: 'payment_status',
                        name: 'payment_status',
                        className: 'text-start capitalize'
                    },
                    {
                        data: 'payment_method',
                        name: 'payment_method',
                        className: 'text-start'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        className: 'text-start'
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
                    }
                ],

                language: {
                    searchPlaceholder: "Cari transaksi...",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ transaksi",
                    infoEmpty: "Tidak ada transaksi ditemukan",
                    zeroRecords: "Tidak ada transaksi yang cocok",
                    emptyTable: "Belum ada transaksi",
                    processing: `<div class="flex items-center justify-center py-4">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="ml-2 text-gray-600 dark:text-gray-400">Loading...</span>
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

        // Success popup
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        // Delete Transaction
        $(document).on('click', '.deleteBtn', function() {
            const id = $(this).data('id');
            const url = '{{ route('transactions.destroy', ':id') }}'.replace(':id', id);

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Transaksi akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                timer: 3000,
                                showConfirmButton: false
                            });
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: xhr.responseJSON?.message || 'Terjadi kesalahan.'
                            });
                        }
                    });
                }
            });
        });

        // Optional: Auto refresh table every 30s
        setInterval(() => table?.ajax?.reload(null, false), 30000);
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
        #roles-table {
            width: 100% !important;
            table-layout: auto !important;
        }
    </style>
@endpush
