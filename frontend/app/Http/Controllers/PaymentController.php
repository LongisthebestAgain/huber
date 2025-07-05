<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ride;
use App\Models\RidePurchase;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function showPaymentPage(Request $request, $rideId, $tripType = 'go')
    {
        $userData = session('user');
        if (!$userData || !isset($userData['id'])) {
            return redirect()->route('login')->with('error', 'Please login to complete payment.');
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

        // Get booking details from session (set during seat selection)
        $bookingData = session('pending_booking_data');
        if (!$bookingData) {
            return redirect()->route('find.rides')->with('error', 'No booking data found. Please select seats first.');
        }

        // Determine price and details based on trip type
        if ($tripType === 'return' && $ride->is_two_way) {
            $pricePerSeat = $ride->return_price_per_person;
            $date = $ride->return_date;
            $time = $ride->return_time;
            $availableSeats = $ride->return_available_seats;
        } else {
            $pricePerSeat = $ride->go_to_price_per_person;
            $date = $ride->date;
            $time = $ride->time;
            $availableSeats = $ride->available_seats;
        }

        return view('booking.payment', compact(
            'ride', 
            'user', 
            'tripType', 
            'pricePerSeat', 
            'date', 
            'time', 
            'bookingData'
        ));
    }

    public function processPayment(Request $request, $rideId, $tripType = 'go')
    {
        $userData = session('user');
        if (!$userData || !isset($userData['id'])) {
            return redirect()->route('login')->with('error', 'Please login to complete payment.');
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

        // Get booking data from session
        $bookingData = session('pending_booking_data');
        if (!$bookingData) {
            return redirect()->route('find.rides')->with('error', 'No booking data found. Please select seats first.');
        }

        // Validate payment method
        $request->validate([
            'payment_method' => 'required|in:visa,mastercard,qr',
        ]);

        $paymentMethod = $request->input('payment_method');

        // Validate credit card fields if credit card payment
        if (in_array($paymentMethod, ['visa', 'mastercard'])) {
            $request->validate([
                'card_number' => 'required|string',
                'card_expiry' => 'required|string',
                'card_cvv' => 'required|string',
                'card_holder_name' => 'required|string|max:255',
            ]);
        }

        try {
            DB::beginTransaction();

            // Determine price and details based on trip type
            if ($tripType === 'return' && $ride->is_two_way) {
                $pricePerSeat = $ride->return_price_per_person;
                $date = $ride->return_date;
                $time = $ride->return_time;
                $availableSeats = $ride->return_available_seats;
            } else {
                $pricePerSeat = $ride->go_to_price_per_person;
                $date = $ride->date;
                $time = $ride->time;
                $availableSeats = $ride->available_seats;
            }

            // Calculate total price
            $totalPrice = $pricePerSeat * $bookingData['number_of_seats'];

            // Prepare payment data
            $paymentData = [
                'payment_method' => $paymentMethod,
                'payment_status' => 'completed',
            ];

            // Hash credit card data if credit card payment
            if (in_array($paymentMethod, ['visa', 'mastercard'])) {
                $paymentData['card_number_hash'] = Hash::make($request->input('card_number'));
                $paymentData['card_expiry_hash'] = Hash::make($request->input('card_expiry'));
                $paymentData['card_cvv_hash'] = Hash::make($request->input('card_cvv'));
                $paymentData['card_holder_name'] = $request->input('card_holder_name');
            }

            // Generate booking reference
            $bookingReference = 'BK' . date('Ymd') . strtoupper(substr(md5(uniqid()), 0, 8));

            // Create the booking
            $booking = RidePurchase::create(array_merge([
                'ride_id' => $rideId,
                'user_id' => $user->id,
                'number_of_seats' => $bookingData['number_of_seats'],
                'total_price' => $totalPrice,
                'special_requests' => $bookingData['special_requests'],
                'trip_type' => $tripType,
                'passenger_details' => $bookingData['passenger_details'],
                'selected_seats' => $bookingData['selected_seats'],
                'seats_confirmed' => true,
                'contact_phone' => $bookingData['contact_phone'],
                'booking_reference' => $bookingReference,
                'booking_date' => $date,
                'booking_time' => $time,
            ], $paymentData));

            // Update available seats
            if ($tripType === 'return' && $ride->is_two_way) {
                $ride->return_available_seats = $availableSeats - $bookingData['number_of_seats'];
            } else {
                $ride->available_seats = $availableSeats - $bookingData['number_of_seats'];
            }
            
            $ride->save();

            // Send booking receipt email
            try {
                \Mail::to($user->email)->send(new \App\Mail\BookingReceipt($booking, $user));
            } catch (\Exception $e) {
                \Log::error('Failed to send booking receipt email: ' . $e->getMessage());
            }

            DB::commit();

            // Clear pending booking data from session
            session()->forget('pending_booking_data');

            // Store booking ID in session for thank you page
            session(['last_booking_id' => $booking->id]);

            return redirect()->route('booking.thank-you', $booking->id)
                ->with('success', 'Payment completed successfully! Your booking is confirmed.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment processing failed: ' . $e->getMessage());
            
            return back()->withErrors(['general' => 'An error occurred while processing your payment. Please try again.']);
        }
    }

    public function showQRPayment(Request $request, $rideId, $tripType = 'go')
    {
        $userData = session('user');
        if (!$userData || !isset($userData['id'])) {
            return redirect()->route('login')->with('error', 'Please login to complete payment.');
        }

        $user = User::find($userData['id']);
        if (!$user) {
            session()->forget(['user', 'user_role']);
            return redirect()->route('login')->with('error', 'User not found. Please login again.');
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

        // Get booking data from session
        $bookingData = session('pending_booking_data');
        if (!$bookingData) {
            return redirect()->route('find.rides')->with('error', 'No booking data found. Please select seats first.');
        }

        // Determine price and details based on trip type
        if ($tripType === 'return' && $ride->is_two_way) {
            $pricePerSeat = $ride->return_price_per_person;
            $date = $ride->return_date;
            $time = $ride->return_time;
        } else {
            $pricePerSeat = $ride->go_to_price_per_person;
            $date = $ride->date;
            $time = $ride->time;
        }

        $totalPrice = $pricePerSeat * $bookingData['number_of_seats'];

        return view('booking.qr-payment', compact(
            'ride', 
            'user', 
            'tripType', 
            'totalPrice', 
            'date', 
            'time', 
            'bookingData'
        ));
    }
}
