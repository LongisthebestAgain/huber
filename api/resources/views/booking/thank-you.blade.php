@extends('layouts.app')

@section('title', 'Thank You for Your Booking')

@section('content')
<div class="container-fluid bg-light min-vh-100 py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="text-center mb-5">
                <div class="mb-4">
                    <i class="fas fa-heart text-danger" style="font-size: 5rem;"></i>
                </div>
                <h1 class="fw-bold text-success mb-3">Thank You for Your Purchase!</h1>
                <p class="text-muted fs-5">Your booking has been successfully completed. We've sent a confirmation email with all the details.</p>
                <div class="alert alert-success d-inline-block" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Booking Reference: {{ $booking->booking_reference }}</strong>
                </div>
            </div>

            <!-- Quick Summary Card -->
            <div class="card shadow border-0 mb-4" style="border-radius: 18px;">
                <div class="card-header bg-primary text-white text-center py-3" style="border-radius: 18px 18px 0 0;">
                    <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Booking Summary</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                    <span class="fw-semibold">From:</span>
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
                                    <span class="fw-semibold">To:</span>
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
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="far fa-clock text-warning me-2"></i>
                                    <span class="fw-semibold">Time:</span>
                                </div>
                                <p class="mb-0 ms-4">
                                    {{ $booking->trip_type === 'return' ? $booking->ride->return_time : $booking->ride->time }}
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-users text-info me-2"></i>
                                    <span class="fw-semibold">Seats:</span>
                                </div>
                                <p class="mb-0 ms-4">{{ $booking->number_of_seats }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-credit-card text-success me-2"></i>
                                    <span class="fw-semibold">Total Paid:</span>
                                </div>
                                <p class="mb-0 ms-4 fw-bold text-success">${{ number_format($booking->total_price, 2) }}</p>
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

            <!-- Next Steps -->
            <div class="card shadow border-0 mb-4" style="border-radius: 18px;">
                <div class="card-header bg-info text-white text-center py-3" style="border-radius: 18px 18px 0 0;">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>What's Next?</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; min-width: 30px;">
                                            <i class="fas fa-envelope" style="font-size: 0.8rem;"></i>
                                        </div>
                                        <div>
                                            <strong>Confirmation Email</strong>
                                            <br>
                                            <small class="text-muted">Check your email for booking details</small>
                                        </div>
                                    </div>
                                </li>
                                <li class="mb-3">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; min-width: 30px;">
                                            <i class="fas fa-clock" style="font-size: 0.8rem;"></i>
                                        </div>
                                        <div>
                                            <strong>Arrive Early</strong>
                                            <br>
                                            <small class="text-muted">Be at pickup location 10 minutes before departure</small>
                                        </div>
                                    </div>
                                </li>
                                <li class="mb-3">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; min-width: 30px;">
                                            <i class="fas fa-map-marker-alt" style="font-size: 0.8rem;"></i>
                                        </div>
                                        <div>
                                            <strong>Check Location</strong>
                                            <br>
                                            <small class="text-muted">Use the map links above to find your pickup point</small>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; min-width: 30px;">
                                            <i class="fas fa-id-card" style="font-size: 0.8rem;"></i>
                                        </div>
                                        <div>
                                            <strong>Bring ID</strong>
                                            <br>
                                            <small class="text-muted">Have valid identification ready</small>
                                        </div>
                                    </div>
                                </li>
                                <li class="mb-3">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; min-width: 30px;">
                                            <i class="fas fa-phone" style="font-size: 0.8rem;"></i>
                                        </div>
                                        <div>
                                            <strong>Contact Driver</strong>
                                            <br>
                                            <small class="text-muted">Call if you're running late</small>
                                        </div>
                                    </div>
                                </li>
                                <li class="mb-3">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; min-width: 30px;">
                                            <i class="fas fa-star" style="font-size: 0.8rem;"></i>
                                        </div>
                                        <div>
                                            <strong>Rate Your Ride</strong>
                                            <br>
                                            <small class="text-muted">Share your experience after the trip</small>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center">
                <a href="{{ route('user.bookings') }}" class="btn btn-primary px-4 py-2 me-3">
                    <i class="fas fa-list me-2"></i>View My Bookings
                </a>
                <a href="{{ route('user.booking.receipt', $booking->id) }}" class="btn btn-success px-4 py-2 me-3" target="_blank">
                    <i class="fas fa-print me-2"></i>Print Receipt
                </a>
                <a href="{{ route('find.rides') }}" class="btn btn-outline-secondary px-4 py-2">
                    <i class="fas fa-search me-2"></i>Find More Rides
                </a>
            </div>

            <!-- Additional Info -->
            <div class="text-center mt-5">
                <p class="text-muted">
                    <i class="fas fa-headset me-2"></i>
                    Need help? Contact our support team at 
                    <a href="mailto:support@hubber.com" class="text-decoration-none">support@hubber.com</a>
                </p>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    transition: box-shadow 0.2s;
}
.card:hover {
    box-shadow: 0 8px 24px rgba(0,0,0,0.15) !important;
}
.btn {
    border-radius: 8px;
    font-weight: 600;
}
.alert {
    border-radius: 12px;
}
</style>
@endsection 