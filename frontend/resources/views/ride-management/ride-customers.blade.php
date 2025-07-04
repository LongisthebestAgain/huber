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
@endsection 