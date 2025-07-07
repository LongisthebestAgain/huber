@extends('layouts.app')

@section('title', 'Profile Management - Hubber')

@section('content')
<div class="profile-page">
    <div class="container mt-4">
        <!-- Back to Home Button - Top Left -->
        <div class="row mb-4">
            <div class="col-12">
                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Home
                </a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">
                            <i class="fas fa-user-edit me-2"></i>Profile Management
                        </h3>
                    </div>
                    <div class="card-body p-4">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('profile.update') }}" id="profileForm" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <!-- Profile Picture Section -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-camera me-2"></i>Profile Picture
                                    </h5>
                                    <div class="d-flex align-items-center">
                                        <div class="me-4">
                                            @if($user->profile_picture)
                                                <img src="{{ asset('storage/' . $user->profile_picture) }}" 
                                                     alt="Profile Picture" 
                                                     class="rounded-circle" 
                                                     style="width: 100px; height: 100px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" 
                                                     style="width: 100px; height: 100px;">
                                                    <i class="fas fa-user text-white" style="font-size: 2.5rem;"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <label for="profile_picture" class="form-label">Upload New Picture</label>
                                            <input type="file" class="form-control @error('profile_picture') is-invalid @enderror" 
                                                   id="profile_picture" name="profile_picture" accept="image/*">
                                            @error('profile_picture')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">JPG, PNG, GIF (max 2MB)</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Personal Information Section -->
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-user me-2"></i>Personal Information
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                           id="first_name" name="first_name" 
                                           value="{{ old('first_name', $user->first_name ?? '') }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                           id="last_name" name="last_name" 
                                           value="{{ old('last_name', $user->last_name ?? '') }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope me-1"></i>Email Address
                                    </label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" 
                                           value="{{ old('email', $user->email ?? '') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">
                                        <i class="fas fa-phone me-1"></i>Phone Number
                                    </label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" 
                                           value="{{ old('phone', $user->phone ?? '') }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="date_of_birth" class="form-label">
                                        <i class="fas fa-calendar me-1"></i>Date of Birth
                                    </label>
                                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                           id="date_of_birth" name="date_of_birth" 
                                           value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}">
                                    @error('date_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">
                                    <i class="fas fa-map-marker-alt me-1"></i>Address
                                </label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="3" 
                                          placeholder="Enter your full address">{{ old('address', $user->address ?? '') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Emergency Contact Section -->
                            <hr class="my-4">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-phone-alt me-2"></i>Emergency Contact
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="emergency_contact_name" class="form-label">Contact Name</label>
                                    <input type="text" class="form-control @error('emergency_contact_name') is-invalid @enderror" 
                                           id="emergency_contact_name" name="emergency_contact_name" 
                                           value="{{ old('emergency_contact_name', $user->emergency_contact_name ?? '') }}">
                                    @error('emergency_contact_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="emergency_contact_phone" class="form-label">Contact Phone</label>
                                    <input type="tel" class="form-control @error('emergency_contact_phone') is-invalid @enderror" 
                                           id="emergency_contact_phone" name="emergency_contact_phone" 
                                           value="{{ old('emergency_contact_phone', $user->emergency_contact_phone ?? '') }}">
                                    @error('emergency_contact_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="emergency_contact_relationship" class="form-label">Relationship</label>
                                    <select class="form-control @error('emergency_contact_relationship') is-invalid @enderror" 
                                            id="emergency_contact_relationship" name="emergency_contact_relationship">
                                        <option value="">Select Relationship</option>
                                        <option value="Spouse" {{ old('emergency_contact_relationship', $user->emergency_contact_relationship ?? '') == 'Spouse' ? 'selected' : '' }}>Spouse</option>
                                        <option value="Parent" {{ old('emergency_contact_relationship', $user->emergency_contact_relationship ?? '') == 'Parent' ? 'selected' : '' }}>Parent</option>
                                        <option value="Sibling" {{ old('emergency_contact_relationship', $user->emergency_contact_relationship ?? '') == 'Sibling' ? 'selected' : '' }}>Sibling</option>
                                        <option value="Friend" {{ old('emergency_contact_relationship', $user->emergency_contact_relationship ?? '') == 'Friend' ? 'selected' : '' }}>Friend</option>
                                        <option value="Other" {{ old('emergency_contact_relationship', $user->emergency_contact_relationship ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('emergency_contact_relationship')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            @if(Session::get('user_role') === 'driver')
                                <hr class="my-4">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-car me-2"></i>Driver Information
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="license_number" class="form-label">License Number</label>
                                        <input type="text" class="form-control @error('license_number') is-invalid @enderror" 
                                               id="license_number" name="license_number" 
                                               value="{{ old('license_number', $user->license_number ?? '') }}">
                                        @error('license_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="license_expiry" class="form-label">License Expiry Date</label>
                                        <input type="date" class="form-control @error('license_expiry') is-invalid @enderror" 
                                               id="license_expiry" name="license_expiry" 
                                               value="{{ old('license_expiry', $user->license_expiry ?? '') }}">
                                        @error('license_expiry')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="vehicle_model" class="form-label">Vehicle Model</label>
                                        <input type="text" class="form-control @error('vehicle_model') is-invalid @enderror" 
                                               id="vehicle_model" name="vehicle_model" 
                                               value="{{ old('vehicle_model', $user->vehicle_model ?? '') }}">
                                        @error('vehicle_model')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="vehicle_year" class="form-label">Vehicle Year</label>
                                        <input type="number" class="form-control @error('vehicle_year') is-invalid @enderror" 
                                               id="vehicle_year" name="vehicle_year" 
                                               value="{{ old('vehicle_year', $user->vehicle_year ?? '') }}">
                                        @error('vehicle_year')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="vehicle_color" class="form-label">Vehicle Color</label>
                                        <input type="text" class="form-control @error('vehicle_color') is-invalid @enderror" 
                                               id="vehicle_color" name="vehicle_color" 
                                               value="{{ old('vehicle_color', $user->vehicle_color ?? '') }}">
                                        @error('vehicle_color')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="license_plate" class="form-label">License Plate</label>
                                        <input type="text" class="form-control @error('license_plate') is-invalid @enderror" 
                                               id="license_plate" name="license_plate" 
                                               value="{{ old('license_plate', $user->license_plate ?? '') }}">
                                        @error('license_plate')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="vehicle_seats" class="form-label">Number of Seats</label>
                                        <input type="number" class="form-control @error('vehicle_seats') is-invalid @enderror" 
                                               id="vehicle_seats" name="vehicle_seats" 
                                               value="{{ old('vehicle_seats', $user->vehicle_seats ?? '') }}" min="1" max="20">
                                        @error('vehicle_seats')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            <hr class="my-4">
                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-end align-items-center gap-3">
                                <a href="{{ route('password.change') }}" class="btn btn-outline-warning">
                                    <i class="fas fa-lock me-2"></i>Change Password
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Profile
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('style')
<style>
/* Profile page specific styling */
.profile-page .container {
    margin-top: 1rem !important;
    margin-bottom: 2rem !important;
}

.profile-page .card {
    border-radius: 15px;
    margin-top: 1rem;
}

.profile-page .card-header {
    border-radius: 15px 15px 0 0 !important;
}

.profile-page .form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.profile-page .btn {
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: 500;
}

.profile-page .btn-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
    border: none;
}

.profile-page .btn-primary:hover {
    background: linear-gradient(135deg, #0b5ed7 0%, #0a58ca 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
}

.profile-page .btn-outline-warning {
    border-color: #ffc107;
    color: #856404;
}

.profile-page .btn-outline-warning:hover {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
}

.profile-page .btn-outline-secondary {
    border-color: #6c757d;
    color: #6c757d;
}

.profile-page .btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: #fff;
}

.profile-page .alert {
    border-radius: 10px;
    border: none;
}

.profile-page .alert-success {
    background: linear-gradient(135deg, #d1e7dd 0%, #badbcc 100%);
    color: #0f5132;
}

.profile-page .alert-danger {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c2c7 100%);
    color: #842029;
}

.profile-page .rounded-circle {
    border: 3px solid #e9ecef;
}

.profile-page .gap-3 {
    gap: 1rem !important;
}
</style>
@endsection 