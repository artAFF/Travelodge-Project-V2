<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\GuestRoom;
use App\Models\Hotel;
use App\Models\NetSpeed;
use App\Models\ServerRoom;
use App\Models\SwitchRoom;
use App\Models\Tlcmn_guest;
use App\Models\Tlcmn_net;
use App\Models\Tlcmn_server;
use App\Models\Tlcmn_switch;
use Illuminate\Http\Request;
use App\Models\Travelodge;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;


class ReadController extends Controller
{
    public function __construct()
    {
        // Require authentication for all methods in this controller
        $this->middleware('auth');
    }

    public function TableReportIssue(Request $request)
    {
        $query = $request->input('query');
        $sort_by = $request->input('sort_by', 'id');
        $sort_order = $request->input('sort_order', 'desc');
        $user = auth()->user();

        $ReportIssues = Travelodge::with(['category', 'department', 'assignee', 'hotel'])
            ->when($query, function ($q) use ($query) {
                $q->whereHas('category', function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%");
                })->orWhereHas('department', function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%");
                })->orWhere('detail', 'like', "%{$query}%")
                    ->orWhereHas('hotel', function ($q) use ($query) {
                        $q->where('hotel_code', 'like', "%{$query}%")
                            ->orWhere('hotel_full_name', 'like', "%{$query}%");
                    });
            })
            ->when($user->role !== 'admin', function ($q) use ($user) {
                $q->where('department_id', $user->department_id);
            })
            ->orderBy($sort_by, $sort_order)
            ->paginate(10)
            ->appends(['query' => $query, 'sort_by' => $sort_by, 'sort_order' => $sort_order]);

        $itSupportUsers = User::whereHas('department', function ($query) {
            $query->where('name', 'IT Support');
        })->get();

        return view('/reports/reportIssue', compact('ReportIssues', 'query', 'sort_by', 'sort_order', 'itSupportUsers'));
    }

    public function inprocess(Request $request)
    {
        $query = $request->input('query');
        $sort_by = $request->input('sort_by', 'id');
        $sort_order = $request->input('sort_order', 'asc');
        $user = auth()->user();

        $in_process = Travelodge::with(['category', 'department', 'assignee', 'hotel'])
            ->where('status', 0)
            ->when($query, function ($q) use ($query) {
                $q->where(function ($subQ) use ($query) {
                    $subQ->whereHas('category', function ($q) use ($query) {
                        $q->where('name', 'like', "%{$query}%");
                    })->orWhereHas('department', function ($q) use ($query) {
                        $q->where('name', 'like', "%{$query}%");
                    })->orWhere('detail', 'like', "%{$query}%")
                        ->orWhereHas('hotel', function ($q) use ($query) {
                            $q->where('hotel_code', 'like', "%{$query}%")
                                ->orWhere('hotel_full_name', 'like', "%{$query}%");
                        });
                });
            })
            ->when($user->role !== 'admin', function ($q) use ($user) {
                $q->where('department_id', $user->department_id);
            })
            ->orderBy($sort_by, $sort_order)
            ->paginate(20)
            ->appends(['query' => $query, 'sort_by' => $sort_by, 'sort_order' => $sort_order]);

        $itSupportUsers = User::whereHas('department', function ($query) {
            $query->where('name', 'IT Support');
        })->get();

        return view('reports.inprocess', compact('in_process', 'query', 'sort_by', 'sort_order', 'itSupportUsers'));
    }

    public function sendToLineImage(Request $request)
    {
        // ย้าย token ไปอ่านจาก .env
        $lineAccessToken = env('LINE_NOTIFY_TOKEN');
        $discordWebhook = env('DISCORD_WEBHOOK_URL');

        if (!$request->hasFile('image')) {
            return response()->json(['success' => false, 'message' => 'No image file found'], 400);
        }

        $image = $request->file('image');

        // ส่งไปที่ Line
        $lineResponse = Http::attach(
            'imageFile',
            file_get_contents($image),
            'table_capture.png'
        )->withHeaders([
            'Authorization' => 'Bearer ' . $lineAccessToken,
        ])->post('https://notify-api.line.me/api/notify', [
            'message' => 'Table Capture',
        ]);

        // ส่งไปที่ Discord
        $discordResponse = Http::attach(
            'file',
            file_get_contents($image),
            'table_capture.png'
        )->post($discordWebhook);

        if ($lineResponse->successful() && $discordResponse->successful()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json([
                'success' => false,
                'line_status' => $lineResponse->status(),
                'discord_status' => $discordResponse->status()
            ], 500);
        }
    }

    public function preview($id)
    {
        // Show preview of a specific issue
        $report = Travelodge::findOrFail($id);
        return view('/reports/preview-issue', compact('report'));
    }

    public function filterDate(Request $request)
    {
        // Filter issues by date range
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $in_process = Travelodge::whereDate('created_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->paginate(15);

        return view('/reports/inprocess', compact('in_process'));
    }
    function NumberOfCurrentStatus()
    {
        // Calculate number of issues by status for each department
        $departments = [];
        $departmentLinks = [];
        $departmentRecords = Department::all();
        foreach ($departmentRecords as $dept) {
            $departments[$dept->id] = ['0' => 0, '1' => 0];
            $departmentLinks[$dept->id] = route('itsup_status', ['department_id' => $dept->id]);
        }
        $travelodges = Travelodge::all();
        foreach ($travelodges as $travelodge) {
            $department_id = $travelodge->department_id;
            $status = $travelodge->status;
            if (isset($departments[$department_id])) {
                if (isset($departments[$department_id][$status])) {
                    $departments[$department_id][$status]++;
                } else {
                    error_log("Invalid status '$status' for department_id '$department_id'");
                }
            } else {
                error_log("Invalid department_id '$department_id'");
            }
        }
        $allTotals = ['0' => 0, '1' => 0, 'total' => 0];
        foreach ($departments as $department_id => $statuses) {
            $departments[$department_id]['total'] = array_sum($statuses);
            $allTotals['0'] += $statuses['0'];
            $allTotals['1'] += $statuses['1'];
            $allTotals['total'] += $departments[$department_id]['total'];
        }
        return view('/home/main', ['departments' => $departments, 'departmentLinks' => $departmentLinks, 'allTotals' => $allTotals]);
    }

    public function itsup_status($department_id)
    {
        // Show in-process issues for a specific department
        $user = auth()->user();
        if ($user->role !== 'admin' && $user->department_id != $department_id) {
            abort(403, 'Unauthorized action.');
        }
        $itsup_statuses = Travelodge::where('department_id', $department_id)
            ->where('status', 0)
            ->paginate(15);
        $department = Department::findOrFail($department_id);
        return view('home.itsup_status', compact('itsup_statuses', 'department'));
    }


    public function TableReportAll(Request $request, $type)
    {
        $prefix = $this->getPrefixFromType($type);
        $source = $type;

        // เปลี่ยนจาก take(10) เป็น paginate(10)
        $ReportGuests = DB::table("{$prefix}guests")
            ->leftJoin('locations', 'locations.id', '=', "{$prefix}guests.location_id")
            ->select("{$prefix}guests.*", 'locations.name as location_name')
            ->orderBy("{$prefix}guests.id", 'desc')
            ->paginate(10, ['*'], 'guests_page');

        $ReportSwitchs = DB::table("{$prefix}switches")
            ->leftJoin('locations', 'locations.id', '=', "{$prefix}switches.location_id")
            ->select("{$prefix}switches.*", 'locations.name as location_name')
            ->orderBy("{$prefix}switches.id", 'desc')
            ->paginate(10, ['*'], 'switches_page');

        $ReportServers = DB::table("{$prefix}servers")
            ->orderBy('id', 'desc')
            ->paginate(10, ['*'], 'servers_page');

        $ReportNetSpeeds = DB::table("{$prefix}nets")
            ->leftJoin('locations', 'locations.id', '=', "{$prefix}nets.location_id")
            ->select("{$prefix}nets.*", 'locations.name as location_name')
            ->orderBy("{$prefix}nets.id", 'desc')
            ->paginate(10, ['*'], 'netspeeds_page');

        if ($request->ajax()) {
            return response()->json([
                'ReportGuests' => view('partials.report_guests', compact('ReportGuests', 'source'))->render(),
                'ReportNetSpeeds' => view('partials.report_netspeeds', compact('ReportNetSpeeds', 'source'))->render(),
                'ReportSwitchs' => view('partials.report_switchs', compact('ReportSwitchs', 'source'))->render(),
                'ReportServers' => view('partials.report_servers', compact('ReportServers', 'source'))->render(),
            ]);
        }

        return view("daily.hotels.{$source}", compact('ReportGuests', 'ReportSwitchs', 'ReportServers', 'ReportNetSpeeds', 'source'));
    }

    private function getPrefixFromType($type)
    {
        switch ($type) {
            case 'ehcm':
                return 'Ehcm_';
            case 'uncm':
                return 'Uncm_';
            default:
                return 'Tlcmn_';
        }
    }
}
