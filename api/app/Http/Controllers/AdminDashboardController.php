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

    public function apiIndex()
    {
        $totalUsers = \App\Models\User::count();
        $totalDrivers = \App\Models\User::where('role', 'driver')->count();
        $totalVerifiedDrivers = \App\Models\User::where('role', 'driver')->where('is_verified', true)->count();
        $totalRides = \App\Models\Ride::count();
        $totalEarnings = \App\Models\RidePurchase::where('payment_status', 'completed')->sum('total_price');

        return response()->json([
            'success' => true,
            'data' => [
                'total_users' => $totalUsers,
                'total_drivers' => $totalDrivers,
                'total_verified_drivers' => $totalVerifiedDrivers,
                'total_rides' => $totalRides,
                'total_earnings' => $totalEarnings,
            ]
        ]);
    }
} 