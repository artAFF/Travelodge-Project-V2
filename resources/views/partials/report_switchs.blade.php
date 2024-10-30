<div class="card shadow-sm">
    <div class="card-header bg-info text-white">
        <h2 class="mb-0">Internet Checking Report</h2>
    </div>
    <div class="card-body">
        @if (count($ReportNetSpeeds) > 0)
            <a href="{{ route('addNetSpeed', ['type' => $source]) }}" class="btn btn-success mb-3">
                <i class="bi bi-plus-circle"></i> Add Internet Check
            </a>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Location</th>
                            <th>Download</th>
                            <th>Upload</th>
                            <th>Created Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ReportNetSpeeds as $ReportNetSpeed)
                            <tr>
                                <td>{{ $ReportNetSpeed->id }}</td>
                                <td>{{ $ReportNetSpeed->location_name ?? 'N/A' }}</td>
                                <td>{{ $ReportNetSpeed->download }}</td>
                                <td>{{ $ReportNetSpeed->upload }}</td>
                                <td>{{ \Carbon\Carbon::parse($ReportNetSpeed->created_at)->format('d/m/Y H:i:s') }}</td>
                                <td>
                                    <a href="{{ route('updateNetSpeed', ['type' => $source, 'id' => $ReportNetSpeed->id]) }}"
                                        class="btn btn-primary btn-sm">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form
                                        action="{{ route('deleteNetSpeed', ['type' => $source, 'id' => $ReportNetSpeed->id]) }}"
                                        method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this report?')">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="justify-content-center">
                {{ $ReportNetSpeeds->links() }}
            </div>
        @else
            <div class="alert alert-info">
                No data found. <a href="{{ route('addNetSpeed', ['type' => $source]) }}"
                    class="btn btn-primary btn-sm">Add Internet Check</a>
            </div>
        @endif
    </div>
</div>
