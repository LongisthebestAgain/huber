<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PasswordChangeController;
use App\Http\Controllers\DriverProfileController;
use App\Http\Controllers\DriverRideManagementController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showChooseRole'])->name('register');

Route::get('/register/choose-role', function () {
    return view('choose-role');
});

Route::get('/register/user', [AuthController::class, 'showUserRegistration'])->name('register.user');

Route::get('/register/driver', [AuthController::class, 'showDriverRegistration'])->name('register.driver');

Route::get('/register/driver-docs', function () {
    return view('register-driver-docs');
});

// Profile management routes
Route::get('/profile', [ProfileController::class, 'show'])->name('user.profile');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

// Driver-specific routes
Route::get('/driver/profile', [DriverProfileController::class, 'show'])->name('driver.profile');
Route::put('/driver/vehicle-photos', [DriverProfileController::class, 'updateVehiclePhotos'])->name('driver.vehicle-photos.update');

// Ride Management for drivers
Route::get('/driver/ride-management', [DriverRideManagementController::class, 'index'])->name('driver.ride.management');
Route::get('/driver/rides/create', [DriverRideManagementController::class, 'create'])->name('driver.rides.create');
Route::post('/driver/rides', [DriverRideManagementController::class, 'store'])->name('driver.rides.store');
Route::get('/driver/my-rides', [DriverRideManagementController::class, 'myRides'])->name('driver.my-rides');
Route::get('/driver/rides/{ride}/edit', [DriverRideManagementController::class, 'edit'])->name('driver.rides.edit');
Route::put('/driver/rides/{ride}', [DriverRideManagementController::class, 'update'])->name('driver.rides.update');

// Password change routes
Route::get('/password/change', [PasswordChangeController::class, 'show'])->name('password.change');
Route::put('/password/change', [PasswordChangeController::class, 'update'])->name('password.change.submit');

// Stubs for navbar links
Route::get('/rides', function () { return 'Available Rides'; })->name('rides');
Route::get('/user/bookings', function () { return 'User Bookings'; })->name('user.bookings');
Route::get('/user/history', function () { return 'User History'; })->name('user.history');

Route::middleware(['web'])->group(function () {
    Route::get('/driver/rides/create', [\App\Http\Controllers\DriverRideManagementController::class, 'create'])->name('driver.rides.create');
    Route::post('/driver/rides', [\App\Http\Controllers\DriverRideManagementController::class, 'store'])->name('driver.rides.store');
});

