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
use App\Http\Controllers\BookingController;
use App\Http\Controllers\UserBookingController;
use App\Http\Controllers\PaymentController;

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
Route::get('/driver/profile/{driverId}', [\App\Http\Controllers\DriverProfileController::class, 'showPublic'])->name('driver.profile.public');

// Ride Management for drivers
Route::get('/driver/ride-management', [DriverRideManagementController::class, 'index'])->name('driver.ride.management');
Route::get('/driver/rides/create', [DriverRideManagementController::class, 'create'])->name('driver.rides.create');
Route::post('/driver/rides', [DriverRideManagementController::class, 'store'])->name('driver.rides.store');
Route::get('/driver/my-rides', [DriverRideManagementController::class, 'myRides'])->name('driver.my-rides');
Route::get('/driver/rides/{ride}/edit', [DriverRideManagementController::class, 'edit'])->name('driver.rides.edit');
Route::put('/driver/rides/{ride}', [DriverRideManagementController::class, 'update'])->name('driver.rides.update');
Route::get('/driver/rides/{ride}/customers/{tripType?}', [DriverRideManagementController::class, 'showRideCustomers'])->name('driver.ride.customers');
Route::get('/find-rides', [DriverRideManagementController::class, 'findRides'])->name('find.rides');
Route::get('/driver/earnings', [DriverRideManagementController::class, 'earnings'])->name('driver.earnings');

// Booking routes
Route::get('/booking/payment/{rideId}/{tripType?}', [BookingController::class, 'showPaymentPage'])->name('booking.payment');
Route::get('/booking/seat-selection/{rideId}/{tripType?}', [BookingController::class, 'showSeatSelection'])->name('booking.seat-selection');
Route::post('/booking/seat-selection/{rideId}/{tripType?}', [BookingController::class, 'processSeatSelection'])->name('booking.process-seat-selection');
Route::post('/booking/process/{rideId}/{tripType?}', [BookingController::class, 'processBooking'])->name('booking.process');
Route::get('/booking/thank-you/{bookingId}', [BookingController::class, 'showThankYou'])->name('booking.thank-you');
Route::get('/booking/confirmation/{bookingId}', [BookingController::class, 'showConfirmation'])->name('booking.confirmation');

// Payment routes
Route::get('/payment/{rideId}/{tripType?}', [PaymentController::class, 'showPaymentPage'])->name('payment.show');
Route::post('/payment/process/{rideId}/{tripType?}', [PaymentController::class, 'processPayment'])->name('payment.process');
Route::get('/payment/qr/{rideId}/{tripType?}', [PaymentController::class, 'showQRPayment'])->name('payment.qr');

// User booking routes
Route::get('/user/bookings', [UserBookingController::class, 'index'])->name('user.bookings');
Route::get('/user/bookings/{bookingId}', [UserBookingController::class, 'show'])->name('user.booking.details');
Route::get('/user/bookings/{bookingId}/receipt', [UserBookingController::class, 'printReceipt'])->name('user.booking.receipt');

// Password change routes
Route::get('/password/change', [PasswordChangeController::class, 'show'])->name('password.change');
Route::put('/password/change', [PasswordChangeController::class, 'update'])->name('password.change.submit');

// Stubs for navbar links
Route::get('/rides', function () { return 'Available Rides'; })->name('rides');
Route::get('/user/history', function () { return 'User History'; })->name('user.history');

Route::middleware(['web'])->group(function () {
    Route::get('/driver/rides/create', [\App\Http\Controllers\DriverRideManagementController::class, 'create'])->name('driver.rides.create');
    Route::post('/driver/rides', [\App\Http\Controllers\DriverRideManagementController::class, 'store'])->name('driver.rides.store');
});

