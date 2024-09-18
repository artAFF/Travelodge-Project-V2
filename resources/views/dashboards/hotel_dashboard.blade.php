@extends('layouts.app')
@section('title', 'Hotel Dashboard')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        #viewSelector,
        #dateFilter {
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
        #dateFilter:focus {
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
            #customDateRange {
                margin-top: 10px;
                margin-left: 0;
            }

            #customDateRange {
                flex-direction: column;
            }

            #customDateRange input {
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
                            <option value="today">Today</option>
                            <option value="yesterday">Yesterday</option>
                            <option value="this_week">This week</option>
                            <option value="last_week">Last week</option>
                            <option value="this_month">This month</option>
                            <option value="last_month">Last month</option>
                            <option value="last_30_days">Last 30 days</option>
                            <option value="this_quarter">This quarter</option>
                            <option value="last_quarter">Last quarter</option>
                            <option value="this_year">This year</option>
                            <option value="last_year">Last year</option>
                            <option value="last_365_days">Last 365 days</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>
                    <div id="customDateRange" style="display: none;">
                        <input type="date" id="startDate" class="form-control ml-3">
                        <input type="date" id="endDate" class="form-control ml-3">
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 animate__animated animate__fadeInLeft">
                <h2 class="text-center">Bar Chart</h2>
                <div class="chart-container" style="height: 400px; width: 100%;">
                    <canvas id='barChart'></canvas>
                </div>
            </div>
            <div class="col-md-6 animate__animated animate__fadeInRight">
                <h2 class="text-center">Pie Chart</h2>
                <div class="chart-container" style="height: 400px; width: 100%;">
                    <canvas id='pieChart'></canvas>
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

        var barChart, pieChart;

        function updateCharts(data) {
            updateBarChart(data);
            updatePieChart(data);
            updateReportCards(data);
        }

        function updateBarChart(data) {
            if (barChart) {
                barChart.destroy();
            }

            var ctx = document.getElementById('barChart').getContext('2d');
            barChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: currentView.charAt(0).toUpperCase() + currentView.slice(1),
                        data: data.data,
                        backgroundColor: data.backgroundColor
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
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

        function updatePieChart(data) {
            if (pieChart) {
                pieChart.destroy();
            }

            var ctx = document.getElementById('pieChart').getContext('2d');
            pieChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: data.labels,
                    datasets: [{
                        data: data.data,
                        backgroundColor: data.backgroundColor
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
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

        function updateReportCards(data) {
            var reportCards = document.getElementById('reportCards');
            reportCards.innerHTML = '';

            data.labels.forEach((label, index) => {
                var card = document.createElement('div');
                card.className = 'col';
                card.innerHTML = `
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">${label}</h5>
                            <p class="card-text">
                                <span class="fs-4 fw-bold text-primary">${data.data[index]}</span>
                                reports
                            </p>
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

        function showDetailTable(label) {
            const hotelCode = '{{ $hotel_code }}';
            const dateFilter = document.getElementById('dateFilter').value;
            let dateParams = '';

            if (dateFilter === 'custom') {
                const startDate = document.getElementById('startDate').value;
                const endDate = document.getElementById('endDate').value;
                dateParams = `&start=${startDate}&end=${endDate}`;
            } else {
                dateParams = `&dateFilter=${dateFilter}`;
            }

            window.location.href =
                `/dashboards/issue-preview?type=${currentView}&label=${encodeURIComponent(label)}&hotel=${encodeURIComponent(hotelCode)}${dateParams}`;
        }

        document.getElementById('viewSelector').addEventListener('change', function() {
            currentView = this.value;
            updateChartsWithDateFilter(document.getElementById('dateFilter').value);
        });

        document.getElementById('dateFilter').addEventListener('change', function() {
            if (this.value === 'custom') {
                document.getElementById('customDateRange').style.display = 'flex';
            } else {
                document.getElementById('customDateRange').style.display = 'none';
                updateChartsWithDateFilter(this.value);
            }
        });

        document.getElementById('startDate').addEventListener('change', updateChartsWithCustomDate);
        document.getElementById('endDate').addEventListener('change', updateChartsWithCustomDate);

        function updateChartsWithDateFilter(filterType) {
            if (filterType === 'all_time') {
                updateCharts(currentView === 'category' ? categoryData : departmentData);
            } else {
                fetch(`/api/hotel-data/${currentView}/${filterType}?hotel={{ $hotel_code }}`)
                    .then(response => response.json())
                    .then(data => {
                        updateCharts(data);
                    });
            }
        }

        function updateChartsWithCustomDate() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            if (startDate && endDate) {
                fetch(`/api/hotel-data/${currentView}/custom?hotel={{ $hotel_code }}&start=${startDate}&end=${endDate}`)
                    .then(response => response.json())
                    .then(data => {
                        updateCharts(data);
                    });
            }
        }

        // Initial chart render
        updateCharts(categoryData);
    </script>

@endsection
