<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function apiIndex()
    {
        return response()->json([
            'message' => 'Welcome to Huber - Your Ride Sharing Platform',
            'status' => 'success',
            'data' => [
                'platform' => 'Huber',
                'description' => 'A ride sharing platform connecting drivers and passengers',
                'features' => [
                    'User Registration',
                    'Driver Registration', 
                    'Ride Booking',
                    'Payment Processing',
                    'Profile Management'
                ]
            ]
        ]);
    }
} 