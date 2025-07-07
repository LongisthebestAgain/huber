@extends('layouts.ride-management')

@section('title', 'All Reviews - Hubber')

@section('main')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="h4 mb-2">All Reviews</h2>
                <p class="text-muted mb-0">View all reviews from your customers across all rides</p>
            </div>
            <a href="{{ route('driver.ride.management') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-star fa-2x mb-2"></i>
                <h4 class="mb-1">{{ $totalReviews }}</h4>
                <p class="mb-0">Total Reviews</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-star-half-alt fa-2x mb-2"></i>
                <h4 class="mb-1">{{ number_format($averageOverallRating, 1) }}</h4>
                <p class="mb-0">Average Rating</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-user-tie fa-2x mb-2"></i>
                <h4 class="mb-1">{{ number_format($averageDriverRating, 1) }}</h4>
                <p class="mb-0">Driver Rating</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-dark h-100">
            <div class="card-body text-center">
                <i class="fas fa-car fa-2x mb-2"></i>
                <h4 class="mb-1">{{ number_format($averageVehicleRating, 1) }}</h4>
                <p class="mb-0">Vehicle Rating</p>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Statistics -->
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Rating Distribution</h5>
            </div>
            <div class="card-body">
                @for($i = 5; $i >= 1; $i--)
                    <div class="d-flex align-items-center mb-2">
                        <div class="me-3" style="width: 60px;">
                            <span class="text-warning">{{ $i }} ★</span>
                        </div>
                        <div class="flex-grow-1 me-3">
                            <div class="progress" style="height: 8px;">
                                @php
                                    $percentage = $totalReviews > 0 ? ($ratingDistribution[$i] / $totalReviews) * 100 : 0;
                                @endphp
                                <div class="progress-bar bg-warning" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                        <div style="width: 40px; text-align: right;">
                            <small class="text-muted">{{ $ratingDistribution[$i] }}</small>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Category Ratings</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <div class="text-center">
                            <div class="h4 text-primary mb-1">{{ number_format($averagePunctualityRating, 1) }}</div>
                            <small class="text-muted">Punctuality</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="text-center">
                            <div class="h4 text-success mb-1">{{ number_format($averageSafetyRating, 1) }}</div>
                            <small class="text-muted">Safety</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="text-center">
                            <div class="h4 text-info mb-1">{{ number_format($averageComfortRating, 1) }}</div>
                            <small class="text-muted">Comfort</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="text-center">
                            <div class="h4 text-warning mb-1">{{ number_format($averageVehicleRating, 1) }}</div>
                            <small class="text-muted">Vehicle</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reviews List -->
<div class="card shadow-sm">
    <div class="card-header bg-light">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>All Reviews ({{ $totalReviews }})
        </h5>
    </div>
    <div class="card-body">
        @if($reviews->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-star text-muted" style="font-size: 4rem;"></i>
                <h4 class="mt-3 text-muted">No Reviews Yet</h4>
                <p class="text-muted">You haven't received any reviews yet. Complete some rides to start getting feedback!</p>
            </div>
        @else
            <div class="row">
                @foreach($reviews as $review)
                    <div class="col-12 mb-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <!-- Customer Info -->
                                        <div class="d-flex align-items-center mb-3">
                                            @if($review->user && $review->user->profile_picture)
                                                <img src="{{ asset('storage/' . $review->user->profile_picture) }}" 
                                                     alt="Profile" class="rounded-circle me-3" width="48" height="48"
                                                     style="object-fit: cover;">
                                            @else
                                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                     style="width: 48px; height: 48px;">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-1">{{ $review->user ? $review->user->name : 'Anonymous' }}</h6>
                                                <small class="text-muted">
                                                    {{ $review->created_at->format('M d, Y \a\t H:i') }}
                                                    <span class="badge bg-info ms-2">{{ strtoupper($review->trip_type) }} TRIP</span>
                                                </small>
                                            </div>
                                        </div>

                                        <!-- Ride Details -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                                <span class="fw-semibold">
                                                    {{ $review->trip_type === 'return' ? $review->ride->destination : $review->ride->station_location }}
                                                </span>
                                                <i class="fas fa-arrow-right mx-2 text-muted"></i>
                                                <span class="fw-semibold">
                                                    {{ $review->trip_type === 'return' ? $review->ride->station_location : $review->ride->destination }}
                                                </span>
                                            </div>
                                            <small class="text-muted">
                                                {{ $review->trip_type === 'return' ? $review->ride->return_date->format('M d, Y') : $review->ride->date->format('M d, Y') }}
                                                at {{ $review->trip_type === 'return' ? $review->ride->return_time->format('H:i') : $review->ride->time->format('H:i') }}
                                            </small>
                                        </div>

                                        <!-- Review Text -->
                                        @if($review->review_text)
                                            <div class="mb-3">
                                                <p class="mb-0 fst-italic">"{{ $review->review_text }}"</p>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-md-4">
                                        <!-- Overall Rating -->
                                        <div class="text-center mb-3">
                                            <div class="h3 text-warning mb-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star{{ $i <= $review->overall_rating ? '' : '-o' }}"></i>
                                                @endfor
                                            </div>
                                            <div class="fw-bold">{{ $review->overall_rating }}/5</div>
                                            <small class="text-muted">Overall Rating</small>
                                        </div>

                                        <!-- Category Ratings -->
                                        <div class="row text-center">
                                            <div class="col-6 mb-2">
                                                <div class="small text-muted">Driver</div>
                                                <div class="fw-semibold text-primary">{{ $review->driver_rating }}/5</div>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <div class="small text-muted">Vehicle</div>
                                                <div class="fw-semibold text-warning">{{ $review->vehicle_rating }}/5</div>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <div class="small text-muted">Punctuality</div>
                                                <div class="fw-semibold text-info">{{ $review->punctuality_rating }}/5</div>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <div class="small text-muted">Safety</div>
                                                <div class="fw-semibold text-success">{{ $review->safety_rating }}/5</div>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <div class="small text-muted">Comfort</div>
                                                <div class="fw-semibold text-info">{{ $review->comfort_rating }}/5</div>
                                            </div>
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
    box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
}
.progress {
    border-radius: 10px;
}
.progress-bar {
    border-radius: 10px;
}
</style>
@endsection 