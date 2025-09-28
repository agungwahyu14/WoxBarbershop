@extends('admin.layouts.app')

@section('title', 'AHP Management')

@section('content')
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-6">AHP Management</h1>
        <div class="bg-white shadow rounded p-6">
            <p class="mb-4">Halaman ini digunakan untuk mengelola data dan proses Analytic Hierarchy Process (AHP) untuk
                rekomendasi gaya rambut dan keputusan operasional barbershop.</p>
            <!-- Example: Table for Criteria Management -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold mb-2">Kriteria AHP</h2>
                <table class="min-w-full border">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 border">Nama Kriteria</th>
                            <th class="px-4 py-2 border">Bobot</th>
                            <th class="px-4 py-2 border">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($criteria ?? [] as $criterion)
                            <tr>
                                <td class="px-4 py-2 border">{{ $criterion->name }}</td>
                                <td class="px-4 py-2 border">{{ $criterion->weight }}</td>
                                <td class="px-4 py-2 border">
                                    <form action="{{ route('admin.ahp-management.updateCriterion', $criterion->id) }}"
                                        method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="name" value="{{ $criterion->name }}"
                                            class="border px-2 py-1 w-32" />
                                        <button type="submit" class="text-blue-500 hover:underline">Edit</button>
                                    </form>
                                    <form action="{{ route('admin.ahp-management.destroyCriterion', $criterion->id) }}"
                                        method="POST" class="inline ml-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <form action="{{ route('admin.ahp-management.storeCriterion') }}" method="POST" class="mt-4 flex gap-2">
                    @csrf
                    <input type="text" name="name" placeholder="Nama Kriteria" class="border px-2 py-1" required />
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Tambah Kriteria</button>
                </form>
            </div>
            <!-- Example: Pairwise Comparison Section -->
            <div>
                <h2 class="text-lg font-semibold mb-2">Pairwise Comparison</h2>
                <p class="mb-4">Kelola perbandingan antar kriteria untuk proses AHP.</p>
                <!-- Placeholder for pairwise comparison matrix -->
                <div class="overflow-x-auto">
                    <table class="min-w-full border">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 border">Kriteria 1</th>
                                <th class="px-4 py-2 border">Kriteria 2</th>
                                <th class="px-4 py-2 border">Nilai Perbandingan</th>
                                <th class="px-4 py-2 border">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($comparisons ?? [] as $comparison)
                                <tr>
                                    <td class="px-4 py-2 border">{{ $comparison->criterion1->name ?? '-' }}</td>
                                    <td class="px-4 py-2 border">{{ $comparison->criterion2->name ?? '-' }}</td>
                                    <td class="px-4 py-2 border">{{ $comparison->value }}</td>
                                    <td class="px-4 py-2 border">
                                        <form action="{{ route('admin.ahp-management.updateComparison', $comparison->id) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="value" value="{{ $comparison->value }}"
                                                min="1" max="9" class="border px-2 py-1 w-16" />
                                            <button type="submit" class="text-blue-500 hover:underline">Edit</button>
                                        </form>
                                        <form
                                            action="{{ route('admin.ahp-management.destroyComparison', $comparison->id) }}"
                                            method="POST" class="inline ml-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:underline">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <form action="{{ route('admin.ahp-management.storeComparison') }}" method="POST"
                    class="mt-4 flex gap-2 items-center">
                    @csrf
                    <select name="criterion_id_1" class="border px-2 py-1" required>
                        <option value="">Kriteria 1</option>
                        @foreach ($criteria as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                    <select name="criterion_id_2" class="border px-2 py-1" required>
                        <option value="">Kriteria 2</option>
                        @foreach ($criteria as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="value" min="1" max="9" placeholder="Nilai"
                        class="border px-2 py-1 w-16" required />
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Tambah Perbandingan</button>
                </form>
            </div>
        </div>
    </div>
@endsection
