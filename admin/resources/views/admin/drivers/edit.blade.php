@extends('layouts.admin')
@section('title', 'Edit Driver')
@section('subtitle', 'Update driver details')
@section('content')
    <div class="card p-4 shadow-sm mx-auto" style="max-width: 500px;">
        <form action="{{ route('admin.drivers.update', $driver->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $driver->name) }}" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $driver->email) }}" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $driver->phone) }}">
            </div>
            <div class="mb-3">
                <label for="license_number" class="form-label">License Number</label>
                <input type="text" class="form-control" id="license_number" name="license_number" value="{{ old('license_number', $driver->license_number) }}">
            </div>
            <div class="mb-3">
                <label for="license_expiry" class="form-label">License Expiry</label>
                <input type="date" class="form-control" id="license_expiry" name="license_expiry" value="{{ old('license_expiry', $driver->license_expiry) }}">
            </div>
            <div class="mb-3">
                <label for="vehicle_model" class="form-label">Vehicle Model</label>
                <input type="text" class="form-control" id="vehicle_model" name="vehicle_model" value="{{ old('vehicle_model', $driver->vehicle_model) }}">
            </div>
            <div class="mb-3">
                <label for="vehicle_year" class="form-label">Vehicle Year</label>
                <input type="text" class="form-control" id="vehicle_year" name="vehicle_year" value="{{ old('vehicle_year', $driver->vehicle_year) }}">
            </div>
            <div class="mb-3">
                <label for="vehicle_color" class="form-label">Vehicle Color</label>
                <input type="text" class="form-control" id="vehicle_color" name="vehicle_color" value="{{ old('vehicle_color', $driver->vehicle_color) }}">
            </div>
            <div class="mb-3">
                <label for="license_plate" class="form-label">License Plate</label>
                <input type="text" class="form-control" id="license_plate" name="license_plate" value="{{ old('license_plate', $driver->license_plate) }}">
            </div>
            <div class="mb-3">
                <label for="vehicle_seats" class="form-label">Vehicle Seats</label>
                <input type="number" class="form-control" id="vehicle_seats" name="vehicle_seats" value="{{ old('vehicle_seats', $driver->vehicle_seats) }}">
            </div>
            <div class="mb-3">
                <label for="profile_picture" class="form-label">Profile Picture</label>
                <input type="file" class="form-control" id="profile_picture" name="profile_picture">
                @if($driver->profile_picture)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $driver->profile_picture) }}" alt="Profile Picture" width="80">
                    </div>
                @endif
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $driver->address) }}">
            </div>
            <div class="mb-3">
                <label for="date_of_birth" class="form-label">Date of Birth</label>
                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $driver->date_of_birth) }}">
            </div>
            <div class="mb-3">
                <label for="emergency_contact_name" class="form-label">Emergency Contact Name</label>
                <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name', $driver->emergency_contact_name) }}">
            </div>
            <div class="mb-3">
                <label for="emergency_contact_phone" class="form-label">Emergency Contact Phone</label>
                <input type="text" class="form-control" id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $driver->emergency_contact_phone) }}">
            </div>
            <div class="mb-3">
                <label for="emergency_contact_relationship" class="form-label">Emergency Contact Relationship</label>
                <input type="text" class="form-control" id="emergency_contact_relationship" name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship', $driver->emergency_contact_relationship) }}">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password <small class="text-muted">(leave blank to keep current)</small></label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
            </div>
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-save me-1"></i> Update Driver
            </button>
        </form>
    </div>
@endsection 