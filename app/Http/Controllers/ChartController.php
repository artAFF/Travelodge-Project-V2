<?php

namespace App\Http\Controllers;

use App\Models\Travelodge;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;

class ChartController extends Controller
{
    public function __construct()
    {
        // Check user authentication before accessing this controller
        $this->middleware('auth');
    }

    public function HotelChart(Request $request)
    {
        // Define hotel names and codes
        $hotels = [
            'TLCMN' => 'TLCMN',
            'EHCM' => 'EHCM',
            'UNCM' => 'UNCM '
        ];
        $data = [];
        $colors = ['#E74C3C', '#2CA02C', '#3498DB'];
        $latestReports = [];

        // Loop through each hotel to gather data
        foreach ($hotels as $hotel => $hotelLabel) {
            // Count the number of entries for each hotel
            $count = Travelodge::where('hotel', $hotel)->count();
            array_push($data, $count);

            // Get the latest 5 reports for each hotel
            $latestHotelReports = Travelodge::where('hotel', $hotel)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(['id', 'detail', 'created_at']);
            $latestReports[$hotelLabel] = $latestHotelReports;
        }

        // Calculate percentages of the data
        $percentages = $this->calculatePercentages($data);
        // Create dataset for the chart
        $datasets = [
            [
                'label' => 'Hotels',
                'data' => $data,
                'backgroundColor' => $colors
            ]
        ];
        $hotels = array_values($hotels);

        // Send data to the view
        return view('/home', compact('datasets', 'hotels', 'percentages', 'latestReports'));
    }

    public function HotelDashboard(Request $request, $hotel)
    {
        // Define hotel names
        $hotel_names = [
            'TLCMN' => 'Travelodge Nimman',
            'EHCM' => 'Eastin Tan',
            'UNCM' => 'U nimman'
        ];

        // Get category and department data for the specified hotel
        $category_data = $this->getCategoryData($hotel);
        $department_data = $this->getDepartmentData($hotel);

        // Return view with hotel data
        return view('dashboards.hotel_dashboard', [
            'hotel_name' => $hotel_names[$hotel],
            'hotel_code' => $hotel,
            'category_data' => $category_data,
            'department_data' => $department_data
        ]);
    }

    public function getHotelDataByDate($view, $filterType, Request $request)
    {
        $hotel = $request->query('hotel');
        $query = Travelodge::where('hotel', $hotel);

        // Apply date filters based on filter type
        if ($filterType === 'custom') {
            $startDate = $request->query('start');
            $endDate = $request->query('end');
            if ($startDate && $endDate) {
                $startDateTime = Carbon::parse($startDate)->startOfDay();
                $endDateTime = Carbon::parse($endDate)->endOfDay();

                $query->whereBetween('created_at', [$startDateTime, $endDateTime]);
            }
        } else if ($filterType !== 'all_time') {
            $this->applyDateFilter($query, $filterType);
        }

        // Get data based on view type (category or department)
        if ($view === 'category') {
            $data = $this->getCategoryDataFromQuery($query);
        } else {
            $data = $this->getDepartmentDataFromQuery($query);
        }

        return response()->json($data);
    }

    public function getIssueDetails($type, $label, Request $request)
    {
        $hotel = $request->query('hotel');
        $dateFilter = $request->query('dateFilter');
        $startDate = $request->query('start');
        $endDate = $request->query('end');

        // Build query with relations
        $query = Travelodge::with([
            'category:id,name',
            'department:id,name',
            'assignee:id,name'
        ])
            ->where('hotel', $hotel);

        // Filter by type (category or department)
        if ($type === 'category') {
            $query->whereHas('category', function ($q) use ($label) {
                $q->where('name', $label);
            });
        } elseif ($type === 'department') {
            $query->whereHas('department', function ($q) use ($label) {
                $q->where('name', $label);
            });
        }

        // Apply date filter
        if ($dateFilter && $dateFilter !== 'all_time') {
            $this->applyDateFilter($query, $dateFilter);
        } elseif ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Paginate results
        $issues = $query->paginate(10);

        // Transform issues data
        $transformedIssues = collect($issues->items())->map(function ($issue) {
            return [
                'id' => $issue->id,
                'category' => ['name' => $issue->category->name ?? 'N/A'],
                'detail' => $issue->detail ?? 'N/A',
                'remarks' => $issue->remarks ?? 'N/A',
                'department' => ['name' => $issue->department->name ?? 'N/A'],
                'hotel' => $issue->hotel,
                'status' => $issue->status,
                'assignee' => $issue->assignee ? ['name' => $issue->assignee->name] : null,
                'created_at' => $issue->created_at,
                'updated_at' => $issue->updated_at
            ];
        });

        // Return JSON response with pagination info
        return response()->json([
            'data' => $transformedIssues,
            'current_page' => $issues->currentPage(),
            'last_page' => $issues->lastPage(),
            'total' => $issues->total(),
            'per_page' => $issues->perPage()
        ]);
    }

