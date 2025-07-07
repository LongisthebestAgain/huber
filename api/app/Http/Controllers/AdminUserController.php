<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::where('role', '!=', 'driver')
                    ->orWhereNull('role')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'user',
        ]);

        return redirect()->route('admin.users.index')
                        ->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => 'user',
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users.index')
                        ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')
                        ->with('success', 'User deleted successfully.');
    }

    /**
     * Display the specified user and related data.
     */
    public function show($id)
    {
        $user = User::with(['ridePurchases.ride', 'rides'])->findOrFail($id);
        $today = now()->toDateString();
        $currentBookings = $user->ridePurchases->filter(function($b) use ($today) {
            return optional($b->ride)->date >= $today;
        });
        $pastBookings = $user->ridePurchases->filter(function($b) use ($today) {
            return optional($b->ride)->date < $today;
        });
        $currentRides = $user->rides->filter(function($r) use ($today) {
            return $r->date >= $today;
        });
        $pastRides = $user->rides->filter(function($r) use ($today) {
            return $r->date < $today;
        });
        return view('admin.users.show', compact('user', 'currentBookings', 'pastBookings', 'currentRides', 'pastRides'));
    }

    // API: List users
    public function apiIndex()
    {
        $users = User::where('role', '!=', 'driver')
                    ->orWhereNull('role')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    // API: Store user
    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'role' => 'user',
        ]);
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    // API: Show user
    public function apiShow($id)
    {
        $user = User::with(['ridePurchases.ride', 'rides'])->findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    // API: Update user
    public function apiUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => 'user',
        ]);
        if (!empty($validated['password'])) {
            $user->update(['password' => \Illuminate\Support\Facades\Hash::make($validated['password'])]);
        }
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    // API: Delete user
    public function apiDestroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully.'
        ]);
    }
} 