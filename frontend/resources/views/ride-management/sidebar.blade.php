<div class="sidebar-sticky">
    @php
        $profilePic = $user->profile_picture ?? (session('user.profile_picture') ?? null);
        $nameParts = $user && $user->name ? explode(' ', $user->name, 2) : [session('user.first_name') ?? 'Driver', session('user.last_name') ?? ''];
        $firstName = $nameParts[0] ?? '';
        $lastName = $nameParts[1] ?? '';
    @endphp
    @if($profilePic)
        <img src="{{ asset('storage/' . $profilePic) }}" alt="Profile Picture" class="rounded-circle mb-2" style="width: 56px; height: 56px; object-fit: cover; border: 2px solid #e3e6f0;">
    @else
        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 56px; height: 56px; font-size: 2rem;">
            <i class="fas fa-user"></i>
        </div>
    @endif
    <div class="fw-bold" style="font-size: 1.1rem; max-width: 160px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $firstName }} {{ $lastName }}">
        <span class="me-1"><i class="fas fa-car-side text-danger"></i></span>
        {{ \Illuminate\Support\Str::limit($firstName . ' ' . $lastName, 18) }}
    </div>
    <div class="text-muted" style="font-size: 0.95rem;">Driver</div>
    <ul class="nav flex-column mt-4">
        <li class="nav-item mb-2">
            <a class="nav-link {{ request()->routeIs('driver.ride.management') ? 'active bg-primary text-white rounded' : 'text-dark' }}" href="{{ route('driver.ride.management') }}"><i class="fas fa-home me-2"></i>Dashboard</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link {{ request()->routeIs('driver.rides.create') ? 'active bg-primary text-white rounded' : 'text-dark' }}" href="{{ route('driver.rides.create') }}"><i class="fas fa-plus-circle me-2"></i>Create Ride</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link {{ request()->routeIs('driver.my-rides') ? 'active bg-primary text-white rounded' : 'text-dark' }}" href="{{ route('driver.my-rides') }}"><i class="fas fa-random me-2"></i>My Rides</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link text-dark" href="#"><i class="fas fa-chart-line me-2"></i>Earnings</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link text-dark" href="#"><i class="fas fa-star me-2"></i>Reviews</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link text-dark" href="{{ route('driver.profile') }}"><i class="fas fa-user-cog me-2"></i>Profile</a>
        </li>
    </ul>
    <div class="mt-4">
        <a class="btn btn-outline-danger w-100" href="{{ route('home') }}"><i class="fas fa-home me-2"></i>Back to Home</a>
    </div>
</div> 