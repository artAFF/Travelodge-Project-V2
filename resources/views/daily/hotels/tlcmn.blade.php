@extends('layouts.app')
@section('title', 'Daily Report TLCMN')

@section('content')
    <h1 class="text-center my-4">Daily Report for {{ strtoupper($source) }}</h1>

    <div id="report-content">
        @include('partials.report_guests')
        @include('partials.report_switchs')
        @include('partials.report_servers')
        @include('partials.report_netspeeds')
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
    </script>
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
