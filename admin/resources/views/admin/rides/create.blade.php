@extends('layouts.admin')
@section('title', 'Add Ride')
@section('subtitle', 'Create a new ride')
@section('content')
    <div class="card p-4 shadow-sm mx-auto" style="max-width: 600px;">
        <form action="{{ route('admin.rides.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="driver_id" class="form-label">Driver</label>
                <select class="form-select" id="driver_id" name="driver_id" required>
                    <option value="">Select Driver</option>
                    @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>{{ $driver->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="origin" class="form-label">Origin</label>
                <input type="text" class="form-control" id="origin" name="origin" value="{{ old('origin') }}" required>
            </div>
            <div class="mb-3">
                <label for="destination" class="form-label">Destination</label>
                <input type="text" class="form-control" id="destination" name="destination" value="{{ old('destination') }}" required>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date" value="{{ old('date') }}" required>
            </div>
            <button type="submit" class="btn btn-success w-100">
                <i class="fas fa-plus me-1"></i> Add Ride
            </button>
        </form>
    </div>
@endsection 