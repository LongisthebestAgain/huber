@extends('layouts.ride-management')

@section('title', 'Ride Reviews - Hubber')

@section('main')
<div class="card shadow-sm mb-4">
    <div class="card-header bg-info text-white d-flex align-items-center justify-content-between" style="border-radius: 12px 12px 0 0;">
        <div>
            <i class="fas fa-star me-2"></i>
            <h4 class="mb-0">Ride Reviews</h4>
        </div>
        <a href="{{ route('driver.ride.management') }}" class="btn btn-outline-light btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Back to Rides
        </a>
    </div>
    <div class="card-body bg-light" style="border-radius: 0 0 12px 12px;">
        <div class="row mb-4">
            <div class="col-md-6">
                <h5 class="mb-3">Ride Details</h5>
                <div class="mb-2">
                    <strong>Route:</strong> {{ $ride->station_location }} → {{ $ride->destination }}
                </div>
                <div class="mb-2">
                    <strong>Date:</strong> {{ $ride->date->format('l, F d, Y') }}
                </div>
                <div class="mb-2">
                    <strong>Time:</strong> {{ $ride->time->format('H:i A') }}
                </div>
            </div>
            <div class="col-md-6">
                <h5 class="mb-3">Review Statistics</h5>
                @php
                    $totalReviews = $ride->reviews->count();
                    $averageRating = $totalReviews > 0 ? $ride->reviews->avg('overall_rating') : 0;
                    $fiveStarReviews = $ride->reviews->where('overall_rating', 5)->count();
                @endphp
                <div class="mb-2">
                    <strong>Total Reviews:</strong> {{ $totalReviews }}
                </div>
                <div class="mb-2">
                    <strong>Average Rating:</strong> 
                    @if($averageRating > 0)
                        <span class="text-warning">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $averageRating)
                                    ★
                                @else
                                    ☆
                                @endif
                            @endfor
                        </span>
                        ({{ number_format($averageRating, 1) }}/5)
                    @else
                        No reviews yet
                    @endif
                </div>
                <div class="mb-2">
                    <strong>5-Star Reviews:</strong> {{ $fiveStarReviews }} ({{ $totalReviews > 0 ? round(($fiveStarReviews / $totalReviews) * 100, 1) : 0 }}%)
                </div>
            </div>
        </div>

        @if($ride->reviews->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-star text-muted" style="font-size: 4rem;"></i>
                <h4 class="mt-3 text-muted">No Reviews Yet</h4>
                <p class="text-muted">Reviews will appear here once passengers complete their rides and leave feedback.</p>
            </div>
        @else
            <h5 class="mb-3">All Reviews</h5>
            <div class="row">
                @foreach($ride->reviews->sortByDesc('created_at') as $review)
                    <div class="col-12 mb-4">
                        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-bold">{{ $review->user->name }}</h6>
                                                <div class="text-warning mb-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->overall_rating)
                                                            ★
                                                        @else
                                                            ☆
                                                        @endif
                                                    @endfor
                                                    <span class="text-muted ms-2">({{ $review->overall_rating }}/5)</span>
                                                </div>
                                                <small class="text-muted">
                                                    {{ $review->created_at->format('M d, Y \a\t H:i A') }} • 
                                                    {{ ucfirst($review->trip_type) }} Trip
                                                </small>
                                            </div>
                                        </div>
                                        
                                        @if($review->review_text)
                                            <div class="mb-3">
                                                <p class="mb-0">{{ $review->review_text }}</p>
                                            </div>
                                        @endif
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-2">
                                                    <small class="text-muted">Driver:</small>
                                                    <div class="d-flex align-items-center">
                                                        <span class="text-warning me-1">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                @if($i <= $review->driver_rating)
                                                                    ★
                                                                @else
                                                                    ☆
                                                                @endif
                                                            @endfor
                                                        </span>
                                                        <small class="text-muted">({{ $review->driver_rating }}/5)</small>
                                                    </div>
                                                </div>
                                                <div class="mb-2">
                                                    <small class="text-muted">Vehicle:</small>
                                                    <div class="d-flex align-items-center">
                                                        <span class="text-warning me-1">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                @if($i <= $review->vehicle_rating)
                                                                    ★
                                                                @else
                                                                    ☆
                                                                @endif
                                                            @endfor
                                                        </span>
                                                        <small class="text-muted">({{ $review->vehicle_rating }}/5)</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-2">
                                                    <small class="text-muted">Punctuality:</small>
                                                    <div class="d-flex align-items-center">
                                                        <span class="text-warning me-1">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                @if($i <= $review->punctuality_rating)
                                                                    ★
                                                                @else
                                                                    ☆
                                                                @endif
                                                            @endfor
                                                        </span>
                                                        <small class="text-muted">({{ $review->punctuality_rating }}/5)</small>
                                                    </div>
                                                </div>
                                                <div class="mb-2">
                                                    <small class="text-muted">Safety:</small>
                                                    <div class="d-flex align-items-center">
                                                        <span class="text-warning me-1">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                @if($i <= $review->safety_rating)
                                                                    ★
                                                                @else
                                                                    ☆
                                                                @endif
                                                            @endfor
                                                        </span>
                                                        <small class="text-muted">({{ $review->safety_rating }}/5)</small>
                                                    </div>
                                                </div>
                                                <div class="mb-2">
                                                    <small class="text-muted">Comfort:</small>
                                                    <div class="d-flex align-items-center">
                                                        <span class="text-warning me-1">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                @if($i <= $review->comfort_rating)
                                                                    ★
                                                                @else
                                                                    ☆
                                                                @endif
                                                            @endfor
                                                        </span>
                                                        <small class="text-muted">({{ $review->comfort_rating }}/5)</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4 text-end">
                                        <div class="mb-3">
                                            <small class="text-muted">Booking Reference:</small>
                                            <div class="fw-semibold">{{ $review->ridePurchase->booking_reference }}</div>
                                        </div>
                                        <div class="mb-3">
                                            <small class="text-muted">Seats Booked:</small>
                                            <div class="fw-semibold">{{ $review->ridePurchase->number_of_seats }}</div>
                                        </div>
                                        <div class="mb-3">
                                            <small class="text-muted">Total Paid:</small>
                                            <div class="fw-semibold text-success">${{ number_format($review->ridePurchase->total_price, 2) }}</div>
                                        </div>
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