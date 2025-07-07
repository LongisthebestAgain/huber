<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ride;
use App\Models\DriverDocument;
use App\Models\RidePurchase;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalDrivers = User::where('role', 'driver')->count();
        $totalVerifiedDrivers = User::where('role', 'driver')->where('is_verified', true)->count();
        $totalRides = Ride::count();
        $totalEarnings = RidePurchase::where('payment_status', 'completed')->sum('total_price');

        return view('admin.dashboard', compact(
            'totalUsers', 'totalDrivers', 'totalVerifiedDrivers', 'totalRides', 'totalEarnings'
        ));
    }
} 