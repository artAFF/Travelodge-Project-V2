<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Support Report</title>
    <style>
        body {
            font-family: "Sarabun", sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 18pt;
            margin-bottom: 10px;
        }

        .filter-info {
            margin-bottom: 20px;
        }

        .filter-info h2 {
            font-size: 12pt;
            margin-bottom: 10px;
        }

        .filter-table {
            width: 100%;
            border-collapse: collapse;
        }

        .filter-table td {
            width: 50%;
            padding: 5px;
            border: none;
            text-align: left;
            font-size: 10pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
            font-size: 8pt;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>IT Support Report</h1>
        <p>Generated on: {{ now()->format('l, d F Y H:i:s') }}</p>
        <p>Report Period: {{ $start_date }} - {{ $end_date }}</p>
    </div>

    <div class="filter-info">
        <h4>Filters Applied:</h2>
            <table class="filter-table">
                <tr>
                    <td><strong>Category:</strong> {{ $category ?? 'All' }}</td>
                    <td><strong>Department:</strong> {{ $department ?? 'All' }}</td>
                </tr>
                <tr>
                    <td><strong>Hotel:</strong> {{ $hotel ?? 'All' }}</td>
                    <td><strong>Status:</strong> {{ $status ?? 'All' }}</td>
                </tr>
            </table>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Issue</th>
                <th>Detail</th>
                <th>Department</th>
                <th>Hotel</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($issues as $issue)
                <tr>
                    <td>{{ $issue['id'] ?? 'N/A' }}</td>
                    <td>{{ $issue['category']['name'] ?? 'N/A' }}</td>
                    <td>{{ $issue['detail'] ?? 'N/A' }}</td>
                    <td>{{ $issue['department']['name'] ?? 'N/A' }}</td>
                    <td>{{ $issue['hotel']['hotel_code'] ?? 'N/A' }}</td>
                    <td>{{ $issue['status'] === 0 ? 'In-progress' : 'Done' }}</td>
                    <td>{{ isset($issue['created_at']) ? \Carbon\Carbon::parse($issue['created_at'])->format('d/m/Y H:i:s') : 'N/A' }}
                    </td>
                    <td>{{ isset($issue['updated_at']) ? \Carbon\Carbon::parse($issue['updated_at'])->format('d/m/Y H:i:s') : 'N/A' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
