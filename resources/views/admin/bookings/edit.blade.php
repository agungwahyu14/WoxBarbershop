@extends('admin.layouts.app')

@section('content')
    <section class="is-hero-bar mb-6">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Edit Booking
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Modify existing booking</p>
            </div>
        </div>
    </section>

    <section class="section main-section">
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-8 space-y-8">
            <form action="{{ route('bookings.update', $booking->id) }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                @method('PUT')

                {{-- User (Read-only) --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase mb-1">User</label>
                    <input type="text" readonly value="{{ $booking->user->name ?? 'Unknown User' }}"
                        class="w-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white rounded-md border-gray-300 dark:border-gray-600 px-4 py-2">
                </div>

                {{-- Name --}}
                <div>
                    <label for="name"
                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase mb-1">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $booking->name) }}"
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white px-4 py-2"
                        required>
                </div>

                {{-- Service --}}
                <div>
                    <label for="service_id"
                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase mb-1">Service</label>
                    <select name="service_id" id="service_id"
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white px-4 py-2">
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}"
                                {{ $booking->service_id == $service->id ? 'selected' : '' }}>
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Hairstyle --}}
                <div>
                    <label for="hairstyle_id"
                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase mb-1">Hairstyle</label>
                    <select name="hairstyle_id" id="hairstyle_id"
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white px-4 py-2">
                        @foreach ($hairstyles as $style)
                            <option value="{{ $style->id }}"
                                {{ $booking->hairstyle_id == $style->id ? 'selected' : '' }}>
                                {{ $style->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Date & Time --}}
                <div>
                    <label for="date_time"
                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase mb-1">Date &
                        Time</label>
                    <input type="datetime-local" name="date_time" id="date_time"
                        value="{{ old('date_time', \Carbon\Carbon::parse($booking->date_time)->format('Y-m-d\TH:i')) }}"
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white px-4 py-2"
                        required>
                </div>

                {{-- Description --}}
                <div>
                    <label for="description"
                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase mb-1">Description</label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white px-4 py-2">{{ old('description', $booking->description) }}</textarea>
                </div>

                {{-- Queue Number (Read-only) --}}
                <div>
                    <label for="queue_number"
                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase mb-1">Queue
                        Number</label>
                    <input type="number" name="queue_number" id="queue_number" value="{{ $booking->queue_number }}"
                        readonly
                        class="w-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white rounded-md border-gray-300 dark:border-gray-700 px-4 py-2">
                </div>

                {{-- Status --}}
                <div>
                    <label for="status"
                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase mb-1">Status</label>
                    <select name="status" id="status"
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white px-4 py-2">
                        <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Confirmed
                        </option>
                        <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Cancelled
                        </option>
                    </select>
                </div>


                {{-- Submit --}}
                <div class="flex justify-end pt-4">
                    <a href="{{ route('bookings.index') }}"
                        class="mr-4 inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Cancel</a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update</button>
                </div>
            </form>
        </div>
    </section>
@endsection
