@extends('layouts.app')
@section('title', 'IT Support Status')

@section('content')
    @if (count($itsup_statuses) > 0)
        <div class="container">
            <h1 class="text-center mb-4 animate__animated animate__fadeInDown">{{ $department->name ?? 'N/A' }} Issues in the
                Process</h1>

            <form method="GET" action="/filter" class="mb-4 animate__animated animate__fadeIn">
                <div class="row">
                    <div class="col-md-3">
                        <label>Start Date:</label>
                        <input type="date" name="start_date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>End Date:</label>
                        <input type="date" name="end_date" class="form-control">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive animate__animated animate__fadeIn">
                <table class="table table-striped table-hover">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>#</th>
                            <th>Issue</th>
                            <th class="col-md-3">Detail</th>
                            <th>Department</th>
                            <th>Hotel</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($itsup_statuses as $itsup_status)
                            <tr class="animate__animated animate__fadeInUp"
                                style="animation-delay: {{ $loop->index * 0.05 }}s">
                                <td>{{ $itsup_status->id ?? 'N/A' }}</td>
                                <td>{{ $itsup_status->issue ?? 'N/A' }}</td>
                                <td>{{ $itsup_status->detail ?? 'N/A' }}</td>
                                <td>{{ $itsup_status->department->name ?? 'N/A' }}</td>
                                <td>{{ $itsup_status->hotel ?? 'N/A' }}</td>
                                <td class="text-center">
                                    @if (($itsup_status->status ?? null) === 0)
                                        <span class="badge bg-warning animate__animated animate__pulse animate__infinite">In
                                            Progress</span>
                                    @elseif (($itsup_status->status ?? null) === 1)
                                        <span class="badge bg-success animate__animated animate__bounceIn">Completed</span>
                                    @else
                                        <span class="badge bg-secondary">N/A</span>
                                    @endif
                                </td>
                                <td>{{ $itsup_status->created_at ? \Carbon\Carbon::parse($itsup_status->created_at)->format('d-m-Y H:i:s') : 'N/A' }}
                                </td>
                                <td>{{ $itsup_status->updated_at ? \Carbon\Carbon::parse($itsup_status->updated_at)->format('d-m-Y H:i:s') : 'N/A' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4 animate__animated animate__fadeInUp">
                {{ $itsup_statuses->links() }}
            </div>
        </div>
    @else
        <h1 class="text-center py-5 animate__animated animate__fadeIn">No data</h1>
    @endif
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        .table-responsive {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        .table thead {
            background: linear-gradient(45deg, #007bff, #00c6ff);
        }

        .btn-primary {
            background: linear-gradient(45deg, #007bff, #00c6ff);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
        }

        .badge {
            font-size: 0.8rem;
            padding: 0.4em 0.6em;
        }
    </style>
@endsection
