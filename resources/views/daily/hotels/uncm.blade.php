@extends('layouts.app')
@section('title', 'Daily Report UNCM')

@section('content')
    <div class="container animate-fade-in">
        <h1 class="text-center my-4">Daily Report for {{ strtoupper($source) }}</h1>

        <div id="report-content">
            @include('partials.report_guests')
            @include('partials.report_switchs')
            @include('partials.report_servers')
            @include('partials.report_netspeeds')
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        function loadPage(url) {
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#report-content').fadeOut(300, function() {
                        $(this).empty().html(
                            data.ReportGuests +
                            data.ReportSwitchs +
                            data.ReportServers +
                            data.ReportNetSpeeds
                        ).fadeIn(300);
                        initializeAnimations();
                    });
                }
            });
        }

        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            let url = $(this).attr('href');
            if (url.indexOf('type=') === -1) {
                url += (url.indexOf('?') === -1 ? '?' : '&') + 'type={{ $source }}';
            }
            loadPage(url);
        });

        function initializeAnimations() {
            $('.animate-slide-up').each(function(index) {
                $(this).css({
                    'animation-delay': `${index * 0.1}s`
                });
            });
        }

        $(document).ready(function() {
            initializeAnimations();
        });
    </script>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        .animate-slide-up {
            animation: slideUp 0.5s ease-out forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

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
