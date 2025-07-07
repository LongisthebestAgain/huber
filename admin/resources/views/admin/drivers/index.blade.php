@extends('layouts.admin')
@section('title', 'Manage Drivers')
@section('subtitle', 'View, create, edit and manage all drivers')
@section('content')
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="fas fa-user-tie me-2 text-primary"></i>Drivers</h2>
            <p class="text-muted mb-0">Manage and monitor all registered drivers</p>
        </div>
        <a href="{{ route('admin.drivers.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-user-plus me-2"></i> Add Driver
        </a>
    </div>

    <!-- Search and Filter Section -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" id="searchInput" 
                               placeholder="Search drivers by name or email...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="verified">Verified</option>
                        <option value="unverified">Unverified</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="sortFilter">
                        <option value="name">Sort by Name</option>
                        <option value="recent">Sort by Recent</option>
                        <option value="rides">Sort by Rides</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary bg-gradient rounded-3 p-3 me-3">
                            <i class="fas fa-users text-white fa-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="card-title text-muted mb-1">Total Drivers</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $drivers->total() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success bg-gradient rounded-3 p-3 me-3">
                            <i class="fas fa-check-circle text-white fa-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="card-title text-muted mb-1">Verified Drivers</h6>
                            <h3 class="mb-0 fw-bold text-success">{{ $drivers->where('is_verified', true)->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning bg-gradient rounded-3 p-3 me-3">
                            <i class="fas fa-clock text-white fa-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="card-title text-muted mb-1">Pending Verification</h6>
                            <h3 class="mb-0 fw-bold text-warning">{{ $drivers->where('is_verified', false)->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info bg-gradient rounded-3 p-3 me-3">
                            <i class="fas fa-car text-white fa-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="card-title text-muted mb-1">Active Drivers</h6>
                            <h3 class="mb-0 fw-bold text-info">{{ $drivers->where('is_verified', true)->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Drivers Grid -->
    <div class="row g-4" id="driversGrid">
        @foreach($drivers as $driver)
            <div class="col-lg-4 col-md-6 driver-card" 
                 data-name="{{ strtolower($driver->name) }}" 
                 data-email="{{ strtolower($driver->email) }}"
                 data-status="{{ $driver->is_verified ? 'verified' : 'unverified' }}"
                 data-rides="{{ $driver->rides->count() }}">
                <div class="card driver-profile-card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 pt-4 pb-0">
                        <div class="d-flex align-items-center">
                            <div class="driver-avatar me-3">
                                @if($driver->profile_picture)
                                    <img src="{{ asset('storage/' . $driver->profile_picture) }}" 
                                         class="rounded-circle" width="70" height="70" alt="Profile">
                                @else
                                    <div class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="fas fa-user text-white fa-2x"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-1 fw-bold">{{ $driver->name }}</h5>
                                <p class="text-muted mb-1 small">
                                    <i class="fas fa-envelope me-1"></i>{{ $driver->email }}
                                </p>
                                <div class="mt-2">
                                    @if($driver->is_verified)
                                        <span class="badge bg-success bg-gradient">
                                            <i class="fas fa-check-circle me-1"></i>Verified
                                        </span>
                                    @else
                                        <span class="badge bg-warning bg-gradient">
                                            <i class="fas fa-clock me-1"></i>Pending Verification
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body pt-3">
                        <!-- Statistics Row -->
                        <div class="row text-center mb-4">
                            <div class="col-4">
                                <div class="stat-item border-end">
                                    <h6 class="mb-1 fw-bold text-primary">{{ $driver->rides->count() }}</h6>
                                    <small class="text-muted">Total Rides</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-item border-end">
                                    <h6 class="mb-1 fw-bold text-success">
                                        @php
                                            $completedRides = $driver->rides->where('go_completion_status', 'completed')->count() + 
                                                             $driver->rides->where('return_completion_status', 'completed')->count();
                                        @endphp
                                        {{ $completedRides }}
                                    </h6>
                                    <small class="text-muted">Completed</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-item">
                                    <h6 class="mb-1 fw-bold text-info">
                                        @php
                                            $totalEarnings = $driver->ridePurchases->where('payment_status', 'paid')->sum('total_price');
                                        @endphp
                                        ${{ number_format($totalEarnings, 0) }}
                                    </h6>
                                    <small class="text-muted">Earnings</small>
                                </div>
                            </div>
                        </div>

                        <!-- Vehicle Info -->
                        @if($driver->vehicle_model && $driver->vehicle_year)
                            <div class="vehicle-info mb-3 p-3 bg-light rounded">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-car text-primary me-2"></i>
                                    <span class="small fw-semibold">{{ $driver->vehicle_year }} {{ $driver->vehicle_model }}</span>
                                </div>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('admin.drivers.show', $driver->id) }}" 
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-eye me-1"></i> View Details
                            </a>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" 
                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.drivers.edit', $driver->id) }}">
                                            <i class="fas fa-edit me-2 text-primary"></i> Edit Driver
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.driver_documents.show', $driver->id) }}">
                                            <i class="fas fa-file-alt me-2 text-info"></i> View Documents
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.drivers.destroy', $driver->id) }}" method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this driver?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-trash me-2"></i> Delete Driver
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
        {{ $drivers->links() }}
    </div>

    <!-- No Results Message -->
    <div id="noResults" class="text-center py-5" style="display: none;">
        <div class="no-results-container">
            <i class="fas fa-search fa-4x text-muted mb-4"></i>
            <h4 class="text-muted mb-2">No drivers found</h4>
            <p class="text-muted">Try adjusting your search criteria or filters</p>
        </div>
    </div>
@endsection

@push('styles')
<style>
/* Card Styles */
.driver-profile-card {
    transition: all 0.3s ease;
    border-radius: 12px;
    overflow: hidden;
}

.driver-profile-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
}

/* Avatar Styles */
.driver-avatar img {
    border: 3px solid #e9ecef;
    transition: all 0.3s ease;
}

.driver-profile-card:hover .driver-avatar img {
    border-color: #007bff;
}

.avatar-placeholder {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #6c757d, #495057);
    border: 3px solid #e9ecef;
    transition: all 0.3s ease;
}

.driver-profile-card:hover .avatar-placeholder {
    border-color: #007bff;
}

/* Statistics Cards */
.stat-card {
    transition: all 0.3s ease;
    border-radius: 12px;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
}

.stat-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Statistics Items */
.stat-item {
    padding: 0.5rem 0;
}

.stat-item:not(:last-child) {
    border-right: 1px solid #e9ecef;
}

/* Vehicle Info */
.vehicle-info {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-left: 4px solid #007bff;
}

/* Badge Styles */
.badge {
    font-size: 0.75rem;
    padding: 0.5rem 0.75rem;
    border-radius: 20px;
}

/* Button Styles */
.btn-sm {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 500;
}

/* Search and Filter Section */
.input-group-text {
    border: 1px solid #dee2e6;
}

.form-control:focus,
.form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* No Results */
.no-results-container {
    max-width: 400px;
    margin: 0 auto;
}

/* Responsive Design */
@media (max-width: 768px) {
    .stat-card .card-body {
        padding: 1rem;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
    }
    
    .driver-avatar img,
    .avatar-placeholder {
        width: 60px;
        height: 60px;
    }
    
    .btn-sm {
        padding: 0.4rem 0.8rem;
        font-size: 0.875rem;
    }
}

/* Animation for cards */
.driver-card {
    animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Dropdown menu styling */
.dropdown-menu {
    border: none;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

.dropdown-item {
    padding: 0.5rem 1rem;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

/* Pagination styling */
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
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const sortFilter = document.getElementById('sortFilter');
    const driversGrid = document.getElementById('driversGrid');
    const noResults = document.getElementById('noResults');
    const driverCards = document.querySelectorAll('.driver-card');

    function filterAndSortDrivers() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusFilterValue = statusFilter.value;
        const sortValue = sortFilter.value;
        
        let visibleCards = 0;
        const cardsArray = Array.from(driverCards);

        // Filter cards
        cardsArray.forEach(card => {
            const name = card.dataset.name;
            const email = card.dataset.email;
            const status = card.dataset.status;
            const rides = parseInt(card.dataset.rides);

            const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
            const matchesStatus = !statusFilterValue || status === statusFilterValue;

            if (matchesSearch && matchesStatus) {
                card.style.display = 'block';
                visibleCards++;
            } else {
                card.style.display = 'none';
            }
        });

        // Sort cards
        if (sortValue === 'name') {
            cardsArray.sort((a, b) => a.dataset.name.localeCompare(b.dataset.name));
        } else if (sortValue === 'rides') {
            cardsArray.sort((a, b) => parseInt(b.dataset.rides) - parseInt(a.dataset.rides));
        }
        // For 'recent' sorting, we'll keep the original order (most recent first from database)

        // Re-append sorted cards
        cardsArray.forEach(card => {
            driversGrid.appendChild(card);
        });

        // Show/hide no results message
        if (visibleCards === 0) {
            noResults.style.display = 'block';
            driversGrid.style.display = 'none';
        } else {
            noResults.style.display = 'none';
            driversGrid.style.display = 'flex';
        }
    }

    // Event listeners
    searchInput.addEventListener('input', filterAndSortDrivers);
    statusFilter.addEventListener('change', filterAndSortDrivers);
    sortFilter.addEventListener('change', filterAndSortDrivers);
});
</script>
@endpush 