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
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-map-pin text-secondary me-2"></i>
                                    <span class="fw-semibold">To:</span>
                                </div>
                                <p class="mb-0 ms-4">
                                    {{ $booking->trip_type === 'return' ? $booking->ride->station_location : $booking->ride->destination }}
                                </p>
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
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; min-width: 30px;">
                                            <i class="fas fa-phone" style="font-size: 0.8rem;"></i>
                                        </div>
                                        <div>
                                            <strong>Contact Driver</strong>
                                            <br>
                                            <small class="text-muted">Driver will contact you before pickup</small>
                                        </div>
                                    </div>
                                </li>
                                <li class="mb-3">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; min-width: 30px;">
                                            <i class="fas fa-id-card" style="font-size: 0.8rem;"></i>
                                        </div>
                                        <div>
                                            <strong>Bring ID</strong>
                                            <br>
                                            <small class="text-muted">Have valid identification ready</small>
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
                <a href="{{ route('user.bookings') }}" class="btn btn-primary btn-lg px-5 py-3 me-3">
                    <i class="fas fa-list me-2"></i>View My Bookings
                </a>
                <a href="{{ route('find.rides') }}" class="btn btn-outline-secondary btn-lg px-5 py-3">
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
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
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