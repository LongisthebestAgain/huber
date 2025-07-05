<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\DriverDocument;

class RegisterController extends Controller
{
    public function apiRegister(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ]);
    }

    public function apiRegisterDriver(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', 'min:8'],
            'license_number' => ['required', 'string', 'max:255'],
            'license_expiry' => ['required', 'date'],
            'vehicle_model' => ['required', 'string', 'max:255'],
            'vehicle_year' => ['required', 'integer'],
            'vehicle_color' => ['required', 'string', 'max:255'],
            'license_plate' => ['required', 'string', 'max:255'],
            'vehicle_seats' => ['required', 'integer', 'min:1', 'max:20'],
            'vehicle_photo_1' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'vehicle_photo_2' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'vehicle_photo_3' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $user = User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'driver',
            'license_number' => $validated['license_number'],
            'license_expiry' => $validated['license_expiry'],
            'vehicle_model' => $validated['vehicle_model'],
            'vehicle_year' => $validated['vehicle_year'],
            'vehicle_color' => $validated['vehicle_color'],
            'license_plate' => $validated['license_plate'],
            'vehicle_seats' => $validated['vehicle_seats'],
        ]);

        // Store vehicle photos
        $vehiclePhoto1Path = $request->file('vehicle_photo_1')->store('driver-documents', 'public');
        $vehiclePhoto2Path = $request->file('vehicle_photo_2')->store('driver-documents', 'public');
        $vehiclePhoto3Path = $request->file('vehicle_photo_3')->store('driver-documents', 'public');

        // Create driver document record with vehicle photos
        DriverDocument::create([
            'user_id' => $user->id,
            'vehicle_photo_1' => $vehiclePhoto1Path,
            'vehicle_photo_2' => $vehiclePhoto2Path,
            'vehicle_photo_3' => $vehiclePhoto3Path,
            'terms_accepted' => true,
        ]);

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ]);
    }

    public function apiRegisterDriverDocs(Request $request)
    {
        try {
            \Log::info('Driver docs upload request received', [
                'user_id' => $request->input('user_id'),
                'files_present' => [
                    'driver_license' => $request->hasFile('driver_license_file'),
                    'vehicle_registration' => $request->hasFile('vehicle_registration_file'),
                    'insurance_certificate' => $request->hasFile('insurance_certificate_file'),
                ]
            ]);

            $validated = $request->validate([
                'user_id' => ['required', 'exists:users,id'],
                'driver_license_file' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
                'vehicle_registration_file' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
                'insurance_certificate_file' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
                'terms_accepted' => ['required'],
            ]);

            $user = User::findOrFail($validated['user_id']);

            // Check if documents already exist for this user
            $existingDocs = DriverDocument::where('user_id', $user->id)->first();
            if ($existingDocs) {
                // Update existing record with legal documents
                $driverLicensePath = $request->file('driver_license_file')->store('driver-documents', 'public');
                $vehicleRegPath = $request->file('vehicle_registration_file')->store('driver-documents', 'public');
                $insuranceCertPath = $request->file('insurance_certificate_file')->store('driver-documents', 'public');

                $existingDocs->update([
                    'driver_license_file' => $driverLicensePath,
                    'vehicle_registration_file' => $vehicleRegPath,
                    'insurance_certificate_file' => $insuranceCertPath,
                    'terms_accepted' => true,
                ]);

                \Log::info('Driver document record updated', ['document_id' => $existingDocs->id]);
            } else {
                // Create new record
                $driverLicensePath = $request->file('driver_license_file')->store('driver-documents', 'public');
                $vehicleRegPath = $request->file('vehicle_registration_file')->store('driver-documents', 'public');
                $insuranceCertPath = $request->file('insurance_certificate_file')->store('driver-documents', 'public');

                $driverDocument = DriverDocument::create([
                    'user_id' => $user->id,
                    'driver_license_file' => $driverLicensePath,
                    'vehicle_registration_file' => $vehicleRegPath,
                    'insurance_certificate_file' => $insuranceCertPath,
                    'terms_accepted' => true,
                ]);

                \Log::info('Driver document record created', ['document_id' => $driverDocument->id]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Documents uploaded successfully!'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed in driver docs upload', [
                'errors' => $e->errors(),
                'user_id' => $request->input('user_id')
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', array_map(function($errors) {
                    return implode(', ', $errors);
                }, $e->errors())),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error in driver docs upload', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => $request->input('user_id')
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while uploading documents. Please try again.'
            ], 500);
        }
    }
} 