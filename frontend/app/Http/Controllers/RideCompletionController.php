<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ride;
use App\Models\RidePurchase;
use App\Models\RideReview;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RideCompletionController extends Controller
{
    /**
     * Mark a ride as ongoing (driver action)
     */
    public function markAsOngoing(Request $request, $rideId, $tripType = 'go')
    {
        $userData = session('user');
        if (!$userData || !isset($userData['id'])) {
            return redirect()->route('login')->with('error', 'Please login to update ride status.');
        }

        $user = User::find($userData['id']);
        if (!$user) {
            session()->forget(['user', 'user_role']);
            return redirect()->route('login')->with('error', 'User not found. Please login again.');
        }

        $ride = Ride::where('id', $rideId)
            ->where('user_id', $user->id)
            ->first();

        if (!$ride) {
            return redirect()->route('driver.ride.management')->with('error', 'Ride not found or access denied.');
        }

        try {
            DB::beginTransaction();

            if ($tripType === 'return') {
                $ride->return_completion_status = 'ongoing';
            } else {
                $ride->go_completion_status = 'ongoing';
            }

            $ride->save();

            DB::commit();

            return redirect()->route('driver.ride.customers', ['ride' => $rideId, 'tripType' => $tripType])
                ->with('success', 'Ride marked as ongoing successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to mark ride as ongoing. Please try again.');
        }
    }

    /**
     * Mark a ride as completed (driver action)
     */
    public function markAsCompleted(Request $request, $rideId, $tripType = 'go')
    {
        $userData = session('user');
        if (!$userData || !isset($userData['id'])) {
            return redirect()->route('login')->with('error', 'Please login to complete rides.');
        }

        $user = User::find($userData['id']);
        if (!$user) {
            session()->forget(['user', 'user_role']);
            return redirect()->route('login')->with('error', 'User not found. Please login again.');
        }

        $ride = Ride::where('id', $rideId)
            ->where('user_id', $user->id)
            ->first();

        if (!$ride) {
            return redirect()->route('driver.ride.management')->with('error', 'Ride not found or access denied.');
        }

        try {
            DB::beginTransaction();

            if ($tripType === 'return') {
                $ride->return_completion_status = 'completed';
                $ride->return_completed_at = now();
            } else {
                $ride->go_completion_status = 'completed';
                $ride->go_completed_at = now();
            }

            $ride->save();

            DB::commit();

            return redirect()->route('driver.ride.customers', ['ride' => $rideId, 'tripType' => $tripType])
                ->with('success', 'Ride marked as completed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to mark ride as completed. Please try again.');
        }
    }

    /**
     * Show review form for a completed ride
     */
    public function showReviewForm($bookingId, $tripType = 'go')
    {
        $userData = session('user');
        if (!$userData || !isset($userData['id'])) {
            return redirect()->route('login')->with('error', 'Please login to review rides.');
        }

        $user = User::find($userData['id']);
        if (!$user) {
            session()->forget(['user', 'user_role']);
            return redirect()->route('login')->with('error', 'User not found. Please login again.');
        }

        $booking = RidePurchase::with(['ride'])
            ->where('id', $bookingId)
            ->where('user_id', $user->id)
            ->first();

        if (!$booking) {
            return redirect()->route('user.bookings')->with('error', 'Booking not found.');
        }

        // Check if ride is completed (only allow reviews for completed rides)
        $rideStatus = $tripType === 'return' ? $booking->ride->return_completion_status : $booking->ride->go_completion_status;
        
        if ($rideStatus === 'pending') {
            return redirect()->route('user.bookings')->with('error', 'This ride has not started yet. You can review once the ride is completed.');
        }
        
        if ($rideStatus === 'ongoing') {
            return redirect()->route('user.bookings')->with('error', 'This ride is currently ongoing. You can review once the driver marks it as completed.');
        }

        // Check if review already exists
        $existingReview = RideReview::where('ride_purchase_id', $bookingId)
            ->where('trip_type', $tripType)
            ->first();

        if ($existingReview) {
            return redirect()->route('user.bookings')->with('error', 'You have already reviewed this ride.');
        }

        return view('user.review-form', compact('booking', 'tripType'));
    }

    /**
     * Submit a review
     */
    public function submitReview(Request $request, $bookingId, $tripType = 'go')
    {
        $userData = session('user');
        if (!$userData || !isset($userData['id'])) {
            return redirect()->route('login')->with('error', 'Please login to submit reviews.');
        }

        $user = User::find($userData['id']);
        if (!$user) {
            session()->forget(['user', 'user_role']);
            return redirect()->route('login')->with('error', 'User not found. Please login again.');
        }

        $booking = RidePurchase::with(['ride'])
            ->where('id', $bookingId)
            ->where('user_id', $user->id)
            ->first();

        if (!$booking) {
            return redirect()->route('user.bookings')->with('error', 'Booking not found.');
        }

        // Validate review data
        $request->validate([
            'overall_rating' => 'required|integer|min:1|max:5',
            'driver_rating' => 'required|integer|min:1|max:5',
            'vehicle_rating' => 'required|integer|min:1|max:5',
            'punctuality_rating' => 'required|integer|min:1|max:5',
            'safety_rating' => 'required|integer|min:1|max:5',
            'comfort_rating' => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string|max:1000',
        ]);

        // Check if review already exists
        $existingReview = RideReview::where('ride_purchase_id', $bookingId)
            ->where('trip_type', $tripType)
            ->first();

        if ($existingReview) {
            return redirect()->route('user.bookings')->with('error', 'You have already reviewed this ride.');
        }

        try {
            DB::beginTransaction();

            $review = RideReview::create([
                'ride_id' => $booking->ride_id,
                'user_id' => $user->id,
                'ride_purchase_id' => $bookingId,
                'overall_rating' => $request->overall_rating,
                'driver_rating' => $request->driver_rating,
                'vehicle_rating' => $request->vehicle_rating,
                'punctuality_rating' => $request->punctuality_rating,
                'safety_rating' => $request->safety_rating,
                'comfort_rating' => $request->comfort_rating,
                'review_text' => $request->review_text,
                'trip_type' => $tripType,
                'status' => 'approved',
            ]);

            DB::commit();

            return redirect()->route('user.bookings')->with('success', 'Thank you for your review!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to submit review. Please try again.');
        }
    }

    /**
     * View reviews for a ride (driver view)
     */
    public function viewRideReviews($rideId)
    {
        $userData = session('user');
        if (!$userData || !isset($userData['id'])) {
            return redirect()->route('login')->with('error', 'Please login to view reviews.');
        }

        $user = User::find($userData['id']);
        if (!$user) {
            session()->forget(['user', 'user_role']);
            return redirect()->route('login')->with('error', 'User not found. Please login again.');
        }

        $ride = Ride::with(['reviews.user', 'reviews.ridePurchase'])
            ->where('id', $rideId)
            ->where('user_id', $user->id)
            ->first();

        if (!$ride) {
            return redirect()->route('driver.ride.management')->with('error', 'Ride not found or access denied.');
        }

        return view('ride-management.reviews', compact('ride'));
    }
}
