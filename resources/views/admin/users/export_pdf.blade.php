<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Export</title>
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
    <h1>Users Export</h1>
    <table>
        <thead>
            <tr>
                <th style="width:5%">No</th>
                <th style="width:15%">Name</th>
                <th style="width:20%">Email</th>
                <th style="width:15%">No Telepon</th>
                <th style="width:15%">Roles</th>
                <th style="width:10%">Status</th>
                <th style="width:20%">Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $i => $user)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->no_telepon ?? '-' }}</td>
                    <td>{{ $user->roles->pluck('name')->join(', ') ?: 'pelanggan' }}</td>
                    <td class="{{ $user->deleted_at ? 'status-inactive' : 'status-active' }}">
                        {{ $user->deleted_at ? 'Inactive' : 'Active' }}
                    </td>
                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <footer>
        Dicetak pada {{ now()->format('d/m/Y H:i') }}
    </footer>
</body>

</html>
