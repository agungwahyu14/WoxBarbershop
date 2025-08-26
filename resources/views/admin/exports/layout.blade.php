<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - WOX Barbershop</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .header h1 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
            color: #666;
            font-size: 14px;
        }

        .export-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 11px;
            color: #666;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .badge-confirmed {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .badge-completed {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
        }

        @media print {
            body {
                margin: 0;
                padding: 10px;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
    @if (isset($isPrint) && $isPrint)
        <style>
            @media print {
                @page {
                    size: A4 landscape;
                    margin: 10mm;
                }
            }

            .print-button {
                position: fixed;
                top: 10px;
                right: 10px;
                padding: 10px 20px;
                background-color: #007bff;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 14px;
            }

            .print-button:hover {
                background-color: #0056b3;
            }
        </style>
    @endif
</head>

<body>
    @if (isset($isPrint) && $isPrint)
        <button class="print-button no-print" onclick="window.print()">Print</button>
    @endif

    <div class="header">
        <h1>WOX Barbershop</h1>
        <p>{{ $title ?? 'Export Data' }}</p>
        <p>Periode: {{ $monthName ?? 'Semua Data' }}</p>
    </div>

    <div class="export-info">
        <div>Tanggal Export: {{ $exportDate ?? now()->format('d F Y H:i') }}</div>
        <div>Total Data: {{ $data->count() }} record</div>
    </div>

    @yield('content')

    <div class="footer">
        <p>© {{ date('Y') }} WOX Barbershop. Generated automatically from admin system.</p>
    </div>

    @if (isset($isPrint) && $isPrint)
        <script>
            // Auto print for print view
            setTimeout(function() {
                window.print();
            }, 500);
        </script>
    @endif
</body>

</html>
