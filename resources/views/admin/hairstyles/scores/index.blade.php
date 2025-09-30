@extends('admin.layouts.app')

@section('title', 'Hairstyle Scores')

@section('content')
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div>
                <h1 class="title text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-star mr-3"></i>
                    Hairstyle Scores
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    Manage hairstyle scores for Wox's Barbershop
                </p>
            </div>
        </div>
    </section>

    <section class="section main-section">
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <a href="{{ route('admin.hairstyles.score.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md shadow-sm transition-colors duration-200">
                        <span class="icon mr-2"><i class="mdi mdi-plus"></i></span>
                        Tambah Score
                    </a>
                </div>
            </div>

            <div class="card-content rounded-md overflow-x-auto">
                <!-- Container dengan indikator scroll -->
                <div class="relative">
                    <div class="overflow-x-auto pb-4">
                        <table id="scoresTable" class="min-w-[600px] w-full border-collapse">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        #
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Hairstyle
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Kriteria
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Score
                                    </th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data akan diisi oleh DataTables -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Success popup
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            }).then(() => {
                // Reload DataTables instead of entire page
                $('#scoresTable').DataTable().ajax.reload();
            });
        @endif

        // Initialize DataTables
        $(document).ready(function() {
            $('#scoresTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.hairstyles.score.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'hairstyle', // bukan hairstyle_id
                        name: 'hairstyle'
                    },
                    {
                        data: 'criterion', // bukan criterion_id
                        name: 'criterion'
                    },
                    {
                        data: 'score',
                        name: 'score'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,

                    }
                ],
                pageLength: 10,
                responsive: true,
                language: {
                    processing: '<div class="flex justify-center py-4"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div></div>',
                    emptyTable: 'Tidak ada data yang tersedia',
                    zeroRecords: 'Tidak ada data yang cocok ditemukan',
                    info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                    infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
                    infoFiltered: '(difilter dari _MAX_ total data)',
                    search: 'Cari:',
                    paginate: {
                        previous: '<i class="mdi mdi-chevron-left"></i>',
                        next: '<i class="mdi mdi-chevron-right"></i>'
                    }
                }
            });
        });

        // Delete Score
        $(document).on('click', '.deleteBtn', function() {
            const scoreId = $(this).data('id');
            const deleteUrl = '{{ route('admin.hairstyles.score.destroy', ':id') }}'.replace(':id', scoreId);

            Swal.fire({
                title: 'Are you sure?',
                text: "This will permanently delete the score.",
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
                                // Reload DataTables instead of entire page
                                $('#scoresTable').DataTable().ajax.reload();
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
    </script>
@endpush
