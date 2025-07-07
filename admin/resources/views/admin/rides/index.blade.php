@extends('layouts.admin')
@section('title', 'Manage Rides')
@section('subtitle', 'View, create, edit and manage all rides')
@section('content')
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="fas fa-car me-2 text-primary"></i>Rides</h2>
            <p class="text-muted mb-0">Manage and monitor all rides on the platform</p>
        </div>
        <a href="{{ route('admin.rides.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-2"></i> Add Ride
        </a>
    </div>
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 bg-white rounded rides-table">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Driver</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Date & Time</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rides as $ride)
                            <tr>
                                <td class="fw-semibold text-primary">{{ $ride->id }}</td>
                                <td>
                                    @if($ride->driver)
                                        <div>
                                            <strong>{{ $ride->driver->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $ride->driver->email }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted">Driver not found</span>
                                    @endif
                                </td>
                                <td>{{ $ride->station_location }}</td>
                                <td>{{ $ride->destination }}</td>
                                <td>
                                    <div>
                                        <strong>{{ $ride->date->format('M d, Y') }}</strong>
                                        <br>
                                        <small class="text-muted"><i class="fas fa-clock me-1"></i>{{ $ride->time->format('H:i') }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($ride->is_exclusive)
                                        <span class="badge bg-purple bg-gradient text-black"><i class="fas fa-user-lock me-1"></i>Exclusive</span>
                                    @else
                                        <span class="badge bg-blue bg-gradient text-black"><i class="fas fa-users me-1"></i>Shared</span>
                                    @endif
                                    @if($ride->is_two_way)
                                        <br><span class="badge bg-info bg-gradient mt-1 text-black"><i class="fas fa-exchange-alt me-1"></i>Two-way</span>
                                    @endif
                                </td>
                                <td>
                                    @if($ride->go_completion_status === 'completed')
                                        <span class="badge bg-success bg-gradient"><i class="fas fa-check-circle me-1"></i>Completed</span>
                                    @elseif($ride->go_completion_status === 'ongoing')
                                        <span class="badge bg-warning bg-gradient"><i class="fas fa-spinner me-1"></i>Ongoing</span>
                                    @else
                                        <span class="badge bg-secondary bg-gradient"><i class="fas fa-clock me-1"></i>Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if($ride->is_exclusive)
                                        <span class="fw-semibold text-success">${{ number_format($ride->go_to_exclusive_price, 2) }}</span>
                                    @else
                                        <span class="fw-semibold text-info">${{ number_format($ride->go_to_price_per_person, 2) }}</span><span class="text-muted small">/person</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.rides.passengers', $ride->id) }}" class="btn btn-outline-info btn-sm" title="View Passengers">
                                            <i class="fas fa-users"></i>
                                        </a>
                                        <form action="{{ route('admin.rides.destroy', $ride->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                    onclick="return confirm('Are you sure you want to delete this ride?')" title="Delete Ride">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center mt-4">
        {{ $rides->links() }}
    </div>
@endsection

@push('styles')
<style>
.rides-table th {
    font-weight: 600;
    color: #495057;
    vertical-align: middle;
}
.rides-table tbody tr {
    transition: background 0.2s;
}
.rides-table tbody tr:hover {
    background: #f8f9fa;
}
.badge.bg-purple {
    background-color: #6f42c1 !important;
    color: #fff;
}
.badge.bg-blue {
    background-color: #0d6efd !important;
    color: #fff;
}
.badge.bg-gradient {
    background-image: linear-gradient(135deg, rgba(0,123,255,0.1), rgba(0,123,255,0.2));
}
.btn-group .btn {
    border-radius: 0.375rem !important;
    margin-right: 0.25rem;
}
.btn-group .btn:last-child {
    margin-right: 0;
}
.table-responsive {
    border-radius: 0.75rem;
    overflow: hidden;
}
.pagination {
    gap: 0.25rem;
}
.page-link {
    border-radius: 8px;
    border: none;
    padding: 0.5rem 0.75rem;
    color: #007bff;
    transition: all 0.2s ease;
}
.page-link:hover {
    background-color: #007bff;
    color: white;
    transform: translateY(-1px);
}
.page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
}
.text-black {
    color: #000 !important;
}
</style>
@endpush 