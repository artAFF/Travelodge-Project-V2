@extends('layouts.app')
@section('title', 'Hotel Dashboard')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        #viewSelector,
        #dateFilter,
        #compareDateFilter {
            width: auto;
            min-width: 150px;
            max-width: 200px;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 4px;
            padding: 5px 10px;
            transition: all 0.3s ease;
        }

        #viewSelector:focus,
        #dateFilter:focus,
        #compareDateFilter:focus {
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
        }

        .form-group {
            margin-left: 20px;
        }

        .chart-container {
            transition: transform 0.3s ease;
        }

        .chart-container:hover {
            transform: scale(1.02);
        }

        .card {
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        @media (max-width: 768px) {
            .row>div {
                flex-direction: column;
            }

            #viewSelector,
            #dateFilter,
            #compareDateFilter,
            #customDateRange,
            #compareCustomDateRange {
                margin-top: 10px;
                margin-left: 0;
            }

            #customDateRange,
            #compareCustomDateRange {
                flex-direction: column;
            }

            #customDateRange input,
            #compareCustomDateRange input {
                margin-top: 10px;
            }
        }
    </style>

    <div class="container-fluid px-4">
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-center flex-wrap">
                <h1 class="animate__animated animate__fadeInDown">Dashboard for {{ $hotel_name }}</h1>
                <div class="d-flex animate__animated animate__fadeInRight">
                    <div class="form-group mb-0">
                        <select id="viewSelector" class="form-control">
                            <option value="category">Category</option>
                            <option value="department">Department</option>
                        </select>
                    </div>
                    <div class="form-group mb-0 ml-3">
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
                    <div id="customDateRange" style="display: none;">
                        <input type="date" id="startDate" class="form-control ml-3">
                        <input type="date" id="endDate" class="form-control ml-3">
                    </div>
                    <div class="form-group mb-0 ml-3">
                        <button id="compareBtn" class="btn btn-primary">Compare</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="compareDateRange" style="display: none;">
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
            <div id="compareCustomDateRange" style="display: none;">
                <input type="date" id="compareStartDate" class="form-control ml-3">
                <input type="date" id="compareEndDate" class="form-control ml-3">
            </div>
        </div>

        <div class="row">
            <div class="row">
                <div class="col-md-12 animate__animated animate__fadeInLeft">
                    <h2 class="text-center">Comparison Chart</h2>
                    <div class="chart-container" style="height: 400px; width: 100%;">
                        <canvas id='mainChart'></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12 animate__animated animate__fadeInUp">
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
            updatePieChart(data);
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
                    animation: {
                        duration: 1000,
                        easing: 'easeOutQuart'
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
                    animation: {
                        duration: 1000,
                        easing: 'easeOutQuart'
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

            anime({
                targets: '#reportCards .card',
                opacity: [0, 1],
                translateY: [50, 0],
                delay: anime.stagger(100),
                duration: 800,
                easing: 'easeOutQuad'
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
                updateChartsWithDateFilter();
            }
        });

        document.getElementById('compareDateFilter').addEventListener('change', function() {
            if (this.value === 'custom') {
                document.getElementById('compareCustomDateRange').style.display = 'flex';
            } else {
                document.getElementById('compareCustomDateRange').style.display = 'none';
                updateChartsWithDateFilter();
            }
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

        function updateChartsWithDateFilter() {
            const filterType = document.getElementById('dateFilter').value;
            const compareFilterType = document.getElementById('compareDateFilter').value;

            if (filterType === 'all_time' && !compareMode) {
                updateCharts(currentView === 'category' ? categoryData : departmentData);
            } else {
                let url = `/api/hotel-data/${currentView}/${filterType}?hotel={{ $hotel_code }}`;
                if (filterType === 'custom') {
                    const startDate = document.getElementById('startDate').value;
                    const endDate = document.getElementById('endDate').value;
                    url += `&start=${startDate}&end=${endDate}`;
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
                                compareUrl += `&start=${compareStartDate}&end=${compareEndDate}`;
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
        }

        // Initial chart render
        updateCharts(categoryData);
    </script>

@endsection
