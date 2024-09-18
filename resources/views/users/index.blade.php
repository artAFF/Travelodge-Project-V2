@extends('layouts.app')
@section('title', 'User List')
@section('content')
    <div class="container animate-fade-in">
        <h2 class="my-4 text-center">User List</h2>
        <div class="mb-4 text-end">
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus me-2"></i>Add New User
            </a>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr class="animate-row">
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->department ? $user->department->name : 'N/A' }}</td>
                                    <td>
                                        @if ($user->role === 'admin')
                                            <span class="badge bg-danger">Administrator</span>
                                        @elseif($user->role === 'user')
                                            <span class="badge bg-success">User</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $user->role }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this user?')">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
