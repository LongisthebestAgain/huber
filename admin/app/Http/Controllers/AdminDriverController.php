<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ride;
use App\Models\RidePurchase;
use App\Models\RideReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminDriverController extends Controller
{
    public function index()
    {
        $drivers = User::where('role', 'driver')
            ->with(['rides', 'driverDocuments'])
            ->paginate(12);
        return view('admin.drivers.index', compact('drivers'));
    }

    public function show($id)
    {
        $driver = User::where('role', 'driver')
            ->with(['rides', 'driverDocuments', 'ridePurchases'])
            ->findOrFail($id);
        
        // Get driver statistics
        $totalRides = $driver->rides()->count();
        $completedRides = $driver->rides()
            ->where('go_completion_status', 'completed')
            ->orWhere('return_completion_status', 'completed')
            ->count();
        
        // Calculate total earnings
        $totalEarnings = $driver->ridePurchases()
            ->where('payment_status', 'paid')
            ->sum('total_price');
        
        // Get recent rides
        $recentRides = $driver->rides()
            ->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->take(5)
            ->get();
        
        // Get average rating
        $reviews = RideReview::whereHas('ride', function($query) use ($driver) {
            $query->where('user_id', $driver->id);
        })->get();
        
        $averageRating = $reviews->count() > 0 ? $reviews->avg('overall_rating') : 0;
        
        // Get monthly earnings for the last 6 months
        $monthlyEarnings = $driver->ridePurchases()
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subMonths(6))
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(total_price) as total')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
        
        return view('admin.drivers.show', compact(
            'driver', 
            'totalRides', 
            'completedRides', 
            'totalEarnings', 
            'recentRides', 
            'averageRating',
            'monthlyEarnings'
        ));
    }

    public function create()
    {
        return view('admin.drivers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'driver',
            'is_verified' => false,
        ]);
        return redirect()->route('admin.drivers.index')->with('success', 'Driver created successfully');
    }

    public function edit($id)
    {
        $driver = User::where('role', 'driver')->findOrFail($id);
        return view('admin.drivers.edit', compact('driver'));
    }

    public function update(Request $request, $id)
    {
        $driver = User::where('role', 'driver')->findOrFail($id);
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $driver->id,
            'password' => 'nullable|min:6',
        ]);
        $driver->name = $request->name;
        $driver->email = $request->email;
        if ($request->password) {
            $driver->password = Hash::make($request->password);
        }
        $driver->save();
        return redirect()->route('admin.drivers.index')->with('success', 'Driver updated successfully');
    }

    public function destroy($id)
    {
        $driver = User::where('role', 'driver')->findOrFail($id);
        $driver->delete();
        return redirect()->route('admin.drivers.index')->with('success', 'Driver deleted successfully');
    }
} 