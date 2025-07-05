<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\DriverDocument;

class DriverProfileController extends Controller
{
    public function show()
    {
        // Get user from session
        $userData = session('user');
        if (!$userData) {
            return redirect()->route('login')->with('error', 'Please login to access your driver profile.');
        }

        $user = User::find($userData['id']);
        if (!$user) {
            session()->forget(['user', 'user_role']);
            return redirect()->route('login')->with('error', 'User not found. Please login again.');
        }

        $driverDocuments = DriverDocument::where('user_id', $user->id)->first();
        
        return view('driver-profile', compact('user', 'driverDocuments'));
    }

    public function updateVehiclePhotos(Request $request)
    {
        // Get user from session
        $userData = session('user');
        if (!$userData) {
            return redirect()->route('login')->with('error', 'Please login to update vehicle photos.');
        }

        $user = User::find($userData['id']);
        if (!$user) {
            session()->forget(['user', 'user_role']);
            return redirect()->route('login')->with('error', 'User not found. Please login again.');
        }

        $request->validate([
            'vehicle_photo_1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'vehicle_photo_2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'vehicle_photo_3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $driverDocuments = DriverDocument::where('user_id', $user->id)->first();

        if (!$driverDocuments) {
            return redirect()->back()->with('error', 'Driver documents not found.');
        }

        $updated = false;

        // Handle vehicle photo 1
        if ($request->hasFile('vehicle_photo_1')) {
            if ($driverDocuments->vehicle_photo_1) {
                Storage::disk('public')->delete($driverDocuments->vehicle_photo_1);
            }
            $path = $request->file('vehicle_photo_1')->store('driver-documents', 'public');
            $driverDocuments->vehicle_photo_1 = $path;
            $updated = true;
        }

        // Handle vehicle photo 2
        if ($request->hasFile('vehicle_photo_2')) {
            if ($driverDocuments->vehicle_photo_2) {
                Storage::disk('public')->delete($driverDocuments->vehicle_photo_2);
            }
            $path = $request->file('vehicle_photo_2')->store('driver-documents', 'public');
            $driverDocuments->vehicle_photo_2 = $path;
            $updated = true;
        }

        // Handle vehicle photo 3
        if ($request->hasFile('vehicle_photo_3')) {
            if ($driverDocuments->vehicle_photo_3) {
                Storage::disk('public')->delete($driverDocuments->vehicle_photo_3);
            }
            $path = $request->file('vehicle_photo_3')->store('driver-documents', 'public');
            $driverDocuments->vehicle_photo_3 = $path;
            $updated = true;
        }

        if ($updated) {
            $driverDocuments->save();
            return redirect()->back()->with('success', 'Vehicle photos updated successfully.');
        }

        return redirect()->back()->with('info', 'No changes were made.');
    }

    public function showPublic($driverId)
    {
        $user = \App\Models\User::where('id', $driverId)->where('role', 'driver')->firstOrFail();
        $driverDocuments = \App\Models\DriverDocument::where('user_id', $user->id)->first();
        
        // Get all reviews for this driver
        $reviews = \App\Models\RideReview::with(['ride', 'user', 'ridePurchase'])
            ->whereHas('ride', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics
        $totalReviews = $reviews->count();
        $averageOverallRating = $totalReviews > 0 ? $reviews->avg('overall_rating') : 0;
        $averageDriverRating = $totalReviews > 0 ? $reviews->avg('driver_rating') : 0;
        $averageVehicleRating = $totalReviews > 0 ? $reviews->avg('vehicle_rating') : 0;

        // Rating distribution
        $ratingDistribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $ratingDistribution[$i] = $reviews->where('overall_rating', $i)->count();
        }

        // Get previous rides (completed)
        $previousRides = \App\Models\Ride::where('user_id', $user->id)
            ->where(function($q) {
                $q->where('go_completion_status', 'completed')
                  ->orWhere('return_completion_status', 'completed');
            })
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        // Get available rides (pending only)
        $availableRides = \App\Models\Ride::where('user_id', $user->id)
            ->where('go_completion_status', 'pending')
            ->get();

        // Filter the rides based on availability
        $filteredAvailableRides = $availableRides->filter(function($ride) {
            if ($ride->is_exclusive) {
                // For exclusive rides, check if no bookings exist
                $hasBookings = \App\Models\RidePurchase::where('ride_id', $ride->id)
                    ->where('trip_type', 'go')
                    ->exists();
                return !$hasBookings;
            } else {
                // For shared rides, check if seats are available
                return $ride->available_seats > 0;
            }
        })->take(10);

        // Get available return rides (pending only)
        $availableReturnRides = \App\Models\Ride::where('user_id', $user->id)
            ->where('is_two_way', true)
            ->where('return_completion_status', 'pending')
            ->get();

        // Filter return rides
        $filteredReturnRides = $availableReturnRides->filter(function($ride) {
            if ($ride->return_is_exclusive) {
                // For exclusive return rides, check if no bookings exist
                $hasBookings = \App\Models\RidePurchase::where('ride_id', $ride->id)
                    ->where('trip_type', 'return')
                    ->exists();
                return !$hasBookings;
            } else {
                // For shared return rides, check if seats are available
                return $ride->return_available_seats > 0;
            }
        })->take(5);

        return view('driver-profile-public', compact(
            'user', 
            'driverDocuments', 
            'reviews', 
            'totalReviews', 
            'averageOverallRating', 
            'averageDriverRating', 
            'averageVehicleRating', 
            'ratingDistribution',
            'previousRides',
            'filteredAvailableRides',
            'filteredReturnRides'
        ));
    }

    // API Methods
    public function apiShow(Request $request)
    {
        // Get user from token authentication
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'Please login to access your driver profile.',
                'status' => 'error'
            ], 401);
        }

