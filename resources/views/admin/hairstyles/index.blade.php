@extends('admin.layouts.app')

@section('title', 'Hairstyle Management')

@section('content')
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div>
                <h1 class="title text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-cut mr-3"></i>
                    Hairstyle Management
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    Manage hairstyle recommendations for Wox’s Barbershop
                </p>
            </div>
        </div>
    </section>

    <section class="section main-section">
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <a href="{{ route('admin.hairstyles.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md shadow-sm transition-colors duration-200">
                        <span class="icon mr-2"><i class="mdi mdi-plus"></i></span>
                        Create Hairstyle
                    </a>

                </div>
            </div>

            <div class="card-content ">
                <table id="hairstyles-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Bentuk Kepala</th>
                            <th>Tipe Rambut</th>
                            <th>Style Preference</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"></tbody>
                </table>
            </div>
        </div>
    </section>
@endsection


@push('scripts')
    <script>
        $(document).ready(function() {
            let table = $('#hairstyles-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.hairstyles.index') }}',
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
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'bentuk_kepala',
                        name: 'bentuk_kepala',
                        render: function(data, type, row) {
                            // Data sekarang sudah berupa HTML dari controller
                            return data || '-';
                        }
                    },
                    {
                        data: 'tipe_rambut',
                        name: 'tipe_rambut',
                        render: function(data, type, row) {
                            return data || '-';
                        }
                    },
                    {
                        data: 'style_preference',
                        name: 'style_preference',
                        render: function(data, type, row) {
                            return data || '-';
                        }
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data) {
                            return data ||
                                '<div class="flex justify-center"><i class="fas fa-image text-gray-400"></i></div>';
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                dom: "<'hidden'B><'flex flex-col md:flex-row justify-between items-center gap-4 mb-4'lf><'overflow-x-auto't><'flex flex-col md:flex-row justify-between items-center gap-4 mt-4'ip>",
                language: {
                    info: "Showing _START_ to _END_ of _TOTAL_ hairstyles",
                    infoEmpty: "No hairstyles found",
                    zeroRecords: "No matching hairstyles",
                    emptyTable: "No hairstyles available",
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
            }).then(() => {
                location.reload(); // reload halaman penuh setelah update / create
            });
        @endif

        // Delete User
        $(document).on('click', '.deleteBtn', function() {
            const hairstyleId = $(this).data('id');
            const deleteUrl = '{{ route('admin.hairstyles.destroy', ':id') }}'.replace(':id', hairstyleId);

            Swal.fire({
                title: 'Are you sure?',
                text: "This will permanently delete the hairstyle.",
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
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location
                                    .reload(); // reload halaman penuh setelah delete
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message ||
                                    'Something went wrong.',
                            });
                        }
                    });
                }
            });
        });

        // Auto reload with authentication check
        let refreshInterval = setInterval(function() {
            // Check if user is still authenticated
            fetch('{{ route('admin.hairstyles.index') }}', {
                method: 'HEAD',
                credentials: 'same-origin'
            }).then(response => {
                if (response.ok) {
                    // User is still authenticated, reload table
                    table.ajax.reload(null, false);
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
        // });
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
            min-width: 120px;
            /* biar tidak terlalu kecil */
        }

        /* Mobile view */
        @media (max-width: 768px) {
            .dt-buttons {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
                /* jarak antar tombol */
                width: 100%;
            }

            .dt-buttons .dt-button {
                width: 100% !important;
                /* full width */
                text-align: center;
                /* biar teks rata tengah */
            }
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
