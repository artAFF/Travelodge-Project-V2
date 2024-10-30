<div class="card shadow-sm">
    <div class="card-header bg-warning text-dark">
        <h2 class="mb-0">Server Room Report</h2>
    </div>
    <div class="card-body">
        @if (count($ReportServers) > 0)
            <a href="{{ route('addServer', ['type' => $source]) }}" class="btn btn-success mb-3">
                <i class="bi bi-plus-circle"></i> Add Server Room Check
            </a>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Server Temperature</th>
                            <th>UPS Temperature</th>
                            <th>UPS Battery</th>
                            <th>UPS Period Of Service</th>
                            <th>Created Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ReportServers as $ReportServer)
                            <tr>
                                <td>{{ $ReportServer->id }}</td>
                                <td>{{ $ReportServer->server_temp }}</td>
                                <td>{{ $ReportServer->ups_temp }}</td>
                                <td>{{ $ReportServer->ups_battery }}</td>
                                <td>{{ $ReportServer->ups_time }}</td>
                                <td>{{ \Carbon\Carbon::parse($ReportServer->created_at)->format('d/m/Y H:i:s') }}</td>
                                <td>
                                    <a href="{{ route('updateServer', ['type' => $source, 'id' => $ReportServer->id]) }}"
                                        class="btn btn-primary btn-sm">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form
                                        action="{{ route('deleteServer', ['type' => $source, 'id' => $ReportServer->id]) }}"
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
        @else
            <div class="alert alert-info">
                No data found. <a href="{{ route('addServer', ['type' => $source]) }}"
                    class="btn btn-primary btn-sm">Add Server Room Check</a>
            </div>
        @endif
    </div>
</div>
