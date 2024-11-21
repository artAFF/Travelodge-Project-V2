<div class="report-details">
    <p><strong>ID:</strong> {{ $report->id }}</p>
    <p><strong>Issue:</strong> {{ $report->category->name ?? 'N/A' }}</p>
    <p><strong>Detail:</strong> {{ $report->detail }}</p>
    <p><strong>Remarks:</strong>
        @if ($report->remarks)
            {!! nl2br(e($report->remarks)) !!}
        @else
            No Remarks
        @endif
    </p>
    <p><strong>Department:</strong> {{ $report->department->name ?? 'N/A' }}</p>
    <p><strong>Hotel:</strong> {{ $report->hotel->hotel_code ?? 'N/A' }}</p>
    <p><strong>Assignee:</strong> {{ $report->assignee->name ?? 'N/A' }}</p>
    <p><strong>Status:</strong> {{ $report->status === 0 ? 'In Progress' : 'Done' }}</p>
    <p><strong>Created:</strong> {{ \Carbon\Carbon::parse($report->created_at)->format('l, d F Y H:i:s') }}</p>
    <p><strong>Updated:</strong> {{ \Carbon\Carbon::parse($report->updated_at)->format('l, d F Y H:i:s') }}</p>

    <!-- File Attachment Section -->
    <p>
        <strong>Attached File:</strong>
        @if ($report->file_path)
            @php
                $extension = strtolower(pathinfo(storage_path('app/public/' . $report->file_path), PATHINFO_EXTENSION));
            @endphp
            @if ($extension == 'pdf')
                <a href="{{ asset('storage/' . $report->file_path) }}" target="_blank">Open PDF in new tab</a>
            @elseif (in_array($extension, ['jpg', 'jpeg', 'png']))
                <a href="{{ asset('storage/' . $report->file_path) }}" target="_blank">View Image</a>
            @else
                <a href="{{ route('download.file', $report->id) }}">Download File</a>
            @endif
        @else
            No file attached.
        @endif
    </p>

    <!-- File Preview Section -->
    @if ($report->file_path)
        @if (in_array($extension, ['jpg', 'jpeg', 'png']))
            <img src="{{ asset('storage/' . $report->file_path) }}" alt="Attached Image"
                style="max-width: 100%; height: auto;">
        @elseif ($extension == 'pdf')
            <embed src="{{ asset('storage/' . $report->file_path) }}" type="application/pdf" width="100%"
                height="600px" />
        @else
            <p>File type not supported for preview. <a href="{{ route('download.file', $report->id) }}">Download
                    File</a></p>
        @endif
    @endif
</div>
