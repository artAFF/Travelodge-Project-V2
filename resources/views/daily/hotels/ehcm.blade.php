@extends('layouts.app')
@section('title', 'Daily Report EHCM')

@section('content')
    <h1 class="text-center my-4">Daily Report for {{ strtoupper($source) }}</h1>

    <div id="report-content">
        @include('partials.report_guests')
        @include('partials.report_switchs')
        @include('partials.report_servers')
        @include('partials.report_netspeeds')
    </div>
    <style>
        .card {
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .card:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
        }

        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }
    </style>
@endsection
