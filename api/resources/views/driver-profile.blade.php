@extends('layouts.app')

@section('title', 'Driver Profile - Hubber')

@section('content')
<div class="driver-profile-page">
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
            <div class="col-md-10">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">
                            <i class="fas fa-car me-2"></i>Driver Profile
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

                        <!-- Legal Documents Section (Read-Only) -->
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-file-alt me-2"></i>Legal Documents
                        </h5>
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <div class="document-card">
                                    <h6 class="text-muted mb-2">Driver's License</h6>
                                    @if($driverDocuments && $driverDocuments->driver_license_file)
                                        <div class="document-preview">
                                            <img src="{{ asset('storage/' . $driverDocuments->driver_license_file) }}" 
                                                 alt="Driver's License" 
                                                 class="img-fluid rounded" 
                                                 style="max-height: 200px; width: 100%; object-fit: cover;">
                                        </div>
                                    @else
                                        <div class="no-document">
                                            <i class="fas fa-file-alt text-muted" style="font-size: 3rem;"></i>
                                            <p class="text-muted mt-2">No document uploaded</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="document-card">
                                    <h6 class="text-muted mb-2">Vehicle Registration</h6>
                                    @if($driverDocuments && $driverDocuments->vehicle_registration_file)
                                        <div class="document-preview">
                                            <img src="{{ asset('storage/' . $driverDocuments->vehicle_registration_file) }}" 
                                                 alt="Vehicle Registration" 
                                                 class="img-fluid rounded" 
                                                 style="max-height: 200px; width: 100%; object-fit: cover;">
                                        </div>
                                    @else
                                        <div class="no-document">
                                            <i class="fas fa-file-alt text-muted" style="font-size: 3rem;"></i>
                                            <p class="text-muted mt-2">No document uploaded</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="document-card">
                                    <h6 class="text-muted mb-2">Insurance Certificate</h6>
                                    @if($driverDocuments && $driverDocuments->insurance_certificate_file)
                                        <div class="document-preview">
                                            <img src="{{ asset('storage/' . $driverDocuments->insurance_certificate_file) }}" 
                                                 alt="Insurance Certificate" 
                                                 class="img-fluid rounded" 
                                                 style="max-height: 200px; width: 100%; object-fit: cover;">
                                        </div>
                                    @else
                                        <div class="no-document">
                                            <i class="fas fa-file-alt text-muted" style="font-size: 3rem;"></i>
                                            <p class="text-muted mt-2">No document uploaded</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Vehicle Photos Section -->
                        <hr class="my-4">
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-images me-2"></i>Vehicle Photos
                        </h5>
                        <form method="POST" action="{{ route('driver.vehicle-photos.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="row mb-4">
                                <div class="col-md-4 mb-3">
                                    <div class="vehicle-photo-card">
                                        <h6 class="text-muted mb-2">Vehicle Photo 1</h6>
                                        @if($driverDocuments && $driverDocuments->vehicle_photo_1)
                                            <div class="current-photo mb-2">
                                                <img src="{{ asset('storage/' . $driverDocuments->vehicle_photo_1) }}" 
                                                     alt="Vehicle Photo 1" 
                                                     class="img-fluid rounded" 
                                                     style="max-height: 150px; width: 100%; object-fit: cover;">
                                            </div>
                                        @endif
                                        <input type="file" class="form-control @error('vehicle_photo_1') is-invalid @enderror" 
                                               name="vehicle_photo_1" accept="image/*">
                                        @error('vehicle_photo_1')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Front view</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <div class="vehicle-photo-card">
                                        <h6 class="text-muted mb-2">Vehicle Photo 2</h6>
                                        @if($driverDocuments && $driverDocuments->vehicle_photo_2)
                                            <div class="current-photo mb-2">
                                                <img src="{{ asset('storage/' . $driverDocuments->vehicle_photo_2) }}" 
                                                     alt="Vehicle Photo 2" 
                                                     class="img-fluid rounded" 
                                                     style="max-height: 150px; width: 100%; object-fit: cover;">
                                            </div>
                                        @endif
                                        <input type="file" class="form-control @error('vehicle_photo_2') is-invalid @enderror" 
                                               name="vehicle_photo_2" accept="image/*">
                                        @error('vehicle_photo_2')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Side view</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <div class="vehicle-photo-card">
                                        <h6 class="text-muted mb-2">Vehicle Photo 3</h6>
                                        @if($driverDocuments && $driverDocuments->vehicle_photo_3)
                                            <div class="current-photo mb-2">
                                                <img src="{{ asset('storage/' . $driverDocuments->vehicle_photo_3) }}" 
                                                     alt="Vehicle Photo 3" 
                                                     class="img-fluid rounded" 
                                                     style="max-height: 150px; width: 100%; object-fit: cover;">
                                            </div>
                                        @endif
                                        <input type="file" class="form-control @error('vehicle_photo_3') is-invalid @enderror" 
                                               name="vehicle_photo_3" accept="image/*">
                                        @error('vehicle_photo_3')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Rear view</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end mb-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Vehicle Photos
                                </button>
                            </div>
                        </form>

                        <!-- Vehicle Information Section -->
                        <hr class="my-4">
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-info-circle me-2"></i>Vehicle Information
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">License Plate</label>
                                <p class="form-control-plaintext">{{ $user->license_plate ?? 'Not provided' }}</p>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Vehicle Color</label>
                                <p class="form-control-plaintext">{{ $user->vehicle_color ?? 'Not provided' }}</p>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Vehicle Model</label>
                                <p class="form-control-plaintext">{{ $user->vehicle_model ?? 'Not provided' }}</p>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Vehicle Year</label>
                                <p class="form-control-plaintext">{{ $user->vehicle_year ?? 'Not provided' }}</p>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Number of Seats</label>
                                <p class="form-control-plaintext">{{ $user->vehicle_seats ?? 'Not provided' }}</p>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Driver's License Number</label>
                                <p class="form-control-plaintext">{{ $user->license_number ?? 'Not provided' }}</p>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">License Expiry Date</label>
                                <p class="form-control-plaintext">{{ $user->license_expiry ?? 'Not provided' }}</p>
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('user.profile') }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit me-2"></i>Edit Personal Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('style')
<style>
/* Driver profile page specific styling */
.driver-profile-page .container {
    margin-top: 1rem !important;
    margin-bottom: 2rem !important;
}