        $driverDocuments = DriverDocument::where('user_id', $user->id)->first();
        
        return response()->json([
            'message' => 'Driver profile retrieved successfully',
            'status' => 'success',
            'data' => [
                'user' => $user,
                'driver_documents' => $driverDocuments
            ]
        ]);
    }

    public function apiUpdateVehiclePhotos(Request $request)
    {
        // Get user from token authentication
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'Please login to update vehicle photos.',
                'status' => 'error'
            ], 401);
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'vehicle_photo_1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'vehicle_photo_2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'vehicle_photo_3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $driverDocuments = DriverDocument::where('user_id', $user->id)->first();

        if (!$driverDocuments) {
            return response()->json([
                'message' => 'Driver documents not found.',
                'status' => 'error'
            ], 404);
        }

        $updated = false;

        // Handle vehicle photo 1
        if ($request->hasFile('vehicle_photo_1')) {
            if ($driverDocuments->vehicle_photo_1) {
                Storage::disk('public')->delete($driverDocuments->vehicle_photo_1);
            }
            $path = $request->file('vehicle_photo_1')->store('driver-documents', 'public');
            $driverDocuments->vehicle_photo_1 = $path;
            $updated = true;
        }

        // Handle vehicle photo 2
        if ($request->hasFile('vehicle_photo_2')) {
            if ($driverDocuments->vehicle_photo_2) {
                Storage::disk('public')->delete($driverDocuments->vehicle_photo_2);
            }
            $path = $request->file('vehicle_photo_2')->store('driver-documents', 'public');
            $driverDocuments->vehicle_photo_2 = $path;
            $updated = true;
        }

        // Handle vehicle photo 3
        if ($request->hasFile('vehicle_photo_3')) {
            if ($driverDocuments->vehicle_photo_3) {
                Storage::disk('public')->delete($driverDocuments->vehicle_photo_3);
            }
            $path = $request->file('vehicle_photo_3')->store('driver-documents', 'public');
            $driverDocuments->vehicle_photo_3 = $path;
            $updated = true;
        }

        if ($updated) {
            $driverDocuments->save();
            return response()->json([
                'message' => 'Vehicle photos updated successfully.',
                'status' => 'success',
                'data' => [
                    'driver_documents' => $driverDocuments
                ]
            ]);
        }

        return response()->json([
            'message' => 'No changes were made.',
            'status' => 'info'
        ]);
    }

    public function apiShowPublic(Request $request, $driverId)
    {
        $user = \App\Models\User::where('id', $driverId)->where('role', 'driver')->first();
        if (!$user) {
            return response()->json([
                'message' => 'Driver not found.',
                'status' => 'error'
            ], 404);
        }

        $driverDocuments = \App\Models\DriverDocument::where('user_id', $user->id)->first();
        
        // Get all reviews for this driver
        $reviews = \App\Models\RideReview::with(['ride', 'user', 'ridePurchase'])
            ->whereHas('ride', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics
        $totalReviews = $reviews->count();
        $averageOverallRating = $totalReviews > 0 ? $reviews->avg('overall_rating') : 0;
        $averageDriverRating = $totalReviews > 0 ? $reviews->avg('driver_rating') : 0;
        $averageVehicleRating = $totalReviews > 0 ? $reviews->avg('vehicle_rating') : 0;

        // Rating distribution
        $ratingDistribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $ratingDistribution[$i] = $reviews->where('overall_rating', $i)->count();
        }

        // Get previous rides (completed)
        $previousRides = \App\Models\Ride::where('user_id', $user->id)
            ->where(function($q) {
                $q->where('go_completion_status', 'completed')
                  ->orWhere('return_completion_status', 'completed');
            })
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        // Get available rides (pending only)
        $availableRides = \App\Models\Ride::where('user_id', $user->id)
            ->where('go_completion_status', 'pending')
            ->get();

        // Filter the rides based on availability
        $filteredAvailableRides = $availableRides->filter(function($ride) {
            if ($ride->is_exclusive) {
                // For exclusive rides, check if no bookings exist
                $hasBookings = \App\Models\RidePurchase::where('ride_id', $ride->id)
                    ->where('trip_type', 'go')
                    ->exists();
                return !$hasBookings;
            } else {
                // For shared rides, check if seats are available
                return $ride->available_seats > 0;
            }
        })->take(10);

        // Get available return rides (pending only)
        $availableReturnRides = \App\Models\Ride::where('user_id', $user->id)
            ->where('is_two_way', true)
            ->where('return_completion_status', 'pending')
            ->get();

        // Filter return rides
        $filteredReturnRides = $availableReturnRides->filter(function($ride) {
            if ($ride->return_is_exclusive) {
                // For exclusive return rides, check if no bookings exist
                $hasBookings = \App\Models\RidePurchase::where('ride_id', $ride->id)
                    ->where('trip_type', 'return')
                    ->exists();
                return !$hasBookings;
            } else {
                // For shared return rides, check if seats are available
                return $ride->return_available_seats > 0;
            }
        })->take(5);

        return response()->json([
            'message' => 'Driver public profile retrieved successfully',
            'status' => 'success',
            'data' => [
                'driver' => $user,
                'driver_documents' => $driverDocuments,
                'reviews' => $reviews,
                'statistics' => [
                    'total_reviews' => $totalReviews,
                    'average_overall_rating' => $averageOverallRating,
                    'average_driver_rating' => $averageDriverRating,
                    'average_vehicle_rating' => $averageVehicleRating,
                    'rating_distribution' => $ratingDistribution
                ],
                'previous_rides' => $previousRides,
                'available_rides' => $filteredAvailableRides,
                'available_return_rides' => $filteredReturnRides
            ]
        ]);
    }
} 