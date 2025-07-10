<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class CheckDriverVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in and is a driver
        if (session()->has('user')) {
            $userData = session('user');
            $user = \App\Models\User::find($userData['id']);
            
            // Refresh session to prevent timeout
            if ($user) {
                session()->regenerate();
            }
            
            // Log for debugging
            Log::info('Driver verification check', [
                'user_id' => $userData['id'] ?? 'not_set',
                'user_found' => $user ? 'yes' : 'no',
                'user_role' => $user ? $user->role : 'not_found',
                'is_verified' => $user ? $user->is_verified : 'not_found',
                'route' => $request->route()->getName(),
                'url' => $request->url()
            ]);
            
            // If user is a driver but not verified, redirect to verification pending page
            if ($user && $user->role === 'driver' && !$user->is_verified) {
                Log::warning('Driver access denied - not verified', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'route' => $request->route()->getName()
                ]);
                return redirect()->route('driver.verification.pending')
                    ->with('error', 'Your driver account is pending verification. Please wait for admin approval.');
            }
            
            // If user is not a driver, redirect to home or appropriate page
            if ($user && $user->role !== 'driver') {
                Log::warning('Driver access denied - wrong role', [
                    'user_id' => $user->id,
                    'user_role' => $user->role,
                    'route' => $request->route()->getName()
                ]);
                return redirect()->route('home')->with('error', 'Access denied. Driver privileges required.');
            }
            
            // If user not found in database
            if (!$user) {
                Log::error('User not found in database during driver verification', [
                    'session_user_id' => $userData['id'] ?? 'not_set',
                    'route' => $request->route()->getName()
                ]);
                session()->forget(['user', 'user_role']);
                return redirect()->route('login')->with('error', 'User session invalid. Please login again.');
            }
        } else {
            // If not logged in, redirect to login
            Log::info('Driver verification check - no session', [
                'route' => $request->route()->getName(),
                'url' => $request->url()
            ]);
            return redirect()->route('login')->with('error', 'Please login to access driver features.');
        }

        return $next($request);
    }
}
