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
        
        // Get all rides created by this driver
        $allRides = Ride::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->get();
            
        // Separate go and return rides
        $goRides = collect();
        $returnRides = collect();
        
        foreach ($allRides as $ride) {
            // Add the main ride (go trip)
            $goRides->push($ride);
            
            // If it's a two-way ride with return details, add it as a separate return ride
            if ($ride->is_two_way && $ride->return_date && $ride->return_time) {
                $returnRides->push($ride);
            }
        }
            
        return view('ride-management.index', compact('user', 'goRides', 'returnRides'));
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
            // Price fields - conditional based on ride type
            'go_to_price_per_person' => 'nullable|numeric|min:0',
            'go_to_exclusive_price' => 'nullable|numeric|min:0',
            'return_price_per_person' => 'nullable|numeric|min:0',
            'return_exclusive_price' => 'nullable|numeric|min:0',
        ]);

        // Validate price fields based on ride type
        if ($request->is_exclusive) {
            $request->validate([
                'go_to_exclusive_price' => 'required|numeric|min:0',
            ], [
                'go_to_exclusive_price.required' => 'Exclusive price is required for exclusive rides.',
            ]);
            $validated['go_to_price_per_person'] = null;
        } else {
            $request->validate([
                'go_to_price_per_person' => 'required|numeric|min:0',
            ], [
                'go_to_price_per_person.required' => 'Price per person is required for shared rides.',
            ]);
            $validated['go_to_exclusive_price'] = null;
        }

        if ($request->is_two_way) {
            if ($request->return_is_exclusive) {
                $request->validate([
                    'return_exclusive_price' => 'required|numeric|min:0',
                ], [
                    'return_exclusive_price.required' => 'Return exclusive price is required for exclusive return rides.',
                ]);
                $validated['return_price_per_person'] = null;
            } else {
                $request->validate([
                    'return_price_per_person' => 'required|numeric|min:0',
                ], [
                    'return_price_per_person.required' => 'Return price per person is required for shared return rides.',
                ]);
                $validated['return_exclusive_price'] = null;
            }
        } else {
            $validated['return_price_per_person'] = null;
            $validated['return_exclusive_price'] = null;
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
            'available_seats' => 'required|integer|min:1',
            'is_exclusive' => 'required|boolean',
            'is_two_way' => 'required|boolean',
            // Map URL fields
            'station_location_map_url' => 'nullable|url|max:255',
            'destination_map_url' => 'nullable|url|max:255',
            // Price fields - conditional based on ride type
            'go_to_price_per_person' => 'nullable|numeric|min:0',
            'go_to_exclusive_price' => 'nullable|numeric|min:0',
        ]);

        // Validate price fields based on ride type
        if ($request->is_exclusive) {
            $request->validate([
                'go_to_exclusive_price' => 'required|numeric|min:0',
            ], [
                'go_to_exclusive_price.required' => 'Exclusive price is required for exclusive rides.',
            ]);
            $validated['go_to_price_per_person'] = null;
        } else {
            $request->validate([
                'go_to_price_per_person' => 'required|numeric|min:0',
            ], [
                'go_to_price_per_person.required' => 'Price per person is required for shared rides.',
            ]);
            $validated['go_to_exclusive_price'] = null;
        }

        // Handle return trip fields
        if ($request->is_two_way) {
            $returnValidation = $request->validate([
                'return_station_location' => 'required|string|max:255',
                'return_date' => 'required|date|after_or_equal:date',
                'return_time' => 'required',
                'return_available_seats' => 'required|integer|min:1',
                'return_is_exclusive' => 'required|boolean',
                'return_price_per_person' => 'nullable|numeric|min:0',
                'return_exclusive_price' => 'nullable|numeric|min:0',
                'return_station_location_map_url' => 'nullable|url|max:255',
                'return_destination_map_url' => 'nullable|url|max:255',
            ]);
            
            $validated = array_merge($validated, $returnValidation);
            
            // Validate return price fields based on return ride type
            if ($request->return_is_exclusive) {
                $request->validate([
                    'return_exclusive_price' => 'required|numeric|min:0',
                ], [
                    'return_exclusive_price.required' => 'Return exclusive price is required for exclusive return rides.',
                ]);
                $validated['return_price_per_person'] = null;
            } else {
                $request->validate([
                    'return_price_per_person' => 'required|numeric|min:0',
                ], [
                    'return_price_per_person.required' => 'Return price per person is required for shared return rides.',
                ]);
                $validated['return_exclusive_price'] = null;
            }
            
            // Always set return_destination to station_location
            $validated['return_destination'] = $validated['station_location'];
        } else {
            // Clear return fields for one-way rides
            $validated['return_station_location'] = null;
            $validated['return_destination'] = null;
            $validated['return_date'] = null;
            $validated['return_time'] = null;
            $validated['return_available_seats'] = null;
            $validated['return_is_exclusive'] = null;
            $validated['return_price_per_person'] = null;
            $validated['return_exclusive_price'] = null;
            $validated['return_station_location_map_url'] = null;
            $validated['return_destination_map_url'] = null;
        }

        $ride->update($validated);
        return redirect()->route('driver.my-rides')->with('success', 'Ride updated successfully!');
    }

    public function findRides(Request $request)
    {
        $userData = session('user');
        $userId = $userData['id'] ?? null;
        $query = \App\Models\Ride::with(['user.driverDocuments']);

        // Filtering
        if ($request->filled('date')) {
            $query->where('date', $request->input('date'));
        }
        if ($request->filled('price_min')) {
            $query->where(function($q) use ($request) {
                $q->where('go_to_price_per_person', '>=', $request->input('price_min'))
                  ->orWhere('go_to_exclusive_price', '>=', $request->input('price_min'))
                  ->orWhere('return_price_per_person', '>=', $request->input('price_min'))
                  ->orWhere('return_exclusive_price', '>=', $request->input('price_min'));
            });
        }
        if ($request->filled('price_max')) {
            $query->where(function($q) use ($request) {
                $q->where('go_to_price_per_person', '<=', $request->input('price_max'))
                  ->orWhere('go_to_exclusive_price', '<=', $request->input('price_max'))
                  ->orWhere('return_price_per_person', '<=', $request->input('price_max'))
                  ->orWhere('return_exclusive_price', '<=', $request->input('price_max'));
            });
        }
        if ($request->filled('departure_time')) {
            // Simple time filtering (morning, afternoon, evening)
            $time = $request->input('departure_time');
            if ($time === 'morning') {
                $query->whereBetween('time', ['05:00:00', '11:59:59']);
            } elseif ($time === 'afternoon') {
                $query->whereBetween('time', ['12:00:00', '17:59:59']);
            } elseif ($time === 'evening') {
                $query->whereBetween('time', ['18:00:00', '23:59:59']);
            }
        }
        // Sorting
        if ($request->input('sort_by') === 'price_asc') {
            $query->orderBy('go_to_price_per_person', 'asc');
        } elseif ($request->input('sort_by') === 'price_desc') {
            $query->orderBy('go_to_price_per_person', 'desc');
        } elseif ($request->input('sort_by') === 'earliest') {
            $query->orderBy('date', 'asc')->orderBy('time', 'asc');
        }

        $rides = $query->get();
        $rideEntries = [];
        foreach ($rides as $ride) {
            // Outgoing trip - check availability based on ride type
            $isGoAvailable = false;
            if ($ride->go_completion_status === 'pending') {
                if ($ride->is_exclusive) {
                    // For exclusive rides, check if no bookings exist
                    $hasGoBookings = \App\Models\RidePurchase::where('ride_id', $ride->id)
                        ->where('trip_type', 'go')
                        ->exists();
                    $isGoAvailable = !$hasGoBookings;
                } else {
                    // For shared rides, check if seats are available
                    $isGoAvailable = $ride->available_seats > 0;
                }
            }
            
            if ($isGoAvailable) {
                $hasBookedGo = false;
                if ($userId) {
                    $hasBookedGo = \App\Models\RidePurchase::where('ride_id', $ride->id)
                        ->where('user_id', $userId)
                        ->where('trip_type', 'go')
                        ->exists();
                }
            $rideEntries[] = [
                'type' => 'Go',
                'ride' => $ride,
                'station_location' => $ride->station_location,
                'destination' => $ride->destination,
                'date' => $ride->date,
                'time' => $ride->time,
                    'available_seats' => $ride->is_exclusive ? 1 : $ride->available_seats,
                'is_exclusive' => $ride->is_exclusive,
                    'price_per_person' => $ride->is_exclusive ? $ride->go_to_exclusive_price : $ride->go_to_price_per_person,
                'user' => $ride->user,
                    'has_booked' => $hasBookedGo,
            ];
            }
            
            // Return trip (if exists) - check availability based on ride type
            $isReturnAvailable = false;
            if ($ride->is_two_way && $ride->return_date && $ride->return_time && $ride->return_completion_status === 'pending') {
                if ($ride->return_is_exclusive) {
                    // For exclusive return rides, check if no bookings exist
                    $hasReturnBookings = \App\Models\RidePurchase::where('ride_id', $ride->id)
                        ->where('trip_type', 'return')
                        ->exists();
                    $isReturnAvailable = !$hasReturnBookings;
                } else {
                    // For shared return rides, check if seats are available
                    $isReturnAvailable = $ride->return_available_seats > 0;
                }
            }
            
            if ($isReturnAvailable) {
                $hasBookedReturn = false;
                if ($userId) {
                    $hasBookedReturn = \App\Models\RidePurchase::where('ride_id', $ride->id)
                        ->where('user_id', $userId)
                        ->where('trip_type', 'return')
                        ->exists();
                }
                $rideEntries[] = [
                    'type' => 'Back',
                    'ride' => $ride,
                    'station_location' => $ride->destination,
                    'destination' => $ride->station_location,
                    'date' => $ride->return_date,
                    'time' => $ride->return_time,
                    'available_seats' => $ride->return_is_exclusive ? 1 : $ride->return_available_seats,
                    'is_exclusive' => $ride->return_is_exclusive,
                    'price_per_person' => $ride->return_is_exclusive ? $ride->return_exclusive_price : $ride->return_price_per_person,
                    'user' => $ride->user,
                    'has_booked' => $hasBookedReturn,
                ];
            }
        }
        // Apply rideType filter to both outgoing and return trips
        if ($request->filled('rideType') && in_array($request->input('rideType'), ['shared', 'exclusive'])) {
            if ($request->input('rideType') === 'exclusive') {
                $rideEntries = array_filter($rideEntries, function($entry) {
                    return $entry['is_exclusive'] === true || $entry['is_exclusive'] === 1;
                });
            } else { // shared
                $rideEntries = array_filter($rideEntries, function($entry) {
                    return ($entry['is_exclusive'] === false || $entry['is_exclusive'] === 0 || is_null($entry['is_exclusive'])) && $entry['available_seats'] > 0;
                });
            }
        }
        // Apply price sorting after filtering
        if (($request->input('sort_by') === 'price_asc' || $request->input('sort_by') === 'price_desc')) {
            usort($rideEntries, function($a, $b) use ($request) {
                $aPrice = $a['price_per_person'] ?? 0;
                $bPrice = $b['price_per_person'] ?? 0;
                if ($aPrice == $bPrice) return 0;
                if ($request->input('sort_by') === 'price_asc') {
                    return $aPrice <=> $bPrice;
                } else {
                    return $bPrice <=> $aPrice;
                }
            });
        }
        // Apply price range filter after building rideEntries
        if ($request->filled('price_min')) {
            $min = floatval($request->input('price_min'));
            $rideEntries = array_filter($rideEntries, function($entry) use ($min) {
                return isset($entry['price_per_person']) && $entry['price_per_person'] >= $min;
            });
        }
        if ($request->filled('price_max')) {
            $max = floatval($request->input('price_max'));
            $rideEntries = array_filter($rideEntries, function($entry) use ($max) {
                return isset($entry['price_per_person']) && $entry['price_per_person'] <= $max;
            });
        }
        // Apply 'from' and 'to' filters to rideEntries after building
        if ($request->filled('from')) {
            $from = strtolower($request->input('from'));
            $rideEntries = array_filter($rideEntries, function($entry) use ($from) {
                return strpos(strtolower($entry['station_location']), $from) !== false;
            });
        }
        if ($request->filled('to')) {
            $to = strtolower($request->input('to'));
            $rideEntries = array_filter($rideEntries, function($entry) use ($to) {
                return strpos(strtolower($entry['destination']), $to) !== false;
            });
        }
        return view('find-rides', [
            'rideEntries' => $rideEntries,
            'filters' => $request->all(),
        ]);
    }

    public function earnings(Request $request)
    {
        $userData = session('user');
        if (!$userData || !isset($userData['id'])) {
            return redirect()->route('login')->with('error', 'Please login to access earnings.');
        }
        $user = User::find($userData['id']);
        if (!$user) {
            session()->forget(['user', 'user_role']);
            return redirect()->route('login')->with('error', 'User not found. Please login again.');
        }
        
        // Get all bookings for rides owned by this driver
        $bookings = \App\Models\RidePurchase::with(['ride', 'user'])
            ->whereHas('ride', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics
        $totalEarnings = $bookings->sum('total_price');
        $totalCustomers = $bookings->unique('user_id')->count();
        
        // Count completed rides (both go and return trips)
        $completedRides = \App\Models\Ride::where('user_id', $user->id)
            ->where(function($q) {
                $q->where('go_completion_status', 'completed')
                  ->orWhere('return_completion_status', 'completed');
            })
            ->get();
        
        $totalRidesCompleted = 0;
        foreach ($completedRides as $ride) {
            if ($ride->go_completion_status === 'completed') {
                $totalRidesCompleted++;
            }
            if ($ride->return_completion_status === 'completed') {
                $totalRidesCompleted++;
            }
        }

        return view('ride-management.earnings', compact('user', 'bookings', 'totalEarnings', 'totalCustomers', 'totalRidesCompleted'));
    }

    public function showRideCustomers($rideId, $tripType = null)
    {
        $userData = session('user');
        if (!$userData || !isset($userData['id'])) {
            return redirect()->route('login')->with('error', 'Please login to access ride customers.');
        }
        $user = User::find($userData['id']);
        if (!$user) {
            session()->forget(['user', 'user_role']);
            return redirect()->route('login')->with('error', 'User not found. Please login again.');
        }

        // Get the ride and verify it belongs to this driver
        $ride = Ride::where('id', $rideId)
            ->where('user_id', $user->id)
            ->first();

        if (!$ride) {
            return redirect()->route('driver.ride.management')->with('error', 'Ride not found or you do not have permission to view it.');
        }

        // Get all bookings for this specific ride
        $query = \App\Models\RidePurchase::with(['user'])
            ->where('ride_id', $rideId);
            
        // Filter by trip type if specified
        if ($tripType) {
            $query->where('trip_type', $tripType);
        }
        
        $bookings = $query->orderBy('created_at', 'desc')->get();

        // Calculate seat information for visualization
        $seatInfo = $this->calculateSeatInfo($ride, $tripType, $bookings);

        return view('ride-management.ride-customers', compact('user', 'ride', 'bookings', 'tripType', 'seatInfo'));
    }

    private function calculateSeatInfo($ride, $tripType, $bookings)
    {
        // Determine total seats and available seats based on trip type
        if ($tripType === 'return' && $ride->is_two_way) {
            $totalSeats = $ride->return_available_seats + $bookings->where('trip_type', 'return')->sum('number_of_seats');
            $availableSeats = $ride->return_available_seats;
        } else {
            $totalSeats = $ride->available_seats + $bookings->where('trip_type', 'go')->sum('number_of_seats');
            $availableSeats = $ride->available_seats;
        }

        // Create seat map with customer information
        $seatMap = [];
        for ($seat = 1; $seat <= $totalSeats; $seat++) {
            $seatMap[$seat] = [
                'seat_number' => $seat,
                'is_booked' => false,
                'customer_name' => null,
                'booking_reference' => null,
                'contact_phone' => null,
                'passenger_name' => null,
                'booking_id' => null
            ];
        }

        // Populate booked seats with customer information
        foreach ($bookings as $booking) {
            if ($booking->selected_seats && is_array($booking->selected_seats)) {
                foreach ($booking->selected_seats as $seatNumber) {
                    if (isset($seatMap[$seatNumber])) {
                        $seatMap[$seatNumber]['is_booked'] = true;
                        $seatMap[$seatNumber]['customer_name'] = $booking->user ? $booking->user->name : 'Unknown';
                        $seatMap[$seatNumber]['booking_reference'] = $booking->booking_reference;
                        $seatMap[$seatNumber]['contact_phone'] = $booking->contact_phone;
                        $seatMap[$seatNumber]['booking_id'] = $booking->id;
                        
                        // Find passenger name for this specific seat
                        if ($booking->passenger_details && is_array($booking->passenger_details)) {
                            foreach ($booking->passenger_details as $passenger) {
                                if (isset($passenger['seat_number']) && $passenger['seat_number'] == $seatNumber) {
                                    $seatMap[$seatNumber]['passenger_name'] = $passenger['name'] ?? 'Unknown';
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }

        return [
            'total_seats' => $totalSeats,
            'available_seats' => $availableSeats,
            'booked_seats' => $totalSeats - $availableSeats,
            'seat_map' => $seatMap
        ];
    }
} 