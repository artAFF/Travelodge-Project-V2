@extends('layouts.app')
@section('title', 'Daily Search')
@section('content')
    <div class="container animate-fade-in">
        <h1 class="text-center my-4">Search Results</h1>
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('search') }}" method="GET" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="query" class="form-control" value="{{ $query ?? '' }}"
                                placeholder="Search...">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="start_date" class="form-control" value="{{ $start_date ?? '' }}"
                                placeholder="Start Date">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="end_date" class="form-control" value="{{ $end_date ?? '' }}"
                                placeholder="End Date">
                        </div>
                        <div class="col-md-2">
                            <select name="hotel" class="form-select">
                                <option value="tlcmn" {{ ($hotel ?? '') == 'tlcmn' ? 'selected' : '' }}>TLCMN</option>
                                <option value="ehcm" {{ ($hotel ?? '') == 'ehcm' ? 'selected' : '' }}>EHCM</option>
                                <option value="uncm" {{ ($hotel ?? '') == 'uncm' ? 'selected' : '' }}>UNCM</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="category" class="form-select">
                                <option value="all" {{ ($category ?? '') == 'all' ? 'selected' : '' }}>All</option>
                                <option value="guest" {{ ($category ?? '') == 'guest' ? 'selected' : '' }}>Guest Room
                                </option>
                                <option value="switch" {{ ($category ?? '') == 'switch' ? 'selected' : '' }}>Switch Room
                                </option>
                                <option value="server" {{ ($category ?? '') == 'server' ? 'selected' : '' }}>Server Room
                                </option>
                                <option value="internet" {{ ($category ?? '') == 'internet' ? 'selected' : '' }}>Internet
                                    Checking</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search"></i>
                                Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if (count($guests ?? []) > 0 || count($netSpeeds ?? []) > 0 || count($servers ?? []) > 0 || count($switches ?? []) > 0)
            <div class="text-center mb-4">
                <a href="{{ route('download.pdfdaily') }}" class="btn btn-success btn-lg">
                    <i class="fas fa-file-pdf me-2"></i>Download PDF Report
                </a>
            </div>
        @endif

        @if ($category == 'all' || $category == 'guest')
            <div class="card shadow-sm mb-4 animate-slide-up">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">Guest Rooms</h2>
                </div>
                <div class="card-body">
                    @if (isset($guests) && count($guests) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Location</th>
                                        <th>Room No.</th>
                                        <th>Download</th>
                                        <th>Upload</th>
                                        <th>Channel Number</th>
                                        <th>Channel Name</th>
                                        <th>Created Time</th>
                                        <th>Updated Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($guests as $guest)
                                        <tr class="animate-row">
                                            <td>{{ $guest->id }}</td>
                                            <td>{{ $guest->location }}</td>
                                            <td>{{ $guest->room_no }}</td>
                                            <td>{{ $guest->download }}</td>
                                            <td>{{ $guest->upload }}</td>
                                            <td>{{ $guest->ch_no }}</td>
                                            <td>{{ $guest->ch_name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($guest->created_at)->format('d/m/Y H:i:s') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($guest->updated_at)->format('d/m/Y H:i:s') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $guests->appends(request()->except('guests_page'))->links() }}
                    @else
                        <p class="alert alert-info">No guest room data found.</p>
                    @endif
                </div>
            </div>
        @endif

        @if ($category == 'all' || $category == 'switch')
            <div class="card shadow-sm mb-4 animate-slide-up">
                <div class="card-header bg-success text-white">
                    <h2 class="mb-0">Switch Room</h2>
                </div>
                <div class="card-body">
                    @if (isset($switches) && count($switches) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Location</th>
                                        <th>UPS Battery Percentage</th>
                                        <th>UPS Period of Service</th>
                                        <th>Switch Temperature</th>
                                        <th>Created Time</th>
                                        <th>Updated Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($switches as $switch)
                                        <tr class="animate-row">
                                            <td>{{ $switch->id }}</td>
                                            <td>{{ $switch->location }}</td>
                                            <td>{{ $switch->ups_battery }}</td>
                                            <td>{{ $switch->ups_time }}</td>
                                            <td>{{ $switch->ups_temp }}</td>
                                            <td>{{ \Carbon\Carbon::parse($switch->created_at)->format('d/m/Y H:i:s') }}
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($switch->updated_at)->format('d/m/Y H:i:s') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $switches->appends(request()->except('switches_page'))->links() }}
                    @else
                        <p class="alert alert-info">No switch room data found.</p>
                    @endif
                </div>
            </div>
        @endif

        @if ($category == 'all' || $category == 'server')
            <div class="card shadow-sm mb-4 animate-slide-up">
                <div class="card-header bg-warning text-dark">
                    <h2 class="mb-0">Server Room</h2>
                </div>
                <div class="card-body">
                    @if (isset($servers) && count($servers) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Server Temperature</th>
                                        <th>UPS Temperature</th>
                                        <th>UPS Battery</th>
                                        <th>UPS Period of Service</th>
                                        <th>Created Time</th>
                                        <th>Updated Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($servers as $server)
                                        <tr class="animate-row">
                                            <td>{{ $server->id }}</td>
                                            <td>{{ $server->server_temp }}</td>
                                            <td>{{ $server->ups_temp }}</td>
                                            <td>{{ $server->ups_battery }}</td>
                                            <td>{{ $server->ups_time }}</td>
                                            <td>{{ \Carbon\Carbon::parse($server->created_at)->format('d/m/Y H:i:s') }}
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($server->updated_at)->format('d/m/Y H:i:s') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $servers->appends(request()->except('servers_page'))->links() }}
                    @else
                        <p class="alert alert-info">No server room data found.</p>
                    @endif
                </div>
            </div>
        @endif

        @if ($category == 'all' || $category == 'internet')
            <div class="card shadow-sm mb-4 animate-slide-up">
                <div class="card-header bg-info text-white">
                    <h2 class="mb-0">Internet Checking</h2>
                </div>
                <div class="card-body">
                    @if (isset($netSpeeds) && count($netSpeeds) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Location</th>
                                        <th>Download</th>
                                        <th>Upload</th>
                                        <th>Created Time</th>
                                        <th>Updated Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($netSpeeds as $netSpeed)
                                        <tr class="animate-row">
                                            <td>{{ $netSpeed->id }}</td>
                                            <td>{{ $netSpeed->location }}</td>
                                            <td>{{ $netSpeed->download }}</td>
                                            <td>{{ $netSpeed->upload }}</td>
                                            <td>{{ \Carbon\Carbon::parse($netSpeed->created_at)->format('d/m/Y H:i:s') }}
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($netSpeed->updated_at)->format('d/m/Y H:i:s') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $netSpeeds->appends(request()->except('netSpeeds_page'))->links() }}
                    @else
                        <p class="alert alert-info">No internet checking data found.</p>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        .animate-slide-up {
            animation: slideUp 0.5s ease-out forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        .animate-row {
            animation: slideIn 0.3s ease-out forwards;
            opacity: 0;
            transform: translateX(-20px);
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
        }

        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const rows = document.querySelectorAll('.animate-row');
            rows.forEach((row, index) => {
                row.style.animationDelay = `${index * 0.05}s`;
            });
        });
    </script>
@endsection
