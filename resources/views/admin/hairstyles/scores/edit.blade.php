@extends('admin.layouts.app')

@section('title', 'Edit Score')

@section('content')
    <section class="section main-section">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Edit Hairstyle Score</h1>

            <form action="{{ route('admin.hairstyles.score.update', $hairstyle_score->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hairstyle</label>
                    <select name="hairstyle_id"
                        class="w-full border rounded-md px-3 py-2 mt-1 dark:bg-gray-800 dark:text-white">
                        @foreach ($hairstyles as $h)
                            <option value="{{ $h->id }}"
                                {{ $hairstyle_score->hairstyle_id == $h->id ? 'selected' : '' }}>
                                {{ $h->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('hairstyle_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kriteria</label>
                    <select name="criterion_id"
                        class="w-full border rounded-md px-3 py-2 mt-1 dark:bg-gray-800 dark:text-white">
                        @foreach ($criteria as $c)
                            <option value="{{ $c->id }}"
                                {{ $hairstyle_score->criterion_id == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('criterion_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>


                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Score</label>
                    <input type="number" name="score" step="0.01" min="0"
                        class="w-full border rounded-md px-3 py-2 mt-1 dark:bg-gray-800 dark:text-white"
                        value="{{ old('score', $hairstyle_score->score) }}">
                    @error('score')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.hairstyles.score.index') }}"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-white rounded-lg">Batal</a>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow">Update</button>
                </div>
            </form>
        </div>
    </section>
@endsection
