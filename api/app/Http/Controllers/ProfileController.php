<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    public function show()
    {
        // Get user from session
        $userData = session('user');
        if (!$userData) {
            return redirect()->route('login')->with('error', 'Please login to access your profile.');
        }

        // Get the actual user from database
        $user = User::find($userData['id']);
        if (!$user) {
            session()->forget(['user', 'user_role']);
            return redirect()->route('login')->with('error', 'User not found. Please login again.');
        }

        // Split name into first and last name
        $nameParts = explode(' ', $user->name, 2);
        $user->first_name = $nameParts[0] ?? '';
        $user->last_name = $nameParts[1] ?? '';

        return view('profile', compact('user'));
    }

    public function update(Request $request)
    {
        // Get user from session
        $userData = session('user');
        if (!$userData) {
            return redirect()->route('login')->with('error', 'Please login to update your profile.');
        }

        $user = User::find($userData['id']);
        if (!$user) {
            session()->forget(['user', 'user_role']);
            return redirect()->route('login')->with('error', 'User not found. Please login again.');
        }

        // Validation rules
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'address' => 'nullable|string|max:500',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:100',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'license_number' => 'nullable|string|max:100',
            'license_expiry' => 'nullable|date|after:today',
            'vehicle_model' => 'nullable|string|max:100',
            'vehicle_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'vehicle_color' => 'nullable|string|max:50',
            'license_plate' => 'nullable|string|max:20',
        ];

        // Add driver-specific validation if user is a driver
        if (session('user_role') === 'driver') {
            $driverRules = [
                'license_number' => 'required|string|max:255',
                'license_expiry' => 'required|date|after:today',
                'vehicle_model' => 'required|string|max:255',
                'vehicle_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
                'vehicle_color' => 'required|string|max:255',
                'license_plate' => 'required|string|max:255',
                'vehicle_seats' => 'required|integer|min:1|max:20',
            ];
            $rules = array_merge($rules, $driverRules);
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                // Delete old profile picture if exists
                if ($user->profile_picture) {
                    Storage::disk('public')->delete($user->profile_picture);
                }
                
                // Store new profile picture
                $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
                $user->profile_picture = $profilePicturePath;
            }

            // Update basic information
            $user->name = $request->first_name . ' ' . $request->last_name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->date_of_birth = $request->date_of_birth;
            $user->address = $request->address;
            $user->emergency_contact_name = $request->emergency_contact_name;
            $user->emergency_contact_phone = $request->emergency_contact_phone;
            $user->emergency_contact_relationship = $request->emergency_contact_relationship;

            // Update driver information if applicable
            if (session('user_role') === 'driver') {
                $user->license_number = $request->license_number;
                $user->license_expiry = $request->license_expiry;
                $user->vehicle_model = $request->vehicle_model;
                $user->vehicle_year = $request->vehicle_year;
                $user->vehicle_color = $request->vehicle_color;
                $user->license_plate = $request->license_plate;
                $user->vehicle_seats = $request->vehicle_seats;
            }

            $user->save();

            // Update session data
            session(['user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
            ]]);

            return back()->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while updating your profile. Please try again.');
        }
    }

    // API Methods
    public function apiShow(Request $request)
    {
        // Get user from token authentication
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'Please login to access your profile.',
                'status' => 'error'
            ], 401);
        }

        // Split name into first and last name
        $nameParts = explode(' ', $user->name, 2);
        $user->first_name = $nameParts[0] ?? '';
        $user->last_name = $nameParts[1] ?? '';

        return response()->json([
            'message' => 'Profile retrieved successfully',
            'status' => 'success',
            'data' => [
                'user' => $user,
                'role' => $user->role ?? 'user'
            ]
        ]);
    }

    public function apiUpdate(Request $request)
    {
        // Get user from token authentication
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'Please login to update your profile.',
                'status' => 'error'
            ], 401);
        }

        // Validation rules
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'address' => 'nullable|string|max:500',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:100',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Update basic information
            $user->name = $request->first_name . ' ' . $request->last_name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->date_of_birth = $request->date_of_birth;
            $user->address = $request->address;
            $user->emergency_contact_name = $request->emergency_contact_name;
            $user->emergency_contact_phone = $request->emergency_contact_phone;
            $user->emergency_contact_relationship = $request->emergency_contact_relationship;

            $user->save();

            return response()->json([
                'message' => 'Profile updated successfully!',
                'status' => 'success',
                'data' => [
                    'user' => $user
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating your profile. Please try again.',
                'status' => 'error'
            ], 500);
        }
    }
} 