<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RidePurchase;

class UserBookingController extends Controller
{
    public function index()
    {
        $userData = session('user');
        if (!$userData || !isset($userData['id'])) {
            return redirect()->route('login')->with('error', 'Please login to view your bookings.');
        }

        $user = User::find($userData['id']);
        if (!$user) {
            session()->forget(['user', 'user_role']);
            return redirect()->route('login')->with('error', 'User not found. Please login again.');
        }

        $bookings = RidePurchase::with(['ride.user'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.bookings', compact('user', 'bookings'));
    }

    public function show($bookingId)
    {
        $userData = session('user');
        if (!$userData || !isset($userData['id'])) {
            return redirect()->route('login')->with('error', 'Please login to view booking details.');
        }

        $user = User::find($userData['id']);
        if (!$user) {
            session()->forget(['user', 'user_role']);
            return redirect()->route('login')->with('error', 'User not found. Please login again.');
        }

        $booking = RidePurchase::with(['ride.user'])
            ->where('id', $bookingId)
            ->where('user_id', $user->id)
            ->first();

        if (!$booking) {
            return redirect()->route('user.bookings')->with('error', 'Booking not found.');
        }

        return view('user.booking-details', compact('booking', 'user'));
    }

    public function printReceipt($bookingId)
    {
        $userData = session('user');
        if (!$userData || !isset($userData['id'])) {
            return redirect()->route('login')->with('error', 'Please login to print receipt.');
        }

        $user = User::find($userData['id']);
        if (!$user) {
            session()->forget(['user', 'user_role']);
            return redirect()->route('login')->with('error', 'User not found. Please login again.');
        }

        $booking = RidePurchase::with(['ride.user'])
            ->where('id', $bookingId)
            ->where('user_id', $user->id)
            ->first();

        if (!$booking) {
            return redirect()->route('user.bookings')->with('error', 'Booking not found.');
        }

        return view('user.receipt', compact('booking', 'user'));
    }

    // API Methods
    public function apiIndex(Request $request)
    {
        // Get user from token authentication
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'Please login to view your bookings.',
                'status' => 'error'
            ], 401);
        }

        $bookings = RidePurchase::with(['ride.user'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'message' => 'User bookings retrieved successfully',
            'status' => 'success',
            'data' => [
                'user' => $user,
                'bookings' => $bookings
            ]
        ]);
    }

    public function apiShow(Request $request, $bookingId)
    {
        // Get user from token authentication
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'Please login to view booking details.',
                'status' => 'error'
            ], 401);
        }

        $booking = RidePurchase::with(['ride.user'])
            ->where('id', $bookingId)
            ->where('user_id', $user->id)
            ->first();

        if (!$booking) {
            return response()->json([
                'message' => 'Booking not found.',
                'status' => 'error'
            ], 404);
        }

        return response()->json([
            'message' => 'Booking details retrieved successfully',
            'status' => 'success',
            'data' => [
                'booking' => $booking,
                'user' => $user
            ]
        ]);
    }

    public function apiPrintReceipt(Request $request, $bookingId)
    {
        // Get user from token authentication
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'Please login to print receipt.',
                'status' => 'error'
            ], 401);
        }

        $booking = RidePurchase::with(['ride.user'])
            ->where('id', $bookingId)
            ->where('user_id', $user->id)
            ->first();

        if (!$booking) {
            return response()->json([
                'message' => 'Booking not found.',
                'status' => 'error'
            ], 404);
        }

        return response()->json([
            'message' => 'Receipt data retrieved successfully',
            'status' => 'success',
            'data' => [
                'booking' => $booking,
                'user' => $user,
                'receipt_data' => [
                    'booking_id' => $booking->id,
                    'ride_details' => $booking->ride,
                    'passenger_details' => $booking->passenger_details,
                    'payment_amount' => $booking->total_amount,
                    'booking_date' => $booking->created_at,
                    'trip_type' => $booking->trip_type
                ]
            ]
        ]);
    }
}
