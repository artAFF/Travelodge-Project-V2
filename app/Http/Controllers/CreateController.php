<?php

namespace App\Http\Controllers;

use App\Models\BudgetExpense;
use App\Models\Category;
use App\Models\Department;
use App\Models\Ehcm_guest;
use App\Models\Ehcm_net;
use App\Models\Ehcm_server;
use App\Models\Ehcm_switch;
use App\Models\GuestRoom;
use App\Models\Hotel;
use App\Models\Location;
use App\Models\NetSpeed;
use App\Models\ServerRoom;
use App\Models\SwitchRoom;
use App\Models\Tlcmn_guest;
use App\Models\Tlcmn_net;
use App\Models\Tlcmn_server;
use App\Models\Tlcmn_switch;
use Illuminate\Http\Request;
use App\Models\Travelodge;
use App\Models\Uncm_guest;
use App\Models\Uncm_net;
use App\Models\Uncm_server;
use App\Models\Uncm_switch;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateController extends Controller
{
    public function __construct()
    {
        // Require authentication for all methods in this controller
        $this->middleware('auth');
    }

    public function AddReportIssue()
    {
        session(['previous_url' => url()->previous()]);

        // Fetch all categories and departments
        $categories = Category::all();
        $departments = Department::all();
        $hotels = Hotel::all();

        // Get IT Support users
        $itSupportUsers = User::whereHas('department', function ($query) {
            $query->where('name', 'IT Support');
        })->get();

        // Return view with data
        return view('/reports/addIssue', compact('categories', 'departments', 'itSupportUsers', 'hotels'));
    }

    public function InsertReportIssue(Request $request)
    {
        /* dd($request->all()); */
        // Validate incoming request data
        $request->validate([
            'issues.*.category_id' => 'required|exists:categories,id',
            'issues.*.department_id' => 'required|exists:departments,id',
            'issues.*.detail' => 'required',
            'issues.*.hotel_id' => 'required|exists:hotels,id',
            'issues.*.file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'issues.*.assignee' => 'nullable|exists:users,id',
            'issues.*.budget.expense_name.*' => 'nullable|required_with:issues.*.budget.expense_amount.*',
            'issues.*.budget.expense_amount.*' => 'nullable|required_with:issues.*.budget.expense_name.*|numeric|min:0',
            'created_by' => 'required|exists:users,id',
        ]);

        $issues = $request->input('issues');
        $createdBy = $request->input('created_by');

        foreach ($issues as $index => $issueData) {
            // Create new Travelodge issue with creator info
            $travelodge = Travelodge::create([
                'category_id' => $issueData['category_id'],
                'department_id' => $issueData['department_id'],
                'detail' => $issueData['detail'],
                'hotel_id' => $issueData['hotel_id'],
                'status' => $issueData['status'],
                'assignee_id' => $issueData['assignee'] ?: null,
                'remarks' => $issueData['remarks'] ?? null,
                'created_by' => $createdBy,
            ]);

            // Handle file upload if present
            if ($request->hasFile("issues.{$index}.file")) {
                $file = $request->file("issues.{$index}.file");
                $filename = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads', $filename, 'public');
                $travelodge->update(['file_path' => $filePath]);
            }

            if (isset($issueData['budget'])) {
                $expenseNames = $issueData['budget']['expense_name'] ?? [];
                $expenseAmounts = $issueData['budget']['expense_amount'] ?? [];

                foreach ($expenseNames as $key => $name) {
                    if (!empty($name) && isset($expenseAmounts[$key])) {
                        BudgetExpense::create([
                            'report_issue_id' => $travelodge->id,
                            'expense_name' => $name,
                            'expense_amount' => $expenseAmounts[$key]
                        ]);
                    }
                }
            }
            // Send notifications via NotificationController
            app(NotificationController::class)->sendNewIssueNotifications($travelodge, $issueData);
        }

        return redirect(session('previous_url', '/reports/reportIssue'));
    }

    public function AddGuest($type)
    {
        $buildings = Location::where('type', 'building')->get();
        $floors = Location::where('type', 'floor')->get();
        return view('/guest/addGuest', compact('buildings', 'floors', 'type'));
    }

    public function InsertGuest(Request $request)
    {
        /* dd($request->all()); */
        $request->validate([
            'room_no' => 'required',
            'upload' => 'required',
            'download' => 'required',
            'ch_no' => 'required',
            'ch_name' => 'required',
            'location_id' => 'required|exists:locations,id'
        ]);

        $hotel = $request->input('hotel');
        $model = $this->getModelFromType($hotel, 'guest');

        $data = [
            'location_id' => $request->location_id,
            'room_no' => $request->room_no,
            'upload' => $request->upload,
            'download' => $request->download,
            'ch_no' => $request->ch_no,
            'ch_name' => $request->ch_name,
            'created_at' => now()
        ];

        $model::create($data);
        return redirect("/daily/hotels/{$hotel}");
    }

    public function AddSwitch($type)
    {
        $buildings = Location::where('type', 'building')->get();
        $floors = Location::where('type', 'floor')->get();
        return view('/switchs/addSwitch', compact('buildings', 'floors', 'type'));
    }

    public function InsertSwitch(Request $request)
    {
        $request->validate([
            'ups_battery' => 'required',
            'ups_time' => 'required',
            'ups_temp' => 'required',
            'location_id' => 'required|exists:locations,id'
        ]);

        $hotel = $request->input('hotel');
        $model = $this->getModelFromType($hotel, 'switch');

        $data = [
            'location_id' => $request->location_id,
            'ups_battery' => $request->ups_battery,
            'ups_time' => $request->ups_time,
            'ups_temp' => $request->ups_temp,
            'created_at' => now()
        ];

        $model::create($data);
        return redirect("/daily/hotels/{$hotel}");
    }

    public function AddServer($type)
    {
        return view('/servers/addServer', compact('type'));
    }

    public function InsertServer(Request $request)
    {
        $request->validate([
            'server_temp' => 'required|numeric',
            'ups_temp' => 'required|numeric',
            'ups_battery' => 'required|numeric',
            'ups_time' => 'required|numeric'
        ]);

        $hotel = $request->input('hotel');
        $model = $this->getModelFromType($hotel, 'server');

        $data = [
            'server_temp' => $request->server_temp,
            'ups_temp' => $request->ups_temp,
            'ups_battery' => $request->ups_battery,
            'ups_time' => $request->ups_time,
            'created_at' => now()
        ];

        $model::create($data);
        return redirect("/daily/hotels/{$hotel}");
    }

    public function AddNetSpeed($type)
    {
        $buildings = Location::where('type', 'building')->get();
        $facilities = Location::where('type', 'facility')->get();
        return view('/netspeed/addNetSpeed', compact('buildings', 'facilities', 'type'));
    }

    public function InsertNetSpeed(Request $request)
    {
        $request->validate([
            'upload' => 'required',
            'download' => 'required',
            'location_id' => 'required|exists:locations,id'
        ]);

        $hotel = $request->input('hotel');
        $model = $this->getModelFromType($hotel, 'net');

        $data = [
            'location_id' => $request->location_id,
            'upload' => $request->upload,
            'download' => $request->download,
            'created_at' => now()
        ];

        $model::create($data);
        return redirect("/daily/hotels/{$hotel}");
    }

    private function getModelFromType($type, $modelType)
    {
        switch ($type) {
            case 'tlcmn':
                return "App\\Models\\Tlcmn_{$modelType}";
            case 'ehcm':
                return "App\\Models\\Ehcm_{$modelType}";
            case 'uncm':
                return "App\\Models\\Uncm_{$modelType}";
            default:
                throw new \Exception("Invalid type");
        }
    }
}
