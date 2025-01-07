@extends('layouts.app')
@section('title', 'Budget Settings')

@section('content')
    <div class="container">
        <h2>Hotel Budget Settings</h2>

        <form action="{{ route('structure.budget.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <label>Hotel</label>
                    <select name="hotel_id" class="form-select">
                        @foreach ($hotels as $hotel)
                            <option value="{{ $hotel->id }}">{{ $hotel->hotel_code }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label>Month</label>
                    <input type="month" name="month" class="form-control">
                </div>

                <div class="col-md-4">
                    <label>Budget Amount</label>
                    <input type="number" step="0.01" name="budget_amount" class="form-control">
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Save Budget</button>
        </form>

        <hr>

        <h3>Current Budget Settings</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Hotel</th>
                    <th>Month</th>
                    <th>Budget Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($budgetSettings as $setting)
                    <tr>
                        <td>{{ $setting->hotel->hotel_code }}</td>
                        <td>{{ \Carbon\Carbon::parse($setting->month)->format('F Y') }}</td>
                        <td>{{ number_format($setting->budget_amount, 2) }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning">Edit</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
