@extends('layouts.ride-management')

@section('title', 'Ride Management - Hubber')

@section('main')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h2 class="h4 mb-4">Ride Management</h2>
        <p class="text-muted">This is the Ride Management dashboard for drivers. Here you will be able to create, view, and manage your rides.</p>
    </div>
</div>

<!-- Go Rides Section -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="fas fa-arrow-right me-2"></i>Go Rides ({{ $goRides->count() }})
        </h5>
    </div>
    <div class="card-body">
        @if($goRides->isEmpty())
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                You haven't listed any go rides yet. 
                <a href="{{ route('driver.rides.create') }}" class="alert-link">Create your first ride</a>
            </div>
        @else
            <div class="row">
                @foreach($goRides as $ride)
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card h-100 border-0 shadow-sm ride-card {{ $ride->available_seats <= 0 ? 'fully-booked' : '' }}" 
                             style="cursor: {{ $ride->available_seats > 0 ? 'pointer' : 'not-allowed' }}; transition: transform 0.2s;" 
                             onclick="{{ $ride->available_seats > 0 ? 'window.location.href=\'' . route('driver.ride.customers', ['ride' => $ride->id, 'tripType' => 'go']) . '\'' : '' }}">
                            <div class="card-body">
                                @if($ride->available_seats <= 0)
                                    <div class="fully-booked-overlay">
                                        <span class="badge bg-danger">FULLY BOOKED</span>
                                    </div>
                                @endif
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
                                
                                <div class="row text-center mb-2">
                                    <div class="col-6">
                                        <small class="text-muted d-block">Available Seats</small>
                                        <span class="fw-semibold {{ $ride->available_seats <= 0 ? 'text-danger' : 'text-success' }}">{{ $ride->available_seats }}</span>
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
                                
                                <div class="text-center mt-3">
                                    @if($ride->available_seats > 0)
                                        <button class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-users me-1"></i>
                                            View Customers
                                        </button>
                                    @else
                                        <button class="btn btn-outline-secondary btn-sm" disabled>
                                            <i class="fas fa-users me-1"></i>
                                            Fully Booked
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- Return Rides Section -->
@if($returnRides->isNotEmpty())
<div class="card shadow-sm mb-4">
    <div class="card-header bg-warning text-dark">
        <h5 class="mb-0">
            <i class="fas fa-undo me-2"></i>Return Rides ({{ $returnRides->count() }})
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach($returnRides as $ride)
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card h-100 border-0 shadow-sm ride-card {{ $ride->return_available_seats <= 0 ? 'fully-booked' : '' }}" 
                         style="cursor: {{ $ride->return_available_seats > 0 ? 'pointer' : 'not-allowed' }}; transition: transform 0.2s;" 
                         onclick="{{ $ride->return_available_seats > 0 ? 'window.location.href=\'' . route('driver.ride.customers', ['ride' => $ride->id, 'tripType' => 'return']) . '\'' : '' }}">
                        <div class="card-body">
                            @if($ride->return_available_seats <= 0)
                                <div class="fully-booked-overlay">
                                    <span class="badge bg-danger">FULLY BOOKED</span>
                                </div>
                            @endif
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
                            
                            <div class="row text-center mb-2">
                                <div class="col-6">
                                    <small class="text-muted d-block">Available Seats</small>
                                    <span class="fw-semibold {{ $ride->return_available_seats <= 0 ? 'text-danger' : 'text-success' }}">{{ $ride->return_available_seats }}</span>
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
                            
                            <div class="text-center mt-3">
                                @if($ride->return_available_seats > 0)
                                    <button class="btn btn-outline-warning btn-sm">
                                        <i class="fas fa-users me-1"></i>
                                        View Customers
                                    </button>
                                @else
                                    <button class="btn btn-outline-secondary btn-sm" disabled>
                                        <i class="fas fa-users me-1"></i>
                                        Fully Booked
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Quick Actions Card -->
<div class="card shadow-sm">
    <div class="card-header bg-light">
        <h5 class="mb-0">
            <i class="fas fa-bolt me-2"></i>Quick Actions
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-3">
                <a href="{{ route('driver.rides.create') }}" class="btn btn-primary w-100">
                    <i class="fas fa-plus-circle me-2"></i>Create New Ride
                </a>
            </div>
            <div class="col-md-4 mb-3">
                <a href="{{ route('driver.my-rides') }}" class="btn btn-outline-primary w-100">
                    <i class="fas fa-list me-2"></i>Manage Rides
                </a>
            </div>
            <div class="col-md-4 mb-3">
                <a href="{{ route('driver.earnings') }}" class="btn btn-outline-success w-100">
                    <i class="fas fa-chart-line me-2"></i>View Earnings
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.ride-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
}

.ride-card.fully-booked {
    opacity: 0.7;
    background-color: #f8f9fa;
}

.ride-card.fully-booked:hover {
    transform: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

.fully-booked-overlay {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 10;
}

.ride-card .card-body {
    padding: 1.25rem;
    position: relative;
}

.ride-card .badge {
    font-size: 0.7rem;
}
</style>
@endsection

