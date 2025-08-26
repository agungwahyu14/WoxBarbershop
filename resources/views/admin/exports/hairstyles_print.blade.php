@extends('admin.exports.layout')

@section('title', 'Data Gaya Rambut')

@section('content')
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Gaya Rambut</th>
                <th>Deskripsi</th>
                <th>Jenis Rambut</th>
                <th>Tingkat Kesulitan</th>
                <th>Tanggal Dibuat</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $hairstyle)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $hairstyle->name }}</td>
                    <td>{{ strip_tags($hairstyle->description) ?: '-' }}</td>
                    <td>{{ $hairstyle->hair_type ?: '-' }}</td>
                    <td>{{ $hairstyle->difficulty_level ?: '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($hairstyle->created_at)->format('d/m/Y H:i') }}</td>
                    <td>{{ $hairstyle->deleted_at ? 'Inactive' : 'Active' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px; color: #999;">
                        Tidak ada data gaya rambut
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
