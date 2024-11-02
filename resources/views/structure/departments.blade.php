@extends('layouts.app')
@section('title', 'Manage Departments')
@section('content')
    <div class="container animate-fade-in">
        <h2 class="my-4 text-center">Manage Departments</h2>
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('structure.departments.store') }}" method="POST" class="mb-4">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-8">
                            <input type="text" name="name" class="form-control" placeholder="New Department Name"
                                required>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-plus-circle me-2"></i>Add Department
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($departments as $department)
                                <tr class="animate-row">
                                    <td>{{ $department->id }}</td>
                                    <td>{{ $department->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
