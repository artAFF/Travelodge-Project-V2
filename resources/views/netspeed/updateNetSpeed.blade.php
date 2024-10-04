@extends('layouts.app')
@section('title', 'Update Internet Speed Check')

@section('content')
    <div class="container">
        <form action="{{ route('updatePNetSpeed', ['type' => $type, 'id' => $ReportNetSpeeds->id]) }}" method="POST">
            @csrf

            <div class="form-section">
                <h2>Internet Checking</h2>

                <div class="form-group mb-3">
                    <label for="location_type">Location Type</label>
                    <select class="form-select" id="location_type" name="location_type">
                        <option value="building" {{ $ReportNetSpeeds->location_type == 'building' ? 'selected' : '' }}>
                            Building</option>
                        <option value="facility" {{ $ReportNetSpeeds->location_type == 'facility' ? 'selected' : '' }}>
                            Facility</option>
                    </select>
                </div>

                <div class="form-group mb-3" id="buildingForm"
                    {{ $ReportNetSpeeds->location_type != 'building' ? 'style=display:none;' : '' }}>
                    <label for="building_location">Building</label>
                    <select class="form-select" id="building_location" name="building_location">
                        @foreach ($buildings as $building)
                            <option value="{{ $building->name }}"
                                {{ $ReportNetSpeeds->location == $building->name ? 'selected' : '' }}>
                                {{ $building->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3" id="facilityForm"
                    {{ $ReportNetSpeeds->location_type != 'facility' ? 'style=display:none;' : '' }}>
                    <label for="facility_location">Facility</label>
                    <select class="form-select" id="facility_location" name="facility_location">
                        @foreach ($facilities as $facility)
                            <option value="{{ $facility->name }}"
                                {{ $ReportNetSpeeds->location == $facility->name ? 'selected' : '' }}>
                                {{ $facility->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <input type="hidden" id="location" name="location" value="{{ $ReportNetSpeeds->location }}">
            </div>

            <div class="form-section">
                <h2>Internet Speed</h2>

                <div class="form-floating mb-3">
                    <input type="number" step="0.01" class="form-control" id="download" name="download"
                        placeholder="Download" value="{{ $ReportNetSpeeds->download }}">
                    <label for="download">Download (Mbps)</label>
                </div>
                @error('download')
                    <div class="text-danger">{{ $message }}</div>
                @enderror

                <div class="form-floating mb-3">
                    <input type="number" step="0.01" class="form-control" id="upload" name="upload"
                        placeholder="Upload" value="{{ $ReportNetSpeeds->upload }}">
                    <label for="upload">Upload (Mbps)</label>
                </div>
                @error('upload')
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
            var locationType = document.getElementById('location_type');
            var buildingForm = document.getElementById('buildingForm');
            var facilityForm = document.getElementById('facilityForm');
            var locationHidden = document.getElementById('location');

            locationType.addEventListener('change', function() {
                buildingForm.style.display = 'none';
                facilityForm.style.display = 'none';

                switch (this.value) {
                    case 'building':
                        buildingForm.style.display = 'block';
                        break;
                    case 'facility':
                        facilityForm.style.display = 'block';
                        break;
                }
            });

            document.querySelector('form').addEventListener('submit', function(e) {
                var selectedLocation = locationType.value === 'building' ?
                    document.getElementById('building_location').value :
                    document.getElementById('facility_location').value;
                locationHidden.value = selectedLocation;
            });
        });
    </script>
@endsection
