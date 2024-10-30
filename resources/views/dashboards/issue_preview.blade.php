@extends('layouts.app')
@section('title', 'Issue Preview')
@section('content')
    <style>
        .table th,
        .table td {
            transition: all 0.3s ease;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.1);
        }

        .btn-outline-primary {
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0, 123, 255, 0.2);
        }
    </style>

    <div class="container mt-1">
        <h3 id="previewTitle" class="text-center mb-4"></h3>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Category</th>
                        <th class="text-center">Detail</th>
                        <th class="text-center">Remarks</th>
                        <th class="text-center">Department</th>
                        <th class="text-center">Hotel</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Assignee</th>
                        <th class="text-center">Created At</th>
                        <th class="text-center">Updated At</th>
                    </tr>
                </thead>
                <tbody id="previewTableBody">
                </tbody>
            </table>
        </div>
        <div id="pagination" class="d-flex justify-content-center mt-4"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        let currentPage = 1;

        function loadIssues(page = 1) {
            const urlParams = new URLSearchParams(window.location.search);
            const type = urlParams.get('type');
            const label = urlParams.get('label');
            const hotel = urlParams.get('hotel');
            const dateFilter = urlParams.get('dateFilter');
            const startDate = urlParams.get('start');
            const endDate = urlParams.get('end');

            let apiUrl = `/api/issues/${type}/${label}?hotel=${encodeURIComponent(hotel)}&page=${page}`;

            if (dateFilter && dateFilter !== 'all_time') {
                apiUrl += `&dateFilter=${dateFilter}`;
            } else if (startDate && endDate) {
                apiUrl += `&start=${startDate}&end=${endDate}`;
            }

            $.ajax({
                url: apiUrl,
                method: 'GET',
                success: function(response) {
                    console.log('API Response:', response);
                    updateTable(response.data);
                    updatePagination(response);
                    updateTitle(label, hotel);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error fetching data:', textStatus, errorThrown);
                    console.log('Response Text:', jqXHR.responseText);
                    $('#previewTitle').text('Error loading data');
                }
            });
        }

        function updateTable(issues) {
            const tableBody = $('#previewTableBody');
            tableBody.empty();

            issues.forEach((issue) => {
                const row = $(`
                    <tr>
                        <td class="text-center">${issue.id}</td>
                        <td class="text-center">${issue.category?.name || 'N/A'}</td>
                        <td class="text-center">${issue.detail || 'N/A'}</td>
                        <td class="text-center">${issue.remarks || 'N/A'}</td>
                        <td class="text-center">${issue.department?.name || 'N/A'}</td>
                        <td class="text-center">${issue.hotel?.code || 'N/A'}</td>
                        <td class="text-center">${issue.status === 0 ? 'In Process' : 'Done'}</td>
                        <td class="text-center">${issue.assignee?.name || 'N/A'}</td>
                        <td class="text-center">${formatDate(issue.created_at)}</td>
                        <td class="text-center">${formatDate(issue.updated_at)}</td>
                    </tr>
                `);
                tableBody.append(row);
            });
        }

        function updatePagination(response) {
            const pagination = $('#pagination');
            pagination.empty();

            if (response.last_page > 1) {
                if (response.current_page > 1) {
                    pagination.append(`
                        <button class="btn btn-sm btn-outline-primary m-1" onclick="loadIssues(${response.current_page - 1})">
                            Previous
                        </button>
                    `);
                }

                let startPage = Math.max(1, response.current_page - 2);
                let endPage = Math.min(response.last_page, response.current_page + 2);

                for (let i = startPage; i <= endPage; i++) {
                    pagination.append(`
                        <button class="btn btn-sm btn-outline-primary m-1 ${i === response.current_page ? 'active' : ''}"
                                onclick="loadIssues(${i})">
                            ${i}
                        </button>
                    `);
                }

                if (response.current_page < response.last_page) {
                    pagination.append(`
                        <button class="btn btn-sm btn-outline-primary m-1" onclick="loadIssues(${response.current_page + 1})">
                            Next
                        </button>
                    `);
                }
            }
        }

        function updateTitle(label, hotel) {
            $.ajax({
                url: `/api/hotel-name/${hotel}`,
                method: 'GET',
                success: function(response) {
                    $('#previewTitle').text(`${label} Issues for ${response.hotel_full_name}`);
                },
                error: function(error) {
                    console.error('Error fetching hotel name:', error);
                    $('#previewTitle').text(`${label} Issues for ${hotel}`);
                }
            });
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            const options = {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };
            return date.toLocaleString('en-GB', options).replace(',', '');
        }

        $(document).ready(function() {
            loadIssues();
        });
    </script>
@endsection
