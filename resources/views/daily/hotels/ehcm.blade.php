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

        .pagination {
            margin: 0;
            padding: 0;
        }

        .pagination li {
            display: inline-block;
            margin: 0 2px;
        }

        .pagination li a,
        .pagination li span {
            padding: 6px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            color: #333;
            text-decoration: none;
        }

        .pagination li.active span {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        .pagination li a:hover {
            background-color: #f8f9fa;
        }

        .loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 5px;
            z-index: 1000;
        }
    </style>
    <script>
        $(document).ready(function() {

            function handlePagination(section) {
                $(document).on('click', `#${section} .pagination a`, function(e) {
                    e.preventDefault();
                    let page = $(this).attr('href').split('page=')[1];
                    loadSection(section, page);
                });
            }

            function loadSection(section, page) {
                $.ajax({
                    url: window.location.pathname,
                    data: {
                        [`${section.toLowerCase()}_page`]: page,
                        ajax: true
                    },
                    beforeSend: function() {

                        $(`#${section}`).append(
                            '<div class="text-center loading"><i class="fas fa-spinner fa-spin"></i> Loading...</div>'
                        );
                    },
                    success: function(response) {

                        $(`#${section}`).html(response[section]);


                        let newUrl = new URL(window.location.href);
                        newUrl.searchParams.set(`${section.toLowerCase()}_page`, page);
                        window.history.pushState({}, '', newUrl);


                        $('.loading').remove();
                    },
                    error: function() {
                        alert('Error loading data');
                        $('.loading').remove();
                    }
                });
            }


            handlePagination('ReportGuests');
            handlePagination('ReportNetSpeeds');
            handlePagination('ReportSwitchs');
            handlePagination('ReportServers');
        });
    </script>
@endsection
