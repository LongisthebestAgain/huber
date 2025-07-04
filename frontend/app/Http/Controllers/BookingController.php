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

        // For exclusive rides, go directly to payment
        if ($isExclusive) {
            return view('booking.payment', compact('ride', 'user', 'tripType', 'pricePerSeat', 'availableSeats', 'date', 'time', 'isExclusive'));
        }

        // For shared rides, redirect to seat selection
        return redirect()->route('booking.seat-selection', ['rideId' => $rideId, 'tripType' => $tripType]);
    }

    public function showSeatSelection($rideId, $tripType = 'go')
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

        // Determine available seats based on trip type
        if ($tripType === 'return' && $ride->is_two_way) {
            $availableSeats = $ride->return_available_seats;
            $date = $ride->return_date;
            $time = $ride->return_time;
            $pricePerSeat = $ride->return_price_per_person;
        } else {
            $availableSeats = $ride->available_seats;
            $date = $ride->date;
            $time = $ride->time;
            $pricePerSeat = $ride->go_to_price_per_person;
        }

        // Check if any seats are available
        if ($availableSeats <= 0) {
            return redirect()->route('find.rides')->with('error', 'Sorry, this ride is fully booked and no longer available.');
        }

        // Get already booked seats for this ride and trip type
        $bookedSeats = RidePurchase::where('ride_id', $rideId)
            ->where('trip_type', $tripType)
            ->where('seats_confirmed', true)
            ->pluck('selected_seats')
            ->flatten()
            ->filter()
            ->toArray();

        return view('booking.seat-selection', compact('ride', 'user', 'tripType', 'availableSeats', 'date', 'time', 'pricePerSeat', 'bookedSeats'));
    }

    public function processSeatSelection(Request $request, $rideId, $tripType = 'go')
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
            'number_of_seats' => 'required|integer|min:1',
            'selected_seats' => 'required|array|min:1',
            'selected_seats.*' => 'required|integer|min:1',
            'contact_phone' => 'required|string',
            'passenger_names' => 'required|array|min:1',
            'passenger_names.*' => 'required|string|max:255',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        $numberOfSeats = $request->input('number_of_seats');
        $selectedSeats = $request->input('selected_seats');
        $contactPhone = $request->input('contact_phone');
        $passengerNames = $request->input('passenger_names');
        $specialRequests = $request->input('special_requests');

        // Ensure selectedSeats is an array
        if (!is_array($selectedSeats)) {
            $selectedSeats = [];
        }

        // Determine available seats based on trip type
        if ($tripType === 'return' && $ride->is_two_way) {
            $availableSeats = $ride->return_available_seats;
            $date = $ride->return_date;
            $time = $ride->return_time;
            $pricePerSeat = $ride->return_price_per_person;
        } else {
            $availableSeats = $ride->available_seats;
            $date = $ride->date;
            $time = $ride->time;
            $pricePerSeat = $ride->go_to_price_per_person;
        }

        // Debug information
        Log::info('Seat selection debug', [
            'numberOfSeats' => $numberOfSeats,
            'numberOfSeatsType' => gettype($numberOfSeats),
            'selectedSeats' => $selectedSeats,
            'selectedSeatsCount' => is_array($selectedSeats) ? count($selectedSeats) : 'not array',
            'passengerNames' => $passengerNames,
            'passengerNamesCount' => is_array($passengerNames) ? count($passengerNames) : 'not array',
            'availableSeats' => $availableSeats,
            'tripType' => $tripType,
            'rideId' => $rideId,
            'allRequestData' => $request->all()
        ]);

        // Check if any seats are available
        if ($availableSeats <= 0) {
            return redirect()->route('find.rides')->with('error', 'Sorry, this ride is fully booked and no longer available.');
        }

        // Check if enough seats are available
        if ((int)$numberOfSeats > $availableSeats) {
            return back()->withErrors(['number_of_seats' => 'Not enough seats available.']);
        }

        // Check if selected seats count matches number of seats
        if (count($selectedSeats) !== (int)$numberOfSeats) {
            return back()->withErrors(['selected_seats' => 'Number of selected seats must match the number of seats you want to book.']);
        }

        // Check if selected seats are within valid range (1 to available seats)
        foreach ($selectedSeats as $seatNumber) {
            if ($seatNumber < 1 || $seatNumber > $availableSeats) {
                return back()->withErrors(['selected_seats' => "Seat number {$seatNumber} is not valid. Available seats are 1 to {$availableSeats}."]);
            }
        }

        // Check if selected seats are already booked
        $bookedSeats = RidePurchase::where('ride_id', $rideId)
            ->where('trip_type', $tripType)
            ->where('seats_confirmed', true)
            ->pluck('selected_seats')
            ->flatten()
            ->filter()
            ->toArray();

        $conflictingSeats = array_intersect($selectedSeats, $bookedSeats);
        if (!empty($conflictingSeats)) {
            return back()->withErrors(['selected_seats' => 'Some selected seats are already booked: ' . implode(', ', $conflictingSeats)]);
        }

        // Check if passenger names count matches
        $passengerNames = array_filter($passengerNames, function($name) {
            return !empty(trim($name));
        });

        if (count($passengerNames) !== (int)$numberOfSeats) {
            return back()->withErrors(['passenger_names' => 'Number of passenger names must match the number of seats.']);
        }

        // Calculate total price
        $totalPrice = $pricePerSeat * (int)$numberOfSeats;

        // Create passenger details array
        $passengerDetails = [];
        for ($i = 0; $i < (int)$numberOfSeats; $i++) {
            $passengerDetails[] = [
                'name' => $passengerNames[$i],
                'seat_number' => $selectedSeats[$i],
            ];
        }

        try {
            DB::beginTransaction();

            // Generate booking reference
            $bookingReference = 'BK' . date('Ymd') . strtoupper(substr(md5(uniqid()), 0, 8));

            // Log booking details before creation
            Log::info('Creating booking', [
                'bookingReference' => $bookingReference,
                'rideId' => $rideId,
                'userId' => $user->id,
                'numberOfSeats' => $numberOfSeats,
                'totalPrice' => $totalPrice,
                'tripType' => $tripType,
                'selectedSeats' => $selectedSeats,
                'passengerDetails' => $passengerDetails
            ]);

            // Create the booking
            $booking = RidePurchase::create([
                'ride_id' => $rideId,
                'user_id' => $user->id,
                'number_of_seats' => (int)$numberOfSeats,
                'total_price' => $totalPrice,
                'payment_status' => 'completed', // For now, assume payment is completed
                'payment_method' => 'visa', // Default for now
                'card_last_four' => '1234', // Placeholder
                'card_type' => 'visa', // Default for now
                'special_requests' => $specialRequests,
                'trip_type' => $tripType,
                'passenger_details' => $passengerDetails,
                'selected_seats' => $selectedSeats,
                'seats_confirmed' => true,
                'contact_phone' => $contactPhone,
                'booking_reference' => $bookingReference,
                'booking_date' => $date,
                'booking_time' => $time,
            ]);

            // Update available seats
            if ($tripType === 'return' && $ride->is_two_way) {
                $ride->return_available_seats = $availableSeats - (int)$numberOfSeats;
            } else {
                $ride->available_seats = $availableSeats - (int)$numberOfSeats;
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
        for ($i = 0; $i < (int)$numberOfSeats; $i++) {
            $passengerDetails[] = [
                'name' => $passengerNames[$i],
                'seat_number' => $i + 1,
            ];
        }

        try {
            DB::beginTransaction();

            // Generate booking reference
            $bookingReference = 'BK' . date('Ymd') . strtoupper(substr(md5(uniqid()), 0, 8));

            // Log booking details before creation
            Log::info('Creating booking', [
                'bookingReference' => $bookingReference,
                'rideId' => $rideId,
                'userId' => $user->id,
                'numberOfSeats' => $numberOfSeats,
                'totalPrice' => $totalPrice,
                'tripType' => $tripType,
                'selectedSeats' => $passengerDetails,
                'passengerDetails' => $passengerDetails
            ]);

            // Create the booking
            $booking = RidePurchase::create([
                'ride_id' => $rideId,
                'user_id' => $user->id,
                'number_of_seats' => (int)$numberOfSeats,
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
                $ride->return_available_seats = $availableSeats - (int)$numberOfSeats;
            } else {
                $ride->available_seats = $availableSeats - (int)$numberOfSeats;
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
