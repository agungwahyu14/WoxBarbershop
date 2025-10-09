<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('admin.users_report') }}</title>
    <style>
        body {
            font-family: "DejaVu Sans", Arial, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #bbb;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #2c3e50;
            color: white;
            font-size: 12px;
        }

        tr:nth-child(even) {
            background-color: #f8f8f8;
        }

        .status-active {
            color: green;
            font-weight: bold;
        }

        .status-inactive {
            color: red;
            font-weight: bold;
        }

        footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>

<body>
    <h1>{{ __('admin.users_report') }}</h1>
    @if ($month && $year)
        <p style="text-align: center; margin-bottom: 20px; font-weight: bold;">
            {{ __('admin.period') }}: {{ \Carbon\Carbon::create($year, $month)->format('F Y') }}
        </p>
    @elseif($year)
        <p style="text-align: center; margin-bottom: 20px; font-weight: bold;">
            {{ __('admin.period') }}: {{ __('admin.year') }} {{ $year }}
        </p>
    @else
        <p style="text-align: center; margin-bottom: 20px; font-weight: bold;">
            {{ __('admin.period') }}: {{ __('admin.all_data') }}
        </p>
    @endif
    <table>
        <thead>
            <tr>
                <th style="width:5%">No</th>
                <th style="width:15%">{{ __('admin.name_column') }}</th>
                <th style="width:20%">{{ __('admin.email_address') }}</th>
                <th style="width:15%">{{ __('admin.phone_column') }}</th>
                <th style="width:15%">{{ __('admin.roles_column') }}</th>
                <th style="width:10%">{{ __('admin.status_column') }}</th>
                <th style="width:20%">{{ __('admin.created_at') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $i => $user)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->no_telepon ?? '-' }}</td>
                    <td>{{ $user->roles->pluck('name')->join(', ') ?: __('admin.customer') }}</td>
                    <td class="{{ $user->deleted_at ? 'status-inactive' : 'status-active' }}">
                        {{ $user->deleted_at ? __('admin.inactive') : __('admin.active') }}
                    </td>
                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <footer>
        {{ __('admin.printed_on') }} {{ now()->format('d/m/Y H:i') }}
    </footer>
</body>

</html>
