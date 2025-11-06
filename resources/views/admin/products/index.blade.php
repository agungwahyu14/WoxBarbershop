@extends('admin.layouts.app')

@section('content')
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div>
                <h1 class="title text-3xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-box mr-3"></i> {{ __('admin.products_page_title') }}
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    {{ __('admin.products_page_subtitle') }}
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
                        {{ __('admin.create_product_btn') }}
                    </a>
                </div>
            </div>

            <!-- Table Content -->
            <div class="card-content">
                <table id="products-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('admin.name_column') }}</th>
                            {{-- <th>{{ __('admin.category_column') }}</th> --}}
                            <th>{{ __('admin.price_column') }}</th>
                            <th>{{ __('admin.stock_column') }}</th>
                            <th>{{ __('admin.status_column') }}</th>
                            <th>{{ __('admin.actions_column') }}</th>
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
        const deleteProductWarning = '{{ __('admin.delete_product_warning') }}';
        const yesDeleteIt = '{{ __('admin.yes_delete_it') }}';
        const somethingWentWrong = '{{ __('admin.something_went_wrong') }}';
        const processing = '{{ __('admin.processing') }}';
        const search = '{{ __('admin.search') }}';
        const lengthMenu = '{{ __('admin.show_entries') }}';
        const info = '{{ __('admin.showing_entries') }}';
        const infoEmpty = '{{ __('admin.showing_empty') }}';
        const infoFiltered = '{{ __('admin.filtered_entries') }}';
        const noMatchingProducts = '{{ __('admin.no_matching_products') }}';
        const noProductsAvailable = '{{ __('admin.no_products_available') }}';
        const loadingProducts = '{{ __('admin.loading_products') }}';
        const firstPage = '{{ __('admin.first') }}';
        const lastPage = '{{ __('admin.last') }}';
        const nextPage = '{{ __('admin.next') }}';
        const previousPage = '{{ __('admin.previous') }}';
        const successTitle = '{{ __('admin.success_title') }}';
        const errorTitle = '{{ __('admin.error_title') }}';

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
                    search: search,
                    lengthMenu: "_MENU_",
                    info: '',
                    infoEmpty: infoEmpty,
                    infoFiltered: infoFiltered,
                    zeroRecords: noMatchingProducts,
                    emptyTable: noProductsAvailable,
                    loadingRecords: loadingProducts,
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
                    title: successTitle,
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            $(document).on('click', '.deleteBtn', function() {
                const productId = $(this).data('id');
                const deleteUrl = '{{ route('admin.products.destroy', ':id') }}'.replace(':id', productId);

                Swal.fire({
                    title: areYouSure,
                    text: deleteProductWarning,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: yesDeleteIt
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
                                        title: deleted,
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
                                    title: errorTitle,
                                    text: xhr.responseJSON?.message ||
                                        somethingWentWrong,
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
                            title: successTitle,
                            text: response.message,
                            timer: 3000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href =
                                '{{ route('admin.products.index') }}';
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = xhr.responseJSON?.message || somethingWentWrong;
                        if (xhr.responseJSON?.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
                        }

                        Swal.fire({
                            icon: 'error',
                            title: errorTitle,
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
