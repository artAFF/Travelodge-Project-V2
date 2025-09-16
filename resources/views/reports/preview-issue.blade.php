<div class="report-details horizontal-layout" style="display: flex; flex-direction: column; width: 100%; margin: 0 auto;">
    <div class="report-content" style="display: flex; flex-direction: row; padding: 0;">
        <div class="report-left-column"
            style="flex: 1; padding: 15px; border-right: 1px solid #ddd; background-color: #f8f9fa;">
            <p><strong>ID:</strong> {{ $report->id }}</p>
            <p><strong>Issue:</strong> {{ $report->category->name ?? 'N/A' }}</p>
            <p><strong>Department:</strong> {{ $report->department->name ?? 'N/A' }}</p>
            <p><strong>Hotel:</strong> {{ $report->hotel->hotel_code ?? 'N/A' }}</p>
            <p><strong>Assignee:</strong> {{ $report->assignee->name ?? 'N/A' }}</p>
            <p><strong>Status:</strong> {{ $report->status === 0 ? 'In Progress' : 'Done' }}</p>
            <p><strong>Created:</strong> {{ \Carbon\Carbon::parse($report->created_at)->format('l, d F Y H:i:s') }}</p>
            <p><strong>Updated:</strong> {{ \Carbon\Carbon::parse($report->updated_at)->format('l, d F Y H:i:s') }}</p>
        </div>

        <div class="report-right-column" style="flex: 2; padding: 15px; display: flex; flex-direction: column;">
            <div class="detail-section" style="margin-bottom: 20px;">
                <p><strong>Detail:</strong> {{ $report->detail }}</p>
                <p><strong>Remarks:</strong>
                    @if ($report->remarks)
                        {!! nl2br(e($report->remarks)) !!}
                    @else
                        No Remarks
                    @endif
                </p>
            </div>

            <div class="budget-section" style="margin-bottom: 20px;">
                <p>
                    <strong>Budget Expenses:</strong>
                    @if ($report->budgetExpenses->count() > 0)
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th style="padding: 8px; border: 1px solid #ddd; background-color: #f8f9fa;">Expense
                                        Name</th>
                                    <th style="padding: 8px; border: 1px solid #ddd; background-color: #f8f9fa;">Amount
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalAmount = 0; @endphp
                                @foreach ($report->budgetExpenses as $expense)
                                    <tr>
                                        <td style="padding: 8px; border: 1px solid #ddd;">{{ $expense->expense_name }}
                                        </td>
                                        <td style="padding: 8px; border: 1px solid #ddd;">
                                            {{ number_format($expense->expense_amount, 2) }}</td>
                                    </tr>
                                    @php $totalAmount += $expense->expense_amount; @endphp
                                @endforeach
                                <tr>
                                    <td style="padding: 8px; border: 1px solid #ddd;"><strong>Total</strong></td>
                                    <td style="padding: 8px; border: 1px solid #ddd;">
                                        <strong>{{ number_format($totalAmount, 2) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    @else
                        No budget expenses.
                    @endif
                </p>
            </div>

            <div class="file-section" style="margin-bottom: 20px;">
                <p>
                    <strong>Attached File:</strong>
                    @if ($report->file_path)
                        @php
                            $extension = strtolower(
                                pathinfo(storage_path('app/public/' . $report->file_path), PATHINFO_EXTENSION),
                            );
                            $filename = basename($report->file_path);
                        @endphp
                        @if ($extension == 'pdf')
                            <a href="{{ asset('storage/' . $report->file_path) }}"
                                target="_blank">{{ $filename }}</a>
                        @elseif (in_array($extension, ['jpg', 'jpeg', 'png']))
                            <a href="{{ asset('storage/' . $report->file_path) }}"
                                target="_blank">{{ $filename }}</a>
                        @else
                            <a href="{{ route('download.file', $report->id) }}">Download File</a>
                        @endif
                    @else
                        No file attached.
                    @endif
                </p>

                @if ($report->file_path)
                    @if (in_array($extension, ['jpg', 'jpeg', 'png']))
                        <div
                            style="max-height: 300px; overflow: auto; border: 1px solid #ddd; border-radius: 4px; padding: 10px;">
                            <img src="{{ asset('storage/' . $report->file_path) }}" alt="Attached Image"
                                style="max-width: 100%; height: auto;">
                        </div>
                    @elseif ($extension == 'pdf')
                        <div
                            style="max-height: 300px; overflow: auto; border: 1px solid #ddd; border-radius: 4px; padding: 10px;">
                            <embed src="{{ asset('storage/' . $report->file_path) }}" type="application/pdf"
                                width="100%" height="300px" />
                        </div>
                    @else
                        <p>File type not supported for preview. <a
                                href="{{ route('download.file', $report->id) }}">Download File</a></p>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
