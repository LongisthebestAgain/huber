<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use App\Models\User;
use App\Models\RidePurchase;
use Illuminate\Http\Request;

class AdminRideController extends Controller
{
    public function index()
    {
        $rides = Ride::with('driver')->paginate(10);
        return view('admin.rides.index', compact('rides'));
    }

    public function create()
    {
        $drivers = User::where('role', 'driver')->get();
        return view('admin.rides.create', compact('drivers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'station_location' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'date' => 'required|date|after:today',
            'time' => 'required',
            'available_seats' => 'required|integer|min:1|max:20',
            'is_exclusive' => 'boolean',
            'is_two_way' => 'boolean',
            'go_to_price_per_person' => 'nullable|numeric|min:0',
            'go_to_exclusive_price' => 'nullable|numeric|min:0',
        ]);

        Ride::create($request->all());
        return redirect()->route('admin.rides.index')->with('success', 'Ride created successfully');
    }

    public function edit($id)
    {
        $ride = Ride::with('driver')->findOrFail($id);
        $drivers = User::where('role', 'driver')->get();
        return view('admin.rides.edit', compact('ride', 'drivers'));
    }

    public function update(Request $request, $id)
    {
        $ride = Ride::findOrFail($id);
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'station_location' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required',
            'available_seats' => 'required|integer|min:1|max:20',
            'is_exclusive' => 'boolean',
            'is_two_way' => 'boolean',
            'go_to_price_per_person' => 'nullable|numeric|min:0',
            'go_to_exclusive_price' => 'nullable|numeric|min:0',
        ]);
        
        $ride->update($request->all());
        return redirect()->route('admin.rides.index')->with('success', 'Ride updated successfully');
    }

    public function destroy($id)
    {
        $ride = Ride::findOrFail($id);
        $ride->delete();
        return redirect()->route('admin.rides.index')->with('success', 'Ride deleted successfully');
    }

    public function passengers($id)
    {
        $ride = Ride::findOrFail($id);
        $bookings = RidePurchase::with('user')->where('ride_id', $id)->get();
        return view('admin.rides.passengers', compact('ride', 'bookings'));
    }
} 