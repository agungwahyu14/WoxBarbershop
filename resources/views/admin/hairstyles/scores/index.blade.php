@extends('admin.layouts.app')

@section('title', 'Hairstyle Scores')

@section('content')
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div>
                <h1 class="title text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-star mr-3"></i>
                    {{ __('admin.hairstyle_scores') }}
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    {{ __('admin.manage_hairstyle_scores') }}
                </p>
            </div>
        </div>
    </section>

    <section class="section min-h-screen main-section">
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <a href="{{ route('admin.hairstyles.score.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md shadow-sm transition-colors duration-200">
                        <span class="icon mr-2"><i class="mdi mdi-plus"></i></span>
                        {{ __('admin.add_score') }}
                    </a>
                </div>
            </div>

            <div class="card-content ">
                <!-- Container dengan indikator scroll -->

                <table id="scoresTable">
                    <thead>
                        <tr>
                            <th>
                                #
                            </th>
                            <th>
                                {{ __('admin.hairstyle') }}
                            </th>
                            <th>
                                {{ __('admin.criteria') }}
                            </th>
                            <th>
                                {{ __('admin.score') }}
                            </th>
                            <th class="flex justify-center items-center">
                                {{ __('admin.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data akan diisi oleh DataTables -->
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
        const deleteScoreWarning = '{{ __('admin.delete_score_warning') }}';
        const yesDeleteIt = '{{ __('admin.yes_delete_it') }}';
        const somethingWentWrong = '{{ __('admin.something_went_wrong') }}';
        const processing = '{{ __('admin.processing') }}';
        const search = '{{ __('admin.search') }}';
        const lengthMenu = '{{ __('admin.show_entries') }}';
        const info = '{{ __('admin.showing_entries') }}';
        const infoEmpty = '{{ __('admin.showing_empty') }}';
        const infoFiltered = '{{ __('admin.filtered_entries') }}';
        const noMatchingScores = '{{ __('admin.no_matching_scores') }}';
        const noScoresAvailable = '{{ __('admin.no_scores_available') }}';
        const loadingScores = '{{ __('admin.loading_scores') }}';
        const firstPage = '{{ __('admin.first') }}';
        const lastPage = '{{ __('admin.last') }}';
        const nextPage = '{{ __('admin.next') }}';
        const previousPage = '{{ __('admin.previous') }}';
        const successTitle = '{{ __('admin.success_title') }}';
        const errorTitle = '{{ __('admin.error_title') }}';

        // Success popup
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: successTitle,
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
                        className: 'text-center'

                    }
                ],
                pageLength: 10,
                responsive: true,
                language: {
                    search: search,
                    lengthMenu: "_MENU_", // ✅ hanya tampil dropdown tanpa teks "Show entries"
                    info: info,
                    infoEmpty: infoEmpty,
                    infoFiltered: infoFiltered,
                    zeroRecords: noMatchingScores,
                    emptyTable: noScoresAvailable,
                    loadingRecords: loadingScores,
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
                }

            });
        });

        // Delete Score
        $(document).on('click', '.deleteBtn', function() {
            const scoreId = $(this).data('id');
            const deleteUrl = '{{ route('admin.hairstyles.score.destroy', ':id') }}'.replace(':id', scoreId);

            Swal.fire({
                text: deleteConfirmationText,
                icon: 'warning',
                confirmButtonText: confirmDeleteText
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
                                title: deleteSuccessTitle,
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
                                title: errorTitle,
                                text: xhr.responseJSON?.message || errorMessage,
                            });
                        }
                    });
                }
            });
        });
    </script>
@endpush
