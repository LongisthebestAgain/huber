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
                             style="transition: transform 0.2s;">
                            <div class="card-body">
                                <!-- Ride Status Badge -->
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title mb-0 text-primary">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ $ride->station_location }}
                                    </h6>
                                    <div class="d-flex flex-column align-items-end">
                                        <span class="badge {{ $ride->is_exclusive ? 'bg-danger' : 'bg-success' }} small mb-1">
                                            {{ $ride->is_exclusive ? 'EXCLUSIVE' : 'SHARED' }}
                                        </span>
                                        @if($ride->go_completion_status === 'pending')
                                            <span class="badge bg-secondary small">
                                                <i class="fas fa-clock me-1"></i>Pending
                                            </span>
                                        @elseif($ride->go_completion_status === 'ongoing')
                                            <span class="badge bg-warning text-dark small">
                                                <i class="fas fa-play me-1"></i>Ongoing
                                            </span>
                                        @elseif($ride->go_completion_status === 'completed')
                                            <span class="badge bg-success small">
                                                <i class="fas fa-check-circle me-1"></i>Completed
                                            </span>
                                        @endif
                                    </div>
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
                                        @if($ride->available_seats <= 0)
                                            <div class="mt-1">
                                                <span class="badge bg-danger small">FULLY BOOKED</span>
                                            </div>
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
                                
                                <div class="d-flex gap-2 mt-3">
                                    <a href="{{ route('driver.ride.customers', ['ride' => $ride->id, 'tripType' => 'go']) }}" 
                                       class="btn btn-outline-primary btn-sm flex-fill">
                                        <i class="fas fa-users me-1"></i>
                                        View Customers
                                    </a>
                                    
                                    @if($ride->go_completion_status === 'pending')
                                        <form method="POST" action="{{ route('driver.ride.ongoing', ['rideId' => $ride->id, 'tripType' => 'go']) }}" 
                                              class="flex-fill" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm w-100" 
                                                    onclick="return confirm('Are you sure you want to start this ride? This will remove it from the find rides page.')">
                                                <i class="fas fa-play me-1"></i>Start Ride
                                            </button>
                                        </form>
                                    @elseif($ride->go_completion_status === 'ongoing')
                                        <form method="POST" action="{{ route('driver.ride.complete', ['rideId' => $ride->id, 'tripType' => 'go']) }}" 
                                              class="flex-fill" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm w-100" 
                                                    onclick="return confirm('Are you sure you want to mark this ride as completed?')">
                                                <i class="fas fa-check-circle me-1"></i>Complete
                                            </button>
                                        </form>
                                    @elseif($ride->go_completion_status === 'completed')
                                        <a href="{{ route('driver.ride.reviews', $ride->id) }}" 
                                           class="btn btn-outline-info btn-sm flex-fill">
                                            <i class="fas fa-star me-1"></i>View Reviews
                                        </a>
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
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">
            <i class="fas fa-undo me-2"></i>Return Rides ({{ $returnRides->count() }})
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach($returnRides as $ride)
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card h-100 border-0 shadow-sm ride-card {{ $ride->return_available_seats <= 0 ? 'fully-booked' : '' }}" 
                         style="transition: transform 0.2s;">
                        <div class="card-body">
                            <!-- Ride Status Badge -->
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title mb-0 text-info">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    {{ $ride->destination }}
                                </h6>
                                <div class="d-flex flex-column align-items-end">
                                    <span class="badge {{ $ride->return_is_exclusive ? 'bg-danger' : 'bg-success' }} small mb-1">
                                        {{ $ride->return_is_exclusive ? 'EXCLUSIVE' : 'SHARED' }}
                                    </span>
                                    @if($ride->return_completion_status === 'pending')
                                        <span class="badge bg-secondary small">
                                            <i class="fas fa-clock me-1"></i>Pending
                                        </span>
                                    @elseif($ride->return_completion_status === 'ongoing')
                                        <span class="badge bg-warning text-dark small">
                                            <i class="fas fa-play me-1"></i>Ongoing
                                        </span>
                                    @elseif($ride->return_completion_status === 'completed')
                                        <span class="badge bg-success small">
                                            <i class="fas fa-check-circle me-1"></i>Completed
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="text-center mb-2">
                                <i class="fas fa-arrow-down text-muted"></i>
                            </div>
                            
                            <h6 class="card-title mb-2 text-info">
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
                                    @if($ride->return_available_seats <= 0)
                                        <div class="mt-1">
                                            <span class="badge bg-danger small">FULLY BOOKED</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Price</small>
                                    <span class="fw-semibold text-info">
                                        @if($ride->return_is_exclusive)
                                            ${{ number_format($ride->return_exclusive_price, 2) }}
                                        @else
                                            ${{ number_format($ride->return_price_per_person, 2) }}/seat
                                        @endif
                                    </span>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2 mt-3">
                                <a href="{{ route('driver.ride.customers', ['ride' => $ride->id, 'tripType' => 'return']) }}" 
                                   class="btn btn-outline-info btn-sm flex-fill">
                                    <i class="fas fa-users me-1"></i>
                                    View Customers
                                </a>
                                
                                @if($ride->return_completion_status === 'pending')
                                    <form method="POST" action="{{ route('driver.ride.ongoing', ['rideId' => $ride->id, 'tripType' => 'return']) }}" 
                                          class="flex-fill" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm w-100" 
                                                onclick="return confirm('Are you sure you want to start this return ride? This will remove it from the find rides page.')">
                                            <i class="fas fa-play me-1"></i>Start Ride
                                        </button>
                                    </form>
                                @elseif($ride->return_completion_status === 'ongoing')
                                    <form method="POST" action="{{ route('driver.ride.complete', ['rideId' => $ride->id, 'tripType' => 'return']) }}" 
                                          class="flex-fill" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm w-100" 
                                                onclick="return confirm('Are you sure you want to mark this return ride as completed?')">
                                            <i class="fas fa-check-circle me-1"></i>Complete
                                        </button>
                                    </form>
                                @elseif($ride->return_completion_status === 'completed')
                                    <a href="{{ route('driver.ride.reviews', $ride->id) }}" 
                                       class="btn btn-outline-info btn-sm flex-fill">
                                        <i class="fas fa-star me-1"></i>View Reviews
                                    </a>
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
    opacity: 0.85;
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
}

.ride-card.fully-booked:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
}

.fully-booked-badge {
    text-align: center;
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