.driver-profile-page .card {
    border-radius: 15px;
    margin-top: 1rem;
}

.driver-profile-page .card-header {
    border-radius: 15px 15px 0 0 !important;
}

.driver-profile-page .btn {
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: 500;
}

.driver-profile-page .btn-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
    border: none;
}

.driver-profile-page .btn-primary:hover {
    background: linear-gradient(135deg, #0b5ed7 0%, #0a58ca 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
}

.driver-profile-page .btn-outline-primary {
    border-color: #0d6efd;
    color: #0d6efd;
}

.driver-profile-page .btn-outline-primary:hover {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: #fff;
}

.driver-profile-page .btn-outline-secondary {
    border-color: #6c757d;
    color: #6c757d;
}

.driver-profile-page .btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: #fff;
}

.driver-profile-page .alert {
    border-radius: 10px;
    border: none;
}

.driver-profile-page .alert-success {
    background: linear-gradient(135deg, #d1e7dd 0%, #badbcc 100%);
    color: #0f5132;
}

.driver-profile-page .alert-danger {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c2c7 100%);
    color: #842029;
}

.document-card, .vehicle-photo-card {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 10px;
    padding: 1rem;
    text-align: center;
    min-height: 250px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.no-document {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 200px;
    background: #e9ecef;
    border-radius: 8px;
}

.document-preview {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    overflow: hidden;
    background: white;
}

.current-photo {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    overflow: hidden;
    background: white;
}

.form-control-plaintext {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 0.5rem;
    margin: 0;
}
</style>
@endsection 