<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['user', 'user_role']);
        return redirect()->route('home');
    }

    public function showChooseRole()
    {
        return view('choose-role');
    }

    public function showUserRegistration()
    {
        return view('register');
    }

    public function showDriverRegistration()
    {
        return view('register-driver');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();
        
        if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            // Split name for session
            $nameParts = explode(' ', $user->name, 2);
            $firstName = $nameParts[0] ?? '';
            $lastName = $nameParts[1] ?? '';
            $role = $user->role ?? 'user';
            
            $request->session()->put('user', [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'first_name' => $firstName,
                'last_name' => $lastName,
            ]);
            $request->session()->put('user_role', $role);
            
            // Redirect based on role
            if ($role === 'driver') {
                return redirect()->route('driver.profile');
            } else {
                return redirect()->route('user.profile');
            }
        }
        
        // If we get here, login failed
        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }
} 