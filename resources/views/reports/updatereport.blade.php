@extends('layouts.app')
@section('title', 'Update Issue')

@section('content')
    <form action="{{ route('updatePreport', $ReportIssues->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="previous_url" value="{{ url()->previous() }}">

        <div class="mb-3">
            <label for="issue" class="form-label">Issue Category</label>
            <select class="form-select" id="category" name="category_id">
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $ReportIssues->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        @error('category_id')
            <div class="my-error">
                <span class="text-danger">{{ $message }}</span>
            </div>
        @enderror

        <div class="mb-3">
            <label for="detail" class="form-label">Detail</label>
            <textarea class="form-control" id="detail" name="detail" rows="3">{{ $ReportIssues->detail }}</textarea>
        </div>
        @error('detail')
            <div class="my-error">
                <span class="text-danger">{{ $message }}</span>
            </div>
        @enderror

        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="needRemarks" onchange="toggleRemarks()"
                    {{ $ReportIssues->remarks ? 'checked' : '' }}>
                <label class="form-check-label" for="needRemarks">
                    Add Remarks
                </label>
            </div>
        </div>

        <div class="mb-3" id="remarksContainer" style="display: {{ $ReportIssues->remarks ? 'block' : 'none' }};">
            <label for="remarks" class="form-label">Remarks</label>
            <textarea class="form-control" id="remarks" name="remarks" rows="2">{{ $ReportIssues->remarks }}</textarea>
        </div>


        <div class="mb-3">
            <label for="department" class="form-label">Department</label>
            <select class="form-select" id="department" name="department_id">
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}"
                        {{ $ReportIssues->department_id == $department->id ? 'selected' : '' }}>
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
        </div>


        <select class="form-select" id="hotel" name="hotel_id">
            @foreach ($hotels as $hotel)
                <option value="{{ $hotel->id }}" {{ $ReportIssues->hotel_id == $hotel->id ? 'selected' : '' }}>
                    {{ $hotel->hotel_code }}
                </option>
            @endforeach
        </select>
        @error('hotel')
            <div class="my-error">
                <span class="text-danger">{{ $message }}</span>
            </div>
        @enderror

        <div class="mb-3">
            <label for="assignee" class="form-label">Assignee</label>
            <select class="form-select" id="assignee" name="assignee">
                <option value="">Not Assign</option>
                @foreach ($itSupportUsers as $user)
                    <option value="{{ $user->id }}" {{ $ReportIssues->assignee_id == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status">
                <option value="0" {{ $ReportIssues->status === 0 ? 'selected' : '' }}>In-progress</option>
                <option value="1" {{ $ReportIssues->status === 1 ? 'selected' : '' }}>Done</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="file" class="form-label">Upload New File (PDF or Image)</label>
            <input type="file" class="form-control" id="file" name="file" accept=".pdf,.jpg,.jpeg,.png">
        </div>

        @if ($ReportIssues->file_path)
            <div class="mb-3">
                <p>Current file: <a href="{{ asset('storage/' . $ReportIssues->file_path) }}" target="_blank">View File</a>
                </p>
            </div>
        @endif

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
    </form>

    <script>
        function toggleRemarks() {
            const checkbox = document.getElementById('needRemarks');
            const remarksContainer = document.getElementById('remarksContainer');
            remarksContainer.style.display = checkbox.checked ? 'block' : 'none';
        }
    </script>
@endsection
