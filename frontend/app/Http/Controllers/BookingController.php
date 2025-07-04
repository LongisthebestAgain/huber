<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ride;
use App\Models\RidePurchase;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function showPaymentPage(Request $request, $rideId, $tripType = 'go')
    {
        $userData = session('user');
        if (!$userData || !isset($userData['id'])) {
            return redirect()->route('login')->with('error', 'Please login to book a ride.');
        }

        $user = User::find($userData['id']);
        if (!$user) {
            session()->forget(['user', 'user_role']);
            return redirect()->route('login')->with('error', 'User not found. Please login again.');
        }

        $ride = Ride::with('user')->find($rideId);
        if (!$ride) {
            return redirect()->route('find.rides')->with('error', 'Ride not found.');
        }

        // Determine price and available seats based on trip type
        if ($tripType === 'return' && $ride->is_two_way) {
            if ($ride->return_is_exclusive) {
                $pricePerSeat = $ride->return_exclusive_price; // Total price for exclusive
                $isExclusive = true;
            } else {
                $pricePerSeat = $ride->return_price_per_person;
                $isExclusive = false;
            }
            $availableSeats = $ride->return_available_seats;
            $date = $ride->return_date;
            $time = $ride->return_time;
        } else {
            if ($ride->is_exclusive) {
                $pricePerSeat = $ride->go_to_exclusive_price; // Total price for exclusive
                $isExclusive = true;
            } else {
                $pricePerSeat = $ride->go_to_price_per_person;
                $isExclusive = false;
            }
            $availableSeats = $ride->available_seats;
            $date = $ride->date;
            $time = $ride->time;
        }

        // Check if any seats are available
        if ($availableSeats <= 0) {
            return redirect()->route('find.rides')->with('error', 'Sorry, this ride is fully booked and no longer available.');
        }

        return view('booking.payment', compact('ride', 'user', 'tripType', 'pricePerSeat', 'availableSeats', 'date', 'time', 'isExclusive'));
    }

    public function processBooking(Request $request, $rideId, $tripType = 'go')
    {
        $userData = session('user');
        if (!$userData || !isset($userData['id'])) {
            return redirect()->route('login')->with('error', 'Please login to book a ride.');
        }

        $user = User::find($userData['id']);
        if (!$user) {
            session()->forget(['user', 'user_role']);
            return redirect()->route('login')->with('error', 'User not found. Please login again.');
        }

        $ride = Ride::find($rideId);
        if (!$ride) {
            return redirect()->route('find.rides')->with('error', 'Ride not found.');
        }

        // Validate request
        $request->validate([
            'number_of_seats' => 'nullable|integer|min:1', // Make it nullable for exclusive rides
            'contact_phone' => 'required|string',
            'passenger_names' => 'required|array|min:1',
            'passenger_names.*' => 'required|string|max:255',
            'special_requests' => 'nullable|string|max:1000',
        ], [
            'passenger_names.required' => 'Please provide passenger names.',
            'passenger_names.array' => 'Passenger names must be provided.',
            'passenger_names.min' => 'At least one passenger name is required.',
            'passenger_names.*.required' => 'All passenger names are required.',
            'passenger_names.*.string' => 'Passenger names must be text.',
            'passenger_names.*.max' => 'Passenger names cannot exceed 255 characters.',
        ]);

        $numberOfSeats = $request->input('number_of_seats');
        $numberOfSeatsHidden = $request->input('number_of_seats_hidden');
        $contactPhone = $request->input('contact_phone');
        $passengerNames = $request->input('passenger_names');
        $specialRequests = $request->input('special_requests');

        // Debug information
        Log::info('Booking validation debug', [
            'numberOfSeats' => $numberOfSeats,
            'numberOfSeatsHidden' => $numberOfSeatsHidden,
            'passengerNames' => $passengerNames,
            'passengerNamesCount' => is_array($passengerNames) ? count($passengerNames) : 'not array',
            'passengerNamesType' => gettype($passengerNames)
        ]);

        // Determine price and available seats based on trip type
        if ($tripType === 'return' && $ride->is_two_way) {
            if ($ride->return_is_exclusive) {
                $pricePerSeat = $ride->return_exclusive_price; // Total price for exclusive
                $isExclusive = true;
            } else {
                $pricePerSeat = $ride->return_price_per_person;
                $isExclusive = false;
            }
            $availableSeats = $ride->return_available_seats;
            $date = $ride->return_date;
            $time = $ride->return_time;
        } else {
            if ($ride->is_exclusive) {
                $pricePerSeat = $ride->go_to_exclusive_price; // Total price for exclusive
                $isExclusive = true;
            } else {
                $pricePerSeat = $ride->go_to_price_per_person;
                $isExclusive = false;
            }
            $availableSeats = $ride->available_seats;
            $date = $ride->date;
            $time = $ride->time;
        }

        // Check if any seats are available
        if ($availableSeats <= 0) {
            return redirect()->route('find.rides')->with('error', 'Sorry, this ride is fully booked and no longer available.');
        }

        // For exclusive rides, use hidden input value or available seats
        if ($isExclusive) {
            $numberOfSeats = $numberOfSeatsHidden ?: $availableSeats;
        } else {
            // For shared rides, validate that number of seats is provided
            if (!$numberOfSeats) {
                return back()->withErrors(['number_of_seats' => 'Number of seats is required for shared rides.']);
            }
        }

        // Check if enough seats are available
        if ($numberOfSeats > $availableSeats) {
            return back()->withErrors(['number_of_seats' => 'Not enough seats available.']);
        }

        // Ensure passenger_names is an array
        if (!is_array($passengerNames)) {
            return back()->withErrors(['passenger_names' => 'Passenger names must be provided as an array.']);
        }

        // Filter out empty passenger names and check count
        $passengerNames = array_filter($passengerNames, function($name) {
            return !empty(trim($name));
        });
        
        // For exclusive rides, we only need 1 passenger name
        $requiredPassengerCount = $isExclusive ? 1 : $numberOfSeats;
        
        // Check if number of passenger names matches required count
        if (count($passengerNames) !== $requiredPassengerCount) {
            return back()->withErrors([
                'passenger_names' => "Number of passenger names (" . count($passengerNames) . ") must match required count ({$requiredPassengerCount}). Please fill in all passenger names."
            ]);
        }

        // Calculate total price based on ride type
        if ($isExclusive) {
            $totalPrice = $pricePerSeat; // Fixed total price for exclusive rides
        } else {
            $totalPrice = $pricePerSeat * $numberOfSeats; // Per-person pricing for shared rides
        }

        // Create passenger details array
        $passengerDetails = [];
        for ($i = 0; $i < $numberOfSeats; $i++) {
            if ($isExclusive) {
                // For exclusive rides, use the first passenger name for all seats
                $passengerDetails[] = [
                    'name' => $passengerNames[0],
                    'seat_number' => $i + 1,
                ];
            } else {
                // For shared rides, use individual passenger names
                $passengerDetails[] = [
                    'name' => $passengerNames[$i],
                    'seat_number' => $i + 1,
                ];
            }
        }

        try {
            DB::beginTransaction();

            // Generate booking reference
            $bookingReference = 'BK' . date('Ymd') . strtoupper(substr(md5(uniqid()), 0, 8));

            // Create the booking
            $booking = RidePurchase::create([
                'ride_id' => $rideId,
                'user_id' => $user->id,
                'number_of_seats' => $numberOfSeats,
                'total_price' => $totalPrice,
                'payment_status' => 'completed', // For now, assume payment is completed
                'payment_method' => $request->input('payment_method', 'visa'),
                'card_last_four' => '1234', // Placeholder - in real app, get from payment processor
                'card_type' => $request->input('payment_method', 'visa'),
                'special_requests' => $specialRequests,
                'trip_type' => $tripType,
                'passenger_details' => $passengerDetails,
                'contact_phone' => $contactPhone,
                'booking_reference' => $bookingReference,
                'booking_date' => $date,
                'booking_time' => $time,
            ]);

            // Update available seats
            if ($tripType === 'return' && $ride->is_two_way) {
                $ride->return_available_seats = $availableSeats - $numberOfSeats;
            } else {
                $ride->available_seats = $availableSeats - $numberOfSeats;
            }
            
            $ride->save();

            DB::commit();

            // Store booking ID in session for thank you page
            session(['last_booking_id' => $booking->id]);

            return redirect()->route('booking.thank-you', $booking->id)
                ->with('success', 'Booking completed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking failed: ' . $e->getMessage());
            
            return back()->withErrors(['general' => 'An error occurred while processing your booking. Please try again.']);
        }
    }

    public function showThankYou($bookingId)
    {
        $userData = session('user');
        if (!$userData || !isset($userData['id'])) {
            return redirect()->route('login')->with('error', 'Please login to view booking confirmation.');
        }

        $user = User::find($userData['id']);
        if (!$user) {
            session()->forget(['user', 'user_role']);
            return redirect()->route('login')->with('error', 'User not found. Please login again.');
        }

        $booking = RidePurchase::with(['ride.user'])->where('id', $bookingId)
            ->where('user_id', $user->id)
            ->first();

        if (!$booking) {
            return redirect()->route('find.rides')->with('error', 'Booking not found.');
        }

        return view('booking.thank-you', compact('booking', 'user'));
    }

    public function showConfirmation($bookingId)
    {
        $userData = session('user');
        if (!$userData || !isset($userData['id'])) {
            return redirect()->route('login')->with('error', 'Please login to view booking confirmation.');
        }

        $user = User::find($userData['id']);
        if (!$user) {
            session()->forget(['user', 'user_role']);
            return redirect()->route('login')->with('error', 'User not found. Please login again.');
        }

        $booking = RidePurchase::with(['ride.user'])->where('id', $bookingId)
            ->where('user_id', $user->id)
            ->first();

        if (!$booking) {
            return redirect()->route('find.rides')->with('error', 'Booking not found.');
        }

        return view('booking.confirmation', compact('booking', 'user'));
    }
}
