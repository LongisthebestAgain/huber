@extends('layouts.ride-management')

@section('title', 'Earnings - Hubber')

@section('main')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h2 class="h4 mb-4">Earnings</h2>
        <p class="text-muted">This page lists all bookings for your rides, including payment and passenger details.</p>
    </div>
</div>
<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="mb-3">Bookings & Earnings</h5>
        @if($bookings->isEmpty())
            <div class="alert alert-info">No bookings yet for your rides.</div>
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $i => $booking)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>
                                @if($booking->user)
                                    {{ $booking->user->name }}
                                @else
                                    <span class="text-muted">Unknown</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->user && $booking->user->profile_picture)
                                    <img src="{{ asset('storage/' . $booking->user->profile_picture) }}" alt="Profile" class="rounded-circle" width="36" height="36">
                                @else
                                    <span class="badge bg-secondary">No Photo</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $booking->ride && ($booking->ride->is_exclusive || $booking->ride->return_is_exclusive) ? 'bg-danger' : 'bg-success' }}">
                                    {{ strtoupper($booking->trip_type) }} - {{ ($booking->ride && ($booking->ride->is_exclusive && $booking->trip_type === 'go')) || ($booking->ride && ($booking->ride->return_is_exclusive && $booking->trip_type === 'return')) ? 'EXCLUSIVE' : 'SHARED' }}
                                </span>
                            </td>
                            <td>
                                @if($booking->ride)
                                    {{ $booking->trip_type === 'return' ? $booking->ride->destination : $booking->ride->station_location }}
                                    <i class="fas fa-arrow-right mx-1"></i>
                                    {{ $booking->trip_type === 'return' ? $booking->ride->station_location : $booking->ride->destination }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ ucfirst($booking->trip_type) }}</td>
                            <td>{{ $booking->number_of_seats }}</td>
                            <td class="text-success fw-bold">${{ number_format($booking->total_price, 2) }}</td>
                            <td>{{ ucfirst($booking->payment_method) }}</td>
                            <td><span class="badge bg-primary">{{ $booking->booking_reference }}</span></td>
                            <td>{{ $booking->created_at ? $booking->created_at->format('M d, Y H:i') : '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
