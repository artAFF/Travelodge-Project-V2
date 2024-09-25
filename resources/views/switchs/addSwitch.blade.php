@extends('layouts.app')
@section('title', 'Add Switch Room Check')

@section('content')
    <div class="container">
        <form action="/insertSwitch" method="POST">
            @csrf

            <div class="form-section">
                <h2>Switch Checking</h2>

                <div class="form-group mb-3">
                    <label for="hotel" class="form-label"><b>Hotel</b></label>
                    <select class="form-select" id="hotel" name="hotel">
                        <option value="tlcmn">TLCMN</option>
                        <option value="ehcm">EHCM</option>
                        <option value="uncm">UNCM</option>
                    </select>
                </div>

                <div class="form-group mb-3" id="form1">
                    <label for="location1" class="form-label">Location</label>
                    <select class="form-select" id="location1" name="location1">
                        @foreach ($buildings as $building)
                            <option value="{{ $building->name }}">{{ $building->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3 hidden" id="form2">
                    <label for="location2" class="form-label">Location</label>
                    <select class="form-select" id="location2" name="location2">
                        @for ($i = 1; $i <= 9; $i++)
                            <option value="Floor {{ $i }}">Floor {{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="" id="othersCheckbox">
                    <label class="form-check-label" for="othersCheckbox">
                        Others ( Only EHCM and UNCM )
                    </label>
                </div>

                <input type="hidden" id="location" name="location" value="">
            </div>

            <div class="form-section">
                <h2>Switch Room Information</h2>

                <div class="form-floating mb-3">
                    <input type="number" step="0.01" class="form-control" id="ups_battery" name="ups_battery"
                        placeholder="UPS Battery Percentage">
                    <label for="ups_battery">UPS Battery Percentage (%)</label>
                </div>
                @error('ups_battery')
                    <div class="text-danger">{{ $message }}</div>
                @enderror

                <div class="form-floating mb-3">
                    <input type="number" step="0.01" class="form-control" id="ups_time" name="ups_time"
                        placeholder="UPS Period Of Service Available">
                    <label for="ups_time">UPS Period Of Service Available (minutes)</label>
                </div>
                @error('ups_time')
                    <div class="text-danger">{{ $message }}</div>
                @enderror

                <div class="form-floating mb-3">
                    <input type="number" step="0.01" class="form-control" id="ups_temp" name="ups_temp"
                        placeholder="Switch Temperature">
                    <label for="ups_temp">Switch Temperature (°C)</label>
                </div>
                @error('ups_temp')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Submit</button>
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

        .hidden {
            display: none;
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
            var form = document.querySelector('form');
            var form1 = document.getElementById('form1');
            var form2 = document.getElementById('form2');
            var location1 = document.getElementById('location1');
            var location2 = document.getElementById('location2');
            var locationHidden = document.getElementById('location');

            var othersCheckbox = document.getElementById('othersCheckbox');

            othersCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    form1.classList.add('hidden');
                    form2.classList.remove('hidden');
                } else {
                    form1.classList.remove('hidden');
                    form2.classList.add('hidden');
                }
            });

            form.addEventListener('submit', function(e) {
                if (othersCheckbox.checked) {
                    locationHidden.value = location2.value;
                } else {
                    locationHidden.value = location1.value;
                }
            });
        });
    </script>
@endsection