    private function applyDateFilter($query, $filterType)
    {
        // Apply various date filters based on filter type
        switch ($filterType) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'yesterday':
                $query->whereDate('created_at', Carbon::yesterday());
                break;
            case 'this_week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'last_week':
                $query->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]);
                break;
            case 'this_month':
                $query->whereMonth('created_at', Carbon::now()->month);
                break;
            case 'last_month':
                $query->whereMonth('created_at', Carbon::now()->subMonth()->month);
                break;
            case 'last_30_days':
                $query->where('created_at', '>=', Carbon::now()->subDays(30));
                break;
            case 'this_quarter':
                $query->whereBetween('created_at', [Carbon::now()->startOfQuarter(), Carbon::now()->endOfQuarter()]);
                break;
            case 'last_quarter':
                $query->whereBetween('created_at', [Carbon::now()->subQuarter()->startOfQuarter(), Carbon::now()->subQuarter()->endOfQuarter()]);
                break;
            case 'this_year':
                $query->whereYear('created_at', Carbon::now()->year);
                break;
            case 'last_year':
                $query->whereYear('created_at', Carbon::now()->subYear()->year);
                break;
            case 'last_365_days':
                $query->where('created_at', '>=', Carbon::now()->subDays(365));
                break;
        }
    }

    private function getCategoryData($hotel)
    {
        $query = Travelodge::where('hotel', $hotel);
        return $this->getCategoryDataFromQuery($query);
    }

    private function getDepartmentData($hotel)
    {
        $query = Travelodge::where('hotel', $hotel);
        return $this->getDepartmentDataFromQuery($query);
    }

    private function getCategoryDataFromQuery($query)
    {
        // Get categories with counts, ordered by count
        $categoriesWithCounts = $query->select('category_id')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('category_id')
            ->with('category')
            ->orderByDesc('count')
            ->get();

        $categories = $categoriesWithCounts->map(function ($item) {
            return $item->category->name;
        })->toArray();

        $data = $categoriesWithCounts->pluck('count')->toArray();

        // Define colors for chart
        $colors = ['#C71585', '#E63946', '#F1C23B', '#53577A', '#6495ED', '#20B2AA', '#FFA07A', '#808080', '#7FFFD4', '#D3D3D3', '#90CAF9'];
        $colors = array_slice($colors, 0, count($categories));

        return [
            'labels' => $categories,
            'data' => $data,
            'backgroundColor' => $colors
        ];
    }

    private function getDepartmentDataFromQuery($query)
    {
        // Get departments with counts, ordered by count
        $departmentsWithCounts = $query->select('department_id')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('department_id')
            ->with('department')
            ->orderByDesc('count')
            ->get();

        $departments = $departmentsWithCounts->map(function ($item) {
            return $item->department->name;
        })->toArray();

        $data = $departmentsWithCounts->pluck('count')->toArray();

        // Define colors for chart
        $colors = ['#C71585', '#E63946', '#F1C23B', '#53577A', '#2ECC40', '#3598DC', '#90CAF9', '#D81B60', '#FF9999', '#6A3AB1', '#2196F3', '#1976D2', '#007bff'];
        $colors = array_slice($colors, 0, count($departments));

        return [
            'labels' => $departments,
            'data' => $data,
            'backgroundColor' => $colors
        ];
    }

    private function calculatePercentages($data)
    {
        // Calculate percentages based on total
        $total = array_sum($data);
        return array_map(function ($value) use ($total) {
            return round(($value / $total) * 100, 2);
        }, $data);
    }

    public function getCompareHotelDataByDate($view, $filterType, Request $request)
    {
        $hotel = $request->query('hotel');
        $query = Travelodge::where('hotel', $hotel);

        // Apply date filters for current data
        if ($filterType === 'custom') {
            $startDate = $request->query('start');
            $endDate = $request->query('end');
            if ($startDate && $endDate) {
                $startDateTime = Carbon::parse($startDate)->startOfDay();
                $endDateTime = Carbon::parse($endDate)->endOfDay();
                $query->whereBetween('created_at', [$startDateTime, $endDateTime]);
            }
        } else if ($filterType !== 'all_time') {
            $this->applyDateFilter($query, $filterType);
        }

        // Get current data
        $currentData = $this->getDataByView($query, $view);

        // Get compare data if provided
        $compareData = null;
        $compareStartDate = $request->query('compareStart');
        $compareEndDate = $request->query('compareEnd');
        if ($compareStartDate && $compareEndDate) {
            $compareQuery = Travelodge::where('hotel', $hotel);
            $compareStartDateTime = Carbon::parse($compareStartDate)->startOfDay();
            $compareEndDateTime = Carbon::parse($compareEndDate)->endOfDay();
            $compareQuery->whereBetween('created_at', [$compareStartDateTime, $compareEndDateTime]);
            $compareData = $this->getDataByView($compareQuery, $view);
        }

        return response()->json([
            'currentData' => $currentData,
            'compareData' => $compareData
        ]);
    }

    private function getDataByView($query, $view)
    {
        if ($view === 'category') {
            return $this->getCategoryDataFromQuery($query);
        } else {
            return $this->getDepartmentDataFromQuery($query);
        }
    }
}
