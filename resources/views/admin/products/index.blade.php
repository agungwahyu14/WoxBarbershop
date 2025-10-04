@extends('admin.layouts.app')

@section('content')
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div>
                <h1 class="title text-3xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-box mr-3"></i> Product Management
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    Manage barbershop products and inventory
                </p>
            </div>
        </div>
    </section>

    <section class="section min-h-screen main-section">
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <!-- Table Header -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <a href="{{ route('admin.products.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md shadow-sm transition-colors duration-200">
                        <span class="icon mr-2"><i class="mdi mdi-plus"></i></span>
                        Add Product
                    </a>
                </div>
            </div>

            <!-- Table Content -->
            <div class="card-content">
                <table id="products-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Action</th>
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
            // Initialize DataTable with improved styling
            let table = $('#products-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.products.index') }}',
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
                        className: 'px-6 py-4'
                    },
                    {
                        data: 'category',
                        name: 'category',
                        className: 'px-6 py-4 text-center'
                    },
                    {
                        data: 'price',
                        name: 'price',
                        className: 'px-6 py-4 text-center'
                    },
                    {
                        data: 'stock',
                        name: 'stock',
                        className: 'px-6 py-4 text-center'
                    },
                    {
                        data: 'is_active',
                        name: 'is_active',
                        className: 'px-6 py-4 text-center'
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

                language: {
                    search: "Search:",
                    lengthMenu: "_MENU_ ",
                    info: "Showing _START_ to _END_ of _TOTAL_ products",
                    infoEmpty: "No products found",
                    infoFiltered: "(filtered from _MAX_ total products)",
                    zeroRecords: "No matching products found",
                    emptyTable: "No products available",
                    loadingRecords: "Loading products...",
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
                ], // Sort by product name by default
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
                const productId = $(this).data('id');
                const deleteUrl = '{{ route('admin.products.destroy', ':id') }}'.replace(':id', productId);

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
                                    $('#products-table').DataTable().ajax.reload();
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

            // Handle form submissions via AJAX (for create/update) - exclude logout form
            $('form:not(#logout-form)').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const formData = new FormData(form[0]);
                const url = form.attr('action');
                const method = form.attr('method');

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            timer: 3000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href =
                            '{{ route('admin.products.index') }}';
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

        // Refresh table data with authentication check
        window.refreshInterval = setInterval(function() {
            // Check if user is logging out
            if (window.isLoggingOut) {
                clearInterval(window.refreshInterval);
                return;
            }

            // Check if user is still authenticated
            fetch('{{ route('admin.products.index') }}', {
                method: 'HEAD',
                credentials: 'same-origin'
            }).then(response => {
                if (response.ok && !window.isLoggingOut) {
                    // User is still authenticated, reload table
                    table.ajax.reload(null, false);
                } else {
                    // User is not authenticated, clear interval and redirect
                    clearInterval(window.refreshInterval);
                    if (response.status === 401 || response.status === 419) {
                        window.location.href = '{{ route('login') }}';
                    }
                }
            }).catch(error => {
                // Connection error, clear interval only if not logging out
                if (!window.isLoggingOut) {
                    clearInterval(window.refreshInterval);
                    console.warn('Products auto-refresh stopped due to connection error:', error.message);
                }
            });
        }, 30000); // Refresh every 30 seconds

        // Stop refresh when page is about to unload
        window.addEventListener('beforeunload', function() {
            clearInterval(window.refreshInterval);
        });
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
        #products-table {
            width: 100% !important;
            table-layout: auto !important;
        }
    </style>
@endpush
