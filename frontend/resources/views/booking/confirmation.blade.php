@extends('layouts.app')

@section('title', 'Booking Confirmation')

@section('content')
<div class="container-fluid bg-light min-vh-100 py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="text-center mb-4">
                <div class="mb-3">
                    <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                </div>
                <h1 class="fw-bold text-success">Booking Confirmed!</h1>
                <p class="text-muted">Your ride has been successfully booked. Here are your booking details:</p>
            </div>

            <div class="card shadow border-0 mb-4" style="border-radius: 18px;">
                <div class="card-header bg-success text-white text-center py-3" style="border-radius: 18px 18px 0 0;">
                    <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Booking Details</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-hashtag text-primary me-2"></i>
                                    <span class="fw-semibold">Booking ID:</span>
                                </div>
                                <p class="mb-0 ms-4 fw-bold text-primary">#{{ $booking->id }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                    <span class="fw-semibold">Pickup:</span>
                                </div>
                                <p class="mb-0 ms-4">
                                    {{ $booking->trip_type === 'return' ? $booking->ride->destination : $booking->ride->station_location }}
                                </p>
                                @php
                                    $pickupMapUrl = $booking->trip_type === 'return' ? $booking->ride->return_station_location_map_url : $booking->ride->station_location_map_url;
                                    $pickupLocation = $booking->trip_type === 'return' ? $booking->ride->destination : $booking->ride->station_location;
                                @endphp
                                @if($pickupMapUrl)
                                    <a href="{{ $pickupMapUrl }}" target="_blank" class="btn btn-sm btn-outline-primary mt-1 ms-4">
                                        <i class="fas fa-map-marker-alt me-1"></i>View on Map
                                    </a>
                                @else
                                    <a href="https://maps.google.com/?q={{ urlencode($pickupLocation) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-1 ms-4">
                                        <i class="fas fa-map-marker-alt me-1"></i>View on Map
                                    </a>
                                @endif
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-map-pin text-secondary me-2"></i>
                                    <span class="fw-semibold">Dropoff:</span>
                                </div>
                                <p class="mb-0 ms-4">
                                    {{ $booking->trip_type === 'return' ? $booking->ride->station_location : $booking->ride->destination }}
                                </p>
                                @php
                                    $destinationMapUrl = $booking->trip_type === 'return' ? $booking->ride->return_destination_map_url : $booking->ride->destination_map_url;
                                    $destinationLocation = $booking->trip_type === 'return' ? $booking->ride->station_location : $booking->ride->destination;
                                @endphp
                                @if($destinationMapUrl)
                                    <a href="{{ $destinationMapUrl }}" target="_blank" class="btn btn-sm btn-outline-success mt-1 ms-4">
                                        <i class="fas fa-map-marker-alt me-1"></i>View on Map
                                    </a>
                                @else
                                    <a href="https://maps.google.com/?q={{ urlencode($destinationLocation) }}" target="_blank" class="btn btn-sm btn-outline-success mt-1 ms-4">
                                        <i class="fas fa-map-marker-alt me-1"></i>View on Map
                                    </a>
                                @endif
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="far fa-calendar-alt text-success me-2"></i>
                                    <span class="fw-semibold">Date:</span>
                                </div>
                                <p class="mb-0 ms-4">
                                    {{ $booking->trip_type === 'return' ? $booking->ride->return_date : $booking->ride->date }}
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="far fa-clock text-warning me-2"></i>
                                    <span class="fw-semibold">Time:</span>
                                </div>
                                <p class="mb-0 ms-4">
                                    {{ $booking->trip_type === 'return' ? $booking->ride->return_time : $booking->ride->time }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-user text-primary me-2"></i>
                                    <span class="fw-semibold">Driver:</span>
                                </div>
                                <p class="mb-0 ms-4">{{ $booking->ride->user->name }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-users text-info me-2"></i>
                                    <span class="fw-semibold">Seats Booked:</span>
                                </div>
                                <p class="mb-0 ms-4">{{ $booking->number_of_seats }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-dollar-sign text-success me-2"></i>
                                    <span class="fw-semibold">Price per seat:</span>
                                </div>
                                <p class="mb-0 ms-4">${{ number_format($booking->price_per_seat, 2) }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-credit-card text-warning me-2"></i>
                                    <span class="fw-semibold">Total Paid:</span>
                                </div>
                                <p class="mb-0 ms-4 fw-bold text-success">${{ number_format($booking->total_price, 2) }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-phone text-info me-2"></i>
                                    <span class="fw-semibold">Contact Phone:</span>
                                </div>
                                <p class="mb-0 ms-4">{{ $booking->contact_phone }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Route Map Preview -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card bg-light border-0" style="border-radius: 8px;">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                                <i class="fas fa-map-marker-alt" style="font-size: 12px;"></i>
                                            </div>
                                            <span class="fw-semibold">{{ $pickupLocation }}</span>
                                        </div>
                                        <div class="text-muted">
                                            <i class="fas fa-arrow-right"></i>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="fw-semibold">{{ $destinationLocation }}</span>
                                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center ms-2" style="width: 30px; height: 30px;">
                                                <i class="fas fa-flag-checkered" style="font-size: 12px;"></i>
                                            </div>
                                        </div>
                                        <div class="ms-3">
                                            @php
                                                $routeUrl = "https://maps.google.com/maps?f=d&saddr=" . urlencode($pickupLocation) . "&daddr=" . urlencode($destinationLocation);
                                            @endphp
                                            <a href="{{ $routeUrl }}" target="_blank" class="btn btn-sm btn-primary">
                                                <i class="fas fa-route me-1"></i>Get Directions
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Passenger Details -->
            <div class="card shadow border-0 mb-4" style="border-radius: 18px;">
                <div class="card-header bg-info text-white text-center py-3" style="border-radius: 18px 18px 0 0;">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Passenger Details</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        @foreach($booking->passenger_details as $index => $passenger)
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <span class="fw-bold">{{ $index + 1 }}</span>
                                </div>
                                <div>
                                    <span class="fw-semibold">{{ $passenger['name'] }}</span>
                                    <br>
                                    <small class="text-muted">Seat {{ $passenger['seat_number'] }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @if($booking->special_requests)
            <!-- Special Requests -->
            <div class="card shadow border-0 mb-4" style="border-radius: 18px;">
                <div class="card-header bg-warning text-dark text-center py-3" style="border-radius: 18px 18px 0 0;">
                    <h5 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Special Requests</h5>
                </div>
                <div class="card-body p-4">
                    <p class="mb-0">{{ $booking->special_requests }}</p>
                </div>
            </div>
            @endif

            <!-- Important Information -->
            <div class="card shadow border-0 mb-4" style="border-radius: 18px;">
                <div class="card-header bg-primary text-white text-center py-3" style="border-radius: 18px 18px 0 0;">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Important Information</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Arrive 10 minutes before departure time
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Bring valid identification
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Contact driver if running late
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Payment completed successfully
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Booking confirmation sent to your email
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Contact support for any issues
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center">
                <a href="{{ route('user.bookings') }}" class="btn btn-primary px-4 py-2 me-3">
                    <i class="fas fa-list me-2"></i>View All Bookings
                </a>
                <a href="{{ route('user.booking.receipt', $booking->id) }}" class="btn btn-success px-4 py-2 me-3" target="_blank">
                    <i class="fas fa-print me-2"></i>Print Receipt
                </a>
                <a href="{{ route('find.rides') }}" class="btn btn-outline-secondary px-4 py-2">
                    <i class="fas fa-search me-2"></i>Find More Rides
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: box-shadow 0.2s;
}
.card:hover {
    box-shadow: 0 8px 24px rgba(0,0,0,0.15) !important;
}
.btn {
    border-radius: 8px;
    font-weight: 600;
}
</style>
@endsection 