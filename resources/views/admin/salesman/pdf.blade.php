<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Store Visits Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #4CAF50;
        }

        .header h1 {
            color: #4CAF50;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .header p {
            margin: 5px 0;
            color: #666;
            font-size: 12px;
        }

        .info-section {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }

        .info-section p {
            margin: 5px 0;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }

        td {
            padding: 8px;
            font-size: 10px;
            vertical-align: top;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .status {
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
            display: inline-block;
            font-size: 10px;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-approved {
            background-color: #d4edda;
            color: #155724;
        }

        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 10px;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
            font-style: italic;
        }

        .page-number {
            text-align: center;
            margin-top: 10px;
            color: #666;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Store Visits Report</h1>
        <p><strong>Generated on:</strong> {{ date('F d, Y h:i A') }}</p>
        <p><strong>Total Records:</strong> {{ $visits->count() }}</p>
    </div>

    @if($visits->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">ID</th>
                <th style="width: 15%;">Salesman</th>
                <th style="width: 15%;">Vendor</th>
                <th style="width: 20%;">Purpose</th>
                <th style="width: 20%;">Notes</th>
                <th style="width: 20%;">Feedback</th>
                <th style="width: 20%;">Outcome</th>
                <th style="width: 20%;">Rating</th>
                <th style="width: 20%;">Follow Up Required</th>
                <th style="width: 20%;">Next Follow Up Date</th>
                <th style="width: 15%;">Visit Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($visits as $visit)
            <tr>
                <td>{{ $visit->id }}</td>
                <td>{{ $visit->salesman->name ?? '-' }}</td>
                <td>{{ $visit->vendor->name ?? '-' }}</td>
                <td>{{ $visit->purpose ?? 'N/A' }}</td>
                <td>{{ $visit->notes ?? 'N/A' }}</td>
                <td>{{ $visit->feedback ?? 'N/A' }}</td>
                <td>{{ $visit->outcome ?? 'N/A' }}</td>
                <td>{{ $visit->rating ?? 'N/A' }}</td>
                <td>{{ $visit->follow_up_required ? 'Yes' : 'No' }}</td>
                <td>
                    @if($visit->next_follow_up_date)
                    {{ is_string($visit->next_follow_up_date) ? $visit->next_follow_up_date : $visit->next_follow_up_date->format('d M Y') }}
                    @else
                    -
                    @endif
                </td>
                <td>{{ $visit->created_at->format('d M Y, h:i A') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="no-data">
        <p>No records found for the selected filters.</p>
    </div>
    @endif

    <div class="footer">
        <p>&copy; {{ date('Y') }}. All rights reserved.</p>
        <p>This is a system generated report and does not require a signature.</p>
    </div>
</body>

</html>