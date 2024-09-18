@extends('layouts.app')
@section('title', 'Filter Report Issues')

@section('content')
    <div class="container animate-fade-in">
        <h2 class="my-4 text-center">Filter Report Issues</h2>
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('filter.data') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label for="category_id" class="form-label">Issue Category</label>
                        <select name="category_id" id="category_id" class="form-select">
                            <option value="">All</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="department_id" class="form-label">Department</label>
                        <select name="department_id" id="department_id" class="form-select">
                            <option value="">All</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="hotel" class="form-label">Hotel</label>
                        <select name="hotel" id="hotel" class="form-select">
                            <option value="All">All</option>
                            @foreach ($hotels as $hotel)
                                <option value="{{ $hotel }}">{{ $hotel }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="All">All</option>
                            <option value="0">In-progress</option>
                            <option value="1">Done</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control">
                    </div>

                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">Filter</button>
                    </div>
                </form>
            </div>
        </div>
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

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
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
            // Smooth scrolling
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        });
    </script>
@endsection
