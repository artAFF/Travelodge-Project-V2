@extends('layouts.app')
@section('title', 'Home')
@section('content')
    <div class="container">
        <h2 class="text-center mb-4 animate__animated animate__fadeInDown">Number of current states By Department</h2>
        <div class="table-responsive animate__animated animate__fadeIn">
            <table class="table table-striped table-hover">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Department</th>
                        <th>In-process</th>
                        <th>Done</th>
                        <th>Total</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($departments as $department_id => $statuse)
                        <tr class="animate__animated animate__fadeInUp" style="animation-delay: {{ $loop->index * 0.1 }}s">
                            <td>{{ App\Models\Department::find($department_id)->name ?? 'N/A' }}</td>
                            <td>{{ $statuse['0'] ?? 'N/A' }}</td>
                            <td>{{ $statuse['1'] ?? 'N/A' }}</td>
                            <td>{{ $statuse['total'] ?? 'N/A' }}</td>
                            <td>
                                @if (isset($departmentLinks[$department_id]))
                                    @if (auth()->user()->role === 'admin' || auth()->user()->department_id === $department_id)
                                        <a href="{{ $departmentLinks[$department_id] }}"
                                            class="btn btn-primary btn-sm animate__animated animate__pulse animate__infinite">
                                            <i class="bi bi-hourglass-split"></i>
                                        </a>
                                    @else
                                        <button class="btn btn-secondary btn-sm" disabled>
                                            <i class="bi bi-hourglass-split"></i>
                                        </button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    <tr class="table-info animate__animated animate__fadeInUp"
                        style="animation-delay: {{ count($departments) * 0.1 }}s">
                        <td><strong>Total All</strong></td>
                        <td><strong>{{ $allTotals['0'] }}</strong></td>
                        <td><strong>{{ $allTotals['1'] }}</strong></td>
                        <td><strong>{{ $allTotals['total'] }}</strong></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
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
    </style>
@endsection
