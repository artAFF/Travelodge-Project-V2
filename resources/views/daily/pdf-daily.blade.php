<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Infrastructure Daily Status Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 8pt;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        h1 {
            color: #003366;
            border-bottom: 2px solid #003366;
            padding-bottom: 8px;
            font-size: 14pt;
            margin-bottom: 10px;
        }

        h2 {
            color: #003366;
            margin-top: 10px;
            margin-bottom: 5px;
            font-size: 10pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 4px;
            text-align: left;
            font-size: 7pt;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .section {
            margin-bottom: 15px;
        }

        .section+.section {
            margin-top: -5px;
        }

        .no-data {
            font-style: italic;
            color: #666;
            font-size: 7pt;
            margin-bottom: 10px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 7pt;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>IT Infrastructure Daily Status Report</h1>
        <p>Generated on: {{ now()->format('l, d F Y H:i:s') }}</p>
        <p>Report Period: {{ $start_date ? \Carbon\Carbon::parse($start_date)->format('d/m/y') : 'N/A' }} -
            {{ $end_date ? \Carbon\Carbon::parse($end_date)->format('d/m/y') : 'N/A' }}</p>
        <p>Department: IT Operations</p>
    </div>

    <div class="section">
        <h2>1. Guest Room Network Performance</h2>
        @if (isset($guests) && $guests->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Room No.</th>
                        <th>Location</th>
                        <th>Download Speed (Mbps)</th>
                        <th>Upload Speed (Mbps)</th>
                        <th>Channel Number</th>
                        <th>Channel Name</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($guests as $guest)
                        <tr>
                            <td>{{ $guest->room_no }}</td>
                            <td>{{ $guest->location_name }}</td>
                            <td>{{ number_format($guest->download, 2) }}</td>
                            <td>{{ number_format($guest->upload, 2) }}</td>
                            <td>
                                @if ($guest->ch_no == 0)
                                    Normal
                                @else
                                    {{ $guest->ch_no }}
                                @endif
                            </td>
                            <td>{{ $guest->ch_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($guest->created_at)->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="no-data">No guest room network data available for this reporting period.</p>
        @endif
    </div>

    <div class="section">
        <h2>2. Switch Room Status</h2>
        @if (isset($switches) && $switches->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Location</th>
                        <th>UPS Battery Level (%)</th>
                        <th>UPS Runtime (Minutes)</th>
                        <th>Temperature (°C)</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($switches as $switch)
                        <tr>
                            <td>{{ $switch->location_name }}</td>
                            <td>{{ number_format($switch->ups_battery, 1) }}</td>
                            <td>{{ $switch->ups_time }}</td>
                            <td>{{ number_format($switch->ups_temp, 1) }}</td>
                            <td>{{ \Carbon\Carbon::parse($switch->created_at)->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="no-data">No switch room data available for this reporting period.</p>
        @endif
    </div>

    <div class="section">
        <h2>3. Server Room Environment</h2>
        @if (isset($servers) && $servers->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Server Temperature (°C)</th>
                        <th>UPS Battery Level (%)</th>
                        <th>UPS Runtime (Hours:Minutes)</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($servers as $server)
                        <tr>
                            <td>{{ number_format($server->server_temp, 1) }}</td>
                            <td>{{ number_format($server->ups_battery, 1) }}</td>
                            <td>{{ $server->ups_time }}</td>
                            <td>{{ \Carbon\Carbon::parse($server->created_at)->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="no-data">No server room data available for this reporting period.</p>
        @endif
    </div>

    <div class="section">
        <h2>4. Internet Connection Performance</h2>
        @if (isset($netSpeeds) && $netSpeeds->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Location</th>
                        <th>Download Speed (Mbps)</th>
                        <th>Upload Speed (Mbps)</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($netSpeeds as $netSpeed)
                        <tr>
                            <td>{{ $netSpeed->location_name }}</td>
                            <td>{{ number_format($netSpeed->download, 2) }}</td>
                            <td>{{ number_format($netSpeed->upload, 2) }}</td>
                            <td>{{ \Carbon\Carbon::parse($netSpeed->created_at)->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="no-data">No internet performance data available for this reporting period.</p>
        @endif
    </div>

    <div class="footer">
        <p>This report is generated for internal IT department use. For any issues or concerns, please contact the team
            lead.</p>
    </div>
</body>

</html>
