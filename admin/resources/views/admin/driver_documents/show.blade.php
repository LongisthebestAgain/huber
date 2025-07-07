@extends('layouts.admin')
@section('title', 'Driver Documents - ' . $driver->name)
@section('subtitle', 'View all documents for this driver')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Driver Documents</h2>
            <p class="text-muted mb-0">{{ $driver->name }} ({{ $driver->email }})</p>
        </div>
        <a href="{{ route('admin.driver_documents.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>

    @if($driverDocument)
        <div class="row">
            <!-- Driver Information -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-user me-2"></i>Driver Information</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Name:</strong> {{ $driver->name }}</p>
                        <p><strong>Email:</strong> {{ $driver->email }}</p>
                        <p><strong>Phone:</strong> {{ $driver->phone ?? 'Not provided' }}</p>
                        <p><strong>License Number:</strong> {{ $driver->license_number ?? 'Not provided' }}</p>
                        <p><strong>Vehicle:</strong> 
                            @if($driver->vehicle_model && $driver->vehicle_year)
                                {{ $driver->vehicle_year }} {{ $driver->vehicle_model }}
                            @else
                                Not provided
                            @endif
                        </p>
                        <p><strong>License Plate:</strong> {{ $driver->license_plate ?? 'Not provided' }}</p>
                        <hr>
                        <p><strong>Verification Status:</strong> 
                            @if($driver->is_verified)
                                <span class="badge bg-success">Verified</span>
                            @else
                                <span class="badge bg-warning">Pending Verification</span>
                            @endif
                        </p>
                        
                        @if($driverDocument)
                            <div class="mt-3">
                                @if($driver->is_verified)
                                    <form action="{{ route('admin.driver_documents.unverify', $driver->id) }}" method="POST" style="display:inline-block">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm w-100" onclick="return confirm('Are you sure you want to revoke verification for this driver?')">
                                            <i class="fas fa-times-circle me-1"></i> Revoke Verification
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.driver_documents.verify', $driver->id) }}" method="POST" style="display:inline-block">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm w-100" onclick="return confirm('Are you sure you want to verify this driver?')">
                                            <i class="fas fa-check-circle me-1"></i> Verify Driver
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Legal Documents -->
            <div class="col-md-8 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-file-contract me-2"></i>Legal Documents</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Driver's License -->
                            <div class="col-md-4 mb-3">
                                <h6 class="text-primary">Driver's License</h6>
                                @if($driverDocument->driver_license_file)
                                    <img src="{{ asset('storage/' . $driverDocument->driver_license_file) }}" 
                                         alt="Driver's License" 
                                         class="img-fluid rounded mb-2 border" 
                                         style="max-width: 100%; max-height: 400px;">
                                @else
                                    <p class="text-muted">No document uploaded</p>
                                @endif
                            </div>

                            <!-- Vehicle Registration -->
                            <div class="col-md-4 mb-3">
                                <h6 class="text-primary">Vehicle Registration</h6>
                                @if($driverDocument->vehicle_registration_file)
                                    <img src="{{ asset('storage/' . $driverDocument->vehicle_registration_file) }}" 
                                         alt="Vehicle Registration" 
                                         class="img-fluid rounded mb-2 border" 
                                         style="max-width: 100%; max-height: 400px;">
                                @else
                                    <p class="text-muted">No document uploaded</p>
                                @endif
                            </div>

                            <!-- Insurance Certificate -->
                            <div class="col-md-4 mb-3">
                                <h6 class="text-primary">Insurance Certificate</h6>
                                @if($driverDocument->insurance_certificate_file)
                                    <img src="{{ asset('storage/' . $driverDocument->insurance_certificate_file) }}" 
                                         alt="Insurance Certificate" 
                                         class="img-fluid rounded mb-2 border" 
                                         style="max-width: 100%; max-height: 400px;">
                                @else
                                    <p class="text-muted">No document uploaded</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vehicle Photos -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-images me-2"></i>Vehicle Photos</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @for($i = 1; $i <= 3; $i++)
                                <div class="col-md-4 mb-3">
                                    <h6 class="text-info">Vehicle Photo {{ $i }}</h6>
                                    @if($driverDocument->{'vehicle_photo_' . $i})
                                        <img src="{{ asset('storage/' . $driverDocument->{'vehicle_photo_' . $i}) }}" 
                                             alt="Vehicle Photo {{ $i }}" 
                                             class="img-fluid rounded mb-2 border" 
                                             style="max-width: 100%; max-height: 400px;">
                                    @else
                                        <p class="text-muted">No photo uploaded</p>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @else
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            No documents found for this driver. The driver may not have uploaded their documents yet.
        </div>
    @endif
@endsection 