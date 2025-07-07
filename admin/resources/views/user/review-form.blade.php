@extends('layouts.app')

@section('title', 'Review Your Ride')

@section('content')
<div class="container-fluid bg-light min-vh-100 py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="text-center mb-4">
                <h1 class="fw-bold">Review Your Ride</h1>
                <p class="text-muted">Share your experience and help other passengers</p>
            </div>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px;">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Booking Summary -->
            <div class="card shadow border-0 mb-4" style="border-radius: 18px;">
                <div class="card-header bg-info text-white text-center py-3" style="border-radius: 18px 18px 0 0;">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Trip Details</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Route</label>
                                <div class="d-flex align-items-center">
                                    <div class="text-primary fw-semibold">
                                        {{ $tripType === 'return' ? $booking->ride->destination : $booking->ride->station_location }}
                                    </div>
                                    <i class="fas fa-arrow-right mx-2 text-muted"></i>
                                    <div class="text-primary fw-semibold">
                                        {{ $tripType === 'return' ? $booking->ride->station_location : $booking->ride->destination }}
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Date & Time</label>
                                <div class="fw-semibold">
                                    {{ $tripType === 'return' ? $booking->ride->return_date->format('l, F d, Y') : $booking->ride->date->format('l, F d, Y') }}
                                    at {{ $tripType === 'return' ? $booking->ride->return_time->format('H:i') : $booking->ride->time->format('H:i') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Driver</label>
                                <div class="fw-semibold">{{ $booking->ride->user->name }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Booking Reference</label>
                                <div class="fw-semibold">{{ $booking->booking_reference }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Ride Status</label>
                                @php
                                    $rideStatus = $tripType === 'return' ? $booking->ride->return_completion_status : $booking->ride->go_completion_status;
                                @endphp
                                @if($rideStatus === 'completed')
                                    <div class="badge bg-success">Completed</div>
                                    <small class="text-muted d-block mt-1">You can now review this completed ride.</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Review Form -->
            <div class="card shadow border-0" style="border-radius: 18px;">
                <div class="card-header bg-warning text-dark text-center py-3" style="border-radius: 18px 18px 0 0;">
                    <h5 class="mb-0"><i class="fas fa-star me-2"></i>Rate Your Experience</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('user.booking.review.submit', ['bookingId' => $booking->id, 'tripType' => $tripType]) }}">
                        @csrf
                        
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Overall Rating -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Overall Experience</label>
                            <div class="rating-group">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" name="overall_rating" value="{{ $i }}" id="overall_{{ $i }}" class="rating-input" required>
                                    <label for="overall_{{ $i }}" class="rating-star">★</label>
                                @endfor
                            </div>
                            <small class="text-muted">Rate your overall experience with this ride</small>
                        </div>

                        <!-- Specific Ratings -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Driver Professionalism</label>
                                <div class="rating-group">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" name="driver_rating" value="{{ $i }}" id="driver_{{ $i }}" class="rating-input" required>
                                        <label for="driver_{{ $i }}" class="rating-star">★</label>
                                    @endfor
                                </div>
                                <small class="text-muted">How professional and friendly was the driver?</small>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Vehicle Condition</label>
                                <div class="rating-group">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" name="vehicle_rating" value="{{ $i }}" id="vehicle_{{ $i }}" class="rating-input" required>
                                        <label for="vehicle_{{ $i }}" class="rating-star">★</label>
                                    @endfor
                                </div>
                                <small class="text-muted">How clean and well-maintained was the vehicle?</small>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Punctuality</label>
                                <div class="rating-group">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" name="punctuality_rating" value="{{ $i }}" id="punctuality_{{ $i }}" class="rating-input" required>
                                        <label for="punctuality_{{ $i }}" class="rating-star">★</label>
                                    @endfor
                                </div>
                                <small class="text-muted">Did the driver arrive and depart on time?</small>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Safety</label>
                                <div class="rating-group">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" name="safety_rating" value="{{ $i }}" id="safety_{{ $i }}" class="rating-input" required>
                                        <label for="safety_{{ $i }}" class="rating-star">★</label>
                                    @endfor
                                </div>
                                <small class="text-muted">How safe was the driving?</small>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Comfort</label>
                                <div class="rating-group">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" name="comfort_rating" value="{{ $i }}" id="comfort_{{ $i }}" class="rating-input" required>
                                        <label for="comfort_{{ $i }}" class="rating-star">★</label>
                                    @endfor
                                </div>
                                <small class="text-muted">How comfortable was the ride?</small>
                            </div>
                        </div>

                        <!-- Review Text -->
                        <div class="mb-4">
                            <label for="review_text" class="form-label fw-bold">Additional Comments (Optional)</label>
                            <textarea class="form-control" id="review_text" name="review_text" rows="4" 
                                      placeholder="Share your experience, suggestions, or any additional comments...">{{ old('review_text') }}</textarea>
                            <small class="text-muted">Maximum 1000 characters</small>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('user.bookings') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-arrow-left me-2"></i>Back to Bookings
                            </a>
                            <button type="submit" class="btn btn-warning btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>Submit Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.rating-group {
    display: flex;
    flex-direction: row-reverse;
    gap: 0.25rem;
}

.rating-input {
    display: none;
}

.rating-star {
    font-size: 2rem;
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s ease;
}

.rating-star:hover,
.rating-star:hover ~ .rating-star,
.rating-input:checked ~ .rating-star {
    color: #ffc107;
}

.rating-input:checked ~ .rating-star {
    color: #ffc107;
}

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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character counter for review text
    const reviewText = document.getElementById('review_text');
    const maxLength = 1000;
    
    reviewText.addEventListener('input', function() {
        const remaining = maxLength - this.value.length;
        const small = this.nextElementSibling;
        small.textContent = `${remaining} characters remaining`;
        
        if (remaining < 0) {
            this.value = this.value.substring(0, maxLength);
            small.textContent = '0 characters remaining';
        }
    });
});
</script>
@endsection 