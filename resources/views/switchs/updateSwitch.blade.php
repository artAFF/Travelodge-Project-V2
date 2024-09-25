@extends('layouts.app')
@section('title', 'Update Switch Room Check')

@section('content')
    <div class="container">
        <form action="{{ route('updatePSwitch', ['type' => $type, 'id' => $ReportSwitchs->id]) }}" method="POST">
            @csrf

            <div class="form-section">
                <h2>Switch Checking</h2>

                @php
                    $isOthers = in_array($ReportSwitchs->location, [
                        'Floor 1',
                        'Floor 2',
                        'Floor 3',
                        'Floor 4',
                        'Floor 5',
                        'Floor 6',
                        'Floor 7',
                        'Floor 8',
                        'Floor 9',
                    ]);
                @endphp

                <div class="form-group mb-3" id="form1" {!! $isOthers ? 'style="display:none;"' : '' !!}>
                    <label for="location" class="form-label">Location</label>
                    <select class="form-select" id="location" name="location">
                        @foreach ($buildings as $building)
                            <option value="{{ $building->name }}"
                                {{ $ReportSwitchs->location == $building->name ? 'selected' : '' }}>
                                {{ $building->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3" id="form2" {!! !$isOthers ? 'style="display:none;"' : '' !!}>
                    <label for="location2" class="form-label">Location</label>
                    <select class="form-select" id="location2" name="location">
                        @foreach (['Floor 1', 'Floor 2', 'Floor 3', 'Floor 4', 'Floor 5', 'Floor 6', 'Floor 7', 'Floor 8', 'Floor 9'] as $floor)
                            <option value="{{ $floor }}" {{ $ReportSwitchs->location == $floor ? 'selected' : '' }}>
                                {{ $floor }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="" id="othersCheckbox"
                        {{ $isOthers ? 'checked' : '' }}>
                    <label class="form-check-label" for="othersCheckbox">
                        Others ( Only EHCM and UNCM )
                    </label>
                </div>
            </div>

            <div class="form-section">
                <h2>Switch Room Information</h2>

                <div class="form-floating mb-3">
                    <input type="number" step="0.01" class="form-control" id="ups_battery" name="ups_battery"
                        placeholder="UPS Battery Percentage" value="{{ $ReportSwitchs->ups_battery }}">
                    <label for="ups_battery">UPS Battery Percentage (%)</label>
                </div>
                @error('ups_battery')
                    <div class="text-danger">{{ $message }}</div>
                @enderror

                <div class="form-floating mb-3">
                    <input type="number" step="0.01" class="form-control" id="ups_time" name="ups_time"
                        placeholder="UPS Period Of Service Available" value="{{ $ReportSwitchs->ups_time }}">
                    <label for="ups_time">UPS Period Of Service Available (minutes)</label>
                </div>
                @error('ups_time')
                    <div class="text-danger">{{ $message }}</div>
                @enderror

                <div class="form-floating mb-3">
                    <input type="number" step="0.01" class="form-control" id="ups_temp" name="ups_temp"
                        placeholder="Switch Temperature" value="{{ $ReportSwitchs->ups_temp }}">
                    <label for="ups_temp">Switch Temperature (°C)</label>
                </div>
                @error('ups_temp')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="/daily/hotels/{{ $type }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <style>
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .form-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .form-section h2 {
            margin-bottom: 20px;
            color: #007bff;
        }

        .form-group label {
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .form-select {
            height: calc(3.5rem + 2px);
            padding: 0.5rem 0.75rem;
        }

        .form-floating {
            position: relative;
        }

        .form-floating>.form-control {
            height: calc(3.5rem + 2px);
            padding: 1rem 0.75rem;
        }

        .form-floating>label {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            padding: 1rem 0.75rem;
            pointer-events: none;
            border: 1px solid transparent;
            transform-origin: 0 0;
            transition: opacity .1s ease-in-out, transform .1s ease-in-out;
        }

        .form-floating>.form-control:focus~label,
        .form-floating>.form-control:not(:placeholder-shown)~label {
            opacity: .65;
            transform: scale(.85) translateY(-0.5rem) translateX(0.15rem);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
        }

        .form-actions {
            text-align: right;
        }

        .btn {
            padding: 10px 20px;
            font-size: 16px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var othersCheckbox = document.getElementById('othersCheckbox');
            var form1 = document.getElementById('form1');
            var form2 = document.getElementById('form2');
            var locationSelect1 = document.getElementById('location');
            var locationSelect2 = document.getElementById('location2');

            function toggleForms() {
                if (othersCheckbox.checked) {
                    form1.style.display = 'none';
                    form2.style.display = 'block';
                    locationSelect1.name = '';
                    locationSelect2.name = 'location';
                } else {
                    form1.style.display = 'block';
                    form2.style.display = 'none';
                    locationSelect1.name = 'location';
                    locationSelect2.name = '';
                }
            }

            othersCheckbox.addEventListener('change', toggleForms);

            toggleForms();
        });
    </script>
@endsection
