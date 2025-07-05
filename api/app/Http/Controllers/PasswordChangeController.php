<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PasswordChangeController extends Controller
{
    public function show()
    {
        // Get user from session
        $userData = session('user');
        if (!$userData) {
            return redirect()->route('login')->with('error', 'Please login to change your password.');
        }

        return view('change-password');
    }

    public function update(Request $request)
    {
        // Get user from session
        $userData = session('user');
        if (!$userData) {
            return redirect()->route('login')->with('error', 'Please login to change your password.');
        }

        $user = User::find($userData['id']);
        if (!$user) {
            session()->forget(['user', 'user_role']);
            return redirect()->route('login')->with('error', 'User not found. Please login again.');
        }

        // Validation
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('user.profile')->with('success', 'Password changed successfully!');
    }

    // API Methods
    public function apiShow(Request $request)
    {
        // Get user from token authentication
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'Please login to change your password.',
                'status' => 'error'
            ], 401);
        }

        return response()->json([
            'message' => 'Password change form data',
            'status' => 'success',
            'data' => [
                'form_fields' => [
                    'current_password' => 'required',
                    'new_password' => 'required|min:8|confirmed'
                ]
            ]
        ]);
    }

    public function apiUpdate(Request $request)
    {
        // Get user from token authentication
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'Please login to change your password.',
                'status' => 'error'
            ], 401);
        }

        // Validation
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect.',
                'status' => 'error',
                'errors' => ['current_password' => ['Current password is incorrect.']]
            ], 422);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Password changed successfully!',
            'status' => 'success'
        ]);
    }
} 