<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ride;

class DriverRideManagementController extends Controller
{
    public function index(Request $request)
    {
        $userData = session('user');
        if (!$userData || !isset($userData['id'])) {
            return redirect()->route('login')->with('error', 'Please login to access ride management.');
        }
        $user = User::find($userData['id']);
        if (!$user) {
            session()->forget(['user', 'user_role']);
            return redirect()->route('login')->with('error', 'User not found. Please login again.');
        }
        return view('ride-management.index', compact('user'));
    }

    public function create()
    {
        $userData = session('user');
        if (!$userData || !isset($userData['id'])) {
            return redirect()->route('login')->with('error', 'Please login to create a ride.');
        }
        $user = \App\Models\User::find($userData['id']);
        if (!$user) {
            session()->forget(['user', 'user_role']);
            return redirect()->route('login')->with('error', 'User not found. Please login again.');
        }
        return view('ride-management.create', compact('user'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $userData = session('user');
        if (!$userData || !isset($userData['id'])) {
            return redirect()->route('login')->with('error', 'Please login to create a ride.');
        }
        $user = \App\Models\User::find($userData['id']);
        if (!$user) {
            session()->forget(['user', 'user_role']);
            return redirect()->route('login')->with('error', 'User not found. Please login again.');
        }
        $validated = $request->validate([
            'station_location' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'available_seats' => 'required|integer',
            'is_exclusive' => 'required|boolean',
            'is_two_way' => 'required|boolean',
            // Return trip fields (nullable if not two way)
            'return_station_location' => 'nullable|string|max:255',
            'return_destination' => 'nullable|string|max:255',
            'return_date' => 'nullable|date|after_or_equal:date',
            'return_time' => 'nullable',
            'return_available_seats' => 'nullable|integer',
            'return_is_exclusive' => 'nullable|boolean',
            // Map URL fields
            'station_location_map_url' => 'nullable|url|max:255',
            'destination_map_url' => 'nullable|url|max:255',
            'return_station_location_map_url' => 'nullable|url|max:255',
            'return_destination_map_url' => 'nullable|url|max:255',
            'go_to_price_per_person' => 'required|numeric|min:0',
            'return_price_per_person' => 'nullable|numeric|min:0',
        ]);
        if (!$request->is_two_way) {
            $validated['return_price_per_person'] = null;
        } else {
            $request->validate([
                'return_price_per_person' => 'required|numeric|min:0',
            ]);
        }
        // Always set return_destination to station_location
        $validated['return_destination'] = $validated['station_location'];
        $ride = new \App\Models\Ride($validated);
        $ride->user_id = $user->id;
        $ride->save();
        return redirect()->route('driver.ride.management')->with('success', 'Ride created successfully!');
    }

    public function myRides(Request $request)
    {
        $userData = session('user');
        if (!$userData || !isset($userData['id'])) {
            return redirect()->route('login')->with('error', 'Please login to access your rides.');
        }
        $user = User::find($userData['id']);
        if (!$user) {
            session()->forget(['user', 'user_role']);
            return redirect()->route('login')->with('error', 'User not found. Please login again.');
        }
        return view('ride-management.my-rides', compact('user'));
    }

    public function edit($rideId)
    {
        $userData = session('user');
        if (!$userData || !isset($userData['id'])) {
            return redirect()->route('login')->with('error', 'Please login to edit your ride.');
        }
        $user = User::find($userData['id']);
        if (!$user) {
            session()->forget(['user', 'user_role']);
            return redirect()->route('login')->with('error', 'User not found. Please login again.');
        }
        $ride = Ride::where('id', $rideId)->where('user_id', $user->id)->first();
        if (!$ride) {
            return redirect()->route('driver.my-rides')->with('error', 'Ride not found or access denied.');
        }
        return view('ride-management.edit', compact('user', 'ride'));
    }

    public function update(Request $request, $rideId)
    {
        $userData = session('user');
        if (!$userData || !isset($userData['id'])) {
            return redirect()->route('login')->with('error', 'Please login to update your ride.');
        }
        $user = User::find($userData['id']);
        if (!$user) {
            session()->forget(['user', 'user_role']);
            return redirect()->route('login')->with('error', 'User not found. Please login again.');
        }
        $ride = Ride::where('id', $rideId)->where('user_id', $user->id)->first();
        if (!$ride) {
            return redirect()->route('driver.my-rides')->with('error', 'Ride not found or access denied.');
        }
        $validated = $request->validate([
            'station_location' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'available_seats' => 'required|integer',
            'is_exclusive' => 'required|boolean',
            'is_two_way' => 'required|boolean',
            // Return trip fields (nullable if not two way)
            'return_station_location' => 'nullable|string|max:255',
            'return_destination' => 'nullable|string|max:255',
            'return_date' => 'nullable|date|after_or_equal:date',
            'return_time' => 'nullable',
            'return_available_seats' => 'nullable|integer',
            'return_is_exclusive' => 'nullable|boolean',
            // Map URL fields
            'station_location_map_url' => 'nullable|url|max:255',
            'destination_map_url' => 'nullable|url|max:255',
            'return_station_location_map_url' => 'nullable|url|max:255',
            'return_destination_map_url' => 'nullable|url|max:255',
            'go_to_price_per_person' => 'required|numeric|min:0',
            'return_price_per_person' => 'nullable|numeric|min:0',
        ]);
        if (!$request->is_two_way) {
            $validated['return_price_per_person'] = null;
        } else {
            $request->validate([
                'return_price_per_person' => 'required|numeric|min:0',
            ]);
        }
        // Always set return_destination to station_location
        $validated['return_destination'] = $validated['station_location'];
        $ride->update($validated);
        $ride->save();
        return redirect()->route('driver.my-rides')->with('success', 'Ride updated successfully!');
    }
} 