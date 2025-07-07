<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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
            
            // If user is a driver but not verified, redirect to verification pending page
            if ($user && $user->role === 'driver' && !$user->is_verified) {
                return redirect()->route('driver.verification.pending');
            }
            
            // If user is not a driver, redirect to home or appropriate page
            if ($user && $user->role !== 'driver') {
                return redirect()->route('home')->with('error', 'Access denied. Driver privileges required.');
            }
        } else {
            // If not logged in, redirect to login
            return redirect()->route('login');
        }

        return $next($request);
    }
}
