@extends('layouts.app')
@section('title', 'Edit User')
@section('content')
<div class="container mt-5">
    <h1>Edit User</h1>
    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ $user->phone }}">
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role" required onchange="toggleDriverFields()">
                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                <option value="driver" {{ $user->role === 'driver' ? 'selected' : '' }}>Driver</option>
                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>
        <div id="driver-fields">
            <div class="mb-3">
                <label for="license_number" class="form-label">License Number</label>
                <input type="text" class="form-control" id="license_number" name="license_number" value="{{ $user->license_number }}">
            </div>
            <div class="mb-3">
                <label for="license_expiry" class="form-label">License Expiry</label>
                <input type="date" class="form-control" id="license_expiry" name="license_expiry" value="{{ $user->license_expiry }}">
            </div>
            <div class="mb-3">
                <label for="vehicle_model" class="form-label">Vehicle Model</label>
                <input type="text" class="form-control" id="vehicle_model" name="vehicle_model" value="{{ $user->vehicle_model }}">
            </div>
            <div class="mb-3">
                <label for="vehicle_year" class="form-label">Vehicle Year</label>
                <input type="text" class="form-control" id="vehicle_year" name="vehicle_year" value="{{ $user->vehicle_year }}">
            </div>
            <div class="mb-3">
                <label for="vehicle_color" class="form-label">Vehicle Color</label>
                <input type="text" class="form-control" id="vehicle_color" name="vehicle_color" value="{{ $user->vehicle_color }}">
            </div>
            <div class="mb-3">
                <label for="license_plate" class="form-label">License Plate</label>
                <input type="text" class="form-control" id="license_plate" name="license_plate" value="{{ $user->license_plate }}">
            </div>
            <div class="mb-3">
                <label for="vehicle_seats" class="form-label">Vehicle Seats</label>
                <input type="number" class="form-control" id="vehicle_seats" name="vehicle_seats" value="{{ $user->vehicle_seats }}">
            </div>
        </div>
        <div class="mb-3">
            <label for="profile_picture" class="form-label">Profile Picture</label>
            <input type="file" class="form-control" id="profile_picture" name="profile_picture">
            @if($user->profile_picture)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" width="80">
                </div>
            @endif
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address" name="address" value="{{ $user->address }}">
        </div>
        <div class="mb-3">
            <label for="date_of_birth" class="form-label">Date of Birth</label>
            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ $user->date_of_birth }}">
        </div>
        <div class="mb-3">
            <label for="emergency_contact_name" class="form-label">Emergency Contact Name</label>
            <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name" value="{{ $user->emergency_contact_name }}">
        </div>
        <div class="mb-3">
            <label for="emergency_contact_phone" class="form-label">Emergency Contact Phone</label>
            <input type="text" class="form-control" id="emergency_contact_phone" name="emergency_contact_phone" value="{{ $user->emergency_contact_phone }}">
        </div>
        <div class="mb-3">
            <label for="emergency_contact_relationship" class="form-label">Emergency Contact Relationship</label>
            <input type="text" class="form-control" id="emergency_contact_relationship" name="emergency_contact_relationship" value="{{ $user->emergency_contact_relationship }}">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password (leave blank to keep current)</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
@section('scripts')
<script>
function toggleDriverFields() {
    var role = document.getElementById('role').value;
    var driverFields = document.getElementById('driver-fields');
    if (role === 'driver') {
        driverFields.style.display = '';
    } else {
        driverFields.style.display = 'none';
    }
}
document.addEventListener('DOMContentLoaded', function() {
    toggleDriverFields();
});
</script>
@endsection 