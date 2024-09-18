@extends('layouts.app')
@section('title', 'Manage Buildings')
@section('content')
    <div class="container animate-fade-in">
        <h2 class="my-4 text-center">Manage Buildings</h2>
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('structure.buildings.store') }}" method="POST" class="mb-4">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="name" class="form-control" placeholder="New Building Name"
                                required>
                        </div>
                        <div class="col-md-4">
                            <select name="position" class="form-select" required>
                                <option value="">Add after...</option>
                                @foreach ($buildings as $building)
                                    <option value="{{ $building->id }}">after {{ $building->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-plus-circle me-2"></i>Add Building
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
                            @foreach ($buildings as $building)
                                <tr class="animate-row">
                                    <td>{{ $building->id }}</td>
                                    <td>{{ $building->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
