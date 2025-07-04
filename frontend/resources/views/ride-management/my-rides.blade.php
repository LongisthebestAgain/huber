@extends('layouts.ride-management')

@section('title', 'My Rides - Hubber')

@section('main')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h2 class="h4 mb-4">My Rides</h2>
        <p class="text-muted">This page lists all rides you have created, separated by trip type.</p>
    </div>
</div>

@php 
    $rides = $user->rides()->latest()->get();
    $goRides = $rides->filter(function($ride) {
        return $ride->date && $ride->time;
    });
    $returnRides = $rides->filter(function($ride) {
        return $ride->is_two_way && $ride->return_date && $ride->return_time;
    });
@endphp

<!-- Go Rides Section -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-arrow-right me-2"></i>Go Rides</h5>
    </div>
    <div class="card-body">
        @if($goRides->isEmpty())
            <div class="alert alert-info">No go rides created yet.</div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Available Seats</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($goRides as $i => $ride)
                        @php
                            $isExclusive = $ride->is_exclusive;
                            $availableSeats = $ride->available_seats;
                            $isFullyBooked = $isExclusive && $availableSeats == 0;
                            $status = $isFullyBooked ? 'Fully Booked' : ($availableSeats == 0 ? 'No Seats' : 'Available');
                            $statusClass = $isFullyBooked ? 'bg-danger' : ($availableSeats == 0 ? 'bg-warning' : 'bg-success');
                        @endphp
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $ride->station_location }}</td>
                            <td>{{ $ride->destination }}</td>
                            <td>{{ $ride->date }}</td>
                            <td>{{ $ride->time }}</td>
                            <td>{{ $availableSeats }}</td>
                            <td>
                                <span class="badge {{ $isExclusive ? 'bg-danger' : 'bg-success' }}">
                                    {{ $isExclusive ? 'EXCLUSIVE' : 'SHARED' }}
                                </span>
                            </td>
                            <td>
                                @if($isExclusive)
                                    @if($ride->go_to_exclusive_price !== null)
                                        ${{ number_format($ride->go_to_exclusive_price, 2) }} (Total)
                                    @else
                                        -
                                    @endif
                                @else
                                    @if($ride->go_to_price_per_person !== null)
                                        ${{ number_format($ride->go_to_price_per_person, 2) }}/person
                                    @else
                                        -
                                    @endif
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $statusClass }}">{{ $status }}</span>
                            </td>
                            <td>
                                <a href="{{ route('driver.rides.edit', $ride->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<!-- Return Rides Section -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fas fa-arrow-left me-2"></i>Return Rides</h5>
    </div>
    <div class="card-body">
        @if($returnRides->isEmpty())
            <div class="alert alert-info">No return rides created yet.</div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Available Seats</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($returnRides as $i => $ride)
                        @php
                            $isExclusive = $ride->return_is_exclusive;
                            $availableSeats = $ride->return_available_seats;
                            $isFullyBooked = $isExclusive && $availableSeats == 0;
                            $status = $isFullyBooked ? 'Fully Booked' : ($availableSeats == 0 ? 'No Seats' : 'Available');
                            $statusClass = $isFullyBooked ? 'bg-danger' : ($availableSeats == 0 ? 'bg-warning' : 'bg-success');
                        @endphp
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $ride->return_station_location }}</td>
                            <td>{{ $ride->return_destination }}</td>
                            <td>{{ $ride->return_date }}</td>
                            <td>{{ $ride->return_time }}</td>
                            <td>{{ $availableSeats }}</td>
                            <td>
                                <span class="badge {{ $isExclusive ? 'bg-danger' : 'bg-success' }}">
                                    {{ $isExclusive ? 'EXCLUSIVE' : 'SHARED' }}
                                </span>
                            </td>
                            <td>
                                @if($isExclusive)
                                    @if($ride->return_exclusive_price !== null)
                                        ${{ number_format($ride->return_exclusive_price, 2) }} (Total)
                                    @else
                                        -
                                    @endif
                                @else
                                    @if($ride->return_price_per_person !== null)
                                        ${{ number_format($ride->return_price_per_person, 2) }}/person
                                    @else
                                        -
                                    @endif
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $statusClass }}">{{ $status }}</span>
                            </td>
                            <td>
                                <a href="{{ route('driver.rides.edit', $ride->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<!-- Action Buttons -->
<div class="text-center mt-4">
    <a href="{{ route('driver.rides.create') }}" class="btn btn-primary px-4 py-2 me-3">
        <i class="fas fa-plus-circle me-2"></i>Create New Ride
    </a>
    <a href="{{ route('driver.earnings') }}" class="btn btn-success px-4 py-2 me-3">
        <i class="fas fa-chart-line me-2"></i>View Earnings
    </a>
    <a href="{{ route('driver.ride.management') }}" class="btn btn-outline-secondary px-4 py-2">
        <i class="fas fa-home me-2"></i>Back to Dashboard
    </a>
</div>

<style>
.table-responsive {
    overflow-x: auto;
    white-space: nowrap;
}

.table {
    min-width: 1000px;
}

.card-header {
    border-radius: 8px 8px 0 0 !important;
}

.btn {
    border-radius: 6px;
    font-weight: 500;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}
</style>
@endsection