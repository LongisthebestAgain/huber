<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
} 