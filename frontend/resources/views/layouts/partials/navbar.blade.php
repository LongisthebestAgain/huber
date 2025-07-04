<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="fas fa-car-side"></i> Hubber
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                @if(Session::has('user'))
                    <!-- Authenticated user menu -->
                    {{-- <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.profile') }}">
                            <i class="fas fa-user me-2"></i>
                        </a>
                    </li> --}}
                    
                    @if(Session::get('user_role') === 'driver')
                        {{-- <li class="nav-item">
                            <a class="nav-link" href="{{ route('driver.profile') }}">
                                <i class="fas fa-car me-2"></i>Edit Driver Profile
                            </a>
                        </li> --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('driver.ride.management') }}">
                                <i class="fas fa-tasks me-2"></i>Ride Management
                            </a>
                        </li>
                    @endif
                    
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('find.rides') }}">
                            <i class="fas fa-search me-2"></i>Find Rides
                        </a>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-2"></i>{{ Session::get('user')['first_name'] ?? 'User' }}
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('user.profile') }}">
                                    <i class="fas fa-user me-2"></i>Profile Management
                                </a>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <!-- Guest menu -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary login-btn" href="{{ route('register') }}">Register</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
