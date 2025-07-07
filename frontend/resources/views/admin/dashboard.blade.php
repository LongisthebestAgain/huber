@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('subtitle', 'Overview of platform statistics')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-6 col-md-3">
        <div class="card stat-card shadow-sm border-0 h-100">
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <div class="stat-icon bg-primary text-white mb-3"><i class="fas fa-users"></i></div>
                <h2 class="stat-value">{{ $totalUsers }}</h2>
                <div class="stat-label">Total Users</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card shadow-sm border-0 h-100">
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <div class="stat-icon bg-success text-white mb-3"><i class="fas fa-user-tie"></i></div>
                <h2 class="stat-value">{{ $totalDrivers }}</h2>
                <div class="stat-label">Total Drivers</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card shadow-sm border-0 h-100">
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <div class="stat-icon bg-info text-white mb-3"><i class="fas fa-user-check"></i></div>
                <h2 class="stat-value">{{ $totalVerifiedDrivers }}</h2>
                <div class="stat-label">Verified Drivers</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card shadow-sm border-0 h-100">
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <div class="stat-icon bg-warning text-white mb-3"><i class="fas fa-car"></i></div>
                <h2 class="stat-value">{{ $totalRides }}</h2>
                <div class="stat-label">Total Rides</div>
            </div>
        </div>
    </div>
</div>
<div class="row g-4">
    <div class="col-12 col-md-4 mx-auto">
        <div class="card stat-card-earnings shadow-lg border-0 h-100">
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <div class="stat-icon bg-gradient-earnings text-white mb-3"><i class="fas fa-dollar-sign"></i></div>
                <h1 class="stat-value-earnings">${{ number_format($totalEarnings, 2) }}</h1>
                <div class="stat-label">Total Earnings</div>
            </div>
        </div>
    </div>
</div>
<style>
    .stat-card {
        transition: transform 0.15s, box-shadow 0.15s;
        border-radius: 1.25rem;
        background: #fff;
    }
    .stat-card:hover {
        transform: translateY(-4px) scale(1.03);
        box-shadow: 0 8px 32px rgba(0,0,0,0.08);
    }
    .stat-icon {
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        border-radius: 50%;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    }
    .stat-value {
        font-size: 2.2rem;
        font-weight: 700;
        color: #222;
        margin-bottom: 0.25rem;
    }
    .stat-label {
        font-size: 1.05rem;
        color: #666;
        letter-spacing: 0.5px;
        font-weight: 500;
    }
    .stat-card-earnings {
        background: linear-gradient(135deg, #e0ffe7 0%, #f8fafc 100%);
        border-radius: 1.5rem;
        box-shadow: 0 8px 32px rgba(0, 200, 83, 0.08);
        border: 2px solid #b2f2d7;
    }
    .stat-icon.bg-gradient-earnings {
        background: linear-gradient(135deg, #00c853 0%, #00bfae 100%);
        color: #fff;
        box-shadow: 0 2px 12px rgba(0,200,83,0.15);
    }
    .stat-value-earnings {
        font-size: 2.8rem;
        font-weight: 800;
        color: #00bfae;
        margin-bottom: 0.25rem;
        letter-spacing: 1px;
    }
    @media (max-width: 767px) {
        .stat-card, .stat-card-earnings {
            border-radius: 1rem;
        }
        .stat-value, .stat-value-earnings {
            font-size: 1.5rem;
        }
        .stat-icon {
            width: 44px;
            height: 44px;
            font-size: 1.3rem;
        }
    }
</style>
@endsection 