@extends('admin.layouts.app')

@section('content')
    <div class="p-6 bg-white rounded-lg shadow">
        <h1 class="text-xl font-bold mb-4">Daftar Loyalty</h1>
        <table class="table-auto w-full" id="loyalty-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pengguna</th>
                    <th>Points</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>

    @push('scripts')
        <script>
            $(function() {
                $('#loyalty-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('loyalties.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'user_name',
                            name: 'user.name'
                        },
                        {
                            data: 'points',
                            name: 'points'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });
            });
        </script>
    @endpush
@endsection
