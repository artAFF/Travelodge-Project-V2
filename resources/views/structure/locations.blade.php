@extends('layouts.app')
@section('title', 'Manage Locations')
@section('content')
    <div class="container animate-fade-in">
        <h2 class="my-4 text-center">Manage Locations</h2>
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('structure.locations.store') }}" method="POST" class="mb-4">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-5">
                            <input type="text" name="name" class="form-control" placeholder="New Location Name"
                                required>
                        </div>
                        <div class="col-md-4">
                            <select name="type" class="form-select" required>
                                <option value="">Select Type...</option>
                                <option value="building">Building</option>
                                <option value="floor">Floor</option>
                                <option value="facility">Facility</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-plus-circle me-2"></i>Add Location
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
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($locations as $location)
                                <tr class="animate-row">
                                    <td>{{ $location->id }}</td>
                                    <td>{{ $location->name }}</td>
                                    <td>{{ ucfirst($location->type) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
