<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function __construct()
    {
        // Require authentication for all methods in this controller
        $this->middleware('auth');
    }

    public function index()
    {
        // Retrieve all users with their related department
        $users = User::with('department')->get();
        // Return the index view with the users data
        return view('users.index', compact('users'));
    }

    public function create()
    {
        // Get all departments for the user creation form
        $departments = Department::all();
        // Return the create view with departments data
        return view('users.create', compact('departments'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
            'department_id' => 'required|exists:departments,id',
        ]);

        // Create a new user with the validated data
        User::create([
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'department_id' => $request->department_id,
        ]);

        // Redirect to the users index with a success message
        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function edit(User $user)
    {
        // Get all departments for the user edit form
        $departments = Department::all();
        // Return the edit view with user and departments data
        return view('users.edit', compact('user', 'departments'));
    }

    public function update(Request $request, User $user)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:admin,user',
            'password' => 'nullable|string|min:8|confirmed',
            'department_id' => 'required|exists:departments,id',
        ]);

        // Prepare user data for update
        $userData = $request->only(['name', 'role', 'department_id']);

        // If a new password is provided, hash it
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        // Update the user with the new data
        $user->update($userData);

        // Redirect to the users index with a success message
        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        // Delete the specified user
        $user->delete();

        // Redirect to the users index with a success message
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}
