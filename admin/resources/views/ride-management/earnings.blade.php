@extends('layouts.ride-management')

@section('title', 'Earnings - Hubber')

@section('main')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="h4 mb-2">Earnings Dashboard</h2>
                <p class="text-muted mb-0">Track your earnings, customers, and completed rides</p>
            </div>
            <a href="{{ route('driver.ride.management') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card bg-success text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-dollar-sign fa-2x mb-2"></i>
                <h4 class="mb-1">${{ number_format($totalEarnings, 2) }}</h4>
                <p class="mb-0">Total Earnings</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-users fa-2x mb-2"></i>
                <h4 class="mb-1">{{ $totalCustomers }}</h4>
                <p class="mb-0">Total Customers</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card bg-info text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-check-circle fa-2x mb-2"></i>
                <h4 class="mb-1">{{ $totalRidesCompleted }}</h4>
                <p class="mb-0">Rides Completed</p>
            </div>
        </div>
    </div>
</div>

<!-- Bookings Table -->
<div class="card shadow-sm">
    <div class="card-header bg-light">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>All Bookings ({{ $bookings->count() }})
        </h5>
    </div>
    <div class="card-body">
        @if($bookings->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-calendar-times text-muted" style="font-size: 4rem;"></i>
                <h4 class="mt-3 text-muted">No Bookings Yet</h4>
                <p class="text-muted">You haven't received any bookings yet. Create rides to start earning!</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Passenger Name</th>
                            <th>Profile</th>
                            <th>Booking Type</th>
                            <th>Ride</th>
                            <th>Trip</th>
                            <th>Seats</th>
                            <th>Total Price</th>
                            <th>Payment Method</th>
                            <th>Booking Ref</th>
                            <th>Date</th>
                            <th>Status</th>
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
                                <span class="badge {{ ($booking->ride && $booking->ride->is_exclusive && $booking->trip_type === 'go') || ($booking->ride && $booking->ride->return_is_exclusive && $booking->trip_type === 'return') ? 'bg-danger' : 'bg-success' }}">
                                    {{ strtoupper($booking->trip_type) }} - {{ ($booking->ride && $booking->ride->is_exclusive && $booking->trip_type === 'go') || ($booking->ride && $booking->ride->return_is_exclusive && $booking->trip_type === 'return') ? 'EXCLUSIVE' : 'SHARED' }}
                                </span>
                            </td>
                            <td>
                                @if($booking->ride)
                                    <div class="fw-semibold">
                                        {{ $booking->trip_type === 'return' ? $booking->ride->destination : $booking->ride->station_location }}
                                        <i class="fas fa-arrow-right mx-1 text-muted"></i>
                                        {{ $booking->trip_type === 'return' ? $booking->ride->station_location : $booking->ride->destination }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $booking->trip_type === 'return' ? $booking->ride->return_date->format('M d, Y') : $booking->ride->date->format('M d, Y') }}
                                    </small>
                                @else
                                    <span class="text-muted">-</span>
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
                            <td><span class="badge bg-dark">{{ $booking->booking_reference }}</span></td>
                            <td>{{ $booking->created_at ? $booking->created_at->format('M d, Y H:i') : '-' }}</td>
                            <td>
                                @if($booking->ride)
                                    @php
                                        $rideStatus = $booking->trip_type === 'return' ? $booking->ride->return_completion_status : $booking->ride->go_completion_status;
                                    @endphp
                                    @if($rideStatus === 'pending')
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-clock me-1"></i>Pending
                                        </span>
                                    @elseif($rideStatus === 'ongoing')
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-play me-1"></i>Ongoing
                                        </span>
                                    @elseif($rideStatus === 'completed')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Completed
                                        </span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">Unknown</span>
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
.table th {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    font-weight: 600;
}
</style>
@endsection
 