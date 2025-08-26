@extends('admin.exports.layout')

@section('title', 'Data Pengguna')

@section('content')
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>No. Telepon</th>
                <th>Role</th>
                <th>Email Verified</th>
                <th>Tanggal Daftar</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->no_telepon ?? '-' }}</td>
                    <td>{{ $user->roles->pluck('name')->join(', ') ?: 'pelanggan' }}</td>
                    <td>{{ $user->email_verified_at ? 'Verified' : 'Not Verified' }}</td>
                    <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i') }}</td>
                    <td>{{ $user->deleted_at ? 'Inactive' : 'Active' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px; color: #999;">
                        Tidak ada data pengguna
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
