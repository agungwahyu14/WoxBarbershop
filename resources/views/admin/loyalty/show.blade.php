@extends('admin.layouts.app')

@section('content')
    <section class="section main-section">
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Loyalty</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Informasi detail loyalty pengguna.
                </p>
            </div>

            <div class="px-6 py-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Nama Pengguna</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ $loyalty->user->name ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Poin Loyalty</p>
                        <p class="text-lg font-bold text-yellow-500">
                            {{ $loyalty->points }}
                        </p>
                    </div>
                </div>

                <div class="pt-4">
                    <a href="{{ route('admin.loyalty.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white rounded-lg shadow-sm transition duration-200">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Loyalty
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
