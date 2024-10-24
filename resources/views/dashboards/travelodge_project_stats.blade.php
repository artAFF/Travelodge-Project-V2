@extends('layouts.app')

@section('title', 'Travelodge Project')

@section('content')
    <div class="container">
        <h1>Travelodge Project Dashboard</h1>
        <div class="row">
            <div class="col-md-6">
                <canvas id="travelodgeWeeklyChart"></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="travelodgeMonthlyChart"></canvas>
            </div>
            <div class="col-md-6 mt-4">
                <canvas id="travelodgeDepartmentChart"></canvas>
            </div>
            <div class="col-md-6 mt-4">
                <canvas id="travelodgeCategoryChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const charts = [{
                    id: 'travelodgeWeeklyChart',
                    data: @json($weeklyData)
                },
                {
                    id: 'travelodgeMonthlyChart',
                    data: @json($monthlyData)
                },
                {
                    id: 'travelodgeDepartmentChart',
                    data: @json($departmentData)
                },
                {
                    id: 'travelodgeCategoryChart',
                    data: @json($categoryData)
                }
            ];

            charts.forEach(chart => {
                const ctx = document.getElementById(chart.id).getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: chart.data,
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                                text: chart.data.datasets[0].label
                            }
                        }
                    }
                });
            });
        });
    </script>
@endsection
