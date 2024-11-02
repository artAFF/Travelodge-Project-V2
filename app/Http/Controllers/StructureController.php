<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Department;
use App\Models\Location;
use Illuminate\Http\Request;

class StructureController extends Controller
{
    public function __construct()
    {
        // Require authentication for all methods in this controller
        $this->middleware('auth');
    }

    public function locations()
    {
        // Retrieve all locations ordered by id
        $locations = Location::orderBy('id')->get();
        // Return the locations view with the locations data
        return view('structure.locations', compact('locations'));
    }

    public function categories()
    {
        // Retrieve all categories ordered by id
        $categories = Category::orderBy('id')->get();
        // Return the categories view with the categories data
        return view('structure.categories', compact('categories'));
    }

    public function departments()
    {
        // Retrieve all departments ordered by id
        $departments = Department::orderBy('id')->get();
        // Return the departments view with the departments data
        return view('structure.departments', compact('departments'));
    }

    public function storeLocation(Request $request)
    {
        // Call the generic storeItem method for Location model
        return $this->storeItem($request, Location::class, 'locations');
    }

    public function storeCategory(Request $request)
    {
        // Call the generic storeItem method for Category model
        return $this->storeItem($request, Category::class, 'categories');
    }

    public function storeDepartment(Request $request)
    {
        // Call the generic storeItem method for Department model
        return $this->storeItem($request, Department::class, 'departments');
    }

    private function storeItem(Request $request, $model, $routeName)
    {
        // Validate the incoming request data
        $validationRules = [
            'name' => 'required|string|max:255'
        ];

        // Add type validation only for Location model
        if ($model === Location::class) {
            $validationRules['type'] = 'required|string|in:building,floor,facility';
        }

        $request->validate($validationRules);

        // Prepare data for creation
        $data = [
            'name' => $request->name
        ];

        // Add type only for Location model
        if ($model === Location::class) {
            $data['type'] = $request->type;
        }

        // Create the new item
        $model::create($data);

        // Get the lowercase name of the model
        $itemName = strtolower(class_basename($model));

        // Redirect to the appropriate route with a success message
        return redirect()->route("structure.{$routeName}")
            ->with('success', ucfirst($itemName) . ' added successfully.');
    }
}
