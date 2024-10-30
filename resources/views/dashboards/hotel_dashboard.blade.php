@extends('layouts.app')
@section('title', 'Hotel Dashboard')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .dashboard-header {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .hotel-title {
            color: #000;
            font-size: 24px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }

        .control-group {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .form-control,
        .btn {
            border-radius: 4px;
            height: 38px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -5px;
            margin-left: -5px;
        }

        .form-row>.col,
        .form-row>[class*="col-"] {
            padding-right: 5px;
            padding-left: 5px;
        }

        .mb-2 {
            margin-bottom: 0.5rem !important;
        }

        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
            }

            .form-row>.col,
            .form-row>[class*="col-"] {
                margin-bottom: 10px;
            }
        }
    </style>

    <div class="dashboard-header">
        <div class="row align-items-center mb-3">
            <div class="col-12">
                <h1 class="hotel-title">Dashboard for {{ $hotel_name }}</h1>
            </div>
        </div>
        <div class="control-group">
            <div class="form-row">
                <div class="col-md-3 mb-2">
                    <select id="viewSelector" class="form-control">
                        <option value="category">Category</option>
                        <option value="department">Department</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <select id="dateFilter" class="form-control">
                        <option value="all_time">All Time</option>
                        <optgroup label="Daily">
                            <option value="today">Today</option>
                            <option value="yesterday">Yesterday</option>
                        </optgroup>
                        <optgroup label="Weekly">
                            <option value="this_week">This week</option>
                            <option value="last_week">Last week</option>
                        </optgroup>
                        <optgroup label="Monthly">
                            <option value="this_month">This month</option>
                            <option value="last_month">Last month</option>
                            <option value="last_30_days">Last 30 days</option>
                        </optgroup>
                        <optgroup label="Quarterly">
                            <option value="this_quarter">This quarter</option>
                            <option value="last_quarter">Last quarter</option>
                        </optgroup>
                        <optgroup label="Yearly">
                            <option value="this_year">This year</option>
                            <option value="last_year">Last year</option>
                            <option value="last_365_days">Last 365 days</option>
                        </optgroup>
                        <optgroup label="Custom">
                            <option value="custom">Custom date range</option>
                        </optgroup>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <button id="compareBtn" class="btn btn-primary btn-block">Compare</button>
                </div>
            </div>
            <div id="customDateRange" class="form-row mt-2" style="display: none;">
                <div class="col-md-3 mb-2">
                    <input type="date" id="startDate" class="form-control" placeholder="Start Date">
                </div>
                <div class="col-md-3 mb-2">
                    <input type="date" id="endDate" class="form-control" placeholder="End Date">
                </div>
            </div>
            <div id="compareDateRange" class="form-row mt-2" style="display: none;">
                <div class="col-md-3 mb-2">
                    <select id="compareDateFilter" class="form-control">
                        <option value="all_time">All Time</option>
                        <optgroup label="Daily">
                            <option value="today">Today</option>
                            <option value="yesterday">Yesterday</option>
                        </optgroup>
                        <optgroup label="Weekly">
                            <option value="this_week">This week</option>
                            <option value="last_week">Last week</option>
                        </optgroup>
                        <optgroup label="Monthly">
                            <option value="this_month">This month</option>
                            <option value="last_month">Last month</option>
                            <option value="last_30_days">Last 30 days</option>
                        </optgroup>
                        <optgroup label="Quarterly">
                            <option value="this_quarter">This quarter</option>
                            <option value="last_quarter">Last quarter</option>
                        </optgroup>
                        <optgroup label="Yearly">
                            <option value="this_year">This year</option>
                            <option value="last_year">Last year</option>
                            <option value="last_365_days">Last 365 days</option>
                        </optgroup>
                        <optgroup label="Custom">
                            <option value="custom">Custom date range</option>
                        </optgroup>
                    </select>
                </div>
                <div id="compareCustomDateRange" class="col-md-6" style="display: none;">
                    <div class="form-row">
                        <div class="col-md-6 mb-2">
                            <input type="date" id="compareStartDate" class="form-control" placeholder="Start Date">
                        </div>
                        <div class="col-md-6 mb-2">
                            <input type="date" id="compareEndDate" class="form-control" placeholder="End Date">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-center">Comparison Chart</h2>
                <div class="chart-container" style="height: 400px; width: 100%;">
                    <canvas id='mainChart'></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4"><span id="reportTitle"></span> Reports</h3>
                    <div id="reportCards" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        var categoryData = @json($category_data);
        var departmentData = @json($department_data);
        var currentView = 'category';
        var compareMode = false;
        var mainChart, pieChart;

        function showDetailTable(label) {
            const hotel = '{{ $hotel_code }}';
            const dateFilter = document.getElementById('dateFilter').value;
            let url =
                `/dashboards/issue-preview?type=${currentView}&label=${encodeURIComponent(label)}&hotel=${hotel}&dateFilter=${dateFilter}`;

            if (dateFilter === 'custom') {
                const startDate = document.getElementById('startDate').value;
                const endDate = document.getElementById('endDate').value;
                url += `&start=${startDate}&end=${endDate}`;
            }

            if (!compareMode) {
                window.location.href = url;
            } else {
                fetch(`/api/issues/${currentView}/${label}?hotel=${hotel}&dateFilter=${dateFilter}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                    })
                    .catch(error => console.error('Error:', error));
            }
        }

        function updateCharts(data, compareData = null) {
            updateMainChart(data, compareData);
            updateReportCards(data, compareData);
        }

        function updateMainChart(data, compareData = null) {
            if (mainChart) {
                mainChart.destroy();
            }

            var ctx = document.getElementById('mainChart').getContext('2d');
            var datasets = [{
                type: 'bar',
                label: currentView.charAt(0).toUpperCase() + currentView.slice(1),
                data: data.data,
                backgroundColor: data.backgroundColor
            }];

            if (compareData) {
                datasets.push({
                    type: 'line',
                    label: 'Compare ' + currentView,
                    data: compareData.data,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2,
                    fill: false
                });
            }

            mainChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    onClick: (event, elements) => {
                        if (elements.length > 0) {
                            const index = elements[0].index;
                            showDetailTable(data.labels[index]);
                        }
                    }
                }
            });
        }

        function updateReportCards(data, compareData = null) {
            var reportCards = document.getElementById('reportCards');
            reportCards.innerHTML = '';

            data.labels.forEach((label, index) => {
                var card = document.createElement('div');
                card.className = 'col';
                var compareHtml = '';
                if (compareData) {
                    var diff = data.data[index] - compareData.data[index];
                    var diffClass = diff >= 0 ? 'text-success' : 'text-danger';
                    var diffSymbol = diff >= 0 ? '▲' : '▼';
                    compareHtml = `
                        <p class="card-text ${diffClass}">
                            ${diffSymbol} ${Math.abs(diff)} (${compareData.data[index]} in compare period)
                        </p>
                    `;
                }
                card.innerHTML = `
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">${label}</h5>
                            <p class="card-text">
                                <span class="fs-4 fw-bold text-primary">${data.data[index]}</span>
                                reports
                            </p>
                            ${compareHtml}
                        </div>
                    </div>
                `;
                reportCards.appendChild(card);
            });

            document.getElementById('reportTitle').textContent = currentView.charAt(0).toUpperCase() + currentView.slice(1);

        }

        function updateChartsWithDateFilter() {
            const filterType = document.getElementById('dateFilter').value;
            const compareFilterType = document.getElementById('compareDateFilter').value;

            let url = `/api/hotel-data/${currentView}/${filterType}?hotel={{ $hotel_code }}`;

            if (filterType === 'custom') {
                const startDate = document.getElementById('startDate').value;
                const endDate = document.getElementById('endDate').value;
                if (startDate && endDate) {
                    url += `&start=${startDate}&end=${endDate}`;
                } else {
                    console.error('Please select both start and end dates for custom range');
                    return;
                }
            }

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (compareMode) {
                        let compareUrl =
                            `/api/hotel-data/${currentView}/${compareFilterType}?hotel={{ $hotel_code }}`;
                        if (compareFilterType === 'custom') {
                            const compareStartDate = document.getElementById('compareStartDate').value;
                            const compareEndDate = document.getElementById('compareEndDate').value;
                            if (compareStartDate && compareEndDate) {
                                compareUrl += `&start=${compareStartDate}&end=${compareEndDate}`;
                            } else {
                                console.error('Please select both start and end dates for compare custom range');
                                return;
                            }
                        }
                        fetch(compareUrl)
                            .then(response => response.json())
                            .then(compareData => {
                                updateCharts(data, compareData);
                            });
                    } else {
                        updateCharts(data);
                    }
                });
        }

        document.getElementById('viewSelector').addEventListener('change', function() {
            currentView = this.value;
            updateChartsWithDateFilter();
        });

        document.getElementById('dateFilter').addEventListener('change', function() {
            if (this.value === 'custom') {
                document.getElementById('customDateRange').style.display = 'flex';
            } else {
                document.getElementById('customDateRange').style.display = 'none';
            }
            updateChartsWithDateFilter();
        });

        document.getElementById('compareDateFilter').addEventListener('change', function() {
            if (this.value === 'custom') {
                document.getElementById('compareCustomDateRange').style.display = 'flex';
            } else {
                document.getElementById('compareCustomDateRange').style.display = 'none';
            }
            updateChartsWithDateFilter();
        });

        document.getElementById('compareBtn').addEventListener('click', function() {
            compareMode = !compareMode;
            if (compareMode) {
                this.textContent = 'Cancel Compare';
                document.getElementById('compareDateRange').style.display = 'flex';
            } else {
                this.textContent = 'Compare';
                document.getElementById('compareDateRange').style.display = 'none';
                updateChartsWithDateFilter();
            }
        });

        document.getElementById('startDate').addEventListener('change', updateChartsWithDateFilter);
        document.getElementById('endDate').addEventListener('change', updateChartsWithDateFilter);
        document.getElementById('compareStartDate').addEventListener('change', updateChartsWithDateFilter);
        document.getElementById('compareEndDate').addEventListener('change', updateChartsWithDateFilter);

        // Initial chart render
        updateCharts(categoryData);
    </script>

@endsection
