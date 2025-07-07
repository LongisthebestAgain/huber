@extends('layouts.admin')
@section('title', 'Passengers for Ride #'.$ride->id)
@section('subtitle', 'View all passengers for this ride')
@section('content')
<div class="card shadow-sm p-4 mx-auto" style="max-width: 900px;">
    <h4 class="mb-4">Passengers for Ride <span class="text-primary">#{{ $ride->id }}</span></h4>
    <div class="mb-3">
        <strong>From:</strong> {{ $ride->station_location }}<br>
        <strong>To:</strong> {{ $ride->destination }}<br>
        <strong>Date:</strong> {{ $ride->date }}<br>
        <strong>Time:</strong> {{ $ride->time }}
    </div>
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Booking ID</th>
                    <th>User</th>
                    <th>Contact</th>
                    <th>Trip Type</th>
                    <th>Seats</th>
                    <th>Passenger Details</th>
                    <th>Payment</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td>{{ $booking->id }}<br><small class="text-muted">Ref: {{ $booking->booking_reference }}</small></td>
                        <td>
                            @if($booking->user)
                                <strong>{{ $booking->user->name }}</strong><br>
                                <small>{{ $booking->user->email }}</small>
                            @else
                                <span class="text-muted">User not found</span>
                            @endif
                        </td>
                        <td>{{ $booking->contact_phone }}</td>
                        <td>{{ ucfirst($booking->trip_type) }}</td>
                        <td>{{ $booking->number_of_seats }}</td>
                        <td>
                            @if(is_array($booking->passenger_details) && count($booking->passenger_details))
                                <ul class="mb-0 ps-3">
                                    @foreach($booking->passenger_details as $passenger)
                                        <li>
                                            <strong>Name:</strong> {{ $passenger['name'] ?? 'Unknown' }}
                                            @if(isset($passenger['seat_number']))
                                                <span class="text-muted">(Seat {{ $passenger['seat_number'] }})</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-muted">No details</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $booking->payment_status === 'completed' ? 'success' : ($booking->payment_status === 'pending' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($booking->payment_status) }}
                            </span><br>
                            <strong>Method:</strong> {{ ucfirst($booking->payment_method) }}<br>
                            <strong>Amount:</strong> ${{ number_format($booking->total_price, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No passengers found for this ride.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        <a href="{{ route('admin.rides.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i>Back to Rides</a>
    </div>
</div>
@endsection 