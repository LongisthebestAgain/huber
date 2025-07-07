@extends('layouts.admin')
@section('title', 'Driver Details - ' . $driver->name)
@section('subtitle', 'Comprehensive driver information and statistics')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">{{ $driver->name }}</h2>
            <p class="text-muted mb-0">Driver ID: {{ $driver->id }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.drivers.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Drivers
            </a>
            <a href="{{ route('admin.drivers.edit', $driver->id) }}" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i> Edit Driver
            </a>
        </div>
    </div>

    <!-- Driver Profile Section -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Driver Profile</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-3">
                            @if($driver->profile_picture)
                                <img src="{{ asset('storage/' . $driver->profile_picture) }}" 
                                     class="rounded-circle img-fluid" width="120" height="120" alt="Profile">
                            @else
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                                     style="width: 120px; height: 120px;">
                                    <i class="fas fa-user text-white fa-4x"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Name:</strong> {{ $driver->name }}</p>
                                    <p><strong>Email:</strong> {{ $driver->email }}</p>
                                    <p><strong>Phone:</strong> {{ $driver->phone ?? 'Not provided' }}</p>
                                    <p><strong>Role:</strong> 
                                        <span class="badge bg-primary">{{ ucfirst($driver->role) }}</span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Status:</strong> 
                                        @if($driver->is_verified)
                                            <span class="badge bg-success">Verified</span>
                                        @else
                                            <span class="badge bg-warning">Pending Verification</span>
                                        @endif
                                    </p>
                                    <p><strong>Joined:</strong> {{ $driver->created_at->format('M d, Y') }}</p>
                                    <p><strong>Last Updated:</strong> {{ $driver->updated_at->format('M d, Y') }}</p>
                                    @if($driver->address)
                                        <p><strong>Address:</strong> {{ $driver->address }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-car me-2"></i>Vehicle Information</h5>
                </div>
                <div class="card-body">
                    @if($driver->vehicle_model && $driver->vehicle_year)
                        <p><strong>Vehicle:</strong> {{ $driver->vehicle_year }} {{ $driver->vehicle_model }}</p>
                        <p><strong>Color:</strong> {{ $driver->vehicle_color ?? 'Not specified' }}</p>
                        <p><strong>License Plate:</strong> {{ $driver->license_plate ?? 'Not provided' }}</p>
                        <p><strong>Seats:</strong> {{ $driver->vehicle_seats ?? 'Not specified' }}</p>
                        <p><strong>License Number:</strong> {{ $driver->license_number ?? 'Not provided' }}</p>
                        @if($driver->license_expiry)
                            <p><strong>License Expiry:</strong> {{ $driver->license_expiry }}</p>
                        @endif
                    @else
                        <p class="text-muted">No vehicle information available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <i class="fas fa-route fa-2x mb-2"></i>
                    <h3 class="mb-0">{{ $totalRides }}</h3>
                    <p class="mb-0">Total Rides</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                    <h3 class="mb-0">{{ $completedRides }}</h3>
                    <p class="mb-0">Completed Rides</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <i class="fas fa-dollar-sign fa-2x mb-2"></i>
                    <h3 class="mb-0">${{ number_format($totalEarnings, 2) }}</h3>
                    <p class="mb-0">Total Earnings</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <i class="fas fa-star fa-2x mb-2"></i>
                    <h3 class="mb-0">{{ number_format($averageRating, 1) }}</h3>
                    <p class="mb-0">Average Rating</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Rides and Earnings Chart -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-route me-2"></i>Recent Rides</h5>
                </div>
                <div class="card-body">
                    @if($recentRides->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentRides as $ride)
                                        <tr>
                                            <td>{{ $ride->date->format('M d, Y') }}</td>
                                            <td>{{ $ride->station_location }}</td>
                                            <td>{{ $ride->destination }}</td>
                                            <td>
                                                @if($ride->is_exclusive)
                                                    <span class="badge bg-purple">Exclusive</span>
                                                @else
                                                    <span class="badge bg-blue">Shared</span>
                                                @endif
                                                @if($ride->is_two_way)
                                                    <span class="badge bg-info">Two-way</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($ride->go_completion_status === 'completed')
                                                    <span class="badge bg-success">Completed</span>
                                                @elseif($ride->go_completion_status === 'ongoing')
                                                    <span class="badge bg-warning">Ongoing</span>
                                                @else
                                                    <span class="badge bg-secondary">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($ride->is_exclusive)
                                                    ${{ number_format($ride->go_to_exclusive_price, 2) }}
                                                @else
                                                    ${{ number_format($ride->go_to_price_per_person, 2) }}/person
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-3">No rides found for this driver</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Monthly Earnings</h5>
                </div>
                <div class="card-body">
                    @if($monthlyEarnings->count() > 0)
                        @foreach($monthlyEarnings->take(6) as $earning)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>{{ \Carbon\Carbon::createFromDate($earning->year, $earning->month, 1)->format('M Y') }}</span>
                                <span class="fw-bold">${{ number_format($earning->total, 2) }}</span>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-3">No earnings data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Driver Documents -->
    @if($driver->driverDocuments)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Driver Documents</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="text-center">
                                    <i class="fas fa-id-card fa-3x text-primary mb-2"></i>
                                    <p class="mb-1"><strong>Driver's License</strong></p>
                                    @if($driver->driverDocuments->driver_license_file)
                                        <a href="{{ asset('storage/' . $driver->driverDocuments->driver_license_file) }}" 
                                           target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download me-1"></i> View
                                        </a>
                                    @else
                                        <span class="text-muted">Not uploaded</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <i class="fas fa-car fa-3x text-success mb-2"></i>
                                    <p class="mb-1"><strong>Vehicle Registration</strong></p>
                                    @if($driver->driverDocuments->vehicle_registration_file)
                                        <a href="{{ asset('storage/' . $driver->driverDocuments->vehicle_registration_file) }}" 
                                           target="_blank" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-download me-1"></i> View
                                        </a>
                                    @else
                                        <span class="text-muted">Not uploaded</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <i class="fas fa-shield-alt fa-3x text-warning mb-2"></i>
                                    <p class="mb-1"><strong>Insurance Certificate</strong></p>
                                    @if($driver->driverDocuments->insurance_certificate_file)
                                        <a href="{{ asset('storage/' . $driver->driverDocuments->insurance_certificate_file) }}" 
                                           target="_blank" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-download me-1"></i> View
                                        </a>
                                    @else
                                        <span class="text-muted">Not uploaded</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Emergency Contact Information -->
    @if($driver->emergency_contact_name || $driver->emergency_contact_phone)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-phone-alt me-2"></i>Emergency Contact</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <p><strong>Contact Name:</strong> {{ $driver->emergency_contact_name ?? 'Not provided' }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Contact Phone:</strong> {{ $driver->emergency_contact_phone ?? 'Not provided' }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Relationship:</strong> {{ $driver->emergency_contact_relationship ?? 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.driver_documents.show', $driver->id) }}" class="btn btn-outline-primary">
                            <i class="fas fa-file-alt me-1"></i> View Documents
                        </a>
                        @if($driver->is_verified)
                            <form action="{{ route('admin.driver_documents.unverify', $driver->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-warning" 
                                        onclick="return confirm('Are you sure you want to revoke verification for this driver?')">
                                    <i class="fas fa-times-circle me-1"></i> Revoke Verification
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.driver_documents.verify', $driver->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-success" 
                                        onclick="return confirm('Are you sure you want to verify this driver?')">
                                    <i class="fas fa-check-circle me-1"></i> Verify Driver
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('admin.drivers.destroy', $driver->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger" 
                                    onclick="return confirm('Are you sure you want to delete this driver? This action cannot be undone.')">
                                <i class="fas fa-trash me-1"></i> Delete Driver
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
.card {
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.badge.bg-purple {
    background-color: #6f42c1 !important;
}

.badge.bg-blue {
    background-color: #0d6efd !important;
}
</style>
@endpush 