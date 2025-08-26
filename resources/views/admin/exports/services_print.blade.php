@extends('admin.exports.layout')

@section('title', 'Data Layanan')

@section('content')
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Layanan</th>
                <th>Harga</th>
                <th>Durasi</th>
                <th>Deskripsi</th>
                <th>Tanggal Dibuat</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $service)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $service->name }}</td>
                    <td>Rp{{ number_format($service->price, 0, ',', '.') }}</td>
                    <td>{{ $service->duration }} menit</td>
                    <td>{{ strip_tags($service->description) ?: '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($service->created_at)->format('d/m/Y H:i') }}</td>
                    <td>{{ $service->deleted_at ? 'Inactive' : 'Active' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px; color: #999;">
                        Tidak ada data layanan
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
