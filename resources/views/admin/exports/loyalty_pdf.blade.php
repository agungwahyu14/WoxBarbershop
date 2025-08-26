@extends('admin.exports.layout')

@section('title', 'Data Loyalty')

@section('content')
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Pelanggan</th>
                <th>Email</th>
                <th>Total Poin</th>
                <th>Poin Terpakai</th>
                <th>Sisa Poin</th>
                <th>Level</th>
                <th>Tanggal Update</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $loyalty)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $loyalty->user->name ?? '-' }}</td>
                    <td>{{ $loyalty->user->email ?? '-' }}</td>
                    <td>{{ number_format($loyalty->total_points ?? 0, 0, ',', '.') }}</td>
                    <td>{{ number_format($loyalty->used_points ?? 0, 0, ',', '.') }}</td>
                    <td>{{ number_format(($loyalty->total_points ?? 0) - ($loyalty->used_points ?? 0), 0, ',', '.') }}</td>
                    <td>{{ $loyalty->level ?? 'Bronze' }}</td>
                    <td>{{ \Carbon\Carbon::parse($loyalty->updated_at)->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px; color: #999;">
                        Tidak ada data loyalty
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
