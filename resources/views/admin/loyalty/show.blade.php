@extends('admin.layouts.app')

@section('content')
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div>
                <h1 class="title text-3xl font-bold text-gray-900 dark:text-white"> <i class="fas fa-gift mr-3 "></i>Loyalty
                    Management</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Manage loyalty points of customers at Wox’s
                    Barbershop</p>
            </div>
            <div class="flex items-center space-x-4 mt-4 md:mt-0">
                <a href="{{ route('admin.loyalty.index') }}"
                    class="flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Loyalty
                </a>
            </div>
        </div>
    </section>

    <section class="section main-section">
        <!-- Transaction Status Card -->
        <div class="mb-6">


            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border-2  p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Nama</h2>
                            <p class="text-gray-600 dark:text-gray-400"> {{ $loyalty->user->name ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xl font-bold text-gray-900 dark:text-white">
                            Poin Loyalty
                        </p>
                        <p class=" text-gray-600 dark:text-gray-400">
                            {{ $loyalty->points }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

@push('scripts')
    <script></script>
@endpush

@push('styles')
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .timeline-dot {
            position: relative;
        }

        .timeline-dot::after {
            content: '';
            position: absolute;
            left: 50%;
            top: 100%;
            width: 2px;
            height: 20px;
            background: #e5e7eb;
            transform: translateX(-50%);
        }

        .timeline-dot:last-child::after {
            display: none;
        }
    </style>
@endpush
