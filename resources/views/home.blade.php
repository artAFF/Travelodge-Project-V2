@extends('layouts.app')
@section('title', 'Dashboard Hotel')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

    <h1 class="text-center">Issue by Hotel</h1>

    <div class="row">
        <div class="col-md-6">
            <h2 class="text-center">Bar Chart</h2>
            <div style="height: 400px; width: 100%;" class="chart-container">
                <canvas id='barChart'></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <h2 class="text-center">Pie Chart</h2>
            <div style="height: 400px; width: 100%;" class="chart-container">
                <canvas id='pieChart'></canvas>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        @foreach ($sortedLatestReports as $hotel => $reports)
            <div class="col-md-4" style="animation-delay: {{ $loop->index * 0.1 }}s">
                <div class="card hotel-card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">{{ $hotel }} - Latest 5 Reports</h5>
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach ($reports as $index => $report)
                            <li class="list-group-item report-item">
                                <div class="report-content">
                                    <strong>ID:</strong> {{ $report->id }} <br>
                                    <strong>Detail:</strong> {{ $report->detail }} <br>
                                    <strong>Created at:</strong> {{ $report->created_at }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    </div>

    <style>
        .chart-container {
            transition: transform 0.3s ease;
        }

        .chart-container:hover {
            transform: scale(1.05);
        }

        .hotel-card {
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .hotel-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .report-item {
            background-color: #f8f9fa;
            border-left: 5px solid transparent;
        }

        .report-item:nth-child(odd) {
            background-color: #ffffff;
        }

        .report-item:hover {
            background-color: #e9ecef;
            border-left-color: #007bff;
        }

        .report-content {
            padding: 10px;
        }
    </style>

    <script>
        var barCtx = document.getElementById('barChart').getContext('2d');
        var pieCtx = document.getElementById('pieChart').getContext('2d');

        var chartData = {
            labels: <?php echo json_encode($sortedHotels); ?>,
            datasets: <?php echo json_encode($datasets); ?>
        };

        var chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
        };

        var barChart = new Chart(barCtx, {
            type: 'bar',
            data: chartData,
            options: {
                ...chartOptions,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed.y;
                            }
                        }
                    }
                }
            }
        });

        var pieChart = new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($sortedHotels); ?>,
                datasets: [{
                    data: <?php echo json_encode($percentages); ?>,
                    backgroundColor: <?php echo json_encode($datasets[0]['backgroundColor']); ?>
                }]
            },
            options: {
                ...chartOptions,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed + '%';
                            }
                        }
                    }
                }
            }
        });

        // Click event handlers for both charts
        barChart.options.onClick = pieChart.options.onClick = function(event, elements) {
            if (elements.length > 0) {
                var hotelCode = chartData.labels[elements[0].index];
                window.location.href = "{{ url('/dashboard') }}/" + hotelCode;
            }
        };
    </script>

@endsection
