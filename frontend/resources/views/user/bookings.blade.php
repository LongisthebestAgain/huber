@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
<div class="container-fluid bg-light min-vh-100 py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="text-center mb-4">
                <h1 class="fw-bold">My Bookings</h1>
                <p class="text-muted">View and manage all your ride bookings</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px;">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px;">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow border-0" style="border-radius: 18px;">
                <div class="card-header bg-primary text-white text-center py-3" style="border-radius: 18px 18px 0 0;">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Your Bookings</h5>
                </div>
                <div class="card-body p-4">
                    @if($bookings->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times text-muted" style="font-size: 4rem;"></i>
                            <h4 class="mt-3 text-muted">No Bookings Yet</h4>
                            <p class="text-muted">You haven't made any bookings yet. Start by finding a ride!</p>
                            <a href="{{ route('find.rides') }}" class="btn btn-primary px-4 py-2">
                                <i class="fas fa-search me-2"></i>Find Rides
                            </a>
                        </div>
                    @else
                        <div class="row">
                            @foreach($bookings as $booking)
                                <div class="col-12 mb-4">
                                    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                                        <div class="card-body p-4">
                                            <div class="row align-items-center">
                                                <div class="col-md-8">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                                            <i class="fas fa-car"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1 fw-bold">Booking #{{ $booking->booking_reference }}</h6>
                                                            <span class="badge bg-success">{{ ucfirst($booking->payment_status) }}</span>
                                                            <span class="badge bg-info ms-2">{{ ucfirst($booking->trip_type) }} Trip</span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-2">
                                                                <small class="text-muted">From:</small>
                                                                <div class="fw-semibold">
                                                                    {{ $booking->trip_type === 'return' ? $booking->ride->destination : $booking->ride->station_location }}
                                                                </div>
                                                            </div>
                                                            <div class="mb-2">
                                                                <small class="text-muted">To:</small>
                                                                <div class="fw-semibold">
                                                                    {{ $booking->trip_type === 'return' ? $booking->ride->station_location : $booking->ride->destination }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-2">
                                                                <small class="text-muted">Date:</small>
                                                                <div class="fw-semibold">
                                                                    {{ $booking->trip_type === 'return' ? $booking->ride->return_date : $booking->ride->date }}
                                                                </div>
                                                            </div>
                                                            <div class="mb-2">
                                                                <small class="text-muted">Time:</small>
                                                                <div class="fw-semibold">
                                                                    {{ $booking->trip_type === 'return' ? $booking->ride->return_time : $booking->ride->time }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <small class="text-muted">Driver:</small>
                                                            <div class="fw-semibold">{{ $booking->ride->user->name }}</div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <small class="text-muted">Seats:</small>
                                                            <div class="fw-semibold">{{ $booking->number_of_seats }}</div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <small class="text-muted">Total Paid:</small>
                                                            <div class="fw-semibold text-success">${{ number_format($booking->total_price, 2) }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4 text-end">
                                                    <div class="mb-3">
                                                        <small class="text-muted">Booked on:</small>
                                                        <div class="fw-semibold">{{ $booking->created_at->format('M d, Y') }}</div>
                                                    </div>
                                                    <a href="{{ route('user.booking.details', $booking->id) }}" class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-eye me-1"></i>View Details
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center mt-4">
                <a href="{{ route('find.rides') }}" class="btn btn-primary px-4 py-2 me-3">
                    <i class="fas fa-search me-2"></i>Find More Rides
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary px-4 py-2">
                    <i class="fas fa-home me-2"></i>Back to Home
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
.alert {
    border-radius: 12px;
}
</style>
@endsection 