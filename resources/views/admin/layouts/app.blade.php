<!DOCTYPE html>
<html lang="en" class="scroll-smooth" style="color-scheme: light !important;">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light only">
    <title>@yield('title', 'Admin Dashboard') - WOX Barbershop</title>
    <meta name="description" content="@yield('meta_description', 'Professional barbershop management system')">

    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/apple-touch-icon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/favicon-16x16.png') }}" />
    <link href="https://cdn.materialdesignicons.com/6.5.95/css/materialdesignicons.min.css" rel="stylesheet">

    <!-- Enhanced Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-Avb2QiuDEEvB4bZJYdft2mNjVShBftLdPG8FJ0V7irTLQ8Uo0qcPxh4Plq7G5tGm0rU+1SPhVotteLpBERwTkw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Enhanced CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('css/main.css?v=' . time()) }}">

    <!-- Admin Light Mode Enforcement CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin-light-mode.css?v=' . time()) }}">

    <!-- Admin Light Mode Enforcement JS - Load immediately -->
    <script src="{{ asset('js/admin-light-mode.js?v=' . time()) }}"></script>
    <link rel="stylesheet" href="{{ asset('css/admin-enhanced.css?v=' . time()) }}">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/7.4.47/css/materialdesignicons.min.css">

    <!-- Enhanced DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.0/css/responsive.dataTables.min.css">

    <!-- Custom Enhanced Styles -->
    <style>
        :root {
            --primary-color: #3b82f6;
            --primary-hover: #2563eb;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1f2937;
            --light-bg: #f9fafb;
            --border-color: #e5e7eb;
        }

        /* Language Switcher Styles */
        #language-dropdown .navbar-link {
            border-radius: 0.375rem;
            transition: all 0.2s ease;
        }

        #language-dropdown .navbar-link:hover {
            background-color: rgba(59, 130, 246, 0.1);
        }

        #language-dropdown .navbar-dropdown {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.5rem 0;
            z-index: 9999;
        }

        #language-dropdown .navbar-dropdown .navbar-item:hover {
            background-color: #f3f4f6;
            border-radius: 0.375rem;
            margin: 0 0.5rem;
        }

        #language-dropdown .navbar-dropdown .navbar-item.is-active {
            background-color: rgba(59, 130, 246, 0.1);
            border-radius: 0.375rem;
            margin: 0 0.5rem;
            color: #3b82f6;
        }

        /* Mobile responsive */
        @media (max-width: 768px) {
            #language-dropdown.is-active .navbar-dropdown {
                display: block !important;
            }

            #language-dropdown .navbar-dropdown {
                display: none;
                position: static !important;
                box-shadow: none;
                background-color: rgba(0, 0, 0, 0.02);
                border: none;
                border-radius: 0;
                margin-top: 0.5rem;
            }
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;

            /* Force Light Mode for Admin Pages - Override ALL Dark Mode Styles */
            /* Override any dark mode styles */
            * {
                color-scheme: light !important;
            }

            html,
            body {
                background-color: #f9fafb !important;
                /* bg-gray-50 */
                color: #1f2937 !important;
                /* text-gray-900 */
            }

            /* Override Tailwind dark: classes with light equivalents */
            .dark\:bg-gray-900,
            .dark\:bg-gray-800,
            .dark\:bg-gray-700 {
                background-color: #ffffff !important;
                /* Keep white backgrounds */
            }

            .dark\:text-white,
            .dark\:text-gray-100,
            .dark\:text-gray-200 {
                color: #1f2937 !important;
                /* Keep dark text */
            }

            .dark\:text-gray-400,
            .dark\:text-gray-300 {
                color: #6b7280 !important;
                /* Keep gray-600 for muted text */
            }

            .dark\:border-gray-700,
            .dark\:border-gray-600 {
                border-color: #e5e7eb !important;
                /* Keep light borders */
            }

            .dark\:divide-gray-700 {
                border-color: #e5e7eb !important;
            }

            /* Force light backgrounds for all major containers */
            .container,
            .main-section,
            .section,
            .card,
            .box,
            .panel {
                background-color: #ffffff !important;
                color: #1f2937 !important;
            }

            /* Force light sidebar */
            .sidebar,
            .navbar,
            .menu {
                background-color: #f8fafc !important;
                color: #374151 !important;
            }

            /* Force light inputs and form elements */
            input,
            textarea,
            select,
            .input,
            .textarea,
            .select {
                background-color: #ffffff !important;
                color: #1f2937 !important;
                border-color: #e5e7eb !important;
            }

            /* Force light buttons */
            .button,
            .btn {
                background-color: #f3f4f6 !important;
                color: #374151 !important;
            }

            .button.is-primary,
            .btn-primary {
                background-color: var(--primary-color) !important;
                color: #ffffff !important;
            }

            /* Force light tables */
            .table,
            table {
                background-color: #ffffff !important;
                color: #1f2937 !important;
            }

            .table th,
            table th {
                background-color: #f9fafb !important;
                color: #374151 !important;
            }

            /* Force light modal and dropdown */
            .modal,
            .dropdown-menu,
            .navbar-dropdown {
                background-color: #ffffff !important;
                color: #1f2937 !important;
            }

            transition: all 0.2s;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }

        .form-control {
            border: 2px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 0.75rem;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
    </style>

    @stack('styles')
</head>

<body class="min-h-screen bg-gray-50 transition-colors duration-200">
    <div id="app" class="min-h-screen">
        @include('admin.partials.navbar')
        @include('admin.layouts.sidebar')

        <!-- Enhanced Page Content -->
        <main class="transition-all duration-200 ease-in-out">
            <div class="content-wrapper">
                @yield('content')
            </div>
        </main>
        {{-- 
        @include('admin.partials.footer') --}}
    </div>

    <!-- Enhanced JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Setup jQuery AJAX defaults -->
    <script>
        // Setup CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
    </script>

    <!-- Admin Auto-Refresh Utility -->
    <script src="{{ asset('js/admin-auto-refresh.js?v=' . time()) }}"></script>

    <!-- Enhanced DataTables -->
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.0/js/dataTables.responsive.min.js"></script>

    <!-- Enhanced Buttons -->
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.print.min.js"></script>

    <!-- Dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <!-- Enhanced Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <!-- Main App JS -->
    <script src="{{ asset('js/main.min.js?v=' . time()) }}"></script>
    <script src="{{ asset('js/admin-enhanced.js?v=' . time()) }}"></script>

    <!-- Enhanced Global Scripts -->
    @stack('scripts')
</body>

</html>
