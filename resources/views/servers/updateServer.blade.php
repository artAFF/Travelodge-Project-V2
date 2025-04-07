@extends('layouts.app')
@section('title', 'Update Server Room Check')

@section('content')
    <div class="container">
        <form action="{{ route('updatePserver', ['type' => $type, 'id' => $ReportServers->id]) }}" method="POST"
            onsubmit="return validateForm()">
            @csrf

            <div class="form-section">
                <h2>Server Room Information</h2>

                <div class="form-floating mb-3">
                    <input type="number" class="form-control" id="server_temp" name="server_temp"
                        placeholder="Server Temperature" value="{{ $ReportServers->server_temp }}" min="0"
                        max="100" oninput="validateInput(this, 100, 'server_temp_error')">
                    <label for="server_temp">Server Temperature (°C)</label>
                    <div id="server_temp_error" class="text-danger" style="display:none;">Server temperature must not exceed
                        100°C</div>
                </div>
                @error('server_temp')
                    <div class="text-danger">{{ $message }}</div>
                @enderror

                <div class="form-floating mb-3">
                    <input type="number" step="0.01" class="form-control" id="ups_temp" name="ups_temp"
                        placeholder="UPS Temperature" value="{{ $ReportServers->ups_temp }}" min="0" max="100"
                        oninput="validateInput(this, 100, 'ups_temp_error')">
                    <label for="ups_temp">UPS Temperature (°C)</label>
                    <div id="ups_temp_error" class="text-danger" style="display:none;">UPS temperature must not exceed 100°C
                    </div>
                </div>
                @error('ups_temp')
                    <div class="text-danger">{{ $message }}</div>
                @enderror

                <div class="form-floating mb-3">
                    <input type="number" step="0.01" class="form-control" id="ups_battery" name="ups_battery"
                        placeholder="UPS Battery Percentage" value="{{ $ReportServers->ups_battery }}" min="0"
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
                        placeholder="UPS Period Of Service Available" value="{{ $ReportServers->ups_time }}" min="0"
                        max="600" oninput="validateInput(this, 600, 'ups_time_error')">
                    <label for="ups_time">UPS Period Of Service Available (minutes)</label>
                    <div id="ups_time_error" class="text-danger" style="display:none;">UPS time must not exceed 600 minutes
                    </div>
                </div>
                @error('ups_time')
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

        .form-control:focus {
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
            const serverTemp = parseFloat(document.getElementById('server_temp').value);
            const upsTemp = parseFloat(document.getElementById('ups_temp').value);
            const upsBattery = parseFloat(document.getElementById('ups_battery').value);
            const upsTime = parseFloat(document.getElementById('ups_time').value);

            let isValid = true;

            if (serverTemp > 100) {
                document.getElementById('server_temp_error').style.display = 'block';
                document.getElementById('server_temp').classList.add('is-invalid');
                isValid = false;
            }

            if (upsTemp > 100) {
                document.getElementById('ups_temp_error').style.display = 'block';
                document.getElementById('ups_temp').classList.add('is-invalid');
                isValid = false;
            }

            if (upsBattery > 100) {
                document.getElementById('ups_battery_error').style.display = 'block';
                document.getElementById('ups_battery').classList.add('is-invalid');
                isValid = false;
            }

            if (upsTime > 600) {
                document.getElementById('ups_time_error').style.display = 'block';
                document.getElementById('ups_time').classList.add('is-invalid');
                isValid = false;
            }

            return isValid;
        }

        document.addEventListener('DOMContentLoaded', function() {
            validateInput(document.getElementById('server_temp'), 100, 'server_temp_error');
            validateInput(document.getElementById('ups_temp'), 100, 'ups_temp_error');
            validateInput(document.getElementById('ups_battery'), 100, 'ups_battery_error');
            validateInput(document.getElementById('ups_time'), 600, 'ups_time_error');
        });
    </script>
@endsection
