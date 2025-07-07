@extends('layouts.ride-management')

@section('title', 'Ride Customers - Hubber')

@section('main')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="h4 mb-2">Ride Customers</h2>
                <p class="text-muted mb-0">
                    View all customers who booked this ride
                    @if($tripType)
                        <span class="badge {{ $tripType === 'return' ? 'bg-warning' : 'bg-primary' }} ms-2">
                            {{ strtoupper($tripType) }} TRIP ONLY
                        </span>
                    @endif
                </p>
            </div>
            <a href="{{ route('driver.ride.management') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>
</div>

<!-- Ride Details Card -->
<div class="card shadow-sm mb-4">
    <div class="card-header {{ $tripType === 'return' ? 'bg-warning text-dark' : 'bg-primary text-white' }}">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-info-circle me-2"></i>
                @if($tripType === 'return')
                    Return Trip Details
                @elseif($tripType === 'go')
                    Go Trip Details
                @else
                    Ride Details
                @endif
            </h5>
            
            <!-- Completion Status and Button -->
            <div class="d-flex align-items-center">
                @php
                    $completionStatus = $tripType === 'return' ? $ride->return_completion_status : $ride->go_completion_status;
                    $completedAt = $tripType === 'return' ? $ride->return_completed_at : $ride->go_completed_at;
                @endphp
                
                @if($completionStatus === 'completed')
                    <span class="badge bg-success me-3">
                        <i class="fas fa-check-circle me-1"></i>Completed
                        @if($completedAt)
                            <br><small>{{ $completedAt->format('M d, Y H:i') }}</small>
                        @endif
                    </span>
                @elseif($completionStatus === 'ongoing')
                    <span class="badge bg-warning text-dark me-3">
                        <i class="fas fa-play me-1"></i>Ongoing
                    </span>
                    <form method="POST" action="{{ route('driver.ride.complete', ['rideId' => $ride->id, 'tripType' => $tripType]) }}" class="me-3">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm" 
                                onclick="return confirm('Are you sure you want to mark this ride as completed?')">
                            <i class="fas fa-check-circle me-1"></i>Mark as Completed
                        </button>
                    </form>
                @else
                    <span class="badge bg-secondary me-3">
                        <i class="fas fa-clock me-1"></i>Pending
                    </span>
                    <form method="POST" action="{{ route('driver.ride.ongoing', ['rideId' => $ride->id, 'tripType' => $tripType]) }}" class="me-3">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm" 
                                onclick="return confirm('Are you sure you want to start this ride? This will remove it from the find rides page and customers will be able to review once completed.')">
                            <i class="fas fa-play me-1"></i>Start Ride
                        </button>
                    </form>
                @endif
                
                <a href="{{ route('driver.ride.reviews', $ride->id) }}" class="btn btn-outline-info btn-sm">
                    <i class="fas fa-star me-1"></i>View Reviews
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if($tripType === 'return')
            <!-- Return Trip Details -->
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Return Route</label>
                        <div class="d-flex align-items-center">
                            <div class="text-warning fw-semibold">{{ $ride->destination }}</div>
                            <i class="fas fa-arrow-right mx-2 text-muted"></i>
                            <div class="text-warning fw-semibold">{{ $ride->station_location }}</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Return Date & Time</label>
                        <div class="fw-semibold">
                            {{ $ride->return_date->format('l, F d, Y') }} at {{ $ride->return_time ? $ride->return_time->format('H:i') : '-' }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Return Type</label>
                        <div>
                            <span class="badge {{ $ride->return_is_exclusive ? 'bg-danger' : 'bg-success' }} fs-6">
                                {{ $ride->return_is_exclusive ? 'EXCLUSIVE' : 'SHARED' }}
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Return Price</label>
                        <div class="fw-semibold text-warning">
                            @if($ride->return_is_exclusive)
                                ${{ number_format($ride->return_exclusive_price, 2) }} (Total)
                            @else
                                ${{ number_format($ride->return_price_per_person, 2) }}/person
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Return Available Seats</label>
                        <div class="fw-semibold text-success">{{ $ride->return_available_seats }}</div>
                    </div>
                </div>
            </div>
        @else
            <!-- Go Trip Details -->
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Route</label>
                        <div class="d-flex align-items-center">
                            <div class="text-primary fw-semibold">{{ $ride->station_location }}</div>
                            <i class="fas fa-arrow-right mx-2 text-muted"></i>
                            <div class="text-primary fw-semibold">{{ $ride->destination }}</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Date & Time</label>
                        <div class="fw-semibold">
                            {{ $ride->date->format('l, F d, Y') }} at {{ $ride->time ? $ride->time->format('H:i') : '-' }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Ride Type</label>
                        <div>
                            <span class="badge {{ $ride->is_exclusive ? 'bg-danger' : 'bg-success' }} fs-6">
                                {{ $ride->is_exclusive ? 'EXCLUSIVE' : 'SHARED' }}
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Price</label>
                        <div class="fw-semibold text-primary">
                            @if($ride->is_exclusive)
                                ${{ number_format($ride->go_to_exclusive_price, 2) }} (Total)
                            @else
                                ${{ number_format($ride->go_to_price_per_person, 2) }}/person
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Available Seats</label>
                        <div class="fw-semibold text-success">{{ $ride->available_seats }}</div>
                    </div>
                </div>
            </div>
        @endif
        
        @if(!$tripType && $ride->is_two_way && $ride->return_date && $ride->return_time)
            <hr class="my-3">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Return Route</label>
                        <div class="d-flex align-items-center">
                            <div class="text-primary fw-semibold">{{ $ride->destination }}</div>
                            <i class="fas fa-arrow-right mx-2 text-muted"></i>
                            <div class="text-primary fw-semibold">{{ $ride->station_location }}</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Return Date & Time</label>
                        <div class="fw-semibold">
                            {{ $ride->return_date->format('l, F d, Y') }} at {{ $ride->return_time ? $ride->return_time->format('H:i') : '-' }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Return Type</label>
                        <div>
                            <span class="badge {{ $ride->return_is_exclusive ? 'bg-danger' : 'bg-success' }} fs-6">
                                {{ $ride->return_is_exclusive ? 'EXCLUSIVE' : 'SHARED' }}
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Return Price</label>
                        <div class="fw-semibold text-primary">
                            @if($ride->return_is_exclusive)
                                ${{ number_format($ride->return_exclusive_price, 2) }} (Total)
                            @else
                                ${{ number_format($ride->return_price_per_person, 2) }}/person
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Return Available Seats</label>
                        <div class="fw-semibold text-success">{{ $ride->return_available_seats }}</div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Seat Visualization Card -->
<div class="card shadow-sm mb-4">
    <div class="card-header {{ $tripType === 'return' ? 'bg-warning text-dark' : 'bg-info text-white' }}">
        <h5 class="mb-0">
            <i class="fas fa-chair me-2"></i>
            @if($tripType === 'return')
                Return Trip Seat Map
            @elseif($tripType === 'go')
                Go Trip Seat Map
            @else
                Seat Map
            @endif
            <span class="badge bg-light text-dark ms-2">{{ $seatInfo['total_seats'] }} Total Seats</span>
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <div class="seat-map-container">
                    <div class="seat-map">
                        @foreach($seatInfo['seat_map'] as $seat)
                            <div class="seat-item" 
                                 data-bs-toggle="tooltip" 
                                 data-bs-placement="top"
                                 title="{{ $seat['is_booked'] ? 'Booked by: ' . $seat['customer_name'] . ' | Passenger: ' . $seat['passenger_name'] . ' | Phone: ' . $seat['contact_phone'] . ' | Ref: ' . $seat['booking_reference'] : 'Available seat' }}">
                                <div class="seat-label {{ $seat['is_booked'] ? 'booked' : 'available' }}">
                                    {{ $seat['seat_number'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="seat-stats">
                    <h6 class="fw-bold mb-3">Seat Statistics</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Seats:</span>
                        <span class="fw-semibold">{{ $seatInfo['total_seats'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Available:</span>
                        <span class="fw-semibold text-success">{{ $seatInfo['available_seats'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Booked:</span>
                        <span class="fw-semibold text-danger">{{ $seatInfo['booked_seats'] }}</span>
                    </div>
                    
                    <div class="seat-legend">
                        <h6 class="fw-bold mb-2">Legend</h6>
                        <div class="d-flex align-items-center mb-2">
                            <div class="seat-legend-item available me-2"></div>
                            <span>Available</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="seat-legend-item booked me-2"></div>
                            <span class="text-danger fw-bold">Booked</span>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Tip:</strong> Hover over booked seats to see customer details.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Customers Card -->
<div class="card shadow-sm">
    <div class="card-header {{ $tripType === 'return' ? 'bg-warning text-dark' : 'bg-success text-white' }}">
        <h5 class="mb-0">
            <i class="fas fa-users me-2"></i>
            @if($tripType === 'return')
                Return Trip Customers ({{ $bookings->count() }})
            @elseif($tripType === 'go')
                Go Trip Customers ({{ $bookings->count() }})
            @else
                All Customers ({{ $bookings->count() }})
            @endif
        </h5>
    </div>
    <div class="card-body">
        @if($bookings->isEmpty())
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                @if($tripType === 'return')
                    No customers have booked the return trip yet.
                @elseif($tripType === 'go')
                    No customers have booked the go trip yet.
                @else
                    No customers have booked this ride yet.
                @endif
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Customer Name</th>
                            <th>Profile Picture</th>
                            <th>Trip Type</th>
                            <th>Seats Booked</th>
                            <th>Total Price</th>
                            <th>Payment Method</th>
                            <th>Contact Phone</th>
                            <th>Booking Reference</th>
                            <th>Booking Date</th>
                            <th>Passenger Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $i => $booking)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>
                                @if($booking->user)
                                    <div class="fw-semibold">{{ $booking->user->name }}</div>
                                    <small class="text-muted">{{ $booking->user->email }}</small>
                                @else
                                    <span class="text-muted">Unknown</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->user && $booking->user->profile_picture)
                                    <img src="{{ asset('storage/' . $booking->user->profile_picture) }}" 
                                         alt="Profile" class="rounded-circle" width="40" height="40"
                                         style="object-fit: cover;">
                                @else
                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 40px; height: 40px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $booking->trip_type === 'return' ? 'bg-warning' : 'bg-primary' }}">
                                    {{ strtoupper($booking->trip_type) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $booking->number_of_seats }} seat(s)</span>
                                @if($booking->selected_seats && is_array($booking->selected_seats))
                                    <br><small class="text-muted">Seats: {{ implode(', ', $booking->selected_seats) }}</small>
                                @endif
                            </td>
                            <td class="text-success fw-bold">${{ number_format($booking->total_price, 2) }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ strtoupper($booking->payment_method) }}</span>
                            </td>
                            <td>
                                <a href="tel:{{ $booking->contact_phone }}" class="text-decoration-none">
                                    <i class="fas fa-phone me-1"></i>{{ $booking->contact_phone }}
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-dark">{{ $booking->booking_reference }}</span>
                            </td>
                            <td>{{ $booking->created_at ? $booking->created_at->format('M d, Y H:i') : '-' }}</td>
                            <td>
                                @if($booking->passenger_details && is_array($booking->passenger_details))
                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#passengerModal{{ $booking->id }}">
                                        <i class="fas fa-eye me-1"></i>View
                                    </button>
                                    
                                    <!-- Passenger Details Modal -->
                                    <div class="modal fade" id="passengerModal{{ $booking->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Passenger Details</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-sm">
                                                            <thead>
                                                                <tr>
                                                                    <th>Seat</th>
                                                                    <th>Name</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($booking->passenger_details as $passenger)
                                                                <tr>
                                                                    <td>{{ $passenger['seat_number'] ?? '-' }}</td>
                                                                    <td>{{ $passenger['name'] ?? 'Unknown' }}</td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    @if($booking->special_requests)
                                                        <div class="mt-3">
                                                            <label class="form-label fw-bold">Special Requests:</label>
                                                            <p class="text-muted">{{ $booking->special_requests }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">No details</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<style>
.seat-map-container {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 2rem;
}

.seat-map {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(60px, 1fr));
    gap: 1rem;
    max-width: 600px;
    margin: 0 auto;
}

.seat-item {
    text-align: center;
    position: relative;
}

.seat-label {
    display: inline-block;
    width: 50px;
    height: 50px;
    line-height: 50px;
    text-align: center;
    border-radius: 8px;
    font-weight: bold;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    cursor: pointer;
}

.seat-label.available {
    background: #28a745;
    color: white;
    border-color: #28a745;
}

.seat-label.available:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.seat-label.booked {
    background: #dc3545;
    color: white;
    border-color: #dc3545;
    cursor: help;
}

.seat-label.booked:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
}

.seat-legend-item {
    width: 30px;
    height: 30px;
    border-radius: 6px;
    border: 2px solid #dee2e6;
}

.seat-legend-item.available {
    background: #28a745;
}

.seat-legend-item.booked {
    background: #dc3545;
}

.seat-stats {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.5rem;
    height: 100%;
}

/* Custom tooltip styles */
.tooltip {
    font-size: 0.875rem;
}

.tooltip-inner {
    max-width: 300px;
    text-align: left;
    padding: 0.75rem;
    background-color: #343a40;
    border-radius: 8px;
}

.bs-tooltip-top .tooltip-arrow::before {
    border-top-color: #343a40;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            html: true,
            delay: { show: 100, hide: 100 }
        });
    });

    // Add click event for booked seats to show more details
    document.querySelectorAll('.seat-label.booked').forEach(function(seat) {
        seat.addEventListener('click', function() {
            const tooltip = bootstrap.Tooltip.getInstance(this);
            if (tooltip) {
                tooltip.show();
                setTimeout(() => {
                    tooltip.hide();
                }, 3000);
            }
        });
    });
});
</script>
@endsection 