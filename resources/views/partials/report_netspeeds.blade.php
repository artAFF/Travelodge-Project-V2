<div class="card shadow-sm animate-slide-up">
    <div class="card-header bg-success text-white">
        <h2 class="mb-0">Switch Room Report</h2>
    </div>
    <div class="card-body">
        @if (count($ReportSwitchs) > 0)
            <a href="{{ route('addSwitch', ['type' => $source]) }}" class="btn btn-success mb-3">
                <i class="bi bi-plus-circle"></i> Add Switch Room Check
            </a>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Location</th>
                            <th>UPS Battery Percentage</th>
                            <th>UPS Period Of Service</th>
                            <th>Switch Temperature</th>
                            <th>Created Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ReportSwitchs as $ReportSwitch)
                            <tr>
                                <td>{{ $ReportSwitch->id }}</td>
                                <td>{{ $ReportSwitch->location_name }}</td>
                                <td>{{ $ReportSwitch->ups_battery }}</td>
                                <td>{{ $ReportSwitch->ups_time }}</td>
                                <td>{{ $ReportSwitch->ups_temp }}</td>
                                <td>{{ \Carbon\Carbon::parse($ReportSwitch->created_at)->format('d/m/Y H:i:s') }}</td>
                                <td>
                                    <a href="{{ route('updateSwitch', ['type' => $source, 'id' => $ReportSwitch->id]) }}"
                                        class="btn btn-primary btn-sm">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form
                                        action="{{ route('deleteSwitch', ['type' => $source, 'id' => $ReportSwitch->id]) }}"
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
                {{ $ReportSwitchs->links() }}
            </div>
        @else
            <div class="alert alert-info">
                No data found. <a href="{{ route('addSwitch', ['type' => $source]) }}"
                    class="btn btn-primary btn-sm">Add Switch Room Check</a>
            </div>
        @endif
    </div>
</div>
