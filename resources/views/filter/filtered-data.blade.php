@extends('layouts.app')
@section('title', 'Filtered Data')

@section('content')
    <div class="container animate-fade-in">
        <h1 class="my-4 text-center">Filtered Data</h1>
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table">
                            <tr>
                                <th scope="col" class="text-center">ID</th>
                                <th scope="col" class="text-center">Issue</th>
                                <th scope="col" class="text-center">Detail</th>
                                <th scope="col" class="text-center">Department</th>
                                <th scope="col" class="text-center">Hotel</th>
                                <th scope="col" class="text-center">Status</th>
                                <th scope="col" class="text-center">Created At</th>
                                <th scope="col" class="text-center">Updated At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($issues as $issue)
                                <tr class="animate-row">
                                    <td class="text-center">{{ $issue->id }}</td>
                                    <td class="text-center">{{ $issue->category->name ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $issue->detail }}</td>
                                    <td class="text-center">{{ $issue->department->name ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $issue->hotel }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $issue->status == 0 ? 'bg-warning' : 'bg-success' }}">
                                            {{ $issue->status == 0 ? 'In-progress' : 'Done' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($issue->created_at)->format('d/m/Y H:i:s') }}</td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($issue->updated_at)->format('d/m/Y H:i:s') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="text-center mb-4">
            @if (count($issues) > 0)
                <form action="{{ route('download.pdf') }}" method="GET">
                    <input type="hidden" name="issues" value="{{ json_encode($issues) }}">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-download me-2"></i>Download PDF
                    </button>
                </form>
            @else
                <p class="alert alert-info">No data available for download.</p>
            @endif
        </div>

        <input type="hidden" name="category_id" value="{{ request('category_id') }}">
        <input type="hidden" name="department_id" value="{{ request('department_id') }}">
        <input type="hidden" name="hotel" value="{{ request('hotel') }}">
        <input type="hidden" name="status" value="{{ request('status') }}">
        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
    </div>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        .animate-row {
            animation: slideIn 0.3s ease-out forwards;
            opacity: 0;
            transform: translateX(-20px);
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

        .btn-primary {
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
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
