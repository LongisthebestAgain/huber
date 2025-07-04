@extends('layouts.app')

@section('title', 'Find Your Perfect Ride')

@section('content')
<div class="container-fluid bg-light min-vh-100 py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="text-center mb-4">
                <h1 class="fw-bold">Find Your Perfect Ride</h1>
                <p class="text-muted">Discover comfortable and affordable rides to your destination</p>
            </div>
            <div class="row">
                <!-- Search/Filter Panel -->
                <div class="col-md-3 mb-4">
                    <div class="card shadow filter-panel p-4 border-0">
                        <form method="GET" action="{{ route('find.rides') }}">
                            <div class="mb-4">
                                <label class="form-label fw-semibold">From location</label>
                                <input type="text" name="from" class="form-control" placeholder="From location" value="{{ $filters['from'] ?? '' }}">
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold">To location</label>
                                <input type="text" name="to" class="form-control" placeholder="To location" value="{{ $filters['to'] ?? '' }}">
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Date</label>
                                <input type="date" name="date" class="form-control" value="{{ $filters['date'] ?? '' }}">
                            </div>
                            <hr>
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Ride Type</label>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="rideType" id="allRides" value="all" {{ empty($filters['rideType']) || $filters['rideType'] === 'all' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="allRides">Show all available rides</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="rideType" id="sharedRide" value="shared" {{ ($filters['rideType'] ?? '') === 'shared' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sharedRide">Shared Ride</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rideType" id="wRide" value="exclusive" {{ ($filters['rideType'] ?? '') === 'exclusive' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="exclusiveRide">Exclusive Ride</label>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Price Range (USD)</label>
                                <div class="d-flex gap-2">
                                    <input type="number" name="price_min" class="form-control" placeholder="Min" value="{{ $filters['price_min'] ?? '' }}">
                                    <input type="number" name="price_max" class="form-control" placeholder="Max" value="{{ $filters['price_max'] ?? '' }}">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Departure Time</label>
                                <select class="form-select" name="departure_time">
                                    <option value="" {{ empty($filters['departure_time']) ? 'selected' : '' }}>Any Time</option>
                                    <option value="morning" {{ ($filters['departure_time'] ?? '') === 'morning' ? 'selected' : '' }}>Morning</option>
                                    <option value="afternoon" {{ ($filters['departure_time'] ?? '') === 'afternoon' ? 'selected' : '' }}>Afternoon</option>
                                    <option value="evening" {{ ($filters['departure_time'] ?? '') === 'evening' ? 'selected' : '' }}>Evening</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label class="form-label fw-semibold">Sort By</label>
                                <select class="form-select" name="sort_by">
                                    <option value="price_asc" {{ ($filters['sort_by'] ?? '') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                    <option value="price_desc" {{ ($filters['sort_by'] ?? '') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                                </select>
                            </div>
                            <button class="btn btn-primary w-100 mt-4" type="submit">Search Rides</button>
                            <a href="{{ route('find.rides') }}" class="btn btn-outline-secondary w-100 mt-2">Clear Filters</a>
                        </form>
                    </div>
                </div>
                <!-- Rides List -->
                <div class="col-md-9">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Available Rides</h5>
                        <span class="badge bg-primary">{{ count($rideEntries) }} rides found</span>
                    </div>
                    @php
                        function getVehiclePhoto($entry) {
                            $driverDocs = $entry['user'] && $entry['user']->driverDocuments ? $entry['user']->driverDocuments : null;
                            if ($driverDocs && $driverDocs->vehicle_photo_1) {
                                return asset('storage/' . $driverDocs->vehicle_photo_1);
                            }
                            return 'https://source.unsplash.com/900x220/?car,road,travel';
                        }
                        function getProfilePhoto($entry) {
                            $user = $entry['user'];
                            if ($user && $user->profile_picture) {
                                return asset('storage/' . $user->profile_picture);
                            }
                            return 'https://ui-avatars.com/api/?name=' . urlencode($user ? $user->name : 'Driver') . '&background=0D8ABC&color=fff';
                        }
                    @endphp
                    @forelse($rideEntries as $entry)
                        <div class="ride-card card mb-5 border-0 shadow-lg p-0" style="border-radius: 18px; overflow: hidden;">
                            <div class="ride-cover position-relative">
                                <img src="{{ getVehiclePhoto($entry) }}" class="w-100" style="height: 180px; object-fit: cover;">
                                <div class="position-absolute top-0 start-0 p-3 d-flex align-items-center" style="z-index:2;">
                                    <img src="{{ getProfilePhoto($entry) }}" class="rounded-circle me-2 border border-2" width="48" height="48" alt="Driver Avatar">
                                    <div>
                                        <a href="{{ route('driver.profile.public', ['driverId' => $entry['user'] ? $entry['user']->id : 0]) }}" class="driver-name-badge text-decoration-none px-3 py-1" style="font-size: 1.1rem;">
                                            {{ $entry['user'] ? $entry['user']->name : 'Driver' }}
                                        </a>
                                        <div class="d-flex align-items-center gap-2 mt-1">
                                            <span class="badge {{ $entry['is_exclusive'] ? 'bg-danger' : 'bg-success' }} px-2 py-1" style="font-size:0.85rem;">{{ $entry['is_exclusive'] ? 'EXCLUSIVE' : 'SHARED' }}</span>
                                            <span class="text-warning small">&#9733; 4.8</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <div class="bg-primary bg-opacity-10 rounded-3 p-3 mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                        <span class="fw-semibold">PICKUP</span>
                                        <span class="ms-2">{{ $entry['station_location'] }}</span>
                                        <span class="ms-auto text-muted small"><i class="far fa-calendar-alt me-1"></i>{{ $entry['date'] }} <i class="far fa-clock ms-2 me-1"></i>{{ $entry['time'] }}</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-map-pin text-secondary me-2"></i>
                                        <span class="fw-semibold">DROPOFF</span>
                                        <span class="ms-2">{{ $entry['destination'] }}</span>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
                                    <span class="badge bg-light text-dark border px-3 py-2"><i class="fas fa-car me-1"></i> {{ $entry['user'] && $entry['user']->vehicle_model ? $entry['user']->vehicle_model : 'Car Model' }} ({{ $entry['user'] && $entry['user']->vehicle_color ? $entry['user']->vehicle_color : 'Color' }})</span>
                                    <span class="badge bg-light text-dark border px-3 py-2"><i class="fas fa-users me-1"></i> {{ $entry['available_seats'] }} seats available</span>
                                    <span class="badge bg-light text-dark border px-3 py-2">
                                        <i class="fas fa-dollar-sign me-1"></i> 
                                        <span class="fw-bold">
                                            @if($entry['is_exclusive'])
                                                ${{ number_format($entry['price_per_person'], 2) }} (Total)
                                            @else
                                                ${{ number_format($entry['price_per_person'], 2) }}/person
                                            @endif
                                        </span>
                                    </span>
                                </div>
                                <div class="d-flex justify-content-end">
                                    @if($entry['is_exclusive'])
                                        @if($entry['has_booked'])
                                            <button class="btn btn-secondary px-4 py-2" disabled>
                                                <i class="fas fa-check me-2"></i>Booked
                                            </button>
                                        @else
                                            <a href="{{ route('booking.payment', ['rideId' => $entry['ride']->id, 'tripType' => $entry['type'] === 'Back' ? 'return' : 'go']) }}" class="btn btn-primary px-4 py-2">
                                                <i class="fas fa-credit-card me-2"></i>Book Now
                                            </a>
                                        @endif
                                    @else
                                        @if($entry['has_booked'])
                                            <button class="btn btn-secondary px-4 py-2" disabled>
                                                <i class="fas fa-check me-2"></i>Booked
                                            </button>
                                        @else
                                            <a href="{{ route('booking.seat-selection', ['rideId' => $entry['ride']->id, 'tripType' => $entry['type'] === 'Back' ? 'return' : 'go']) }}" class="btn btn-primary px-4 py-2">
                                                <i class="fas fa-chair me-2"></i>Select Seats
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info">No rides found.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
<style>
.filter-panel {
    border-radius: 18px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.07);
    background: #fff;
}
.ride-card {
    border-radius: 18px;
    background: #fff;
    box-shadow: 0 8px 32px rgba(0,0,0,0.12), 0 1.5px 6px rgba(0,0,0,0.08);
    transition: box-shadow 0.2s, transform 0.2s;
}
.ride-card:hover {
    box-shadow: 0 16px 48px rgba(0,0,0,0.16), 0 3px 12px rgba(0,0,0,0.10);
    transform: translateY(-2px) scale(1.01);
}
.ride-cover img {
    border-radius: 18px 18px 0 0;
}
.hover-underline:hover { text-decoration: underline !important; }
.driver-name-badge {
    background: #0d6efd;
    color: #fff !important;
    border-radius: 16px;
    font-weight: 600;
    display: inline-block;
    box-shadow: 0 2px 8px rgba(13,110,253,0.08);
    transition: background 0.2s;
}
.driver-name-badge:hover {
    background: #084298;
    color: #fff !important;
}
</style>
@endsection 