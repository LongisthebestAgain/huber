@extends('layouts.admin')
@section('title', 'User Details')
@section('subtitle', 'Full information and control for this user')
@section('content')
<div class="card p-4 shadow-sm mx-auto mb-4 user-info-card">
    <h3 class="mb-3"><i class="fas fa-user me-2 text-primary"></i>User Information</h3>
    <dl class="row mb-0">
        <dt class="col-sm-4">ID</dt>
        <dd class="col-sm-8">{{ $user->id }}</dd>
        <dt class="col-sm-4">Name</dt>
        <dd class="col-sm-8">{{ $user->name }}</dd>
        <dt class="col-sm-4">Email</dt>
        <dd class="col-sm-8">{{ $user->email }}</dd>
        <dt class="col-sm-4">Phone</dt>
        <dd class="col-sm-8">{{ $user->phone ?? '-' }}</dd>
        <dt class="col-sm-4">Role</dt>
        <dd class="col-sm-8"><span class="badge bg-info text-dark">{{ $user->role ?? '-' }}</span></dd>
        <dt class="col-sm-4">Status</dt>
        <dd class="col-sm-8"><span class="badge bg-{{ $user->status === 'active' ? 'success' : 'secondary' }}">{{ $user->status ?? 'active' }}</span></dd>
        <dt class="col-sm-4">Created At</dt>
        <dd class="col-sm-8">{{ $user->created_at ? $user->created_at->format('Y-m-d H:i') : '-' }}</dd>
        <dt class="col-sm-4">Updated At</dt>
        <dd class="col-sm-8">{{ $user->updated_at ? $user->updated_at->format('Y-m-d H:i') : '-' }}</dd>
    </dl>
    <div class="mt-4 d-flex gap-2">
        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit
        </a>
        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash-alt"></i> Delete
            </button>
        </form>
    </div>
</div>
<div class="row g-4">
    <div class="col-md-6 mb-4">
        <div class="card p-3 h-100 shadow-sm">
            <div class="section-header bg-primary bg-gradient text-white rounded-3 px-3 py-2 mb-3 d-flex align-items-center">
                <i class="fas fa-ticket-alt me-2"></i>Bookings
            </div>
            <h6 class="text-secondary mb-2">Current Bookings</h6>
            @if(isset($currentBookings) && $currentBookings->count())
                <ul class="list-group list-group-flush mb-3">
                    @foreach($currentBookings as $booking)
                        <li class="list-group-item px-0 border-0 border-bottom booking-list-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-info text-dark me-2">#{{ $booking->id }}</span>
                                    <span class="fw-semibold">Ride: #{{ $booking->ride_id ?? '-' }}</span>
                                </div>
                                <span class="badge bg-{{ $booking->payment_status === 'completed' ? 'success' : ($booking->payment_status === 'pending' ? 'warning' : 'secondary') }}">{{ ucfirst($booking->payment_status) }}</span>
                            </div>
                            @if($booking->ride)
                                <div class="small text-muted mt-1 mb-1">
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $booking->ride->station_location }}
                                    <i class="fas fa-arrow-right mx-1"></i>{{ $booking->ride->destination }}<br>
                                    <i class="fas fa-calendar-alt me-1"></i>{{ $booking->ride->date }}
                                </div>
                            @endif
                            <div class="small">Booked: {{ $booking->created_at->format('Y-m-d') }}</div>
                            <div class="small">Amount: <span class="fw-bold text-success">${{ number_format($booking->total_price, 2) }}</span></div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-muted mb-3">No current bookings found.</div>
            @endif
            <h6 class="text-secondary mb-2 mt-4">Past Bookings</h6>
            @if(isset($pastBookings) && $pastBookings->count())
                <ul class="list-group list-group-flush">
                    @foreach($pastBookings as $booking)
                        <li class="list-group-item px-0 border-0 border-bottom booking-list-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-info text-dark me-2">#{{ $booking->id }}</span>
                                    <span class="fw-semibold">Ride: #{{ $booking->ride_id ?? '-' }}</span>
                                </div>
                                <span class="badge bg-{{ $booking->payment_status === 'completed' ? 'success' : ($booking->payment_status === 'pending' ? 'warning' : 'secondary') }}">{{ ucfirst($booking->payment_status) }}</span>
                            </div>
                            @if($booking->ride)
                                <div class="small text-muted mt-1 mb-1">
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $booking->ride->station_location }}
                                    <i class="fas fa-arrow-right mx-1"></i>{{ $booking->ride->destination }}<br>
                                    <i class="fas fa-calendar-alt me-1"></i>{{ $booking->ride->date }}
                                </div>
                            @endif
                            <div class="small">Booked: {{ $booking->created_at->format('Y-m-d') }}</div>
                            <div class="small">Amount: <span class="fw-bold text-success">${{ number_format($booking->total_price, 2) }}</span></div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-muted">No past bookings found.</div>
            @endif
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card p-3 h-100 shadow-sm">
            <div class="section-header bg-success bg-gradient text-white rounded-3 px-3 py-2 mb-3 d-flex align-items-center">
                <i class="fas fa-car me-2"></i>Rides
            </div>
            <h6 class="text-secondary mb-2">Current Rides</h6>
            @if(isset($currentRides) && $currentRides->count())
                <ul class="list-group list-group-flush mb-3">
                    @foreach($currentRides as $ride)
                        <li class="list-group-item px-0 border-0 border-bottom ride-list-item">
                            <span class="badge bg-success me-2">#{{ $ride->id }}</span>
                            <span class="fw-semibold">{{ $ride->station_location }} <i class="fas fa-arrow-right mx-1"></i> {{ $ride->destination }}</span><br>
                            <span class="small text-muted"><i class="fas fa-calendar-alt me-1"></i>{{ $ride->date ?? '-' }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-muted mb-3">No current rides found.</div>
            @endif
            <h6 class="text-secondary mb-2 mt-4">Past Rides</h6>
            @if(isset($pastRides) && $pastRides->count())
                <ul class="list-group list-group-flush">
                    @foreach($pastRides as $ride)
                        <li class="list-group-item px-0 border-0 border-bottom ride-list-item">
                            <span class="badge bg-secondary me-2">#{{ $ride->id }}</span>
                            <span class="fw-semibold">{{ $ride->station_location }} <i class="fas fa-arrow-right mx-1"></i> {{ $ride->destination }}</span><br>
                            <span class="small text-muted"><i class="fas fa-calendar-alt me-1"></i>{{ $ride->date ?? '-' }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-muted">No past rides found.</div>
            @endif
        </div>
    </div>
</div>
<style>
.user-info-card { border-radius: 1.25rem; }
.section-header { font-size: 1.1rem; font-weight: 600; letter-spacing: 0.5px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
.booking-list-item, .ride-list-item { background: none; border-radius: 0.5rem; margin-bottom: 0.25rem; }
.booking-list-item:last-child, .ride-list-item:last-child { margin-bottom: 0; }
.list-group-item { border: none !important; }
@media (max-width: 767px) {
    .user-info-card { padding: 1.2rem !important; }
    .section-header { font-size: 1rem; }
}
</style>
@endsection 