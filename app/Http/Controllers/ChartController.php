<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Department;
use App\Models\Hotel;
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
        $hotels = Hotel::pluck('hotel_code', 'id')->toArray();
        $data = [];
        $colors = ['#E74C3C', '#2CA02C', '#3498DB'];
        $latestReports = [];

        foreach ($hotels as $id => $hotelCode) {
            $count = Travelodge::where('hotel_id', $id)->count();
            $data[$hotelCode] = $count;

            $latestHotelReports = Travelodge::where('hotel_id', $id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(['id', 'detail', 'created_at']);
            $latestReports[$hotelCode] = $latestHotelReports;
        }

        // Sort data from highest to lowest
        arsort($data);

        // Separate keys and values after sorting
        $sortedHotels = array_keys($data);
        $sortedData = array_values($data);

        // Calculate percentages of the sorted data
        $percentages = $this->calculatePercentages($sortedData);

        // Create dataset for the chart
        $datasets = [
            [
                'label' => 'Hotels',
                'data' => $sortedData,
                'backgroundColor' => array_slice($colors, 0, count($sortedData))
            ]
        ];

        // Sort latestReports based on the number of reports
        $sortedLatestReports = [];
        foreach ($data as $hotelCode => $count) {
            if (isset($latestReports[$hotelCode])) {
                $sortedLatestReports[$hotelCode] = $latestReports[$hotelCode];
            }
        }

        // Send data to the view
        return view('/home', compact('datasets', 'sortedHotels', 'percentages', 'sortedLatestReports'));
    }

    public function HotelDashboard(Request $request, $hotel)
    {
        // Define hotel names
        $hotel_names = Hotel::pluck('hotel_full_name', 'hotel_code')->toArray();

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
        $hotelCode = $request->query('hotel');
        $hotel = Hotel::where('hotel_code', $hotelCode)->firstOrFail();
        $query = Travelodge::where('hotel_id', $hotel->id);

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
        $hotelCode = $request->query('hotel');
        $hotel = Hotel::where('hotel_code', $hotelCode)->firstOrFail();
        $dateFilter = $request->query('dateFilter');
        $startDate = $request->query('start');
        $endDate = $request->query('end');

        // Build query with relations
        $query = Travelodge::with([
            'category:id,name',
            'department:id,name',
            'assignee:id,name',
            'hotel:id,hotel_full_name,hotel_code'
        ])
            ->where('hotel_id', $hotel->id);

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
                'hotel' => [
                    'full_name' => $issue->hotel->hotel_full_name,
                    'code' => $issue->hotel->hotel_code
                ],
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
        $hotelId = Hotel::where('hotel_code', $hotel)->value('id');
        $query = Travelodge::where('hotel_id', $hotelId);
        return $this->getCategoryDataFromQuery($query);
    }
    private function getDepartmentData($hotel)
    {
        $hotelId = Hotel::where('hotel_code', $hotel)->value('id');
        $query = Travelodge::where('hotel_id', $hotelId);
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
            // ตรวจสอบว่า category มีค่าหรือไม่ก่อนเข้าถึงคุณสมบัติ name
            return $item->category ? $item->category->name : 'No Category';
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
        $total = array_sum($data);

        if ($total == 0) {
            return array_fill(0, count($data), 0);
        }

        return array_map(function ($value) use ($total) {
            return round(($value / $total) * 100, 2);
        }, $data);
    }

    public function getCompareHotelDataByDate($view, $filterType, Request $request)
    {
        $hotelCode = $request->query('hotel');
        $hotel = Hotel::where('hotel_code', $hotelCode)->firstOrFail();
        $query = Travelodge::where('hotel_id', $hotel->id);

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
            $compareQuery = Travelodge::where('hotel_id', $hotel->id);
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

    public function showCountChart()
    {
        $weeklyData = $this->getCountWeeklyData();
        $monthlyData = $this->getCountMonthlyData();
        $departmentData = $this->getCountDepartmentData();
        $categoryData = $this->getCountCategoryData();

        return view('dashboards.travelodge_project_stats', compact('weeklyData', 'monthlyData', 'departmentData', 'categoryData'));
    }

    private function getCountWeeklyData()
    {
        $travelodges = Travelodge::selectRaw('DAYOFWEEK(created_at) as day_of_week, COUNT(*) as count')
            ->whereYear('created_at', '=', now()->year)
            ->groupBy('day_of_week')
            ->orderBy('day_of_week')
            ->get();

        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $data = array_fill(0, 7, 0);
        $colors = ['#C71585', '#E63946', '#F1C23B', '#53577A', '#2ECC40', '#3598DC', '#90CAF9'];

        foreach ($travelodges as $travelodge) {
            $index = ($travelodge->day_of_week + 5) % 7;
            $data[$index] = $travelodge->count;
        }

        return [
            'labels' => $days,
            'datasets' => [
                [
                    'label' => 'Cases ',
                    'data' => $data,
                    'backgroundColor' => $colors
                ]
            ]
        ];
    }

    private function getCountMonthlyData()
    {
        $travelodges = Travelodge::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = [];
        $data = [];
        $colors = ['#C71585', '#E63946', '#F1C23B', '#53577A', '#2ECC40', '#3598DC', '#90CAF9', '#D81B60', '#FF9999', '#6A3AB1', '#2196F3', '#1976D2'];

        for ($i = 1; $i <= 12; $i++) {
            $month = date('F', mktime(0, 0, 0, $i, 1));
            $count = $travelodges->firstWhere('month', $i)->count ?? 0;

            $months[] = $month;
            $data[] = $count;
        }

        return [
            'labels' => $months,
            'datasets' => [
                [
                    'label' => 'Cases ',
                    'data' => $data,
                    'backgroundColor' => $colors
                ]
            ]
        ];
    }

    private function getCountDepartmentData()
    {
        $departmentsWithCounts = Travelodge::join('departments', 'travelodges.department_id', '=', 'departments.id')
            ->select('departments.name as department')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('departments.id', 'departments.name')
            ->orderByDesc('count')
            ->get();

        $departments = $departmentsWithCounts->pluck('department')->toArray();
        $data = $departmentsWithCounts->pluck('count')->toArray();
        $colors = ['#C71585', '#E63946', '#F1C23B', '#53577A', '#2ECC40', '#3598DC', '#90CAF9', '#D81B60', '#FF9999', '#6A3AB1', '#2196F3', '#1976D2', '#007bff'];
        $colors = array_slice($colors, 0, count($departments));

        return [
            'labels' => $departments,
            'datasets' => [
                [
                    'label' => 'Cases ',
                    'data' => $data,
                    'backgroundColor' => $colors
                ]
            ]
        ];
    }

    private function getCountCategoryData()
    {
        $categoriesWithCounts = Travelodge::join('categories', 'travelodges.category_id', '=', 'categories.id')
            ->select('categories.name as category')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('count')
            ->get();

        $categories = $categoriesWithCounts->pluck('category')->toArray();
        $data = $categoriesWithCounts->pluck('count')->toArray();
        $colors = ['#C71585', '#E63946', '#F1C23B', '#53577A', '#6495ED', '#20B2AA', '#FFA07A', '#808080', '#7FFFD4', '#D3D3D3', '#90CAF9'];
        $colors = array_slice($colors, 0, count($categories));

        return [
            'labels' => $categories,
            'datasets' => [
                [
                    'label' => 'Cases ',
                    'data' => $data,
                    'backgroundColor' => $colors
                ]
            ]
        ];
    }
}
