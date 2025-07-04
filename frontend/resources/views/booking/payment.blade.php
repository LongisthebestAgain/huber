@extends('layouts.app')

@section('title', 'Payment - Book Your Ride')

@section('content')
<div class="container-fluid bg-light min-vh-100 py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="text-center mb-4">
                <h1 class="fw-bold">Complete Your Booking</h1>
                <p class="text-muted">Review your ride details and provide passenger information</p>
            </div>

            <form method="POST" action="{{ route('booking.process', ['rideId' => $ride->id, 'tripType' => $tripType]) }}">
                @csrf
                
                <div class="row">
                    <!-- Ride Summary -->
                    <div class="col-md-4 mb-4">
                        <div class="card shadow border-0" style="border-radius: 18px;">
                            <div class="card-header bg-primary text-white text-center py-3" style="border-radius: 18px 18px 0 0;">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Ride Summary</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                        <span class="fw-semibold">Pickup:</span>
                                    </div>
                                    <p class="mb-0 ms-4">{{ $tripType === 'return' ? $ride->destination : $ride->station_location }}</p>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-map-pin text-secondary me-2"></i>
                                        <span class="fw-semibold">Dropoff:</span>
                                    </div>
                                    <p class="mb-0 ms-4">{{ $tripType === 'return' ? $ride->station_location : $ride->destination }}</p>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="far fa-calendar-alt text-success me-2"></i>
                                        <span class="fw-semibold">Date:</span>
                                    </div>
                                    <p class="mb-0 ms-4">{{ $date }}</p>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="far fa-clock text-warning me-2"></i>
                                        <span class="fw-semibold">Time:</span>
                                    </div>
                                    <p class="mb-0 ms-4">{{ $time }}</p>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-dollar-sign text-success me-2"></i>
                                        <span class="fw-semibold">
                                            @if($isExclusive)
                                                Total Price:
                                            @else
                                                Price per seat:
                                            @endif
                                        </span>
                                    </div>
                                    <p class="mb-0 ms-4 fw-bold text-primary">${{ number_format($pricePerSeat, 2) }}</p>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-users text-info me-2"></i>
                                        <span class="fw-semibold">Available seats:</span>
                                    </div>
                                    <p class="mb-0 ms-4">{{ $availableSeats }}</p>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-car text-dark me-2"></i>
                                        <span class="fw-semibold">Ride type:</span>
                                    </div>
                                    <p class="mb-0 ms-4">
                                        <span class="badge {{ $isExclusive ? 'bg-danger' : 'bg-success' }}">
                                            {{ $isExclusive ? 'EXCLUSIVE' : 'SHARED' }}
                                        </span>
                                    </p>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-user text-primary me-2"></i>
                                        <span class="fw-semibold">Driver:</span>
                                    </div>
                                    <p class="mb-0 ms-4">{{ $ride->user->name }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Google Maps -->
                        <div class="card shadow border-0 mb-4" style="border-radius: 18px;">
                            <div class="card-header bg-success text-white text-center py-3" style="border-radius: 18px 18px 0 0;">
                                <h5 class="mb-0"><i class="fas fa-map me-2"></i>Route Map</h5>
                            </div>
                            <div class="card-body p-4 text-center">
                                <p class="text-muted mb-3">View the route between pickup and dropoff locations</p>
                                <a href="https://www.google.com/maps/dir/{{ urlencode($tripType === 'return' ? $ride->destination : $ride->station_location) }}/{{ urlencode($tripType === 'return' ? $ride->station_location : $ride->destination) }}" 
                                   target="_blank" 
                                   class="btn btn-success px-4 py-2">
                                    <i class="fas fa-map-marked-alt me-2"></i>View Route on Google Maps
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Details -->
                    <div class="col-md-8">
                        @if($errors->any())
                            <div class="alert alert-danger mb-4" style="border-radius: 12px;">
                                <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</h6>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="card shadow border-0 mb-4" style="border-radius: 18px;">
                            <div class="card-header bg-success text-white py-3" style="border-radius: 18px 18px 0 0;">
                                <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Passenger Information</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="number_of_seats" class="form-label fw-semibold">Number of Seats</label>
                                        <select class="form-select @error('number_of_seats') is-invalid @enderror" id="number_of_seats" name="number_of_seats" required onchange="updatePassengerForms()">
                                            <option value="">Select seats</option>
                                            @for($i = 1; $i <= min($availableSeats, 10); $i++)
                                                <option value="{{ $i }}" {{ old('number_of_seats') == $i ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'seat' : 'seats' }}</option>
                                            @endfor
                                        </select>
                                        <!-- Hidden input for exclusive rides to ensure value is sent -->
                                        <input type="hidden" id="number_of_seats_hidden" name="number_of_seats_hidden" value="">
                                        @error('number_of_seats')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="contact_phone" class="form-label fw-semibold">Contact Phone Number</label>
                                        <input type="tel" class="form-control @error('contact_phone') is-invalid @enderror" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $user->phone) }}" placeholder="Enter phone number" required>
                                        @error('contact_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="special_requests" class="form-label fw-semibold">Special Requests (Optional)</label>
                                    <textarea class="form-control @error('special_requests') is-invalid @enderror" id="special_requests" name="special_requests" rows="3" placeholder="Any special requests or notes for the driver...">{{ old('special_requests') }}</textarea>
                                    @error('special_requests')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div id="passenger-forms">
                                    <!-- Passenger forms will be dynamically generated here -->
                                </div>
                                
                                <!-- Hidden input to store old passenger names -->
                                <input type="hidden" id="old-passenger-names" value="{{ json_encode(old('passenger_names', [])) }}">
                                
                                <!-- Display validation errors for passenger names -->
                                @if($errors->has('passenger_names'))
                                    <div class="alert alert-danger mt-3" style="border-radius: 12px;">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        {{ $errors->first('passenger_names') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Payment Summary -->
                        <div class="card shadow border-0 mb-4" style="border-radius: 18px;">
                            <div class="card-header bg-warning text-dark py-3" style="border-radius: 18px 18px 0 0;">
                                <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Payment Summary</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Payment Method</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="payment_method" id="visa" value="visa" checked>
                                                <label class="form-check-label" for="visa">
                                                    <i class="fab fa-cc-visa text-primary me-2"></i>Visa
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="payment_method" id="mastercard" value="mastercard">
                                                <label class="form-check-label" for="mastercard">
                                                    <i class="fab fa-cc-mastercard text-warning me-2"></i>Mastercard
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="bg-light rounded p-3">
                                            <h6 class="fw-semibold mb-3">Price Breakdown</h6>
                                            @if($isExclusive)
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Total Price:</span>
                                                    <span>${{ number_format($pricePerSeat, 2) }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Number of seats:</span>
                                                    <span id="selected-seats">0</span>
                                                </div>
                                                <hr>
                                                <div class="d-flex justify-content-between fw-bold">
                                                    <span>Total:</span>
                                                    <span id="total-price" class="text-primary">${{ number_format($pricePerSeat, 2) }}</span>
                                                </div>
                                            @else
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Price per seat:</span>
                                                    <span>${{ number_format($pricePerSeat, 2) }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Number of seats:</span>
                                                    <span id="selected-seats">0</span>
                                                </div>
                                                <hr>
                                                <div class="d-flex justify-content-between fw-bold">
                                                    <span>Total:</span>
                                                    <span id="total-price" class="text-primary">$0.00</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('find.rides') }}" class="btn btn-outline-secondary px-4 py-2">
                                <i class="fas fa-arrow-left me-2"></i>Back to Rides
                            </a>
                            <button type="submit" class="btn btn-primary px-5 py-2">
                                <i class="fas fa-credit-card me-2"></i>Complete Booking
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.card {
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    transition: box-shadow 0.2s;
}
.card:hover {
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
}
.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #e0e0e0;
}
.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}
.btn {
    border-radius: 8px;
    font-weight: 600;
}
</style>

<script>
function updatePassengerForms() {
    const numberOfSeats = parseInt(document.getElementById('number_of_seats').value) || 0;
    const passengerFormsContainer = document.getElementById('passenger-forms');
    const selectedSeatsSpan = document.getElementById('selected-seats');
    const totalPriceSpan = document.getElementById('total-price');
    const pricePerSeat = {{ $pricePerSeat }};
    const isExclusive = {{ $isExclusive ? 'true' : 'false' }};
    const availableSeats = {{ $availableSeats }};
    
    // Get old passenger names if they exist
    const oldPassengerNamesInput = document.getElementById('old-passenger-names');
    const oldPassengerNames = oldPassengerNamesInput ? JSON.parse(oldPassengerNamesInput.value || '[]') : [];
    
    // For exclusive rides, automatically set seats to available seats and disable the select
    if (isExclusive) {
        const seatsSelect = document.getElementById('number_of_seats');
        const hiddenInput = document.getElementById('number_of_seats_hidden');
        seatsSelect.value = availableSeats;
        seatsSelect.disabled = true;
        hiddenInput.value = availableSeats; // Set hidden input value
        selectedSeatsSpan.textContent = availableSeats;
    } else {
        const seatsSelect = document.getElementById('number_of_seats');
        const hiddenInput = document.getElementById('number_of_seats_hidden');
        seatsSelect.disabled = false;
        hiddenInput.value = numberOfSeats; // Set hidden input value
        selectedSeatsSpan.textContent = numberOfSeats;
    }
    
    // Calculate total price based on ride type
    let totalPrice;
    let actualNumberOfSeats;
    if (isExclusive) {
        totalPrice = pricePerSeat; // Fixed total price for exclusive rides
        actualNumberOfSeats = 1; // Only 1 passenger name for exclusive rides
    } else {
        totalPrice = numberOfSeats * pricePerSeat; // Per-person pricing for shared rides
        actualNumberOfSeats = numberOfSeats;
    }
    
    totalPriceSpan.textContent = '$' + totalPrice.toFixed(2);
    
    // Clear existing forms
    passengerFormsContainer.innerHTML = '';
    
    if (actualNumberOfSeats > 0) {
        // Create passenger forms
        for (let i = 1; i <= actualNumberOfSeats; i++) {
            const oldValue = oldPassengerNames[i-1] || '';
            const formHtml = `
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="passenger_names_${i-1}" class="form-label fw-semibold">
                            @if($isExclusive)
                                Passenger Full Name <span class="text-danger">*</span>
                            @else
                                Passenger ${i} Full Name <span class="text-danger">*</span>
                            @endif
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="passenger_names_${i-1}" 
                               name="passenger_names[]" 
                               value="${oldValue}"
                               placeholder="@if($isExclusive) Enter passenger full name @else Enter full name for passenger ${i} @endif" 
                               required>
                    </div>
                </div>
            `;
            passengerFormsContainer.innerHTML += formHtml;
        }
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // If there are old passenger names, set the number of seats accordingly
    const oldPassengerNamesInput = document.getElementById('old-passenger-names');
    if (oldPassengerNamesInput) {
        const oldPassengerNames = JSON.parse(oldPassengerNamesInput.value || '[]');
        if (oldPassengerNames.length > 0) {
            const seatsSelect = document.getElementById('number_of_seats');
            seatsSelect.value = oldPassengerNames.length;
        }
    }
    
    updatePassengerForms();
    
    // Add form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const isExclusive = {{ $isExclusive ? 'true' : 'false' }};
        const numberOfSeats = parseInt(document.getElementById('number_of_seats').value) || 0;
        const passengerInputs = document.querySelectorAll('input[name="passenger_names[]"]');
        
        // For exclusive rides, we only need 1 passenger name
        const requiredPassengers = isExclusive ? 1 : numberOfSeats;
        
        // Check if all passenger names are filled
        let allFilled = true;
        passengerInputs.forEach((input, index) => {
            if (index < requiredPassengers && !input.value.trim()) {
                allFilled = false;
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });
        
        if (!allFilled) {
            e.preventDefault();
            alert('Please fill in all passenger names.');
            return false;
        }
    });
});
</script>
@endsection 