@extends('layouts.app')
@section('title', 'Update Switch Room Check')

@section('content')
    <div class="container">
        <form action="{{ route('updatePSwitch', ['type' => $type, 'id' => $ReportSwitchs->id]) }}" method="POST">
            @csrf

            <div class="form-section">
                <h2>Switch Checking</h2>

                <input type="hidden" id="location_id" name="location_id" value="{{ $ReportSwitchs->location_id }}">

                <div class="form-group mb-3">
                    <label for="location_type">Location Type</label>
                    <select class="form-select" id="location_type" name="location_type">
                        <option value="building" {{ $ReportSwitchs->location->type == 'building' ? 'selected' : '' }}>
                            Building
                        </option>
                        <option value="floor" {{ $ReportSwitchs->location->type == 'floor' ? 'selected' : '' }}>
                            Floor
                        </option>
                    </select>
                </div>

                <div class="form-group mb-3" id="buildingForm"
                    {{ $ReportSwitchs->location->type != 'building' ? 'style=display:none;' : '' }}>
                    <label for="building_location">Building</label>
                    <select class="form-select" id="building_location" name="building_location">
                        @foreach ($buildings as $building)
                            <option value="{{ $building->id }}" data-name="{{ $building->name }}"
                                {{ $ReportSwitchs->location_id == $building->id ? 'selected' : '' }}>
                                {{ $building->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3" id="floorForm"
                    {{ $ReportSwitchs->location->type != 'floor' ? 'style=display:none;' : '' }}>
                    <label for="floor_location">Floor</label>
                    <select class="form-select" id="floor_location" name="floor_location">
                        @foreach ($floors as $floor)
                            <option value="{{ $floor->id }}" data-name="{{ $floor->name }}"
                                {{ $ReportSwitchs->location_id == $floor->id ? 'selected' : '' }}>
                                {{ $floor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-section">
                <h2>Switch Room Information</h2>

                <div class="form-floating mb-3">
                    <input type="number" step="0.01" class="form-control" id="ups_battery" name="ups_battery"
                        placeholder="UPS Battery Percentage" value="{{ $ReportSwitchs->ups_battery }}" min="0"
                        max="100" oninput="validateInput(this, 100, 'ups_battery_error')">
                    <label for="ups_battery">UPS Battery Percentage (%)</label>
                    <div id="ups_battery_error" class="text-danger" style="display:none;">UPS battery percentage must not
                        exceed 100%</div>
                </div>
                @error('ups_battery')
                    <div class="text-danger">{{ $message }}</div>
                @enderror

                <div class="form-floating mb-3">
                    <input type="number" step="0.01" class="form-control" id="ups_time" name="ups_time"
                        placeholder="UPS Period Of Service Available" value="{{ $ReportSwitchs->ups_time }}" min="0"
                        max="300" oninput="validateInput(this, 300, 'ups_time_error')">
                    <label for="ups_time">UPS Period Of Service Available (minutes)</label>
                    <div id="ups_time_error" class="text-danger" style="display:none;">UPS time must not exceed 300 minutes
                    </div>
                </div>
                @error('ups_time')
                    <div class="text-danger">{{ $message }}</div>
                @enderror

                <div class="form-floating mb-3">
                    <input type="number" step="0.01" class="form-control" id="ups_temp" name="ups_temp"
                        placeholder="Switch Temperature" value="{{ $ReportSwitchs->ups_temp }}" min="0"
                        max="100" oninput="validateInput(this, 100, 'ups_temp_error')">
                    <label for="ups_temp">Switch Temperature (°C)</label>
                    <div id="ups_temp_error" class="text-danger" style="display:none;">Switch temperature must not exceed
                        100°C</div>
                </div>
                @error('ups_temp')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
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
            var locationType = document.getElementById('location_type');
            var buildingForm = document.getElementById('buildingForm');
            var floorForm = document.getElementById('floorForm');
            var locationIdInput = document.getElementById('location_id');

            function updateLocation() {
                buildingForm.style.display = 'none';
                floorForm.style.display = 'none';

                var selectedOption;
                if (locationType.value === 'building') {
                    buildingForm.style.display = 'block';
                    selectedOption = document.getElementById('building_location').selectedOptions[0];
                } else {
                    floorForm.style.display = 'block';
                    selectedOption = document.getElementById('floor_location').selectedOptions[0];
                }
                locationIdInput.value = selectedOption.value;
            }

            locationType.addEventListener('change', updateLocation);
            document.getElementById('building_location').addEventListener('change', updateLocation);
            document.getElementById('floor_location').addEventListener('change', updateLocation);

            // Set initial state based on database value
            updateLocation();
        });

        function validateInput(input, maxValue, errorId) {
            const value = parseFloat(input.value);
            const errorDiv = document.getElementById(errorId);

            if (value > maxValue) {
                errorDiv.style.display = 'block';
                input.classList.add('is-invalid');
            } else {
                errorDiv.style.display = 'none';
                input.classList.remove('is-invalid');
            }
        }

        function validateForm() {
            const upsBattery = parseFloat(document.getElementById('ups_battery').value);
            const upsTime = parseFloat(document.getElementById('ups_time').value);
            const upsTemp = parseFloat(document.getElementById('ups_temp').value);

            let isValid = true;

            if (upsBattery > 100) {
                document.getElementById('ups_battery_error').style.display = 'block';
                document.getElementById('ups_battery').classList.add('is-invalid');
                isValid = false;
            }

            if (upsTime > 300) {
                document.getElementById('ups_time_error').style.display = 'block';
                document.getElementById('ups_time').classList.add('is-invalid');
                isValid = false;
            }

            if (upsTemp > 100) {
                document.getElementById('ups_temp_error').style.display = 'block';
                document.getElementById('ups_temp').classList.add('is-invalid');
                isValid = false;
            }

            return isValid;
        }
    </script>
@endsection
