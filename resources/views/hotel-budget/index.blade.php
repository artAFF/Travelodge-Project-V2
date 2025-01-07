@extends('layouts.app')
@section('title', 'Hotel Budget')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Hotel Budget</h2>

        <div class="row mb-4">
            <div class="col-md-4">
                <select id="hotelSelect" class="form-select">
                    <option value="">Select Hotel</option>
                    @foreach ($hotels as $hotel)
                        <option value="{{ $hotel->hotel_code }}">{{ $hotel->hotel_full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <input type="month" id="monthSelect" class="form-control">
            </div>
        </div>

        <div class="budget-summary">
            <div class="summary-item">Total Budget: <span id="totalBudget">-</span> Baht</div>
            <div class="summary-item">Total Expense: <span id="totalExpense">-</span> Baht</div>
            <div class="summary-item">Remaining Budget : <span id="remainingBudget">-</span> Baht</div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Amount (Baht)</th>
                    </tr>
                </thead>
                <tbody id="expenseTable">
                    <tr>
                        <td colspan="3" class="text-center">Select Hotel</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <style>
        .budget-summary {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .summary-item {
            font-size: 1.1em;
            margin-bottom: 10px;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function loadBudgetData() {
                const hotelCode = $('#hotelSelect').val();
                const month = $('#monthSelect').val();

                if (!hotelCode) {
                    resetDisplay();
                    return;
                }

                $.get(`/hotel-budget/data/${hotelCode}/${month}`)
                    .done(function(response) {
                        $('#totalBudget').text(response.total_budget);
                        $('#totalExpense').text(response.total_expense);
                        $('#remainingBudget').text(response.remaining_budget);

                        let tableContent = '';
                        response.expenses.forEach((expense, index) => {
                            tableContent += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${expense.expense_name}</td>
                        <td class="text-end">${numberFormat(expense.expense_amount)}</td>
                    </tr>
                `;
                        });

                        $('#expenseTable').html(tableContent ||
                            '<tr><td colspan="3" class="text-center">No expenses found</td></tr>');
                    })
                    .fail(function() {
                        alert('Unable to fetch data. Please try again');
                        resetDisplay();
                    });
            }

            $('#hotelSelect, #monthSelect').change(loadBudgetData);

            function resetDisplay() {
                $('#totalBudget').text('-');
                $('#totalExpense').text('-');
                $('#remainingBudget').text('-');
                $('#expenseTable').html('<tr><td colspan="3" class="text-center">Please Select Hotel</td></tr>');
            }

            function numberFormat(number) {
                return new Intl.NumberFormat('th-TH', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(number);
            }
        });
    </script>
@endsection
