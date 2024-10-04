@extends('layouts.app')
@section('title', 'Update Guest Room Check')

@section('content')
    <div class="container">
        <form action="{{ route('updatePguest', ['type' => $type, 'id' => $ReportGuests->id]) }}" method="POST">
            @csrf

            <div class="form-section">
                <h2>Guest Room Information</h2>

                <div class="form-group mb-3">
                    <label for="location_type">Location Type</label>
                    <select class="form-select" id="location_type" name="location_type">
                        <option value="building" {{ $ReportGuests->location_type == 'building' ? 'selected' : '' }}>Building
                        </option>
                        <option value="floor" {{ $ReportGuests->location_type == 'floor' ? 'selected' : '' }}>Floor
                        </option>
                        <option value="facility" {{ $ReportGuests->location_type == 'facility' ? 'selected' : '' }}>Facility
                        </option>
                    </select>
                </div>

                <div class="form-group mb-3" id="buildingForm"
                    {{ $ReportGuests->location_type != 'building' ? 'style=display:none;' : '' }}>
                    <label for="building_location">Building</label>
                    <select class="form-select" id="building_location" name="building_location">
                        @foreach ($buildings as $building)
                            <option value="{{ $building->name }}"
                                {{ $ReportGuests->location == $building->name ? 'selected' : '' }}>
                                {{ $building->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3" id="floorForm"
                    {{ $ReportGuests->location_type != 'floor' ? 'style=display:none;' : '' }}>
                    <label for="floor_location">Floor</label>
                    <select class="form-select" id="floor_location" name="floor_location">
                        @foreach ($floors as $floor)
                            <option value="{{ $floor->name }}"
                                {{ $ReportGuests->location == $floor->name ? 'selected' : '' }}>
                                {{ $floor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-floating mb-3">
                    <input type="number" class="form-control" id="room_no" name="room_no" placeholder="Room Number"
                        value="{{ $ReportGuests->room_no }}">
                    <label for="room_no">Room Number</label>
                </div>
                @error('room_no')
                    <div class="text-danger">{{ $message }}</div>
                @enderror

                <div class="form-section">
                    <h2>Internet Speed</h2>

                    <div class="form-floating mb-3">
                        <input type="number" step="0.1" class="form-control" id="download" name="download"
                            placeholder="Download" value="{{ $ReportGuests->download }}">
                        <label for="download">Download (Mbps)</label>
                    </div>
                    @error('download')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                    <div class="form-floating mb-3">
                        <input type="number" step="0.1" class="form-control" id="upload" name="upload"
                            placeholder="Upload" value="{{ $ReportGuests->upload }}">
                        <label for="upload">Upload (Mbps)</label>
                    </div>
                    @error('upload')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-section">
                    <h2>TV Channel</h2>

                    <div class="form-floating mb-3">
                        <input type="number" step="0.01" class="form-control" id="ch_no" name="ch_no"
                            placeholder="Channel Number" value="{{ $ReportGuests->ch_no }}">
                        <label for="ch_no">Channel Number</label>
                    </div>
                    @error('ch_no')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="ch_name" name="ch_name" placeholder="Channel Name"
                            value="{{ $ReportGuests->ch_name }}">
                        <label for="ch_name">Channel Name</label>
                    </div>
                    @error('ch_name')
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
            var floorForm = document.getElementById('floorForm');

            locationType.addEventListener('change', function() {
                buildingForm.style.display = 'none';
                floorForm.style.display = 'none';
                facilityForm.style.display = 'none';

                switch (this.value) {
                    case 'building':
                        buildingForm.style.display = 'block';
                        break;
                    case 'floor':
                        floorForm.style.display = 'block';
                        break;
                }
            });
        });
    </script>
@endsection
