@extends('layouts.app')

@section('title', $user->name . ' - Driver Profile - Hubber')

@section('content')
<div class="container-fluid bg-light min-vh-100 py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
            </div>

            <!-- Driver Header -->
            <div class="card shadow border-0 mb-4" style="border-radius: 18px;">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center">
                            @if($user->profile_picture)
                                <img src="{{ asset('storage/' . $user->profile_picture) }}" 
                                     alt="Profile" class="rounded-circle mb-3" width="120" height="120"
                                     style="object-fit: cover; border: 4px solid #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                            @else
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mb-3 mx-auto" 
                                     style="width: 120px; height: 120px; font-size: 3rem;">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h2 class="fw-bold mb-2">{{ $user->name }}</h2>
                            <p class="text-muted mb-3">
                                <i class="fas fa-car me-2"></i>Professional Driver
                            </p>
                            
                            <!-- Rating Display -->
                            <div class="d-flex align-items-center mb-3">
                                <div class="h4 text-warning mb-0 me-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= $averageOverallRating ? '' : '-o' }}"></i>
                                    @endfor
                                </div>
                                <div class="ms-2">
                                    <div class="fw-bold">{{ number_format($averageOverallRating, 1) }}/5</div>
                                    <small class="text-muted">{{ $totalReviews }} reviews</small>
                                </div>
                            </div>

                            <!-- Quick Stats -->
                            <div class="row">
                                <div class="col-4 text-center">
                                    <div class="fw-bold text-primary">{{ $previousRides->count() }}</div>
                                    <small class="text-muted">Completed Rides</small>
                                </div>
                                <div class="col-4 text-center">
                                    <div class="fw-bold text-success">{{ $filteredAvailableRides->count() }}</div>
                                    <small class="text-muted">Available Rides</small>
                                </div>
                                <div class="col-4 text-center">
                                    <div class="fw-bold text-info">{{ $totalReviews }}</div>
                                    <small class="text-muted">Reviews</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-end">
                            <div class="mb-3">
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-check-circle me-1"></i>Verified Driver
                                </span>
                            </div>
                            @if($user->license_number)
                                <div class="small text-muted">
                                    <i class="fas fa-id-card me-1"></i>License: {{ $user->license_number }}
                                </div>
                            @endif
                            @if($user->vehicle_model)
                                <div class="small text-muted">
                                    <i class="fas fa-car me-1"></i>{{ $user->vehicle_model }} ({{ $user->vehicle_year }})
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vehicle Photos -->
            @if($driverDocuments && ($driverDocuments->vehicle_photo_1 || $driverDocuments->vehicle_photo_2 || $driverDocuments->vehicle_photo_3))
            <div class="card shadow border-0 mb-4" style="border-radius: 18px;">
                <div class="card-header bg-primary text-white text-center py-3" style="border-radius: 18px 18px 0 0;">
                    <h5 class="mb-0"><i class="fas fa-images me-2"></i>Vehicle Photos</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        @if($driverDocuments->vehicle_photo_1)
                        <div class="col-md-4 mb-3">
                            <div class="text-center">
                                <img src="{{ asset('storage/' . $driverDocuments->vehicle_photo_1) }}" 
                                     alt="Vehicle Front View" class="img-fluid rounded shadow-sm" 
                                     style="max-height: 200px; width: 100%; object-fit: cover;">
                                <div class="mt-2">
                                    <small class="text-muted">Front View</small>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($driverDocuments->vehicle_photo_2)
                        <div class="col-md-4 mb-3">
                            <div class="text-center">
                                <img src="{{ asset('storage/' . $driverDocuments->vehicle_photo_2) }}" 
                                     alt="Vehicle Side View" class="img-fluid rounded shadow-sm" 
                                     style="max-height: 200px; width: 100%; object-fit: cover;">
                                <div class="mt-2">
                                    <small class="text-muted">Side View</small>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($driverDocuments->vehicle_photo_3)
                        <div class="col-md-4 mb-3">
                            <div class="text-center">
                                <img src="{{ asset('storage/' . $driverDocuments->vehicle_photo_3) }}" 
                                     alt="Vehicle Rear View" class="img-fluid rounded shadow-sm" 
                                     style="max-height: 200px; width: 100%; object-fit: cover;">
                                <div class="mt-2">
                                    <small class="text-muted">Rear View</small>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Rating Distribution -->
            @if($totalReviews > 0)
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <div class="card shadow border-0 h-100" style="border-radius: 18px;">
                        <div class="card-header bg-light text-center py-3" style="border-radius: 18px 18px 0 0;">
                            <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Rating Distribution</h5>
                        </div>
                        <div class="card-body p-4">
                            @for($i = 5; $i >= 1; $i--)
                                <div class="d-flex align-items-center mb-2">
                                    <div class="me-3" style="width: 60px;">
                                        <span class="text-warning">{{ $i }} ★</span>
                                    </div>
                                    <div class="flex-grow-1 me-3">
                                        <div class="progress" style="height: 8px;">
                                            @php
                                                $percentage = $totalReviews > 0 ? ($ratingDistribution[$i] / $totalReviews) * 100 : 0;
                                            @endphp
                                            <div class="progress-bar bg-warning" style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </div>
                                    <div style="width: 40px; text-align: right;">
                                        <small class="text-muted">{{ $ratingDistribution[$i] }}</small>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="card shadow border-0 h-100" style="border-radius: 18px;">
                        <div class="card-header bg-light text-center py-3" style="border-radius: 18px 18px 0 0;">
                            <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Category Ratings</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <div class="text-center">
                                        <div class="h4 text-primary mb-1">{{ number_format($averageDriverRating, 1) }}</div>
                                        <small class="text-muted">Driver</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="text-center">
                                        <div class="h4 text-warning mb-1">{{ number_format($averageVehicleRating, 1) }}</div>
                                        <small class="text-muted">Vehicle</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Available Rides -->
            @if($filteredAvailableRides->isNotEmpty() || $filteredReturnRides->isNotEmpty())
            <div class="card shadow border-0 mb-4" style="border-radius: 18px;">
                <div class="card-header bg-success text-white text-center py-3" style="border-radius: 18px 18px 0 0;">
                    <h5 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Available Rides</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Go Trips -->
                    @if($filteredAvailableRides->isNotEmpty())
                    <div class="mb-4">
                        <h6 class="text-primary mb-3"><i class="fas fa-arrow-right me-2"></i>Go Trips</h6>
                        <div class="row">
                            @foreach($filteredAvailableRides as $ride)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title mb-0 text-primary">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                {{ $ride->station_location }}
                                            </h6>
                                            <span class="badge {{ $ride->is_exclusive ? 'bg-danger' : 'bg-success' }} small">
                                                {{ $ride->is_exclusive ? 'EXCLUSIVE' : 'SHARED' }}
                                            </span>
                                        </div>
                                        
                                        <div class="text-center mb-2">
                                            <i class="fas fa-arrow-down text-muted"></i>
                                        </div>
                                        
                                        <h6 class="card-title mb-2 text-primary">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {{ $ride->destination }}
                                        </h6>
                                        
                                        <div class="row text-center mb-2">
                                            <div class="col-6">
                                                <small class="text-muted d-block">Date</small>
                                                <span class="fw-semibold">{{ $ride->date->format('M d, Y') }}</span>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block">Time</small>
                                                <span class="fw-semibold">{{ $ride->time ? $ride->time->format('H:i') : '-' }}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="row text-center mb-3">
                                            <div class="col-6">
                                                <small class="text-muted d-block">Available Seats</small>
                                                @if($ride->is_exclusive)
                                                    <span class="fw-semibold text-success">Exclusive</span>
                                                @else
                                                    <span class="fw-semibold text-success">{{ $ride->available_seats }}</span>
                                                @endif
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block">Price</small>
                                                <span class="fw-semibold text-primary">
                                                    @if($ride->is_exclusive)
                                                        ${{ number_format($ride->go_to_exclusive_price, 2) }}
                                                    @else
                                                        ${{ number_format($ride->go_to_price_per_person, 2) }}/seat
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                        
                                        @if($ride->go_completion_status === 'pending')
                                            @if($ride->is_exclusive)
                                                <a href="{{ route('booking.payment', ['rideId' => $ride->id, 'tripType' => 'go']) }}" 
                                                   class="btn btn-primary btn-sm w-100">
                                                    <i class="fas fa-credit-card me-1"></i>Book Exclusive
                                                </a>
                                            @else
                                                <a href="{{ route('booking.seat-selection', ['rideId' => $ride->id, 'tripType' => 'go']) }}" 
                                                   class="btn btn-primary btn-sm w-100">
                                                    <i class="fas fa-bookmark me-1"></i>Select Seats
                                                </a>
                                            @endif
                                        @else
                                            <div class="text-center">
                                                <span class="badge bg-secondary">Not Available</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Return Trips -->
                    @if($filteredReturnRides->isNotEmpty())
                    <div>
                        <h6 class="text-warning mb-3"><i class="fas fa-arrow-left me-2"></i>Return Trips</h6>
                        <div class="row">
                            @foreach($filteredReturnRides as $ride)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title mb-0 text-warning">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                {{ $ride->destination }}
                                            </h6>
                                            <span class="badge {{ $ride->return_is_exclusive ? 'bg-danger' : 'bg-success' }} small">
                                                {{ $ride->return_is_exclusive ? 'EXCLUSIVE' : 'SHARED' }}
                                            </span>
                                        </div>
                                        
                                        <div class="text-center mb-2">
                                            <i class="fas fa-arrow-down text-muted"></i>
                                        </div>
                                        
                                        <h6 class="card-title mb-2 text-warning">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {{ $ride->station_location }}
                                        </h6>
                                        
                                        <div class="row text-center mb-2">
                                            <div class="col-6">
                                                <small class="text-muted d-block">Date</small>
                                                <span class="fw-semibold">{{ $ride->return_date->format('M d, Y') }}</span>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block">Time</small>
                                                <span class="fw-semibold">{{ $ride->return_time ? $ride->return_time->format('H:i') : '-' }}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="row text-center mb-3">
                                            <div class="col-6">
                                                <small class="text-muted d-block">Available Seats</small>
                                                @if($ride->return_is_exclusive)
                                                    <span class="fw-semibold text-success">Exclusive</span>
                                                @else
                                                    <span class="fw-semibold text-success">{{ $ride->return_available_seats }}</span>
                                                @endif
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block">Price</small>
                                                <span class="fw-semibold text-warning">
                                                    @if($ride->return_is_exclusive)
                                                        ${{ number_format($ride->return_exclusive_price, 2) }}
                                                    @else
                                                        ${{ number_format($ride->return_price_per_person, 2) }}/seat
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                        
                                        @if($ride->return_completion_status === 'pending')
                                            @if($ride->return_is_exclusive)
                                                <a href="{{ route('booking.payment', ['rideId' => $ride->id, 'tripType' => 'return']) }}" 
                                                   class="btn btn-warning btn-sm w-100">
                                                    <i class="fas fa-credit-card me-1"></i>Book Exclusive
                                                </a>
                                            @else
                                                <a href="{{ route('booking.seat-selection', ['rideId' => $ride->id, 'tripType' => 'return']) }}" 
                                                   class="btn btn-warning btn-sm w-100">
                                                    <i class="fas fa-bookmark me-1"></i>Select Seats
                                                </a>
                                            @endif
                                        @else
                                            <div class="text-center">
                                                <span class="badge bg-secondary">Not Available</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Previous Rides -->
            @if($previousRides->isNotEmpty())
            <div class="card shadow border-0 mb-4" style="border-radius: 18px;">
                <div class="card-header bg-info text-white text-center py-3" style="border-radius: 18px 18px 0 0;">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Previous Rides</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        @foreach($previousRides as $ride)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title mb-0 text-info">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {{ $ride->station_location }}
                                        </h6>
                                        <span class="badge bg-success small">
                                            <i class="fas fa-check-circle me-1"></i>Completed
                                        </span>
                                    </div>
                                    
                                    <div class="text-center mb-2">
                                        <i class="fas fa-arrow-down text-muted"></i>
                                    </div>
                                    
                                    <h6 class="card-title mb-2 text-info">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ $ride->destination }}
                                    </h6>
                                    
                                    <div class="row text-center mb-2">
                                        <div class="col-6">
                                            <small class="text-muted d-block">Date</small>
                                            <span class="fw-semibold">{{ $ride->date->format('M d, Y') }}</span>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Time</small>
                                            <span class="fw-semibold">{{ $ride->time ? $ride->time->format('H:i') : '-' }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <small class="text-muted d-block">Type</small>
                                            <span class="badge {{ $ride->is_exclusive ? 'bg-danger' : 'bg-success' }} small">
                                                {{ $ride->is_exclusive ? 'EXCLUSIVE' : 'SHARED' }}
                                            </span>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Price</small>
                                            <span class="fw-semibold text-info">
                                                @if($ride->is_exclusive)
                                                    ${{ number_format($ride->go_to_exclusive_price, 2) }}
                                                @else
                                                    ${{ number_format($ride->go_to_price_per_person, 2) }}/seat
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Reviews -->
            @if($reviews->isNotEmpty())
            <div class="card shadow border-0" style="border-radius: 18px;">
                <div class="card-header bg-warning text-dark text-center py-3" style="border-radius: 18px 18px 0 0;">
                    <h5 class="mb-0"><i class="fas fa-star me-2"></i>Customer Reviews ({{ $totalReviews }})</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        @foreach($reviews->take(6) as $review)
                        <div class="col-12 mb-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <!-- Customer Info -->
                                            <div class="d-flex align-items-center mb-3">
                                                @if($review->user && $review->user->profile_picture)
                                                    <img src="{{ asset('storage/' . $review->user->profile_picture) }}" 
                                                         alt="Profile" class="rounded-circle me-3" width="48" height="48"
                                                         style="object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                         style="width: 48px; height: 48px;">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-1">{{ $review->user ? $review->user->name : 'Anonymous' }}</h6>
                                                    <small class="text-muted">
                                                        {{ $review->created_at->format('M d, Y') }}
                                                        <span class="badge bg-info ms-2">{{ strtoupper($review->trip_type) }} TRIP</span>
                                                    </small>
                                                </div>
                                            </div>

                                            <!-- Ride Details -->
                                            <div class="mb-3">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                                    <span class="fw-semibold">
                                                        {{ $review->trip_type === 'return' ? $review->ride->destination : $review->ride->station_location }}
                                                    </span>
                                                    <i class="fas fa-arrow-right mx-2 text-muted"></i>
                                                    <span class="fw-semibold">
                                                        {{ $review->trip_type === 'return' ? $review->ride->station_location : $review->ride->destination }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Review Text -->
                                            @if($review->review_text)
                                                <div class="mb-3">
                                                    <p class="mb-0 fst-italic">"{{ $review->review_text }}"</p>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="col-md-4">
                                            <!-- Overall Rating -->
                                            <div class="text-center mb-3">
                                                <div class="h3 text-warning mb-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star{{ $i <= $review->overall_rating ? '' : '-o' }}"></i>
                                                    @endfor
                                                </div>
                                                <div class="fw-bold">{{ $review->overall_rating }}/5</div>
                                                <small class="text-muted">Overall Rating</small>
                                            </div>

                                            <!-- Category Ratings -->
                                            <div class="row text-center">
                                                <div class="col-6 mb-2">
                                                    <div class="small text-muted">Driver</div>
                                                    <div class="fw-semibold text-primary">{{ $review->driver_rating }}/5</div>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <div class="small text-muted">Vehicle</div>
                                                    <div class="fw-semibold text-warning">{{ $review->vehicle_rating }}/5</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.card {
    transition: box-shadow 0.2s;
}
.card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
}
.progress {
    border-radius: 10px;
}
.progress-bar {
    border-radius: 10px;
}
.btn {
    border-radius: 8px;
    font-weight: 600;
}
</style>
@endsection 